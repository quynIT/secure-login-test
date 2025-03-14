<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo đặt lại mật khẩu</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #4776E6, #8E54E9);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #4776E6;
        }
        .paragraph {
            font-size: 16px;
            color: #505050;
            margin-bottom: 20px;
        }
        .info-box {
            background-color: #f0f7ff;
            border-left: 4px solid #4776E6;
            border-radius: 4px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }
        .info-label {
            font-weight: bold;
            width: 100px;
            color: #4776E6;
        }
        .info-value {
            font-family: monospace;
            font-size: 16px;
            background-color: #e9f0fb;
            padding: 5px 10px;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #4776E6, #8E54E9);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
            transition: all 0.3s;
        }
        .button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(71, 118, 230, 0.3);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .alert {
            background-color: #fff8e6;
            border-left: 4px solid #ffcc00;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-icon {
            color: #ffcc00;
            margin-right: 10px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #777;
        }
        .divider {
            height: 1px;
            background-color: #e1e1e1;
            margin: 25px 0;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
                border-radius: 0;
            }
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>THÔNG BÁO ĐẶT LẠI MẬT KHẨU</h1>
        </div>
        
        <div class="content">
            <div class="greeting">Xin chào, {{ $user->name }}!</div>
            
            <p class="paragraph">Bạn đã được cấp tài khoản truy cập vào hệ thống. Dưới đây là thông tin đăng nhập của bạn:</p>
            
            <div class="info-box">
                <div class="info-item">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Mật khẩu:</div>
                    <div class="info-value">{{ $password }}</div>
                </div>
            </div>
            
            <div class="button-container">
                <a href="{{ url('http://example-app.test/login') }}" class="button">ĐĂNG NHẬP NGAY</a>
            </div>
            
            <div class="alert">
                <span class="alert-icon">⚠️</span>
                Vui lòng đổi mật khẩu sau khi đăng nhập lần đầu để đảm bảo bảo mật tài khoản.
            </div>
            
            <p class="paragraph">Nếu bạn gặp bất kỳ vấn đề gì, vui lòng liên hệ bộ phận hỗ trợ qua email <a href="mailto:support@example.com" style="color: #4776E6;">support@example.com</a> hoặc số điện thoại <strong>0123 456 789</strong>.</p>
            
            <div class="divider"></div>
            
            <p class="paragraph">Trân trọng,<br><strong>{{ config('app.name') }}</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} {{ config('app.name') }}. Tất cả các quyền được bảo lưu.</p>
            <p>Email này được gửi tự động, vui lòng không phản hồi.</p>
        </div>
    </div>
</body>
</html>