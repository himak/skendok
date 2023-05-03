<?php

namespace App\Http\Requests;

use App\Models\Odosielatel;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateOdosielatelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('odosielatel_edit');
    }

    public function rules()
    {
        return [
            'odosielatel' => [
                'string',
                'nullable',
            ],
        ];
    }
}
