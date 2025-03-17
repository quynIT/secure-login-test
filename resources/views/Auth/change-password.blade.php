<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi mật khẩu</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #224abe 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            border: 1px solid #e3e6f0;
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }
        
        .input-group-text {
            border-radius: 0.5rem 0 0 0.5rem;
            background-color: #f8f9fc;
            border: 1px solid #e3e6f0;
            border-right: none;
        }
        
        .password-wrapper .form-control {
            border-radius: 0 0.5rem 0.5rem 0;
        }
        
        .logo {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }
        
        .password-strength {
            height: 8px;
            border-radius: 4px;
            margin-top: 8px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s, background-color 0.3s;
        }
        
        .password-feedback {
            font-size: 0.8rem;
            margin-top: 0.25rem;
            color: var(--secondary-color);
        }
        
        .toggle-password {
            cursor: pointer;
            color: var(--secondary-color);
        }
        
        .card-footer {
            background-color: white;
            border-top: none;
            border-radius: 0 0 1rem 1rem !important;
            padding: 1.5rem;
        }
        
        .animation-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }
        
        .circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(34, 74, 190, 0.1) 100%);
        }
        
        .circle-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
        }
        
        .circle-2 {
            width: 500px;
            height: 500px;
            bottom: -250px;
            right: -250px;
        }
        
        .success-checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            display: none;
        }
        
        .check-icon {
            width: 80px;
            height: 80px;
            position: relative;
            border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid var(--success-color);
        }
        
        .check-icon::before {
            top: 48px;
            left: 26px;
            transform: rotate(45deg);
            width: 15px;
            height: 4px;
            position: absolute;
            content: "";
            background-color: var(--success-color);
        }
        
        .check-icon::after {
            top: 44px;
            left: 34px;
            transform: rotate(135deg);
            width: 30px;
            height: 4px;
            position: absolute;
            content: "";
            background-color: var(--success-color);
        }
    </style>
</head>
<body>
    <div class="animation-container">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-6 col-md-8">
                <!-- Logo -->
                <div class="text-center mb-4">
                    <!-- Replace with your logo -->
                    <img src="https://star5sco.com/wp-content/uploads/2024/06/logo-star5sco-3.png" alt="Logo" class="logo">
                    <h4 class="text-primary mb-0">Hệ thống quản lý nhân viên</h4>
                </div>
                
                <div class="card">
                    <div class="card-header text-center">
                        <h4 class="mb-0"><i class="fas fa-key me-2"></i>Đổi mật khẩu</h4>
                        <p class="card-text mt-2 mb-0">Vui lòng đổi mật khẩu để tiếp tục sử dụng hệ thống</p>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Success message container -->
                        <div id="success-message" class="alert alert-success text-center d-none">
                            <div class="success-checkmark">
                                <div class="check-icon"></div>
                            </div>
                            <p class="mt-3">Đổi mật khẩu thành công!</p>
                            <p>Đang chuyển hướng...</p>
                        </div>
                        
                        <!-- Form container -->
                        <div id="form-container">
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <form id="password-form" method="POST" action="{{ route('password.update') }}">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <div class="input-group password-wrapper">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới" required>
                                        <span class="input-group-text toggle-password" onclick="togglePassword('password')">
                                            <i class="far fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="password-strength mt-2">
                                        <div class="password-strength-bar" id="strengthBar"></div>
                                    </div>
                                    <div class="password-feedback" id="passwordFeedback">
                                        Mật khẩu phải có ít nhất 8 ký tự
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                                    <div class="input-group password-wrapper">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                                        <span class="input-group-text toggle-password" onclick="togglePassword('password_confirmation')">
                                            <i class="far fa-eye"></i>
                                        </span>
                                    </div>
                                    <div class="match-feedback text-danger mt-1" id="matchFeedback"></div>
                                </div>
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Cập nhật mật khẩu
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card-footer text-center">
                        <p class="text-muted mb-0">
                            <i class="fas fa-shield-alt me-1"></i>
                            Bảo mật bởi Hệ thống quản lý nhân viên
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const icon = passwordField.parentNode.querySelector('.toggle-password i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const feedback = document.getElementById('passwordFeedback');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let tips = [];
            
            if (password.length >= 8) {
                strength += 25;
            } else {
                tips.push("Mật khẩu phải có ít nhất 8 ký tự");
            }
            
            if (password.match(/[a-z]/)) {
                strength += 25;
            } else {
                tips.push("Thêm chữ cái thường");
            }
            
            if (password.match(/[A-Z]/)) {
                strength += 25;
            } else {
                tips.push("Thêm chữ cái hoa");
            }
            
            if (password.match(/[0-9]/) || password.match(/[^A-Za-z0-9]/)) {
                strength += 25;
            } else {
                tips.push("Thêm số hoặc ký tự đặc biệt");
            }
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.style.backgroundColor = '#dc3545'; 
                feedback.style.color = '#dc3545';
            } else if (strength <= 50) {
                strengthBar.style.backgroundColor = '#ffc107'; 
                feedback.style.color = '#ffc107';
            } else if (strength <= 75) {
                strengthBar.style.backgroundColor = '#0dcaf0'; 
                feedback.style.color = '#0dcaf0';
            } else {
                strengthBar.style.backgroundColor = '#198754'; 
                feedback.style.color = '#198754';
            }
            
           
            if (tips.length > 0) {
                feedback.textContent = tips.join(', ');
            } else {
                feedback.textContent = 'Mật khẩu mạnh';
            }
        });
        
        const confirmInput = document.getElementById('password_confirmation');
        const matchFeedback = document.getElementById('matchFeedback');
        
        confirmInput.addEventListener('input', function() {
            if (this.value && this.value !== passwordInput.value) {
                matchFeedback.textContent = 'Mật khẩu không khớp';
            } else {
                matchFeedback.textContent = '';
            }
        });
        
        document.getElementById('password-form').addEventListener('submit', function(e) {
            e.preventDefault();
        
            if (passwordInput.value.length < 8) {
                feedback.textContent = 'Mật khẩu phải có ít nhất 8 ký tự';
                return;
            }
            
            if (passwordInput.value !== confirmInput.value) {
                matchFeedback.textContent = 'Mật khẩu không khớp';
                return;
            }
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('form-container').classList.add('d-none');
                    const successMessage = document.getElementById('success-message');
                    successMessage.classList.remove('d-none');
                    document.querySelector('.success-checkmark').style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger';
                    errorDiv.textContent = data.message || 'Có lỗi xảy ra';
                    
                    this.prepend(errorDiv);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>