<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Form extends Model
{
    public $casts = [
        'form_data' => 'object'
    ];

    public function jsonData(): Attribute
    {
        return new Attribute(
            get: fn () => [
                'type' => $this->type,
                'is_required' => $this->is_required,
                'label' => $this->name,
                'extensions' => $this->extensions ?? 'null',
                'options' => json_encode($this->options),
                'old_id' => '',
            ],
        );
    }


    public function mergeDefaultTransferFields()
    {
        $formData                 = (object) $this->form_data;
        $formData->account_name   = $this->getFields('Account Name');
        $formData->account_number = $this->getFields('Account Number');
        $formData                 = collect($formData)->sortByDesc('default');
        $this->form_data          = $formData;
    }

    public function getFields($name)
    {
        return [
            'name'        => $name,
            'label'       => titleToKey($name),
            'is_required' => 'required',
            'extensions'  => '',
            'options'     => [],
            'type'        => 'text',
            'width'       => '12',
            'default'     => true,
        ];
    }
}
