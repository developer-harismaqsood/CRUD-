<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\Mail\UserEmail;

class CompanyController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth')->except(['create','remove','get']);
    }

    public function index()
    {
        $users = User::get();
        $companies = Company::get();

        $me = Auth::user();

        return view('pages.company.index',compact('companies','me','users'));
    }

    public function create(Request $request)
    {
        $message = '';

        if(empty($request->data['id']))
        {
            $base64     = $request->data['src'];
            $name       = $request->data['logo'];

            $base_64 = substr($base64, strpos($base64, ',') + 1);

            Storage::disk('local')->put('logo'.'/'.$name, $base_64, 'public');
            $image_url = env('APP_URL').'logo/'.$name;

                Company::create([
                    'name'    => $request->data['name'],
                    'email'   => $request->data['email'],
                    'logo'   =>  $image_url,
                    'website'   => $request->data['website'],
                ]);



                $message = 'Company Created Successfully.';
                $status = 'success';

                
            $details = [
                'title' => 'Company CRM',
                'body'   => 'Company Registered Successfully'
            ];
       
            \Mail::to('developer.harismaqsood@gmail.com')->send(new \App\Mail\UserEmail($details));
        }
        else
        {
            if($request->type === 'new')
            {
                $base64     = $request->data['src'];
                $name       = $request->data['logo'];

                $base_64 = substr($base64, strpos($base64, ',') + 1);
                file_put_contents(public_path().'/logo/'.$name, base64_decode($base_64));

                $image_url = env('APP_URL').'logo/'.$name;

            }
            else{
                $image_url = $request->data['logo'];
            }


                Company::where('id',$request->data['id'])->Update(
                    [
                       'name'          => $request->data['name'],
                        'email'   => $request->data['email'],
                        'logo'   =>  $image_url,
                        'website'   => $request->data['website'],
                    ],
                );
                $message = 'Company Updated Successfully.';
                $status = 'success';
        }

            $companies = Company::get();

            return response()->json(['status' => $status, 'message' => $message, 'data' => $companies]);
    }

     public function remove($id)
    {
        Company::find($id)->delete();

        $companies = Company::get();

        return response()->json(['status' => 'Success','message' => $companies]);
    }

     public function get($id)
    {
        $company = Company::where('id',$id)->first();

        return response()->json(['status' => 'Success','message' => $company]);
    }
}
