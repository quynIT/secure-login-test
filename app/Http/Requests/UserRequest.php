<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,manager,employee',
            'department_id' => 'required|exists:departments,id',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ];

        // Nếu đang cập nhật user (có user_id), thêm except để bỏ qua email hiện tại
        if ($this->route('id')) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->route('id');
        }

        // Password là bắt buộc khi tạo mới, nhưng tùy chọn khi cập nhật
        if (!$this->route('id')) {
            $rules['password'] = 'nullable|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
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
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại trong hệ thống',
            'role.required' => 'Vai trò không được để trống',
            'role.in' => 'Vai trò không hợp lệ',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
            'avatar.image' => 'File phải là hình ảnh',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB',
        ];
    }
}