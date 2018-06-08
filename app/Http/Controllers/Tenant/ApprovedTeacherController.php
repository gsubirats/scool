<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ApprovedTeacher;
use App\Models\Address;
use App\Models\AdministrativeStatus;
use App\Models\Employee;
use App\Models\Identifier;
use App\Models\Location;
use App\Repositories\PersonRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

/**
 * Class ApprovedTeacherController.
 *
 * @package App\Http\Controllers\Tenant
 */
class ApprovedTeacherController extends Controller
{
    public $userRepository;

    public $teacherRepository;

    public $personRepository;

    /**
     * ApprovedTeacherController constructor.
     *
     * @param $userRepository
     */
    public function __construct(
        UserRepository $userRepository,
        TeacherRepository $teacherRepository,
        PersonRepository $personRepository)
    {
        $this->userRepository = $userRepository;
        $this->teacherRepository = $teacherRepository;
        $this->personRepository = $personRepository;

    }


    /**
     * Store
     *
     * @param ApprovedTeacher $request
     * @param $tenant
     */
    public function store(ApprovedTeacher $request, $tenant)
    {
        // TODO -> obtain users domain from tenant -> Example @iesebre.com
        $user = (object) [
            'name' => name($request->givenName, $request->sn1, $request->sn2),
            'email' => $request->username . '@iesebre.com',
            'photo' => $request->photo
        ];
        $user = $this->userRepository->store($user);

        $user->addRole(Role::findByName('Teacher','web'));

        // Assign job to user (create a new employee)
        //TODO que passa si assignem una feina a un usuari que ja està assignada a aquell usuari?
        $start_at = null;
        if (AdministrativeStatus::findByName('Substitut/a')->id === $request->administrative_status_id) {
            $start_at = Carbon::now();
        }
        Employee::create([
            'user_id' => $user->id,
            'job_id' => $request->job_id,
            'start_at' => $start_at
        ]);

        //Create Teacher
        $teacher = (object) [
            'user_id' => $user->id ?? null,
            'code' => $request->code,
            'administrative_status_id' => $request->administrative_status_id ?? null,
            'specialty_id' => $request->specialty_id ?? null,
            'titulacio_acces' => $request->titulacio_acces ?? null,
            'altres_titulacions' => $request->altres_titulacions ?? null,
            'idiomes' => $request->idiomes ?? null,
            'altres_formacions' => $request->altres_formacions ?? null,
            'perfil_professional' => $request->perfil_professional ?? null,
            'data_inici_treball' => $request->data_inici_treball ?? null,
            'data_incorporacio_centre' => $request->data_incorporacio_centre ?? null,
            'data_superacio_oposicions' => $request->data_superacio_oposicions ?? null,
            'lloc_destinacio_definitiva' => $request->lloc_destinacio_definitiva ?? null
        ];
        $this->teacherRepository->store($teacher);

        // DEPARTMENT TODO: BE SURE TO ADD NEW USER TO CORRESPONDING DEPARTMENT

        //Create identifier
        $identifier = Identifier::firstOrCreate([
            'value' => $request->identifier,
            'type_id' => $request->identifier_type
        ]);

        //Create person
        $person = (object) [
            'user_id' => $user->id ?? null,
            'identifier_id' => $identifier->id,
            'givenName' => $request->givenName,
            'sn1' => $request->sn1,
            'sn2' => $request->sn2 ?? null,
            'birthdate' => $request->birthdate ?? null,
            'birthplace_id' => $request->birthplace_id ?? null,
            'gender' => $request->gender ?? null,
            'civil_status' => $request->civil_status ?? null,
            'phone' => $request->phone ?? null,
            'other_phones' => $request->other_phones ?? null,
            'mobile' => $request->mobile ?? null,
            'other_mobiles' => $request->other_mobiles ?? null,
            'email' => $request->email ?? null,
            'other_emails' => $request->other_emails ?? null,
            'notes' => $request->notes ?? null
        ];
        $person = $this->personRepository->store($person);

        //Create address
        if(! $location = Location::findByName( $request->address_location)) {
            $location = Location::create([
                'name' => strtoupper($request->address_location),
                'postalcode' => $request->address_postalcode
            ]);
        }

        if ($address = Address::where('person_id',$person->id)->first()) {
            $address->name = $request->address;
            $address->number = $request->address_number ?? null;
            $address->floor = $request->address_floor ?? null;
            $address->floor_number = $request->address_floor_number ?? null;
            $address->location_id = $location->id;
            $address->province_id = $request->address_province_id ?? null;
            $address->save();
        } else {
            Address::create([
                'person_id' => $person->id,
                'name' => $request->address,
                'number' => $request->address_number ?? null,
                'floor' => $request->address_floor ?? null,
                'floor_number' => $request->address_floor_number ?? null,
                'location_id' => $location->id,
                'province_id' => $request->address_province_id ?? null
            ]);
        }

    }


}