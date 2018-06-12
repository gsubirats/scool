<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteApprovedTeacher;
use App\Http\Requests\StoreApprovedTeacher;
use App\Models\Address;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\PendingTeacher;
use App\Models\Teacher;
use App\Models\User;
use App\Repositories\PersonRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;
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
     * @param StoreApprovedTeacher $request
     * @param $tenant
     */
    public function store(StoreApprovedTeacher $request)
    {
        // TODO -> obtain users domain from tenant -> Example @iesebre.com
        $user = (object) [
            'name' => name($request->name, $request->sn1, $request->sn2),
            'email' => $request->username . '@iesebre.com',
            'photo' => $request->photo
        ];
        $user = $this->userRepository->store($user);

        $user->addRole(Role::findByName('Teacher','web'));

        // Assign job to user (create a new employee)
        $user->assignTeacherJob($request->job_id,$request->administrative_status_id);

        //Create Teacher
        $teacher = (object) [
            'user_id' => $user->id ?? null,
            'code' => Teacher::firstAvailableCode(),
            'administrative_status_id' => $request->administrative_status_id ?? null,
            'specialty_id' => $request->specialty_id ?? null,
            'titulacio_acces' => $request->degree ?? null,
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
            'type_id' => IdentifierType::findByName($request->identifier_type)->id
        ]);

        $other_phones = null;
        if ($request->other_phones) {
            $other_phones = json_encode(explode(',', $request->other_phones));
        }

        $other_mobiles = null;
        if ($request->other_mobiles) {
            $other_mobiles = json_encode(explode(',', $request->other_mobiles));
        }

        $other_emails = null;
        if ($request->other_emails) {
            $other_emails = json_encode(explode(',', $request->other_emails));
        }

        //Create person
        $person = (object) [
            'user_id' => $user->id ?? null,
            'identifier_id' => $identifier->id,
            'givenName' => $request->name,
            'sn1' => $request->sn1,
            'sn2' => $request->sn2 ?? null,
            'birthdate' => $request->birthdate ?? null,
            'birthplace_id' => $request->birthplace_id ?? null,
            'gender' => $request->gender ?? null,
            'civil_status' => $request->civil_status ?? null,
            'phone' => $request->phone ?? null,
            'other_phones' => $other_phones,
            'mobile' => $request->mobile ?? null,
            'other_mobiles' => $other_mobiles,
            'email' => $request->email ?? null,
            'other_emails' => $other_emails ?? null,
            'notes' => $request->notes ?? null
        ];
        $person = $this->personRepository->store($person);

        //Create address

        $location = Location::find($request->locality_id);
        if(!$location) $location = Location::findByName( $request->locality);
        if(!$location) {
            $location = Location::create([
                'name' => strtoupper($request->locality),
                'postalcode' => $request->postal_code
            ]);
        }

        if ($address = Address::where('person_id',$person->id)->first()) {
            $address->name = $request->street;
            $address->number = $request->number ?? null;
            $address->floor = $request->floor ?? null;
            $address->floor_number = $request->floor_number ?? null;
            $address->location_id = $location->id;
            $address->province_id = $request->province_id ?? null;
            $address->save();
        } else {
            Address::create([
                'person_id' => $person->id,
                'name' => $request->street,
                'number' => $request->number ?? null,
                'floor' => $request->floor ?? null,
                'floor_number' => $request->floor_number ?? null,
                'location_id' => $location->id,
                'province_id' => $request->province_id ?? null
            ]);
        }

        PendingTeacher::destroy($request->pending_teacher_id);
    }

    /**
     * Destroy.
     *
     * @param DeleteApprovedTeacher $request
     * @param $tenant
     * @param $user_id
     */
    public function destroy(DeleteApprovedTeacher $request, $tenant,  User $user)
    {
        $person = $user->person;
        if ($person && $person->user) {
            if ($person->user) {
                $person->user->teacher()->delete();
                $person->user->rmRole(Role::findByName('Teacher','web'));
                $person->user->unassignJobs();
                $person->user()->delete();
            }
            // Unassign jobs to user (remove employee)
            $person->identifier()->delete();
            $person->birthplace()->delete();
            $person->address()->delete();
            $person->delete();
        }


    }

}
