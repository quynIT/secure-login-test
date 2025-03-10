<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Together AI Chat</title>
    <!-- Tailwind CDN -->
    <style>
        /* Chatbot Styles */
        .floating-chatbot {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .chat-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #1e67b4;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s;
            position: relative;
        }

        .chat-button::before,
        .chat-button::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #1e67b4;
            opacity: 0.6;
            z-index: -1;
            animation: ripple 2s infinite;
        }

        .chat-button::after {
            animation-delay: 0.5s;
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }
            100% {
                transform: scale(1.8);
                opacity: 0;
            }
        }

        .chat-button.incoming {
            animation: shake 1s infinite;
        }

        @keyframes shake {
            0%, 100% {
                transform: scale(1);
            }
            25% {
                transform: scale(1.05);
            }
            50% {
                transform: scale(1);
            }
            75% {
                transform: scale(1.05);
            }
        }

        /* Để hiệu ứng chỉ hiển thị khi chat chưa mở */
        .chat-button:not(.open)::before,
        .chat-button:not(.open)::after {
            animation-play-state: running;
        }

        .chat-button.open::before,
        .chat-button.open::after {
            animation-play-state: paused;
            opacity: 0;
        }

        .chat-button:hover {
            transform: scale(1.05);
        }

        .chat-window {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 350px;
            max-height: 500px;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
            border: 1px solid #1e67b4;
        }

        .chat-header {
            background-color: #1e67b4;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .company-info {
            display: flex;
            align-items: center;
        }

        .company-logo {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
            background-color: white;
        }

        .chat-header h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 500;
        }

        .close-button {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .message-list {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 350px;
        }

        .message {
            padding: 10px 15px;
            border-radius: 18px;
            max-width: 80%;
            line-height: 1.4;
            font-size: 14px;
        }

        .user-message {
            align-self: flex-end;
            background-color: #e3f2fd;
            border-bottom-right-radius: 5px;
        }

        .ai-message {
            align-self: flex-start;
            background-color: #f1f1f1;
            border-bottom-left-radius: 5px;
        }

        .system-message {
            align-self: flex-start;
            background-color: #f1f1f1;
            border-bottom-left-radius: 5px;
            color: #555;
        }

        .options-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .option-button {
            background-color: white;
            border: 1px solid #1e67b4;
            border-radius: 20px;
            padding: 8px 15px;
            text-align: left;
            color: #1e67b4;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .option-button:hover {
            background-color: #f0f8ff;
        }

        .message-input-form {
            display: flex;
            padding: 10px;
            border-top: 1px solid #eee;
            background-color: #f9f9f9;
        }

        .message-input-form input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
            font-size: 14px;
        }

        .message-input-form button {
            background-color: #1e67b4;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-left: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .message-input-form button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .chat-footer {
            padding: 8px;
            text-align: center;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-window {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body>
    <!-- Chatbot container -->
    <div id="togetherAIChat" class="floating-chatbot"></div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo chatbot
            initTogetherAIChat({
                companyName: "Connect Work",
                companyLogo: "Images/Background.png",
                primaryColor: "#1e67b4",
                apiKey: "{{ env('TOGETHER_API_KEY') }}" // Sử dụng biến môi trường từ Laravel
            });
        });

        function initTogetherAIChat(config) {
            const {
                companyName = "Connect Work",
                companyLogo = "/logo512.png",
                primaryColor = "#1e67b4",
                apiKey
            } = config;

            // Tạo state
            let state = {
                isOpen: false,
                messages: [
                    {
                        id: "1",
                        text: `Hello! ${companyName} are here to help.`,
                        sender: "system",
                        timestamp: new Date()
                    },
                    {
                        id: "2",
                        text: "Bạn cần hỗ trợ về điều gì?\n(What can we help you with?)",
                        sender: "system",
                        timestamp: new Date()
                    }
                ],
                input: "",
                isLoading: false,
                selectedOption: null,
                hasNewMessage: false
            };

            // Danh sách các tùy chọn mặc định
            const defaultOptions = [
                { id: "jobs", text: "Tìm kiếm việc làm" },
                { id: "recruitment", text: "Đăng tuyển nhân sự" },
                { id: "profile", text: "Quản lý hồ sơ" },
                { id: "interview", text: "Lịch phỏng vấn" },
                { id: "others", text: "Vấn đề khác" }
            ];

            // Tạo DOM elements
            const chatContainer = document.getElementById('togetherAIChat');
            renderChatbot();

            // Thiết lập mô phỏng tin nhắn mới
            setTimeout(() => {
                simulateNewMessage();
            }, 3000);

            // Render UI
            function renderChatbot() {
                // Xóa nội dung cũ
                chatContainer.innerHTML = '';

                // Chat window
                if (state.isOpen) {
                    const chatWindow = document.createElement('div');
                    chatWindow.className = 'chat-window';
                    chatWindow.style.borderColor = primaryColor;

                    // Header
                    const chatHeader = document.createElement('div');
                    chatHeader.className = 'chat-header';
                    chatHeader.style.backgroundColor = primaryColor;
                    
                    const companyInfo = document.createElement('div');
                    companyInfo.className = 'company-info';
                    
                    const logo = document.createElement('img');
                    logo.src = companyLogo;
                    logo.alt = companyName;
                    logo.className = 'company-logo';
                    
                    const title = document.createElement('h3');
                    title.textContent = companyName;
                    
                    const closeButton = document.createElement('button');
                    closeButton.className = 'close-button';
                    closeButton.innerHTML = '&#x2715;';
                    closeButton.onclick = toggleChat;
                    
                    companyInfo.appendChild(logo);
                    companyInfo.appendChild(title);
                    
                    chatHeader.appendChild(companyInfo);
                    chatHeader.appendChild(closeButton);
                    
                    chatWindow.appendChild(chatHeader);

                    // Message list
                    const messageList = document.createElement('div');
                    messageList.className = 'message-list';
                    
                    state.messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = `message ${message.sender}-message`;
                        messageDiv.textContent = message.text;
                        messageList.appendChild(messageDiv);
                    });

                    // Options
                    if (!state.selectedOption && state.messages.length <= 2) {
                        const optionsContainer = document.createElement('div');
                        optionsContainer.className = 'options-container';
                        
                        defaultOptions.forEach(option => {
                            const optionButton = document.createElement('button');
                            optionButton.className = 'option-button';
                            optionButton.style.borderColor = primaryColor;
                            optionButton.style.color = primaryColor;
                            optionButton.textContent = option.text;
                            optionButton.onclick = () => handleOptionSelect(option.text);
                            
                            optionsContainer.appendChild(optionButton);
                        });
                        
                        messageList.appendChild(optionsContainer);
                    }

                    // Loading indicator
                    if (state.isLoading) {
                        const loadingDiv = document.createElement('div');
                        loadingDiv.className = 'message ai-message';
                        loadingDiv.textContent = 'Đang suy nghĩ...';
                        messageList.appendChild(loadingDiv);
                    }

                    // Message end reference
                    const messagesEndRef = document.createElement('div');
                    messageList.appendChild(messagesEndRef);
                    
                    chatWindow.appendChild(messageList);

                    // Input form
                    const inputForm = document.createElement('form');
                    inputForm.className = 'message-input-form';
                    inputForm.onsubmit = handleSendMessage;
                    
                    const inputField = document.createElement('input');
                    inputField.type = 'text';
                    inputField.value = state.input;
                    inputField.placeholder = 'Chọn một tùy chọn';
                    inputField.disabled = state.isLoading;
                    inputField.oninput = (e) => updateInput(e.target.value);
                    console.log(state.isLoading);
                    const sendButton = document.createElement('button');
                    sendButton.type = 'submit';
                    sendButton.disabled = state.isLoading ;
                    sendButton.style.backgroundColor = primaryColor;
                    sendButton.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>';
                    
                    inputForm.appendChild(inputField);
                    inputForm.appendChild(sendButton);
                    
                    chatWindow.appendChild(inputForm);

                    // Footer
                    const chatFooter = document.createElement('div');
                    chatFooter.className = 'chat-footer';
                    chatFooter.textContent = 'Powered by QuynIT AI';
                    
                    chatWindow.appendChild(chatFooter);
                    
                    chatContainer.appendChild(chatWindow);

                    // Scroll to bottom
                    setTimeout(() => {
                        messagesEndRef.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }

                // Chat button
                const chatButton = document.createElement('button');
                chatButton.className = `chat-button ${state.isOpen ? 'open' : ''} ${state.hasNewMessage ? 'incoming' : ''}`;
                chatButton.onclick = toggleChat;
                chatButton.style.backgroundColor = primaryColor;
                
                if (state.isOpen) {
                    chatButton.innerHTML = '<svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path></svg>';
                } else {
                    chatButton.innerHTML = '<img width="50" height="50" src="Images/Background.png" alt="ChatAI" />';
                }
                
                chatContainer.appendChild(chatButton);
            }

            // Toggle chat open/close
            function toggleChat() {
                state.isOpen = !state.isOpen;
                
                if (state.isOpen) {
                    state.hasNewMessage = false;
                }
                
                renderChatbot();
            }

            // Update input value
            function updateInput(value) {
                state.input = value;
            }

            // Add message to chat
            function addMessage(text, sender) {
                const newMessage = {
                    id: Date.now().toString(),
                    text,
                    sender,
                    timestamp: new Date()
                };
                
                state.messages = [...state.messages, newMessage];
                renderChatbot();
            }

            // Handle option select
            function handleOptionSelect(optionText) {
                state.selectedOption = optionText;
                addMessage(optionText, 'user');
                sendMessageToAI(optionText);
            }

            // Send message to AI
            async function sendMessageToAI(userMessage) {
                state.isLoading = true;
                renderChatbot();
                
                try {
                    const response = await fetch('https://api.together.xyz/v1/completions', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${apiKey}`
                        },
                        body: JSON.stringify({
                            model: 'mistralai/Mixtral-8x7B-Instruct-v0.1',
                            prompt: `Bạn là trợ lý ảo của ${companyName}. Hãy trả lời câu hỏi này bằng tiếng Việt, và đưa ra các thông tin hữu ích: ${userMessage}\nAssistant:`,
                            max_tokens: 1000,
                            temperature: 0.7,
                            top_p: 0.9,
                            stop: ["\nUser:", "\n\nUser:"],
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.choices && data.choices.length > 0) {
                        const aiResponse = data.choices[0].text.trim();
                        addMessage(aiResponse, 'ai');
                    } else {
                        addMessage('Xin lỗi, tôi không thể xử lý yêu cầu của bạn.', 'ai');
                    }
                } catch (error) {
                    console.error('Error communicating with AI:', error);
                    addMessage('Đã xảy ra lỗi khi kết nối với AI.', 'ai');
                } finally {
                    state.isLoading = false;
                    renderChatbot();
                }
            }

            // Handle send message
            function handleSendMessage(e) {
                e.preventDefault();
                if (state.input.trim() === '') return;
                
                addMessage(state.input, 'user');
                sendMessageToAI(state.input);
                state.input = '';
                renderChatbot();
            }

            // Simulate new message
            function simulateNewMessage() {
                if (!state.isOpen) {
                    state.hasNewMessage = true;
                    renderChatbot();
                    
                    setTimeout(() => {
                        state.hasNewMessage = false;
                        renderChatbot();
                    }, 10000);
                }
            }
        }
    </script>
</body>
</html>