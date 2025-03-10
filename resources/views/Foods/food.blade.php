@extends('layout.app')
<!-- Form thêm món ăn mới -->
@section('content')
    
<h2>Thêm món ăn mới haha hehe</h2>
<form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="name">Tên món ăn:</label>
    <input class="border-2" type="text" name="name" required>
    <br>

    <label for="description">Mô tả:</label>
    <input class="border-2" type="text" name="description" required>
    <br>

    <label for="price">Giá:</label>
    <input class="border-2" type="number" name="price" required>
    <br>

    <label for="category_id">Danh mục:</label>
    <select name="category_id" required>
        <option value="">-- Chọn danh mục --</option>
        @foreach ($category as $categories)
            <option value="{{ $categories->id }}">{{ $categories->name }}</option>
        @endforeach
    </select>
    <br>

    <label for="image">Hình ảnh:</label>
    <input type="file" name="image" required>
    <br>

    <button type="submit" class="border-2 p-2 bg-green-500 rounded-3xl">Thêm món ăn</button>
</form>
<thead>
    <tr>
        <th>ID</th>
        <th>Tên món ăn</th>
        <th>Mô tả</th>
        <th>Giá</th>
        <th>Danh mục</th>
        <th>image</th> <!-- Thêm cột -->
    </tr>
</thead>
</br>
<tbody>
    @foreach ($foods as $food)
    <tr>
        <td class="p-4 ">{{ $food->id }}</td>
        <td class="p-4 ">{{ $food->name }}</td>
        <td class="p-4 ">{{ $food->description }}</td>
        <td class="p-4 ">{{ $food->price }} $</td>
        <td class="p-4 ">{{ $food->category_id }}</td> <!-- Hiển thị category -->
        <td class="p-4 ">
            <a href="{{ route('foods.edit', $food->id) }}">Edit</a>
        </td>
        <td>
            <form action="/foods/delete/{{ $food->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red p-2">Delete</button>
            </form>
        </td>
     
        <td class="p-4">
            {{-- @dd( $food->image); --}}
            <img src="{{ asset($food->image) }}" alt="{{ $food->image }}" width="100">
        </td>
       
    </tr>
    @endforeach
</tbody>
@endsection
