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
    public function create()
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
