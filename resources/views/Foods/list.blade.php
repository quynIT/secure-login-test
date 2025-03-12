@extends('layout.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Tiêu đề trang -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Quản lý món ăn</h1>
        <button id="showFormBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md transition duration-300 ease-in-out flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Thêm món ăn
        </button>
    </div>

    <!-- Form popup -->
    <div id="foodForm" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md transform transition-all duration-300 ease-in-out">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Thêm Món Ăn</h2>
                <button type="button" id="closeFormBtn" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tên món ăn:</label>
                    <input class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" type="text" name="name" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả:</label>
                    <textarea class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" name="description" rows="2" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giá:</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">$</span>
                        <input class="w-full border border-gray-300 p-2 pl-8 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" type="number" name="price" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Danh mục:</label>
                    <select name="category_id" class="w-full border border-gray-300 p-2 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach ($category as $categories)
                            <option value="{{ $categories->id }}">{{ $categories->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hình ảnh:</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col w-full h-32 border-2 border-dashed border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer">
                            <div class="flex flex-col items-center justify-center pt-7">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400 group-hover:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                    Tải ảnh lên
                                </p>
                            </div>
                            <input type="file" name="image" class="opacity-0" required />
                        </label>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="closeFormBtnBottom" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md transition">
                        Hủy
                    </button>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md shadow-sm transition">
                        Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng danh sách món ăn -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hình ảnh</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên món ăn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Danh mục</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($foods as $food)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $food->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ asset($food->image) }}" alt="{{ $food->name }}" class="h-16 w-16 object-cover rounded-md border border-gray-200">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $food->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $food->description }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">${{ number_format($food->price, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $food->category_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                            <div class="flex space-x-2">
                                <a href="{{ route('foods.edit', $food->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md transition">
                                    Sửa
                                </a>
                                <form action="/foods/delete/{{ $food->id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded-md transition" onclick="return confirm('Bạn có chắc chắn muốn xóa món ăn này?')">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const showFormBtn = document.getElementById("showFormBtn");
        const closeFormBtn = document.getElementById("closeFormBtn");
        const closeFormBtnBottom = document.getElementById("closeFormBtnBottom");
        const foodForm = document.getElementById("foodForm");

        showFormBtn.addEventListener("click", function() {
            foodForm.classList.remove("hidden");
            setTimeout(() => {
                foodForm.querySelector('div').classList.add('scale-100');
                foodForm.querySelector('div').classList.remove('scale-95');
            }, 10);
        });

        const closeForm = function() {
            foodForm.querySelector('div').classList.add('scale-95');
            foodForm.querySelector('div').classList.remove('scale-100');
            setTimeout(() => {
                foodForm.classList.add("hidden");
            }, 300);
        };

        closeFormBtn.addEventListener("click", closeForm);
        closeFormBtnBottom.addEventListener("click", closeForm);

        // Đóng form khi click ra ngoài
        foodForm.addEventListener("click", function(event) {
            if (event.target === foodForm) {
                closeForm();
            }
        });
    });
</script>