<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chào mừng bạn đến với 5S!</title>
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
            background: linear-gradient(135deg, #0062cc, #0097e6);
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px;
        }
        .welcome-message {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0062cc;
        }
        .paragraph {
            font-size: 16px;
            color: #505050;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background: #0062cc;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .button:hover {
            background: #004e9e;
        }
        .benefits {
            background-color: #f5f9ff;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .benefit-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .benefit-icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
            color: #0062cc;
        }
        .footer {
            background-color: #e8f4fc;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #505050;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-icon {
            display: inline-block;
            margin: 0 10px;
            width: 30px;
            height: 30px;
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
            <img src="https://star5sco.com/wp-content/uploads/2024/06/logo-star5sco-3.png" alt="5S Logo" class="logo">
            <h1>CHÀO MỪNG ĐẾN VỚI 5S!</h1>
        </div>
        
        <div class="content">
            <div class="welcome-message">Xin chào, {{ $name }}!</div>
            
            <p class="paragraph">Chúng tôi vui mừng thông báo rằng tài khoản của bạn đã được đăng ký thành công tại 5S. Cảm ơn bạn đã tin tưởng và lựa chọn dịch vụ của chúng tôi.</p>
            
            <p class="paragraph">Bây giờ bạn có thể truy cập đầy đủ vào tất cả các tính năng và dịch vụ mà chúng tôi cung cấp. Hãy khám phá ngay hôm nay!</p>
            
            <center>
                <a href="https://star5sco.com/" style="color: #e8f4fc;" class="button">KHÁM PHÁ NGAY</a>
            </center>
            
            <div class="benefits">
                <h3 style="color: #0062cc; margin-top: 0;">Lợi ích thành viên của bạn:</h3>
                
                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>Truy cập không giới hạn vào tất cả các dịch vụ 5S</div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>Hỗ trợ kỹ thuật ưu tiên 24/7</div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>Cập nhật tin tức và ưu đãi đặc biệt</div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div>Tham gia cộng đồng người dùng 5S</div>
                </div>
            </div>
            
            <p class="paragraph">Nếu bạn có bất kỳ câu hỏi hoặc cần hỗ trợ, đừng ngần ngại liên hệ với đội ngũ hỗ trợ khách hàng của chúng tôi tại <a href="mailto:support@5s.com" style="color: #0062cc;">support@5s.com</a>.</p>
            
            <div class="divider"></div>
            
            <p class="paragraph" style="font-style: italic;">Chúc bạn có trải nghiệm tuyệt vời cùng 5S!</p>
            
            <p class="paragraph" style="font-weight: bold;">Trân trọng,<br>Đội ngũ 5S</p>
        </div>
        
        <div class="footer">
            <div class="social-links">
                <a href="#" class="social-icon"><img src="" alt="Facebook"></a>
                <a href="#" class="social-icon"><img src="" alt="LinkedIn"></a>
                <a href="#" class="social-icon"><img src="" alt="Instagram"></a>
                <a href="#" class="social-icon"><img src="" alt="YouTube"></a>
            </div>
            
            <p>© 2025 Công ty 5S. Tất cả các quyền được bảo lưu.</p>
            <p>Địa chỉ: Tòa nhà 5S, 123 Đường Lê Lợi, Quận 1, TP.HCM</p>
            <p><a href="#" style="color: #0062cc;">Chính sách bảo mật</a> | <a href="#" style="color: #0062cc;">Điều khoản sử dụng</a></p>
        </div>
    </div>
</body>
</html>