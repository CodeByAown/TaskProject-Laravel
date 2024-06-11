<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductModelController extends Controller
{
    public function index()
    {
        $models = ProductModel::with(['brand'])->withCount('items')->paginate(10);
        $brands = Brand::all(); // Fetch all brands
        return view('models.index', compact('models', 'brands'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id'
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        ProductModel::create([
            'name' => $request->name,
            'brand_id' => $request->brand_id
        ]);
    
        return redirect()->route('models.index')->with('success', 'Model added successfully');
    }
    
    public function edit(ProductModel $model)
    {
        $brands = Brand::all();
        return view('models.edit', compact('model', 'brands'));
    }
    
    public function update(Request $request, ProductModel $model)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id'
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $model->update([
            'name' => $request->name,
            'brand_id' => $request->brand_id
        ]);
    
        return redirect()->route('models.index')->with('success', 'Model updated successfully');
    }
    
    public function destroy(ProductModel $model)
    {
        $model->delete();
        return redirect()->route('models.index')->with('success', 'Model deleted successfully');
    }
    
}
