<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Store a new order in the database and handle subscription asynchronously.
     */
    public function fetch(Request $request)
    {
        // Fetch all orders from the 'orders' table
        $orders = Order::all();

        // Return the orders as a JSON response
        return response()->json($orders);
    }

    /**
     * Store a new order in the database and handle subscription asynchronously.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'basket' => 'required|array',
            'basket.*.name' => 'required|string|max:255',
            'basket.*.type' => 'required|string|in:unit,subscription',
            'basket.*.price' => 'required|numeric|min:0',
        ]);

        // Create the order in the database
        $order = Order::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'basket' => json_encode($request->basket), // Store basket as JSON
        ]);

        // Process any subscriptions asynchronously
        $this->handleSubscription($request->basket);

        // Return the created order as a response
        return response()->json($order, 201);
    }

    /**
     * Handle subscriptions asynchronously by sending to the third-party API.
     */
    protected function handleSubscription($basket)
    {
        // Find subscription items in the basket
        $subscriptions = collect($basket)->filter(function ($item) {
            return $item['type'] === 'subscription';
        });

        // If there are subscriptions, send them asynchronously to the third-party API
        if ($subscriptions->isNotEmpty()) {
            foreach ($subscriptions as $subscription) {
                // Prepare the data for the third-party API
                $data = [
                    'ProductName' => $subscription['name'],
                    'Price' => $subscription['price'],
                    'Timestamp' => now()->toDateTimeString(),
                ];

                // Send the request asynchronously to the third-party API
                // You can use Laravel's built-in Http facade
                Http::async()->post('https://very-slow-api.com/orders', $data);
            }
        }
    }
}
