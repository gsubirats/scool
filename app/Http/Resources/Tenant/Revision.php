<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Revision.
 *
 * @package App\Http\Resources\Tenant
 */
class Revision extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'revisionable_type' => $this->revisionable_type,
            'revisionable_id' => $this->revisionable_id,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'user_hashid' => optional($this->user)->hashid,
            'user_description' => $this->userDescription($this->user),
            'key' => $this->key,
            'field_name' => $this->fieldName(),
            'old_value' => $this->getOldValue(),
            'new_value' => $this->getNewValue(),
            'old_value_raw' => $this->old_value,
            'new_value_raw' => $this->new_value,
            'old_value_description' => $this->getOldValueDescription(),
            'new_value_description' => $this->getNewValueDescription(),
            'created_at' =>  $this->created_at,
            'formatted_created_at_diff' => $this->formatted_created_at_diff,
            'element' => $this->element(),
            'type' => $this->actionType(),
            'description' => $this->description()
        ];
    }

    /**
     * Get old value description.
     */
    protected function getOldValueDescription()
    {
        $description = $this->getOldValue();

        if($this->getOldValue() !== 'res/buit' && $this->getOldValue() != null
            && $this->getNewValue() !== $this->new_value) {
            $description = $description . ' (' . $this->old_value .')';
        }
        return $description;
    }

    /**
     * Get new value description.
     */
    protected function getNewValueDescription()
    {
        $description = $this->getNewValue();

        if($this->getNewValue() != 'res/buit' && $this->getNewValue() != null
           && $this->getNewValue() !== $this->new_value) {
            $description = $description . ' (' . $this->new_value .')';
        }
        return $description;
    }
    /**
     * Get old value.
     *
     * @return string
     */
    protected function getOldValue()
    {
        if ($this->oldValue() === 'nothing') return 'res/buit';
        return $this->oldValue();
    }

    /**
     * Get new value.
     *
     * @return string
     */
    protected function getNewValue()
    {
        if ($this->newValue() === 'nothing') return 'res/buit';
        return $this->newValue();
    }

    /**
     * Description.
     *
     * @return string
     */
    protected function description()
    {
        $description = optional($this->user)->name . ' ' . $this->actionTypeDescription();
        if ($this->actionType() === 'Modificació') {
            $description = $description . ' el camp ' . $this->fieldName() . ' de ';
        }
        $description = $description . ' ' . $this->elementLocale() . ' ' . $this->elementLocaleIdentifier();
        if ($this->actionType() === 'Modificació') {
            $oldValue = 'buit/res';
            if ($this->oldValue() && $this->oldValue() !== 'nothing') $oldValue = $this->oldValue();
            $newValue = 'buit/res';
            if ($this->newValue() && $this->newValue() !== 'nothing') $newValue = $this->newValue();
            $description = $description . ' de ' . $oldValue . ' a ' . $newValue;
        }
        return $description;
    }


    /**
     *  Action type description.
     *
     * @return string
     */
    protected function actionType() {
        if ($this->key == 'created_at' && !$this->old_value) {
            return 'Creació';
        }
        return 'Modificació';
        // TODO eliminació!
    }

    /**
     *  Action type description.
     *
     * @return string
     */
    protected function actionTypeDescription() {
        if ($this->key == 'created_at' && !$this->old_value) {
            return 'ha creat';
        }
        return 'ha canviat';
        // TODO ha eliminat!
    }

    /**
     * Element locale.
     *
     * @return mixed
     */
    protected function elementLocale() {
        try {
            if ($this->revisionable) {
                return $this->revisionable->localeName();
            } else {
                return $this->revisionable_type;
            }
        } catch(\Exception $e) {
            return $this->revisionable_type;
        }
    }

    /**
     * Element locale identifier.
     *
     * @return mixed
     */
    protected function elementLocaleIdentifier() {
        try {
            if ($this->revisionable) {
                return $this->revisionable->localeIdentifier();
            } else {
                return '(no disponible)';
            }
        } catch(\Exception $e) {
            return '(no disponible)';
        }
    }

    /**
     * Element.
     */
    protected function element() {
        try {
            if ($this->revisionable) {
                return $this->revisionable->identifiableName();
            } else {
                return $this->revisionable_type;
            }
        } catch(\Exception $e) {
            return $this->revisionable_type;
        }
    }

    /**
     * User description.
     *
     * @param $user
     * @return string
     */
    protected function userDescription($user)
    {
        if($user) {
            return $user->name . ' (' . $user->id. ' ' . $user->email . ')';
        }
        return '';
    }
}
