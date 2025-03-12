<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Foods;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function food()
{
    $foods = Foods::all();
    $categories = Category::all(); // Lấy danh sách danh mục

    return view('foods.food', [
        'foods' => $foods,
        'category' => $categories 
    ]);
}
public function list()
{
    $foods = Foods::all();
    $categories = Category::all(); // Lấy danh sách danh mục

    return view('foods.list', [
        'foods' => $foods,
        'category' => $categories 
    ]);
}
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price' => 'required|numeric',
        'category_id' => 'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $generatedImageName = 'image_' . time() . '.' . $request->image->extension();
    
    $request->image->move(public_path('images'), $generatedImageName);
    
    Foods::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'category_id' => $request->category_id,
        'image' => 'images/' . $generatedImageName, 
    ]);
    
    return redirect()->route('foods.index')->with('success', 'Món ăn đã được thêm!');
}
    public function edit($id)
    {
        $food = Foods::findOrFail($id);
        return view('foods.edit', compact('food'));
    }

    // Xử lý cập nhật dữ liệu
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
        ]);

        $food = Foods::findOrFail($id);
        $food->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('foods.index')->with('success', 'Cập nhật thành công!');
    }
    public function delete($id)
{
    $food = Foods::findOrFail($id);
    $food->delete();

    return redirect()->route('foods.index')->with('success', 'Xóa thành công!');
}

}
