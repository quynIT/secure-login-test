<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1 class="bg-">Đây là trang product</h1>
    @foreach ($names as $eachName)
        <h3>Tên hello: {{$eachName}}</h3>
    @endforeach
</body>
</html>