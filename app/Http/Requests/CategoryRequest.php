<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Enumerations\CategoryType;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $this->id,
            'type' => 'required|' . Rule::in([CategoryType::MainCategory, CategoryType::SubCategory])
        ];
    }

    public function messages()
    {
        return [
            'required' => __('admin\requests\sidebar.required'),
            'slug.unique' => __('admin\requests\sidebar.unique'),

        ];
    }
}
