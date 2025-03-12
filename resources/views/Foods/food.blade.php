@extends('layout.app')


<!-- Form thêm món ăn mới -->
@section('content')


<div class="container mx-auto p-4">
    <div class="bg-[#ff8e4d] rounded-t-lg p-4 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <h2 class="text-white text-2xl font-bold">Flash Deals</h2>
            <div class="flex gap-1">
                <div class="bg-gray-800 text-white rounded px-2 py-1 text-sm">01</div>
                <div class=" text-white rounded px-1 py-1 text-sm">:</div>
                <div class="bg-gray-800 text-white rounded px-2 py-1 text-sm">55</div>
                <div class=" text-white rounded px-1 py-1 text-sm">:</div>
                <div class="bg-gray-800 text-white rounded px-2 py-1 text-sm">05</div>
            </div>
        </div>
        <a href="#" class="text-white hover:underline">Xem tất cả</a>
    </div>
    
    <!-- foods Grid -->
    <div class="bg-[#ff8e4d] p-4 rounded-b-lg mb-7">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach ($foods->sortBy('price')->take(6) as $food)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full relative group transition-all">
                <!-- Discount Badge -->
                <div class="absolute top-2 left-2 bg-orange-500 text-white rounded-full text-xs px-2 py-1 z-10">
                    {{ rand(1, 5) }}%
                </div>
                
                <!-- food Image -->
                <div class="relative pt-2 flex justify-center items-center h-48">
                    <img src="{{ $food->image }}" alt="{{ $food->name }}" class="max-h-full object-contain">
                </div>
                
                <!-- food Info -->
                <div class="p-3 flex-grow flex flex-col">
                    <!-- Price Info -->
                    <div class="flex items-center mb-1">
                        <span class="text-orange-500 font-bold">{{ number_format( rand(0, 20000) , 0, ',', '.') }} đ</span>
                        <span class="text-gray-400 text-xs line-through ml-2">{{ number_format($food->price, 0, ',', '.') }} đ</span>
                    </div>
                    
                    <!-- food Name -->
                    <h3 class="text-sm line-clamp-2 mb-2 flex-grow">{{ $food->name }}</h3>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                        <div class="bg-orange-400 h-2 rounded-full" style="width: {{ $food->sold_percentage }}%"></div>
                    </div>
                    
                    <div class="text-xs text-gray-500">Đã bán {{ rand(1, 20) }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- food Card Template -->
        @foreach ($foods as $food)
        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100 flex flex-col h-[530px] relative transition-transform transform hover:scale-105 hover:shadow-xl">

                <img src="Images/logoprodcut.jpg" alt="NewFree" class="h-14 w-full">

            <div class="pt-2 flex justify-center items-center h-64 rounded-3xl">
                <img src="{{ $food->image }}" alt="{{ $food->name }}" class="max-h-full object-contain">
            </div>
            
            <!-- food Info -->
            <div class="p-4 flex-grow pb-1 h-5">
                <!-- Brand -->
                <h3 class="text-gray-700 font-medium mb-1">{{ $food->name }}</h3>
                
                <!-- food Name -->
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
                 <form action="{{ route('cart.add') }}" method="POST" class="absolute right-4 bottom-10">
                    @csrf
                    <input type="hidden" name="food_id" value="{{ $food->id }}">
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                </form>
            </div>
            
           
        </div>
        @endforeach
        <!-- Repeat the food card for each food -->
        
    </div>
</div>


@endsection

