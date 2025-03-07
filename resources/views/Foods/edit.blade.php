<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa món ăn</title>
</head>
<body>
    <h1>Chỉnh sửa món ăn</h1>

    <form action="{{ route('foods.update', $food->id) }}" method="POST">
        @csrf
        <label for="name">Tên món ăn:</label>
        <input type="text" name="name" value="{{ $food->name }}" required>
        <br>

        <label for="description">Mô tả:</label>
        <input type="text" name="description" value="{{ $food->description }}" required>
        <br>

        <label for="price">Giá:</label>
        <input type="number" name="price" value="{{ $food->price }}" required>
        <br>

        <button type="submit">Cập nhật</button>
    </form>
</body>
</html>
