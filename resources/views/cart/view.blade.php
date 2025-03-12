@extends('layout.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Giỏ hàng của bạn</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(count($cart) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Sản phẩm</th>
                        <th class="text-left py-2">Giá</th>
                        <th class="text-left py-2">Số lượng</th>
                        <th class="text-left py-2">Tổng</th>
                        <th class="text-left py-2">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0 @endphp
                    
                    @foreach($cart as $id => $details)
                        @php $total += $details['price'] * $details['quantity'] @endphp
                        <tr class="border-b">
                            <td class="py-4">
                                <div class="flex items-center">
                                    <img src="{{ $details['image'] }}" alt="{{ $details['name'] }}" class="w-16 h-16 object-contain mr-4">
                                    <div>
                                        <div class="font-bold">{{ $details['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($details['description'], 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">{{ number_format($details['price'], 0, ',', '.') }}đ</td>
                            <td class="py-4">
                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                    @csrf
                                    <input type="hidden" name="food_id" value="{{ $id }}">
                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1" class="w-16 border border-gray-300 rounded p-1 text-center">
                                    <button type="submit" class="ml-2 bg-gray-200 px-2 py-1 rounded text-sm">Cập nhật</button>
                                </form>
                            </td>
                            <td class="py-4 font-bold">{{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}đ</td>
                            <td class="py-4">
                                <form action="{{ route('cart.remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="food_id" value="{{ $id }}">
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between border-b pb-4">
                <div class="text-lg font-bold">Tổng cộng:</div>
                <div class="text-lg font-bold text-orange-500">{{ number_format($total, 0, ',', '.') }}đ</div>
            </div>
            
            <div class="flex justify-between items-center mt-4">
                <a href="{{ url('/') }}" class="text-orange-500 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Tiếp tục mua hàng
                </a>
                
                <div class="flex space-x-4">
                    <form action="{{ route('cart.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Xóa giỏ hàng</button>
                    </form>
                    
                    <a href="#" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">Thanh toán</a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <p class="text-lg mb-4">Giỏ hàng của bạn đang trống</p>
            <a href="{{ url('/') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded inline-block">
                Tiếp tục mua hàng
            </a>
        </div>
    @endif
</div>
@endsection