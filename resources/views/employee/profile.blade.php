<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .profile-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        .avatar-container {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .tab-active {
            border-bottom: 2px solid #6366f1;
            color: #6366f1;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .custom-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .custom-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen">
        <!-- Thanh điều hướng -->
        <nav class="bg-white shadow-sm py-4 px-6">
            <div class="container mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <img src="https://star5sco.com/wp-content/uploads/2024/06/logo-star5sco-3.png" alt="Logo" class="h-10">
                    <span class="text-3xl font-bold text-gray-800">Company</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center text-gray-700 focus:outline-none">
                            <span class="mr-2">Xin chào, {{ $user->name }}</span>
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://kynguyenlamdep.com/wp-content/uploads/2022/06/avatar-cute-vui-nhon.jpg' }}" alt="Avatar" class="w-8 h-8 rounded-full">
                        </button>
                    
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Header Profile -->
        <div class="profile-header text-white py-12">
            <div class="container mx-auto px-6">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center mb-4 md:mb-0">
                        <div class="avatar-container bg-white p-1 rounded-full mr-6">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://kynguyenlamdep.com/wp-content/uploads/2022/06/avatar-cute-vui-nhon.jpg' }}" alt="Avatar" class="w-24 h-24 rounded-full">
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                            <p class="text-indigo-100">{{ $user->department->name }}</p>
                            <p class="flex items-center mt-1">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <button class="btn-primary text-white py-2 px-6 rounded-lg font-medium flex items-center">
                            <i class="fas fa-edit mr-2"></i> Chỉnh sửa thông tin
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-6 py-8">
            <div class="flex border-b mb-6">
                <button class="tab-active pb-3 px-4 font-medium">Thông tin cá nhân</button>
                <button class="text-gray-500 pb-3 px-4 font-medium">Hoạt động</button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Thông tin cá nhân -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6 custom-card">
                        <h2 class="text-xl font-semibold mb-6 text-gray-800">Thông tin cá nhân</h2>
                        
                        <form id="profileForm" action="{{ route('employee.updateProfile') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="avatar">
                                    Ảnh đại diện
                                </label>
                                <div class="flex items-center">
                                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://kynguyenlamdep.com/wp-content/uploads/2022/06/avatar-cute-vui-nhon.jpg' }}" alt="Current avatar" class="w-20 h-20 rounded-full mr-4">
                                    <div>
                                        <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*">
                                        <label for="avatar" class="btn-primary text-white py-2 px-4 rounded cursor-pointer inline-block">
                                            <i class="fas fa-upload mr-2"></i> Tải ảnh lên
                                        </label>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG hoặc GIF. Tối đa 2MB.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="name">
                                        Họ và tên
                                    </label>
                                    <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:outline-none py-2 px-3">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="email">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ $user->email }}" class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:outline-none py-2 px-3">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="department_id">
                                        Phòng ban
                                    </label>
                                    <select name="department_id" id="department_id" class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:outline-none py-2 px-3" disabled>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Chỉ quản trị viên mới có thể thay đổi phòng ban.</p>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="role">
                                        Chức vụ
                                    </label>
                                    <input type="text" id="role" value="{{ $user->role }}" class="form-input w-full rounded-lg border-gray-300 shadow-sm py-2 px-3" disabled>
                                </div>
                            </div>
                            
                            <h3 class="text-lg font-semibold mb-4 mt-8 text-gray-800">Thay đổi mật khẩu</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="current_password">
                                        Mật khẩu hiện tại
                                    </label>
                                    <input type="password" name="current_password" id="current_password" class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:outline-none py-2 px-3">
                                </div>
                                
                                <div></div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="password">
                                        Mật khẩu mới
                                    </label>
                                    <input type="password" name="password" id="password" class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:outline-none py-2 px-3">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2" for="password_confirmation">
                                        Xác nhận mật khẩu mới
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input w-full rounded-lg border-gray-300 shadow-sm focus:outline-none py-2 px-3">
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="button" class="bg-gray-100 text-gray-700 py-2 px-6 rounded-lg font-medium mr-2">
                                    Hủy bỏ
                                </button>
                                <button type="submit" class="btn-primary text-white py-2 px-6 rounded-lg font-medium">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 custom-card">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Trạng thái tài khoản</h2>
                        <div class="flex items-center text-sm">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span>Hoạt động</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-4">
                            <i class="far fa-clock mr-1"></i> Đăng nhập gần nhất: {{ now()->format('d/m/Y H:i') }}
                        </p>
                        
                        @if($user->force_password_change)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-100 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Yêu cầu đổi mật khẩu</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Vui lòng đổi mật khẩu của bạn để tiếp tục sử dụng hệ thống.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-sm p-6 custom-card">
                        <h2 class="text-xl font-semibold mb-4 text-gray-800">Thông báo</h2>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-2 h-2 mt-2 bg-indigo-500 rounded-full"></span>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Cập nhật hệ thống</p>
                                    <p class="text-xs text-gray-500">Hệ thống sẽ bảo trì vào 20/03/2025</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <span class="flex-shrink-0 w-2 h-2 mt-2 bg-indigo-500 rounded-full"></span>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Họp phòng ban</p>
                                    <p class="text-xs text-gray-500">9:00 AM, 16/03/2025</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Hiển thị thông báo thành công nếu có -->
    @if(session('success'))
    <div id="successNotification" class="fixed top-4 right-4 px-6 py-3 rounded shadow-lg bg-green-500 text-white flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('successNotification').style.opacity = '0';
            setTimeout(() => {
                document.getElementById('successNotification').remove();
            }, 500);
        }, 3000);
    </script>
    @endif
    
    <!-- Hiển thị lỗi validation nếu có -->
    @if($errors->any())
    <div id="errorNotification" class="fixed top-4 right-4 px-6 py-3 rounded shadow-lg bg-red-500 text-white">
        <div class="flex items-center mb-2">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <span class="font-semibold">Có lỗi xảy ra</span>
        </div>
        <ul class="text-sm list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('errorNotification').style.opacity = '0';
            setTimeout(() => {
                document.getElementById('errorNotification').remove();
            }, 500);
        }, 5000);
    </script>
    @endif
    
    <script>
        // Hiển thị ảnh đại diện khi chọn file
        document.getElementById('avatar').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    document.querySelector('img[alt="Current avatar"]').src = event.target.result;
                }
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>