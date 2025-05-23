<?php

namespace App\Http\Controllers;

use id;
use App\Models\User;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\UsersResource;
use function Laravel\Prompts\progress;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;
use Illuminate\Foundation\Auth\User as AuthUser;

class AdminController extends Controller
{
    public function allUsers()
    {
        $users = User::get();
        // $users = User::paginate(10); // 10 users لكل صفحة
        return UsersResource::collection($users);
    }
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    public function userdelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // الحذف بدون الحاجة للمعرّف
        return  response()->json(['msg' => 'user delete successfuly'], 200);
    }
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    public function updaterole($id, Request  $request)
    {
        $user = User::find($id);
        if ($user == null) {
            return  response()->json(['msg' => 'data not found'], 404);
        }
        $error =  Validator::make($request->all(), [
            // 'name' => 'required|string|max:255',
            // 'email'=> 'required|string|email',
            'role' => 'nullable|string|in:user,admin,moderator',
        ]);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        $user->update([
            // 'name' => $request->name,
            // 'email' => $request->email,
            'role' => $request->role,
        ]);
        return  response()->json(['msg' => 'user update successfuly'], 201);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function store(Request  $request)
    {
        $error =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'quantity' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        $image = Storage::disk('public')->putFile('products', $request->image);
        Products::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'image' => $image,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),  // إضافة آلية للمستخدم الحالي
        ]);
        return response()->json(['msg' => 'product created successfuly'], 201);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function delete($id)
    {
        $product = Products::findOrFail($id);
        // Storage::delete($product->image); // تأكد من أن مسار الصورة صحيح
        Storage::disk('public')->delete($product->image);
        $product->delete(); // الحذف بدون الحاجة للمعرّف
        return  response()->json(['msg' => 'product delete successfuly'], 200);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function update($id, Request  $request)
    {
        $product = Products::find($id);
        if ($product == null) {
            return  response()->json(['msg' => 'data not found'], 404);
        }
        $error =  Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'image|mimes:png,jpg,jpeg',
            'quantity' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        $image = $product->image;

        if ($request->hasFile("image")) {

            Storage::disk('public')->delete($product->image); // استخدام 'public' disk للحذف
            $image = Storage::disk('public')->putFile('products', $request->image); // استخدام 'public' disk للرفع

        }
        $product->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => $request->price,
            'image' => $image,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
        ]);
        return  response()->json(['msg' => 'product update successfuly'], 201);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//
    public function createCategories(Request $request)
    {

        $error = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        Categories::create([
            'name' => $request->name,
            'user_id' => auth()->id(),  // إضافة آلية للمستخدم الحالي
        ]);
        return response()->json(['msg' => 'Categories created successfuly'], 201);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//

    public function allCategories()
    {
        $Categories = Categories::get();
        // $Categories = Categories::paginate(10); // 10 users لكل صفحة
        return CategoriesResource::collection($Categories);
    }
    //*******************************************************************//
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    //===================================================================//

    public function updateCategories($id, Request $request)
    {
        $Categories = Categories::find($id);
        if ($Categories == null) {
            return  response()->json(['msg' => 'data not found'], 404);
        }
        $error = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()], 422);
        }
        $Categories->update([
            'name' => $request->name,
        ]);
        return  response()->json(['msg' => '$Categories update successfuly'], 201);
    }
    //*******************************************************************//
    //*******************************************************************//
    //===================================================================//
    //===================================================================//
    public function Categoriesdelete($id)
    {
        $Categories = Categories::findOrFail($id);
        $Categories->delete(); // الحذف بدون الحاجة للمعرّف
        return  response()->json(['msg' => 'user delete successfuly'], 200);
    }


    
}
