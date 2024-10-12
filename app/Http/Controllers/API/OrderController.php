<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\Order;

use App\Models\Order_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allOrder = Order::with('orderItems') ->get();
        return response()->json($allOrder);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function userOrder()
    {
        $user = auth()->guard('user')->id();

        $userOrder = Order::with('orderItems')->where('owner_id', $user)->get();
        return response()->json($userOrder);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validatedData = $request->validate([
                'owner_id' => 'required|exists:users,id',
                'user_name' => 'required|string',
                'user_email' => 'required|email',
                'shipping_name' => 'required|string',
                'shipping_email' => 'required|email',
                'shipping_phone' => 'required|string',
                'shipping_city' => 'required|string',
                'shipping_upzila' => 'required|string',
                'shipping_address' => 'required|string',
                'shipping_zip' => 'required|string',
                'sub_total' => 'required|numeric',
                'shipping_cost' => 'required|numeric',
                'bill' => 'required|numeric',
                'payment_method' => 'required|string',
                'transaction_id' => 'nullable|string',
                'account_no' => 'nullable|string',
               'order_items' => 'required|array',
                      'order_items.*.item_id' => 'required|integer|min:1',
                'order_items.*.name' => 'required|string',
                'order_items.*.price' => 'required|numeric',
                'order_items.*.image' => 'nullable|string',
                'order_items.*.quantity' => 'required|integer|min:1',
            ]);

            // Remove order_items from validatedData
            $orderItems = $validatedData['order_items'];
            unset($validatedData['order_items']);

            // Create the order
            $order = Order::create($validatedData);

            if (!$order) {
                throw new \Exception('Failed to create order');
            }

            // Loop through order items and create them
            foreach ($orderItems as $item) {
                $orderItem = $order->orderItems()->create($item);
                if (!$orderItem) {
                    throw new \Exception('Failed to create order item');
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('orderItems')
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'An error occurred while creating the order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //

        if(auth()->guard('admin')->check()){

            $order = Order::with('orderItems')->find($id);
           if(!$order){
               return response()->json([
                   'message' => 'Order not found'
               ],404);
           }
            return response()->json($order);
        }
        $owner = auth()->guard('user')->id();
        if(!$owner){
            return response()->json([
                'message' => 'You must be login'
            ],401);
        }
        $order = Order::with('orderItems')->where('owner_id', $owner)->find($id);
        if(!$order){
            return response()->json([
                'message' => 'Order not found'
            ],400);
        }
        return response()->json($order);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request, string $id)
    {
        $owner = auth()->guard('user')->id();

        try {
            $order = Order::findOrFail($id);

            // Validate the request
            $request->validate([
                'status' => 'required|string|in:Pending,Approve,Processing,Courier,Shipped,Delivered',
            ]);

            // Update the order status
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'message' => 'Order status updated successfully',
                'order' => $order
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the order status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
