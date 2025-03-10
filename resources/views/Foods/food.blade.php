@extends('layout.app')
<!-- Form thêm món ăn mới -->
@section('content')
<button id="showFormBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg mb-4 right-8 mt-5 fixed z-50 cursor-pointer">
    + Thêm món ăn
</button>


<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Product Card Template -->
        @foreach ($foods as $food)
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 flex flex-col h-[530px] relative transition-transform transform hover:scale-105 hover:shadow-xl">

                <img src="Images/logoprodcut.jpg" alt="NewFree" class="h-14 w-full">

            <div class="pt-2 flex justify-center items-center h-64 rounded-3xl">
                <img src="{{ $food->image }}" alt="{{ $food->name }}" class="max-h-full object-contain">
            </div>
            
            <!-- Product Info -->
            <div class="p-4 flex-grow pb-1 h-5">
                <!-- Brand -->
                <h3 class="text-gray-700 font-medium mb-1">{{ $food->name }}</h3>
                
                <!-- Product Name -->
                <h2 class="text-sm line-clamp-2 mb-2">{{ $food->description }}</h2>
                
                <!-- Ratings -->
                <div class="flex items-center mb-2">
                    <div class="flex items-center bg-orange-500 text-white px-2 rounded">
                        <span class="font-medium">{{ rand(1, 5) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                    <span class="text-gray-500 text-sm ml-2">({{ rand(1, 100) }})</span>
                    
                    <div class="flex-grow ml-16">
                        <div class="flex items-center">
                            <span class="text-gray-500 text-sm line-through mr-2">10000 đ</span>
                            <span class="bg-orange-500 text-white text-xs px-1 rounded">{{ $food->discount_percent }}%</span>
                        </div>
                        
                    </div>
                    
                    <!-- Sales Count -->
                    <span class="text-gray-500 text-sm">{{ $food->sales_count }}</span>
                </div>

                <div class="mt-2">
                    <div class="text-orange-500 font-bold text-xl mt-1">
                        {{ number_format($food->price, 0, ',', '.') }} đ
                    </div>
                </div>
                <div class="px-4 pb-3 mt-3">
                    <div class="bg-orange-100 text-orange-800 text-sm rounded px-3 py-1 text-center">
                        Đang bán chạy
                    </div>
                </div>
                 <!-- Add to Cart Button -->
            <div class="absolute right-4 bottom-10">
                <button class="bg-orange-500 hover:bg-orange-600 text-white p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </button>
            </div>
            </div>
            
           
        </div>
        @endforeach
        <!-- Repeat the product card for each product -->
        
    </div>
</div>

<div id="foodForm" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-30  z-50">
    <!-- Form Box -->
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-xl font-semibold text-center mb-4">Thêm Món Ăn</h2>
        
        <form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf

            <div>
                <label class="block font-medium">Tên món ăn:</label>
                <input class="w-full border p-2 rounded" type="text" name="name" required>
            </div>

            <div>
                <label class="block font-medium">Mô tả:</label>
                <input class="w-full border p-2 rounded" type="text" name="description" required>
            </div>

            <div>
                <label class="block font-medium">Giá:</label>
                <input class="w-full border p-2 rounded" type="number" name="price" required>
            </div>

            <div>
                <label class="block font-medium">Danh mục:</label>
                <select name="category_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach ($category as $categories)
                        <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium">Hình ảnh:</label>
                <input type="file" name="image" class="w-full border p-2 rounded" required>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between">
                <button type="button" id="closeFormBtn" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                    Đóng
                </button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Thêm món ăn
                </button>
            </div>
        </form>
    </div>
</div>


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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const showFormBtn = document.getElementById("showFormBtn");
        const closeFormBtn = document.getElementById("closeFormBtn");
        const foodForm = document.getElementById("foodForm");

        showFormBtn.addEventListener("click", function() {
            foodForm.classList.remove("hidden");
        });

        closeFormBtn.addEventListener("click", function() {
            foodForm.classList.add("hidden");
        });

        // Đóng form khi click ra ngoài
        foodForm.addEventListener("click", function(event) {
            if (event.target === foodForm) {
                foodForm.classList.add("hidden");
            }
        });
    });
</script>