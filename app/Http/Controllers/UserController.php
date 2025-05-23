<?php

namespace App\Http\Controllers;

use App\Models\Cart;

use App\Models\Products;
use App\Models\Categories;
use App\Models\Add_to_cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function all()
    {
        // $products = Products::all();
        $products = Products::with('user')->get();
        return ProductResource::collection($products);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function getCategory($id)
    {
        $products = Products::where('category_id', $id)->get();
        if ($products->isEmpty()) {
            return response()->json(['msg' => 'No products found for this category'], 404);
        }
        return ProductResource::collection($products);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function addToCart(Request $request, $id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'يجب تسجيل الدخول أولاً'], 401);
            }
            $product = Products::find($id);
            if (!$product) {
                return response()->json(['message' => 'المنتج غير موجود'], 404);
            }
            $cartItem = Cart::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->first();
            if ($cartItem) {
                $cartItem->quantity += 1;
                $cartItem->save();
            } else {
                $newCartItem = Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1
                ]);
                if (!$newCartItem) {
                    Log::error('Failed to create cart item for user_id: ' . $user->id . ', product_id: ' . $product->id);
                    return response()->json(['message' => 'فشل في إضافة المنتج إلى السلة'], 500);
                }
            }
            return response()->json(['message' => 'تمت إضافة المنتج إلى السلة']);
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json(['message' => 'حدث خطأ ما في الخادم'], 500);
        }
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function viewCart()
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        return response()->json([
            'cart' => $cartItems
        ]);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function updateQuantity(Request $request)
    {
        $error =  Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();
        if (!$cartItem) {
            return response()->json(['message' => 'العنصر غير موجود في السلة'], 404);
        }
        $cartItem->update(['quantity' => $request->quantity]);
        return response()->json([
            'message' => 'تم تحديث الكمية بنجاح',
            'cart_item' => $cartItem
        ]);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function removeFromCart(Request $request, $id)
    {
        // التحقق من صحة product_id
        $error = Validator::make(
            ['product_id' => $id],
            [
                'product_id' => 'required|exists:products,id',
            ]
        );
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        // البحث عن المنتج في السلة للمستخدم المصادق
        $cartItem = Cart::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->first();
        if (!$cartItem) {
            return response()->json(['message' => 'العنصر غير موجود في السلة'], 404);
        }
        // حذف العنصر من السلة
        try {
            $cartItem->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء حذف العنصر من السلة'], 500);
        }
        return response()->json(['message' => 'تم حذف العنصر من السلة بنجاح'], 200);
    }
    public function clearCart(Request $request)
    {
        $cartItems = Cart::where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'السلة فارغة بالفعل'], 200);
        }
        try {
            Cart::where('user_id', auth()->id())->delete();
        } catch (\Exception $e) {
            return response()->json(['message' => 'حدث خطأ أثناء تفريغ السلة'], 500);
        }
        return response()->json(['message' => 'تم تفريغ السلة بنجاح'], 200);
    }
}
