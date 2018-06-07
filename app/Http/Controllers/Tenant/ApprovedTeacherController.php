<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ApprovedTeacher;
use App\Repositories\PersonRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\UserRepository;

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

        //Create person
        $person = (object) [
            'user_id' => $user->id ?? null,
            'identifier_id' => $request->identifier_id,
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
        $this->personRepository->store($person);
    }


}
