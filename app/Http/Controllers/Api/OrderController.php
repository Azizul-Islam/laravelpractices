<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderResource;
use App\Models\OrderProduct;
use App\Models\Order;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepository;

    /**
     * The function is a constructor that injects an OrderRepositoryInterface dependency into the
     * class.
     * 
     * @param OrderRepositoryInterface orderRepository The `orderRepository` parameter in the
     * constructor is an instance of a class that implements the `OrderRepositoryInterface`. This
     * parameter is injected into the class through dependency injection, allowing the class to
     * interact with and utilize the methods provided by the `OrderRepositoryInterface` implementation.
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
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
     * The function `store` accepts an `OrderRequest` object, creates a new order using the data from
     * the request, and returns a JSON response with a success message and the order data, handling any
     * exceptions by dumping the error message.
     * 
     * @param OrderRequest request The `store` function in the code snippet is responsible for storing
     * an order based on the data provided in the `OrderRequest` object. Here's a breakdown of the
     * code:
     * 
     * @return The `store` function is returning a JSON response with a success message and the data of
     * the created order. The response includes a 'msg' key with the value 'Order submit success' and a
     * 'data' key with the value of the created order.
     */
    public function store(OrderRequest $request)
    {
        try {
            $data = $request->all();
            $order = $this->orderRepository->create($data);
            return response()->json([
                'msg' => 'Order submit success',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
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
     * The function `customerOrder` retrieves and returns orders belonging to the authenticated user in
     * a JSON response, handling cases where orders are found or not found.
     * 
     * @return If the `` variable is set and contains data, a JSON response with the orders data
     * and a success message 'Orders find success' is being returned. If the `` variable is not
     * set or empty, a JSON response with an empty data field and a message 'Order not found!' is being
     * returned. If an exception occurs during the execution of the code, the error message will be
     */
    public function curtomerOrder()
    {
        try {
            $orders = Order::where('user_id', auth()->user()->id)->latest()->get();
            if (isset($orders)) {
                return response()->json([
                    'data' => $orders,
                    'msg' => 'Orders find success'
                ]);
            }
            return response()->json([
                'data' => '',
                'msg' => 'Order not found!'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
