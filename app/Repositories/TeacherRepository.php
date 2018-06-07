<?php

namespace App\Repositories;

use App\Models\Teacher;

/**
 * Class TeacherRepository.
 *
 * @package App\Repositories
 */
class TeacherRepository
{
    /**
     * Store teacher.
     *
     * @param $request
     * @return mixed
     */
    public function store($teacher)
    {
        return Teacher::create([
            'user_id' => $teacher->user_id ?? null,
            'code' => $teacher->code,
            'administrative_status_id' => $teacher->administrative_status_id ?? null,
            'specialty_id' => $teacher->specialty_id ?? null,
            'titulacio_acces' => $teacher->titulacio_acces ?? null,
            'altres_titulacions' => $teacher->altres_titulacions ?? null,
            'idiomes' => $teacher->idiomes ?? null,
            'altres_formacions' => $teacher->altres_formacions ?? null,
            'perfil_professional' => $teacher->perfil_professional ?? null,
            'data_inici_treball' => $teacher->data_inici_treball ?? null,
            'data_incorporacio_centre' => $teacher->data_incorporacio_centre ?? null,
            'data_superacio_oposicions' => $teacher->data_superacio_oposicions ?? null,
            'lloc_destinacio_definitiva' => $teacher->lloc_destinacio_definitiva ?? null
        ]);
    }
}