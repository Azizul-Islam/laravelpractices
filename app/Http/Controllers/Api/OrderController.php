<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Models\OrderProduct;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * The index function retrieves the latest orders and returns them as a JSON response using
     * OrderResource.
     * 
     * @return An array is being returned with a key 'data' containing a collection of OrderResource
     * objects for the latest 10 orders paginated by 10.
     */
    function index()
    {
        $orders = Order::latest()->paginate(10);
        return response()->json([
            'data' => OrderResource::collection($orders)
        ]);
    }


    /**
     * The function `show` retrieves an order by its ID and returns its details in JSON format using a
     * resource class.
     * 
     * @param id The `show` function in the code snippet is responsible for retrieving and displaying
     * details of a specific order based on the provided `` parameter. The `` parameter is used
     * to identify the order that needs to be fetched from the database.
     * 
     * @return A JSON response containing the data of the order with the specified ID, formatted using
     * the OrderDetailsResource class.
     */
    public function show($id)
    {
        try {
            $order = Order::findOrFail($id);
            return response()->json([
                'data' => new OrderDetailsResource($order)
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    /**
     * The function `placeOrder` in PHP stores an order with order details and related products in the
     * database and returns a JSON response with a success message and order data.
     * 
     * @param Request request The `placeOrder` function you provided is responsible for storing an order
     * and its related products in the database. It takes a `Request` object as a parameter, which
     * likely contains the necessary data for creating the order.
     * 
     * @return The `placeOrder` function is returning a JSON response with a success message 'Order
     * submit success' and the data of the newly created order. The data includes the order details such
     * as order number, user ID, total amount, and payment method.
     */
    public function placeOrder(Request $request)
    {
        try {
            //store order
            $order = new Order;
            $order->order_number = 'ORD-' . rand();
            $order->user_id = auth()->user()->id;
            $order->total_amount = $request->total;
            $order->payment_method = $request->payment_method;
            $order->save();

            $orderProducts = [];
            foreach ($request->carts as $cart) {
                $orderProducts[] = [
                    'product_id' => $cart['product_id'],
                    'order_id' => $order->id,
                    'qty' => $cart['qty'],
                    'price' => $cart['price']
                ];
            }
            //insert order_product table
            OrderProduct::insert($orderProducts);
            return response()->json([
                'msg' => 'Order submit success',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
