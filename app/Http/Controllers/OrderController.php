<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Order;
use App\OrderItem;
use App\SiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlaced;

class OrderController extends Controller
{
    public function checkout()
    {
        $sessionId = session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        return view('checkout', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:1000',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,bank_transfer,online',
        ]);

        $sessionId = session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        DB::beginTransaction();

        try {
            $subtotal = $cartItems->sum(function($item) {
                return $item->quantity * ($item->product->sale_price ?: $item->product->price);
            });

            $shipping = 30000; // 30k shipping fee
            $tax = 0;
            $total = $subtotal + $shipping + $tax;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(), // null if guest
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->name,
                'customer_email' => $request->email,
                'customer_phone' => $request->phone,
                'customer_address' => $request->address . ', ' . $request->city,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_price' => $item->product->sale_price ?: $item->product->price,
                    'quantity' => $item->quantity,
                    'total' => $item->quantity * ($item->product->sale_price ?: $item->product->price)
                ]);

                // Update product stock
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }

            // Clear cart
            Cart::where('session_id', $sessionId)->delete();

            DB::commit();

            // Send email notification to admin emails
            try {
                $emailList = SiteSetting::get('order_notification_emails', 'minhanh.itqn@gmail.com,ngochuyen2410@gmail.com');
                $emails = array_map('trim', explode(',', $emailList));
                
                foreach ($emails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($email)->send(new OrderPlaced($order));
                        Log::info('Order notification email sent', ['email' => $email, 'order_id' => $order->id]);
                    }
                }
            } catch (\Exception $e) {
                // Log email error but don't fail the order
                Log::error('Failed to send order notification email', [
                    'error' => $e->getMessage(),
                    'order_id' => $order->id
                ]);
            }

            return redirect()->route('order.success', $order->id)->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollback();

            // Log the actual error
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return back()->with('error', 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        return view('order.success', compact('order'));
    }
}
