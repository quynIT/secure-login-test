<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foods;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $food_id = $request->food_id;
        $food = Foods::findOrFail($food_id);
        
        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);
        
        // Nếu sản phẩm đã có trong giỏ, tăng số lượng
        if(isset($cart[$food_id])) {
            $cart[$food_id]['quantity']++;
        } else {
            // Nếu sản phẩm chưa có trong giỏ, thêm mới
            $cart[$food_id] = [
                "id" => $food->id,
                "name" => $food->name,
                "quantity" => 1,
                "price" => $food->price,
                "image" => $food->image,
                "description" => $food->description
            ]; 
        }
        
        // Lưu giỏ hàng vào session
        session()->put('cart', $cart);
        
        return redirect()->route('cart.view')->with('success', 'Thêm sản phẩm vào giỏ hàng thành công!');
    }
    
    public function viewCart()
    {
        $cart = session()->get('cart', []);
        return view('cart.view', compact('cart'));
    }
    
    public function removeFromCart(Request $request)
    {
        $food_id = $request->food_id;
        
        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);
        
        if(isset($cart[$food_id])) {
            unset($cart[$food_id]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.view')->with('success', 'Xóa sản phẩm khỏi giỏ hàng thành công!');
    }
    
    // Cập nhật số lượng
    public function updateCart(Request $request)
    {
        $food_id = $request->food_id;
        $quantity = $request->quantity;
        
        // Lấy giỏ hàng hiện tại từ session
        $cart = session()->get('cart', []);
        
        if(isset($cart[$food_id])) {
            $cart[$food_id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.view')->with('success', 'Cập nhật giỏ hàng thành công!');
    }
    
    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('cart.view')->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}