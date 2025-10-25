<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Product;

class CartController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        $cartItems = Cart::where('session_id', $sessionId)->with('product.category')->get();

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

        // Check if product already in cart
        $cartItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->sale_price
            ]);
        }

        // Get total cart count
        $cartCount = Cart::where('session_id', $sessionId)->sum('quantity');

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

        $shipping = 30000; // Fixed shipping fee
        $total = $subtotal + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }
}
