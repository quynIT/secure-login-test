<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|unique:departments,name|max:255',
            'description' => 'nullable|string',
        ];

       
        if ($this->route('id')) {
            $rules['name'] = 'sometimes|string|unique:departments,name,' . $this->route('id') . '|max:255';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên phòng ban không được để trống',
            'name.string' => 'Tên phòng ban phải là chuỗi ký tự',
            'name.unique' => 'Tên phòng ban đã tồn tại trong hệ thống',
            'name.max' => 'Tên phòng ban không được vượt quá 255 ký tự',
            'description.string' => 'Mô tả phải là chuỗi ký tự',
        ];
    }
}