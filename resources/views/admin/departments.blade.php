@extends('layout.app')
@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý phòng ban</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body class="bg-gray-100 ">
    <div class="container mx-auto px-4 py-8 min-h-screen">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 bg-gradient-to-r from-blue-600 to-blue-700 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-white">Danh sách phòng ban</h2>
                <button id="openAddModal" class="bg-white text-blue-600 px-4 py-2 rounded-lg shadow hover:bg-blue-50 transition duration-200 font-medium flex items-center">
                    <i class="fas fa-plus mr-2"></i> Thêm phòng ban mới
                </button>
            </div>
            
            <div id="successAlert" class="hidden bg-green-100 border-l-4 border-green-500 text-green-700 p-4 m-4" role="alert">
                <p class="font-medium">Thao tác thành công!</p>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-800 text-white text-left">
                                <th class="py-3 px-4 rounded-tl-lg">ID</th>
                                <th class="py-3 px-4">Tên phòng ban</th>
                                <th class="py-3 px-4">Mô tả</th>
                                <th class="py-3 px-4">Số nhân viên</th>
                                <th class="py-3 px-4 rounded-tr-lg text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-3 px-4">{{ $department->id }}</td>
                                <td class="py-3 px-4 font-medium">{{ $department->name }}</td>
                                <td class="py-3 px-4">{{ $department->description ?: 'Không có mô tả' }}</td>
                                <td class="py-3 px-4">{{ $department->users->count() }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex justify-center space-x-2">
                                        <button class="view-employees bg-green-500 hover:bg-green-600 text-white p-2 rounded-md transition duration-200" 
                                        data-id="{{ $department->id }}" data-name="{{ $department->name }}">
                                            <i class="fas fa-users"></i>
                                        </button>
                                        <button class="edit-department bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-md transition duration-200" 
                                        data-id="{{ $department->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-department bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition duration-200" 
                                        data-id="{{ $department->id }}" data-name="{{ $department->name }}" data-count="{{ $department->users->count() }}">
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

    <!-- Modal Thêm Phòng Ban -->
    <div id="addDepartmentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="border-b pb-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Thêm phòng ban</h3>
                <button class="close-modal text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="addDepartmentForm" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên phòng ban</label>
                    <input type="text" id="name" name="name" required 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm">
                    <span id="name-error" class="text-red-500 text-sm"></span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea id="description" name="description" rows="3" 
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 shadow-sm"></textarea>
                    <span id="description-error" class="text-red-500 text-sm"></span>
                </div>
                
                <div class="flex justify-end space-x-3 mt-4">
                    <button type="button" class="close-modal px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Thêm phòng ban
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Modal Sửa Phòng Ban -->
    <div id="editDepartmentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b p-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Cập nhật thông tin phòng ban</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="editDepartmentForm" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="edit_department_id" name="id">
                
                <div class="space-y-2">
                    <label for="edit_name" class="block text-sm font-medium text-gray-700">Tên phòng ban</label>
                    <input type="text" id="edit_name" name="name" required 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500">
                    <span id="edit-name-error" class="text-red-500 text-sm"></span>
                </div>
                
                <div class="space-y-2">
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea id="edit_description" name="description" rows="3" 
                        class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500"></textarea>
                    <span id="edit-description-error" class="text-red-500 text-sm"></span>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" class="close-modal px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Cập nhật phòng ban
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Xác nhận xóa -->
    <div id="deleteDepartmentModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 max-w-md mx-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Xác nhận xóa</h3>
            <p class="mb-6" id="delete-confirmation-message">Bạn có chắc chắn muốn xóa phòng ban này?</p>
            <div class="flex justify-end space-x-3">
                <button class="close-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Hủy
                </button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Xóa
                </button>
            </div>
        </div>
    </div>
    <div id="viewEmployeesModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl">
            <div class="border-b p-4 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-800">Danh sách nhân viên - <span id="department-name"></span></h3>
                <button class="close-modal text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-4 text-gray-600" id="employee-count"></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="py-2 px-4 border-b">ID</th>
                                <th class="py-2 px-4 border-b">Tên</th>
                                <th class="py-2 px-4 border-b">Email</th>
                                <th class="py-2 px-4 border-b">Vai trò</th>
                            </tr>
                        </thead>
                        <tbody id="employees-list">
                            <!-- Danh sách nhân viên sẽ được thêm vào đây bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div id="no-employees" class="hidden py-4 text-center text-gray-500">
                    Không có nhân viên nào trong phòng ban này
                </div>
            </div>
            
            <div class="border-t p-4 flex justify-end">
                <button class="close-modal px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
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

    // Hiển thị modal thêm phòng ban
    $('#openAddModal').click(function() {
        $('#addDepartmentModal').removeClass('hidden');
        resetFormErrors();
    });

    // Đóng modal
    $('.close-modal').click(function() {
        $('#addDepartmentModal, #editDepartmentModal, #deleteDepartmentModal, #viewEmployeesModal').addClass('hidden');
        resetFormErrors();
    });

    // Xóa thông báo lỗi form
    function resetFormErrors() {
        $('#name-error, #description-error, #edit-name-error, #edit-description-error').text('');
    }

    // Thêm phòng ban 
    $('#addDepartmentForm').submit(function(e) {
        e.preventDefault();
        resetFormErrors();
        
        let formData = {
            name: $('#name').val(),
            description: $('#description').val(),
            _token: $('input[name="_token"]').val()
        };
        
        $.ajax({
            url: '/departments',
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#addDepartmentModal').addClass('hidden');
                showSuccessAlert();
                // Làm mới trang sau khi thêm thành công
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
                
                if (errors.name) {
                    $('#name-error').text(errors.name[0]);
                }
                if (errors.description) {
                    $('#description-error').text(errors.description[0]);
                }
            }
        });
    });
    
    // Mở modal chỉnh sửa phòng ban và tải thông tin
    $('.edit-department').click(function() {
        let departmentId = $(this).data('id');
        $('#edit_department_id').val(departmentId);
        resetFormErrors();
        
        // Tải thông tin phòng ban
        $.ajax({
            url: `/departments/${departmentId}`,
            type: 'GET',
            success: function(department) {
                $('#edit_name').val(department.name);
                $('#edit_description').val(department.description);
                $('#editDepartmentModal').removeClass('hidden');
            },
            error: function(xhr) {
                alert('Không thể tải thông tin phòng ban!');
            }
        });
    });

    // Cập nhật phòng ban 
    $('#editDepartmentForm').submit(function(e) {
        e.preventDefault();
        resetFormErrors();
        
        let departmentId = $('#edit_department_id').val();
        let formData = {
            name: $('#edit_name').val(),
            description: $('#edit_description').val(),
            _token: $('input[name="_token"]').val()
        };
        
        $.ajax({
            url: `/departments/${departmentId}`,
            type: 'PUT',
            data: formData,
            success: function(response) {
                $('#editDepartmentModal').addClass('hidden');
                showSuccessAlert();
                // Làm mới trang sau khi cập nhật thành công
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
                
                if (errors.name) {
                    $('#edit-name-error').text(errors.name[0]);
                }
                if (errors.description) {
                    $('#edit-description-error').text(errors.description[0]);
                }
            }
        });
    });

    // Hiển thị modal xác nhận xóa
    $('.delete-department').click(function() {
    let departmentId = $(this).data('id');
    let departmentName = $(this).data('name');
    let userCount = $(this).data('count');
    
    let message = `Bạn có chắc chắn muốn xóa phòng ban <strong>${departmentName}</strong>?`;
    if (userCount > 0) {
        message += ` <span class="text-red-600">Phòng ban này đang có ${userCount} nhân viên.</span>`;
    }
    
    $('#delete-confirmation-message').html(message);
    $('#confirm-delete').data('id', departmentId);
    $('#deleteDepartmentModal').removeClass('hidden');
});

    // Xóa phòng ban 
    $('#confirm-delete').click(function() {
        let departmentId = $(this).data('id');
        
        $.ajax({
            url: `/departments/${departmentId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function(response) {
                $('#deleteDepartmentModal').addClass('hidden');
                showSuccessAlert();
                // Làm mới trang sau khi xóa thành công
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                $('#deleteDepartmentModal').addClass('hidden');
                alert('Có lỗi xảy ra khi xóa phòng ban!');
            }
        });
    });
    $('.view-employees').click(function() {
    let departmentId = $(this).data('id');
    let departmentName = $(this).data('name');
    
    $('#department-name').text(departmentName);
    $('#employees-list').empty();
    $('#no-employees').addClass('hidden');
    
    // Tải danh sách nhân viên của phòng ban
    $.ajax({
        url: `/departments/${departmentId}`,
        type: 'GET',
        success: function(department) {
            let users = department.users;
            $('#employee-count').text(`Tổng số: ${users.length} nhân viên`);
            
            if (users.length > 0) {
                users.forEach(function(user) {
                    $('#employees-list').append(`
                        <tr class="hover:bg-gray-50">
                            <td class="py-2 px-4 border-b">${user.id}</td>
                            <td class="py-2 px-4 border-b font-medium">${user.name}</td>
                            <td class="py-2 px-4 border-b">${user.email}</td>
                            <td class="py-2 px-4 border-b capitalize">${user.role}</td>
                        </tr>
                    `);
                });
            } else {
                $('#no-employees').removeClass('hidden');
            }
            
            $('#viewEmployeesModal').removeClass('hidden');
        },
        error: function(xhr) {
            alert('Không thể tải danh sách nhân viên!');
        }
    });
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
</body>
</html>
@endsection