<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['create','remove','get']);
    }

    public function index()
    {
        $users = User::get();

        $me = Auth::user();

        return view('pages.user.user_management',compact('users','me'));
    }

    public function create(Request $request)
    {
        $message = '';

        if(empty($request->data['id']))
        {
            $check_email = User::where('email',$request->data['email'])->first();

            if($check_email)
            {
                $message = 'Email Already Exist.';
                $status = 'error';
            }
            else
            {
                User::create([
                    'name'      => $request->data['name'],
                    'email'     => $request->data['email'],
                    'password' => Hash::make($request->data['password'])
                ]);

                $message = 'Account Created Successfully.';
                $status = 'success';
            }
        }
        else
        {

            $check_email = User::where('id','!=', $request->data['id'])->where('email','=', $request->data['email'])->first();

            if($check_email)
            {
                $message = 'Email Already Exist.';
                $status = 'error';
            }
            else{
                User::where('id',$request->data['id'])->Update(
                    [
                        'name'      => $request->data['name'],
                        'email'     => $request->data['email'],
                        'password' => Hash::make($request->data['password'])
                    ],
                );
                $message = 'Account Updated Successfully.';
                $status = 'success';
            }
        }

            $users = User::get();

            return response()->json(['status' => $status, 'message' => $message, 'data' => $users]);
    }

     public function remove($id)
    {
        User::where('id',$id)->delete();

        $users = User::get();

        return response()->json(['status' => 'Success','message' => $users]);
    }

     public function get($id)
    {
        $user = User::where('id',$id)->first();

        return response()->json(['status' => 'Success','message' => $user]);
    }

}

