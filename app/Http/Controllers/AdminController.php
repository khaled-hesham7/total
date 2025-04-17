<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class AdminController extends Controller
// {
//     //
// }



namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(Request $request)

    {
        // $users = User::all();
        // $role = $request->user()->role;
        // if ($role == 'admin') {
        //     return view('admin.dashboard', compact('users'));
        //     } else {
        //         return redirect()->route('home');
        //         }

       {{echo "Hello World";}}

        // return response()->json([
        //     'message' => 'Welcome, Admin!',
        //     'data' => [
        //         'users_count' => User::count(),
        //         'admins_count' => User::where('role', 'admin')->count(),
        //     ]
        // ]);
    }
}