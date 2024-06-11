<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Item;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;


class ItemController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'id');
        $direction = $request->input('direction', 'asc');
    
        $query = Item::query();
    
        switch ($sort) {
            case 'brand':
                $query->join('brands', 'items.brand_id', '=', 'brands.id')
                    ->select('items.*', 'brands.name as brand_name')
                    ->orderBy('brands.name', $direction);
                break;
            case 'model':
                $query->leftJoin('product_models', 'items.model_id', '=', 'product_models.id')
                    ->select('items.*', 'product_models.name as model_name')
                    ->orderBy('product_models.name', $direction);
                break;
            default:
                $query->orderBy($sort, $direction);
                break;
        }
    
        $items = $query->with('brand', 'productModel')->paginate(10);
        $brands = Brand::all();
        $models = ProductModel::all();
    
        return view('items.index', compact('items', 'brands', 'models'));
    }
    
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'nullable|exists:product_models,id',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // If model_id is not present in the request, set it to null
        $data = $request->except(['_token']);
        if (!isset($data['model_id'])) {
            $data['model_id'] = null;
        }
    
        Item::create($data);
    
        return redirect()->route('items.index')->with('success', 'Item added successfully');
    }
    

    public function update(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'brand_id' => 'required|exists:brands,id',
            'model_id' => 'nullable|exists:product_models,id',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        $item->update($request->all());
    
        return redirect()->route('items.index')->with('success', 'Item updated successfully');
    }
    

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully');
    }

    public function getModelsByBrand($brand_id)
    {
        $models = ProductModel::where('brand_id', $brand_id)->pluck('name', 'id');
        return response()->json($models);
    }

    public function itemexport()
    {
        $items = Item::with('brand', 'productModel')->get();
    
        $csvFileName = 'items_export_' . date('YmdHis') . '.csv';
    
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $csvFileName . '"',
        ];
    
        $callback = function () use ($items) {
            $file = fopen('php://output', 'w');
    
            // CSV header
            fputcsv($file, ['ID', 'Name', 'Brand', 'Model', 'Date Added']);
    
            // CSV data rows
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->brand->name,
                    $item->productModel->name ?? 'N/A',
                    $item->created_at,
                ]);
            }
    
            fclose($file);
        };
    
        return Response::stream($callback, 200, $headers);
    }
    
}
