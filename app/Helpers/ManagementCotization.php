<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

Class ManagementCotization {
    // Agregar item
    static public function addItemToCart($product_id){

        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;
        foreach ($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }
        if ($existing_item !== null){
            $cart_items[$existing_item]['quantity']++;
            $cart_items[$existing_item]['total_amount']= $cart_items[$existing_item]['quantity'] *  $cart_items[$existing_item]['unit_amount'];
        }else{
            $product = Product::where('id',$product_id)->first(['id','name','price','images']);
            if($product){
                $cart_items[]=[
                    'product_id'    => $product_id,
                    'name'          => $product->name,
                    'image'         => $product->images[0],
                    'quantity'      => 1,
                    'unit_amount'   => $product->price,
                    'total_amount'  => $product->price
                ];
            }
        }
        self::addCartItemsToCooke($cart_items);
        return count($cart_items);
    }

    // Eliminar item
    static public function removeCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        foreach ($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
               unset($cart_items[$key]);
            //    // TODO: Revisar si ponemos el break
            //     break;
            }
        }
        self::addCartItemsToCooke($cart_items);
        return $cart_items;
    }

    // Colocar carrito en la cookie
    static public function addCartItemsToCooke($cart_items){
        Cookie::queue('cart_items',json_encode($cart_items),60*24*30); // 30 Días
    }

    // Quita el carrito de la cookie
    static public function clearCartItems(){
        Cookie::queue(Cookie::forget('cart_items'));
    }

    // Lee el carrito de la cooki
    static public function getCartItemsFromCookie(){
        $cart_items = json_decode(cookie::get('cart_items'),true);
        if(!$cart_items){
            $cart_items = [];
        }
        return $cart_items;
    }
    // Incrementar Cantidad
    static public function incrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        $existing_item = null;
        foreach ($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                $cart_items[$key]['quantity']++;
                $cart_items[$key]['total_amount']= $cart_items[$key]['quantity'] *  $cart_items[$existing_item]['unit_amount'];
            }
        }
        self::addCartItemsToCooke($cart_items);
        return $cart_items;
    }

    // Decrementar Cantidad
    static public function decrementQuantityToCartItem($product_id){
        $cart_items = self::getCartItemsFromCookie();
        $existing_item = null;
        foreach ($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                if( $cart_items[$key]['quantity'] > 1){
                    $cart_items[$key]['quantity']--;
                    $cart_items[$key]['total_amount']= $cart_items[$key]['quantity'] *  $cart_items[$existing_item]['unit_amount'];
                }
            }
        }
        self::addCartItemsToCooke($cart_items);
        return $cart_items;
    }
    // Calcular el gran total

    static public function calculateGrandTotal($items)
    {
        return array_sum(array_column($items,'total_amount'));

    }

    static public function addItemToCartWithQty($product_id,$qty=1){

        $cart_items = self::getCartItemsFromCookie();

        $existing_item = null;
        foreach ($cart_items as $key => $item){
            if($item['product_id'] == $product_id){
                $existing_item = $key;
                break;
            }
        }
        if ($existing_item !== null){
            $cart_items[$existing_item]['quantity'] = $qty;
            $cart_items[$existing_item]['total_amount']= $cart_items[$existing_item]['quantity'] *  $cart_items[$existing_item]['unit_amount'];
        }else{
            $product = Product::where('id',$product_id)->first(['id','name','price','images']);
            if($product){
                $cart_items[]=[
                    'product_id'    => $product_id,
                    'name'          => $product->name,
                    'image'         => $product->images[0],
                    'quantity'      => $qty,
                    'unit_amount'   => $product->price,
                    'total_amount'  => $product->price
                ];
            }
        }
        self::addCartItemsToCooke($cart_items);
        return count($cart_items);
    }


}
