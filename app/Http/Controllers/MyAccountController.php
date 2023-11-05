<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Auth;
use URL;
use DB;
use Illuminate\Support\Facades\Mail;
use Dirape\Token\Token;

class MyAccountController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['change_password']);
    }

    public function index()
    {
        $me = Auth::user();

        return view('pages.my_account.index',compact('me'));
    }

    public function change_password(Request $request)
    {
        
            $check_email = User::where('id',$request->data['id'])->first();
       
          
            User::where('id',$request->data['id'])->Update(
                [
                    'password' => Hash::make($request->data['password'])
                ],
            );

            $message = 'Password Changes Successfully.';
            $status = 'success';

            return response()->json(['status' => $status, 'message' => $message]);
    }
}
