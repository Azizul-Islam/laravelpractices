<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderProduct;

class OrderRepository implements OrderRepositoryInterface
{
   /**
    * The function creates a new order in a PHP application, associates it with a user, saves order
    * details, and inserts order products into a database table.
    * 
    * @param array request The `create` function you provided seems to be creating an order with its
    * associated products. The function takes an array `` as a parameter, which likely contains
    * the necessary data for creating the order and its products.
    * 
    * @return The `create` function is returning the newly created Order object after storing the order
    * details and associated products in the database.
    */
    public function create(array $request)
    {
        //store order
        $order = new Order;
        $order->order_number = 'ORD-' . rand();
        $order->user_id = auth()->user()->id;
        $order->total_amount = $request['total'];
        $order->payment_method = $request['payment_method'];
        $order->save();

        $orderProducts = [];
        foreach ($request['carts'] as $cart) {
            $orderProducts[] = [
                'product_id' => $cart['product_id'],
                'order_id' => $order->id,
                'qty' => $cart['qty'],
                'price' => $cart['price']
            ];
        }
        //insert order_product table
        OrderProduct::insert($orderProducts);
        return $order;
    }
}
