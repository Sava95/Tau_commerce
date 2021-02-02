<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Store; 
use App\Models\Product;
use App\Models\ProductStore; 
use Illuminate\Support\Facades\Validator;

use Auth;

class MainController extends Controller
{
    // ######################## Store ################################################
    public function store()
    {
        $uniqueCode = base_convert(sha1(uniqid(mt_rand())), 16, 36);
        $store_saved = 'False';

        return view('create_store',  compact('uniqueCode', 'store_saved'));
    }

    public function create_store(Request $request)
    {
        $uniqueCode = base_convert(sha1(uniqid(mt_rand())), 16, 36);

        // Validation
        $request->validate([
            'store_name' => 'required|min:5',
            'store_code' => 'required',
            'store_custom_url' => 'required'
        ]);

        // Creating new Store object
        $new_store = new Store;

        $new_store->name = $request->input('store_name');
        $new_store->code = $request->input('store_code');
        $new_store->base_url = $request->input('store_custom_url');
        $new_store->description = $request->input('store_description');
        $new_store->is_deleted = 0;
        $new_store->save();

        $store_saved = 'True';

        return view('create_store',  compact('uniqueCode', 'store_saved'));;
    }

    // ######################## Product ################################################
    public function product()
    {
        $uniqueCode = base_convert(sha1(uniqid(mt_rand())), 16, 36);
        
        $stores = Store::where('is_deleted', 0)->get();
        
        $data = [
            'uniqueCode' => $uniqueCode,
            'stores' => $stores,
        ];
        
        return view('create_product')->with($data);
    }

    public function create_product(Request $request)
    {
        $uniqueCode = base_convert(sha1(uniqid(mt_rand())), 16, 36);
        $custom_url = uniqid(mt_rand());
        $user = auth()->user();

        // Validation
        $validator = Validator::make($request->all(), [
            'prod_name' => 'required|min:5',
            'product_price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        } else { 
            // Creating new Product object
            $new_product = new Product;

            $new_product->name = $request->input('prod_name');
            $new_product->sku = $request->input('sku');
            $new_product->price = $request->input('product_price');
            $new_product->user_id = $user->id;
            $new_product->user_name = $user->name;
            $new_product->description = $request->input('product_description');
            $new_product->is_deleted = $request->input('is_deleted');

            if ($request->input('product_custom_url') != null) {
                $new_product->custom_url = $request->input('product_custom_url');
            } else { 
                $new_product->custom_url = $custom_url;
            }

            $new_product->save();

        
            // Creating new ProductStore relation
            $stores = Store::whereIn('name', $request->input('stores'))->where('is_deleted', 0)->get();

            foreach ($stores as $store) {
                $new_product_store = new ProductStore;
                $new_product_store->product_id = $new_product->id;
                $new_product_store->store_id = $store->id;
                $new_product_store->is_deleted = 0;
                $new_product_store->save();
            }
            

            return response()->json($uniqueCode);
        }
        
    }

    public function user_profile()
    {
        $products = Product::where('user_id', Auth::user()->id)->where('is_deleted', 0)->paginate(4);
        
        return view('your_ads', compact( 'products'));
    }
}