<?php

namespace Txsoura\Core\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class QueryParamsRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->include) {
            $this->merge([
                'include' => Str::lower($this->include)
            ]);
        }
        if ($this->q) {
            $this->merge([
                'q' => Str::lower($this->q),
            ]);
        }
        if ($this->sort) {
            $this->merge([
                'sort' => Str::lower($this->sort),
            ]);
        }
        if ($this->date_column) {
            $this->merge([
                'date_column' => Str::lower($this->date_column),
            ]);
        }
        if ($this->value_column) {
            $this->merge([
                'value_column' => Str::lower($this->value_column),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'include' => 'sometimes|required|string',
            'q' => 'sometimes|required|string',
            'sort' => 'sometimes|required|string',
            'paginate' => 'sometimes|required|numeric|min:1',
            'page' => 'sometimes|required|numeric|min:1',
            'take' =>  'sometimes|required|numeric|min:1',
            'date_column' =>  'sometimes|required|string',
            'date_start' =>  'sometimes|required|date:before_or_equal:date_end',
            'date_end' =>  'sometimes|required|date|after_or_equal:date_start',
            'value_column' =>  'sometimes|required|string',
            'value_min' =>  'sometimes|required|numeric|ite:value_max',
            'value_max' =>  'sometimes|required|numeric|gte:value_min'
        ];
    }
}
