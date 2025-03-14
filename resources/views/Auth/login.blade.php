@extends('layout.app')

@section('content')
<div class="py-12 bg-gray-100 min-h-screen">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-16">
            <div class="px-6 py-4 bg-white border-b border-gray-200 text-center">
                <h4 class="text-xl font-semibold text-gray-800">Đăng nhập</h4>
            </div>
            
            <div class="p-6">
                <div id="error-container" class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded hidden">
                    <ul id="error-list" class="list-disc pl-5">
                    </ul>
                </div>

                <form id="loginForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email</label>
                        <input type="email" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="email" name="email" required>
                        <p class="text-red-500 text-xs mt-1 email-error hidden"></p>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Mật khẩu</label>
                        <input type="password" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                            id="password" name="password" required>
                        <p class="text-red-500 text-xs mt-1 password-error hidden"></p>
                    </div>

                    <div class="mb-6 flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Ghi nhớ đăng nhập</label>
                    </div>

                    <div>
                        <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            Đăng nhập
                        </button>
                    </div>
                    
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Chưa có tài khoản? 
                            <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium">Đăng ký</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
   document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    document.querySelectorAll('.text-red-500').forEach(el => {
        el.classList.add('hidden');
    });
    document.getElementById('error-container').classList.add('hidden');
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xử lý...';
    submitButton.disabled = true;
    submitButton.classList.add('opacity-75', 'cursor-not-allowed');
    
    const token = document.querySelector('input[name="_token"]').value;
    
    const formData = new FormData();
    formData.append('email', document.getElementById('email').value);
    formData.append('password', document.getElementById('password').value);
    formData.append('remember', document.getElementById('remember').checked ? '1' : '0');
    formData.append('_token', token);
    
    // Gửi request
    fetch('{{ route("login.submit") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
    if (!response.ok) {
        return response.json().then(data => {
            throw data;
        });
    }
    return response.json();
    })
    .then(data => {
        if (data.success) {
            const successAlert = document.createElement('div');
            successAlert.className = 'mt-4 mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded';
            successAlert.textContent = 'Đăng nhập thành công! Đang chuyển hướng...';
            
            // Thêm thông báo vào form
            const form = document.getElementById('loginForm');
            form.parentNode.insertBefore(successAlert, form);
            
            setTimeout(() => {
                window.location.href = data.redirect || '/foods';
            }, 1500);
        } else {
            throw { errors: { general: ['Thông tin đăng nhập không chính xác'] } };
        }
    })
    .catch(error => {
        // Khôi phục nút submit về trạng thái ban đầu
        submitButton.innerHTML = originalButtonText;
        submitButton.disabled = false;
        submitButton.classList.remove('opacity-75', 'cursor-not-allowed');
        
        if (error.errors) {
            // Hiển thị lỗi validation
            for (const [field, messages] of Object.entries(error.errors)) {
                if (field === 'general') {
                    const errorContainer = document.getElementById('error-container');
                    const errorList = document.getElementById('error-list');
                    errorList.innerHTML = '';
                    
                    const errorItem = document.createElement('li');
                    errorItem.textContent = messages[0];
                    errorList.appendChild(errorItem);
                    
                    errorContainer.classList.remove('hidden');
                } else {
                    const errorElement = document.querySelector(`.${field}-error`);
                    if (errorElement) {
                        errorElement.textContent = messages[0];
                        errorElement.classList.remove('hidden');
                    }
                }
            }
        } else {
            // Hiển thị lỗi chung
            const errorContainer = document.getElementById('error-container');
            const errorList = document.getElementById('error-list');
            errorList.innerHTML = '';
            
            const errorItem = document.createElement('li');
            errorItem.textContent = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
            errorList.appendChild(errorItem);
            
            errorContainer.classList.remove('hidden');
        }
    });
});
</script>
@endsection