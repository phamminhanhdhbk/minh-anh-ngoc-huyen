<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Product;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)
                        ->with(['product.category', 'product.images'])
                        ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($request->product_id);
        $sessionId = session()->getId();
        
        // Debug logging
        Log::info('Cart Add Request', [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'session_id' => $sessionId,
            'has_session' => !empty($sessionId)
        ]);

        // Ensure session is started
        if (empty($sessionId)) {
            session()->start();
            $sessionId = session()->getId();
            Log::info('Session started', ['new_session_id' => $sessionId]);
        }

        // Check if product already in cart
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
            Log::info('Cart updated', ['cart_item_id' => $cartItem->id, 'new_quantity' => $cartItem->quantity]);
        } else {
            $newCart = Cart::create([
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->sale_price ?? $product->price
            ]);
            Log::info('Cart created', ['cart_item_id' => $newCart->id]);
        }

        // Get total cart count
        $cartCount = Cart::where('session_id', $sessionId)->sum('quantity');
        Log::info('Cart count', ['count' => $cartCount]);

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được thêm vào giỏ hàng!',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->quantity = $request->quantity;
        $cart->save();

        $sessionId = session()->getId();
        $cartCount = Cart::where('session_id', $sessionId)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Giỏ hàng đã được cập nhật!',
            'cart_count' => $cartCount
        ]);
    }

    public function remove(Cart $cart)
    {
        $sessionId = session()->getId();
        $cart->delete();

        $cartCount = Cart::where('session_id', $sessionId)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã được xóa khỏi giỏ hàng!',
            'cart_count' => $cartCount
        ]);
    }

    public function count()
    {
        $sessionId = session()->getId();
        $count = Cart::where('session_id', $sessionId)->sum('quantity');

        return response()->json(['count' => $count]);
    }

    public function checkout()
    {
        $sessionId = session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->sale_price ?: $item->product->price) * $item->quantity;
        });

        // Use configured shipping settings
        $freeThreshold = (int) setting('free_shipping_amount', 2000000);
        $defaultShipping = (int) setting('shipping_fee', 30000);

        // If subtotal reaches free shipping threshold, set shipping to 0
        $shipping = ($subtotal >= $freeThreshold) ? 0 : $defaultShipping;
        $total = $subtotal + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }
}
