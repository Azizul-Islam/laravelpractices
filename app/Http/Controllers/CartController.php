<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart($a,$product_id)
    {
        $product = Product::findOrFail($product_id);
        $cart = session()->get('cart');
        if(!$cart){
            $cart = [
                $product->id => [
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'qty' => 1,
                    'price' => $product->amount
                    ]
                ];
                session()->put('cart',$cart);
                return redirect()->route('dealer.product.index','dealer')->with('success','Added to cart');
        }
        if(isset($cart[$product->id])){
            $cart[$product->id]['qty']++;
            session()->put('cart',$cart);
            return redirect()->route('dealer.product.index','dealer')->with('success','Added to cart');
        }
        $cart[$product->id] = [
            'product_id' => $product->id,
            'title' => $product->title,
            'qty' => 1,
            'price' => $product->amount
        ];
        session()->put('cart',$cart);
        return redirect()->route('dealer.product.index','dealer')->with('success','Added to cart');
        
    }
    function removeCart($null,$id)
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])){
            unset($cart[$id]);
            session()->put('cart',$cart);
        }
        return redirect()->back()->with('warning','Remove from cart');
    }
}
