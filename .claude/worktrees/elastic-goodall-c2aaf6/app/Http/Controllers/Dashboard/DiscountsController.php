<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Validator;

class DiscountsController extends Controller
{

    // get discount view
    public function getDiscountsView(Request $request)
    {
        $products = Product::select('id', 'product_id', 'description', 'retail_price')->get();

        return view('dashboard.pages.discounts.discount')->with(compact('products'));
    }


    // save discount for all products selected
    public function saveDiscounts(Request $request)
    {

        $rules = [
            'products' => 'required',
            'discount_value' => 'required',
            'discount_type' => 'required',
        ];

        $messages = [
            'products.required' => 'Please select products ',
            'discount_value.required' => 'Please enter product Discount Value',
            'discount_type.required' => 'Please enter product  Discount Type',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $request->validate($rules, $messages);

        try {
            $products = $request->products;
            $discount_type = $request->discount_type;
            $discount_value = $request->discount_value;

            foreach ($products as $id) {
                $product = Product::find($id);
                $product->update([
                    'discount_type' => $discount_type,
                    'discount_value' => $discount_value,
                ]);
                if ($product->discount_type == 'fixed') {
                    $product->retail_price -= $product->discount_value;
                } else {
                    $product->retail_price = (($product->retail_price) - (($product->retail_price) * ($product->discount_value / 100)));
                }

                $product->save();
            }

            session()->flash('success', 'The Discout Has Been Applied To Choosed Products Successfully!');

            return redirect()->route('dashboard.discounts-view');
        } catch (\Exception $e) {

        }

    }
}
