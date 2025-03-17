@extends('layout.app')
@section('content')
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8 min-h-screen">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Danh sách người dùng</h2>
                <div class="flex space-x-2">
                    <button id="resetSelectedButton" class="bg-orange-500 text-white px-4 py-2 rounded-lg shadow hover:bg-orange-600 transition duration-200 font-medium flex items-center disabled:opacity-50 disabled:cursor-not-allowed hidden">
                        <i class="fas fa-key mr-2"></i> Reset mật khẩu
                    </button>
                    <button id="openAddModal" class="bg-white text-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-50 transition duration-200 font-medium flex items-center">
                        <i class="fas fa-plus mr-2"></i> Thêm người dùng
                    </button>
                </div>
            </div>
            
            <div id="successAlert" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p class="font-medium">Thao tác thành công!</p>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-800 text-white text-left">
                                <th class="py-3 px-4 w-10">
                                    <input type="checkbox" id="select-all" class="form-checkbox rounded">
                                </th>
                                <th class="py-3 px-4">Tên</th>
                                <th class="py-3 px-4">Email</th>
                                <th class="py-3 px-4">Phòng ban</th>
                                <th class="py-3 px-4">Vai trò</th>
                                <th class="py-3 px-4 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-3 px-4">
                                    <input type="checkbox" class="user-checkbox form-checkbox rounded" value="{{ $user->id }}">
                                </td>
                                <td class="py-3 px-4 font-medium">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">{{ $user->department->name }}</td>
                                <td class="py-3 px-4 capitalize">{{ $user->role }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center space-x-2">
                                        <button class="reset-password bg-orange-500 hover:bg-orange-600 text-white p-2 rounded-md transition duration-200" 
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        <button class="edit-user bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md transition duration-200" 
                                        data-id="{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-user bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition duration-200" 
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Nhân Viên -->
    <div id="addUserModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="border-b pb-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Thêm nhân viên</h3>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addUserForm" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên nhân viên</label>
                    <input type="text" id="name" name="name" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                </div>
                
                
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Vai trò</label>
                    <select id="role" name="role" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                        <option value="employee">Nhân viên</option>
                        <option value="root">Quản trị</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phòng ban</label>
                    <select id="department_id" name="department_id" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                        <option value="">-- Chọn phòng ban --</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                </div>
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" class="close-modal px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Thêm nhân viên
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Sửa Nhân Viên -->
    <div id="editUserModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b p-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Cập nhật thông tin nhân viên</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editUserForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="edit_user_id" name="id">
                
                <div class="space-y-2">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Tên nhân viên</label>
                    <input type="text" id="edit_name" name="name" required 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="space-y-2">
                    <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="edit_email" name="email" required 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="space-y-2">
                    <label for="edit_password" class="block text-sm font-medium text-gray-700">
                        Mật khẩu (để trống nếu không thay đổi)
                    </label>
                    <input type="password" id="edit_password" name="password" 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="space-y-2">
                    <label for="edit_role" class="block text-sm font-medium text-gray-700">Vai trò</label>
                    <select id="edit_role" name="role" required 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="employee">Nhân viên</option>
                        <option value="root">Quản trị</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="edit_department_id" class="block text-sm font-medium text-gray-700">Phòng ban</label>
                    <select id="edit_department_id" name="department_id" 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Chọn phòng ban --</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                
               
    
                <!-- Thêm trường ảnh đại diện -->
                <div class="space-y-2">
                    <label for="edit_avatar" class="block text-sm font-medium text-gray-700">Ảnh đại diện</label>
                    <input type="file" id="edit_avatar" name="avatar" accept="image/*" 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" class="close-modal px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Cập nhật nhân viên
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div id="resetPasswordModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md mx-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Xác nhận reset mật khẩu</h3>
            <p class="mb-2" id="reset-confirmation-message"></p>
            <div class="text-sm text-gray-600 mb-6">
                Mật khẩu mới sẽ được tạo tự động và gửi vào email của người dùng.
            </div>
            <div class="flex justify-end space-x-3">
                <button class="close-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Hủy
                </button>
                <button id="confirm-reset" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    Reset mật khẩu
                </button>
            </div>
        </div>
    </div>
    <!-- Modal Kết quả Reset Password -->
<div id="resetResultModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 w-96 mx-auto">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Kết quả reset mật khẩu</h3>
        <div class="max-h-60 overflow-y-auto mb-4" id="reset-results">
            <!-- Kết quả sẽ được thêm vào đây bằng JavaScript -->
        </div>
        <div class="flex justify-end">
            <button class="close-modal px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Đóng
            </button>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Hiển thị modal thêm nhân viên
    $('#openAddModal').click(function() {
        $('#addUserModal').removeClass('hidden');
    });

    // Đóng modal
    $('.close-modal').click(function() {
        $('#addUserModal, #editUserModal').addClass('hidden');
    });

    // Thêm nhân viên 
    $('#addUserForm').submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    
    $.ajax({
        url: '/users',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#addUserModal').addClass('hidden');
            showSuccessAlert();
            setTimeout(function() {
                location.reload();
            }, 1000);
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors || {};
            let errorMessage = 'Có lỗi xảy ra:';
            $.each(errors, function(field, messages) {
                errorMessage += '\n- ' + messages.join(', ');
            });
            
            alert(errorMessage);
        }
    });
});
     // Mở modal chỉnh sửa nhân viên và tải thông tin người dùng
     $('.edit-user').click(function() {
        let userId = $(this).data('id');
        $('#edit_user_id').val(userId);
        
        // Tải thông tin người dùng
        $.ajax({
            url: `/users/${userId}`,
            type: 'GET',
            success: function(user) {
                $('#edit_name').val(user.name);
                $('#edit_email').val(user.email);
                $('#edit_role').val(user.role);
                $('#edit_department_id').val(user.department_id);
                $('#editUserModal').removeClass('hidden');
            },
            error: function(xhr) {
                alert('Không thể tải thông tin người dùng!');
            }
        });
    });

    // Cập nhật nhân viên 
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        let userId = $('#edit_user_id').val();
        
        // Tạo FormData để có thể gửi file
        let formData = new FormData(this);
        formData.append('_method', 'PUT'); 
        console.log(formData);
        for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]); 
    }
        $.ajax({
            url: `/users/${userId}`,
            type: 'POST',
            data: formData,
            processData: false,  
            contentType: false,  
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                $('#editUserModal').addClass('hidden');
                showSuccessAlert();
                // Làm mới trang sau khi cập nhật thành công
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
                let errorMessage = 'Có lỗi xảy ra:';
                
                // Hiển thị lỗi cụ thể cho từng trường
                $.each(errors, function(field, messages) {
                    errorMessage += '\n- ' + messages.join(', ');
                });
                
                alert(errorMessage);
            }
        });
    });

    // Xóa nhân viên 
    $('.delete-user').click(function() {
        let userId = $(this).data('id');
        
        if (confirm('Bạn có chắc chắn muốn xóa nhân viên này không?')) {
            $.ajax({
                url: `/users/${userId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(response) {
                    showSuccessAlert();
                    // Làm mới trang sau khi xóa thành công
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    alert('Có lỗi xảy ra khi xóa nhân viên!');
                }
            });
        }
    });
    
    // Hiển thị thông báo thành công
    function showSuccessAlert() {
        $('#successAlert').removeClass('hidden');
        setTimeout(function() {
            $('#successAlert').addClass('hidden');
        }, 3000);
    }
});
</script>
<script>
    $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $('.close-modal').click(function() {
        $('#resetPasswordModal, #resetResultModal').addClass('hidden');
    });
    $('#select-all').change(function() {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
        updateResetButton();
    });

    $('.user-checkbox').change(function() {
        updateResetButton();
    });

    function updateResetButton() {
        const anyChecked = $('.user-checkbox:checked').length > 0;
        if (anyChecked) {
            $('#resetSelectedButton').removeClass('hidden');
        } else {
            $('#resetSelectedButton').addClass('hidden');
        }
    }

    // Reset password cho một người dùng
    $('.reset-password').click(function() {
        let userId = $(this).data('id');
        let userName = $(this).data('name');
        
        $('#reset-confirmation-message').html(`Bạn có chắc chắn muốn reset mật khẩu cho người dùng <strong>${userName}</strong>?`);
        $('#confirm-reset').data('ids', [userId]);
        $('#resetPasswordModal').removeClass('hidden');
    });

    // Reset password cho nhiều người dùng
    $('#resetSelectedButton').click(function() {
        let selectedIds = [];
        $('.user-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        $('#reset-confirmation-message').html(`Bạn có chắc chắn muốn reset mật khẩu cho <strong>${selectedIds.length}</strong> người dùng đã chọn?`);
        $('#confirm-reset').data('ids', selectedIds);
        $('#resetPasswordModal').removeClass('hidden');
    });

    // Xác nhận reset password
    $('#confirm-reset').click(function() {
        let userIds = $(this).data('ids');
        
        $.ajax({
            url: '/users/reset-password',
            type: 'POST',
            data: {
            user_ids: userIds
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#resetPasswordModal').addClass('hidden');
                
                // Hiển thị kết quả reset
                let resultsHtml = '<ul class="space-y-2">';
                $.each(response.results, function(userId, result) {
                    if (result.success) {
                        resultsHtml += `
                            <li class="bg-green-50 p-3 rounded-md border-l-4 border-green-500">
                                <div class="font-medium">${result.user}</div>
                                <div class="text-sm text-gray-600">${result.email}</div>
                                <div class="text-xs text-green-600 mt-1">Đã gửi mật khẩu mới qua email</div>
                            </li>
                        `;
                    } else {
                        resultsHtml += `
                            <li class="bg-red-50 p-3 rounded-md border-l-4 border-red-500">
                                <div class="font-medium">ID: ${userId}</div>
                                <div class="text-sm text-red-600">${result.message}</div>
                            </li>
                        `;
                    }
                });
                resultsHtml += '</ul>';
                
                $('#reset-results').html(resultsHtml);
                $('#resetResultModal').removeClass('hidden');
                
                // Reset các checkbox
                $('.user-checkbox, #select-all').prop('checked', false);
                updateResetButton();
            },
            error: function(xhr) {
                $('#resetPasswordModal').addClass('hidden');
                alert('Có lỗi xảy ra khi reset mật khẩu!');
            }
        });
    });

});
</script>
</body>
</html>
@endsection