<?php

namespace App\Http\Controllers;

use App\CyProduct;
use App\CyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CyProductController extends Controller
{
    public function index()
    {
        $products = CyProduct::all();
        return view('admin.cy_plant.cy_store.cy_product.index', compact('products'));
    }

    public function create(Request $request)
    {
        $product_types = CyType::all();
        return view('admin.cy_plant.cy_store.cy_product.create' , compact('product_types'));
    }

    public function store(Request $request)
    {
        $product_types = $request->all();
        if($request->hasFile('img')){
            $file_name = $request ->file('img')->store('','public');
            $product_types['img'] = $file_name;
        }
        CyProduct::create($product_types);
        return redirect('/home/cy_product_list');
    }

    public function edit($id)
    {
        $products = CyProduct::find($id);
        $product_types = CyType::all();
        return view('admin.cy_plant.cy_store.cy_product.edit', compact('products' , 'product_types'));
    }

    public function update(Request $request, $id)
    {
        $request_data = $request->all();
        $item =CyProduct::find($id);

        // 判斷是否有更新圖片
        if($request->hasFile('img')){
            // 刪除原有圖片
            Storage::disk('public')->delete($item->img);
            // 上傳新的圖片
            $file_name = $request->file('img')->store('','public');
            $request_data['img'] = $file_name;
        }


        $item->update($request_data);
        return redirect('/home/cy_product_list');
    }

    public function destroy($id)
    {
        $item = CyProduct::find($id);
        $origin_img = $item->img;
        if(Storage::disk('public')->exists($origin_img)){
        Storage::disk('public')->delete($origin_img);
        }
        $item->delete();
        return redirect('home/cy_product_list');
    }
}
