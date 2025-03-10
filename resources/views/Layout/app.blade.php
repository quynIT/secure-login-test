<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) 
</head>
<body>
    @include('Layout.header')
    @yield('content')
    <div id="togetherAIChat" class="floating-chatbot"></div>
    @include('Layout.chatai')
    @include('Layout.footer')   
</body>
</html>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        initTogetherAIChat({
            companyName: "Connect Work",
            companyLogo: "Images/Background.png",
            primaryColor: "#1e67b4",
            apiKey: "{{ env('TOGETHER_API_KEY') }}" 
        });
    });
</script>