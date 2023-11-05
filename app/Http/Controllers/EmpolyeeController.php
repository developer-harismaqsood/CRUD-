<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;
use Auth;

class EmpolyeeController extends Controller
{
         public function __construct()
    {
        $this->middleware('auth')->except(['create','remove','get']);
    }

    public function index()
    {
        $companies = Company::get();
        $employee = Employee::get();
        $me = Auth::user();

        return view('pages.empolyee.index',compact('companies','me','employee'));
    }

    public function create(Request $request)
    {
        $message = '';

        if(empty($request->data['id']))
        {
                Employee::create([
                    'first_name'          => $request->data['first_name'],
                    'last_name'   => $request->data['last_name'],
                    'phone'   => $request->data['phone'],
                    'email'   => $request->data['email'],
                    'company_id'      => $request->data['company_id']
                ]);

                $message = 'Employee Created Successfully.';
                $status = 'success';
        }
        else
        {
                Employee::where('id',$request->data['id'])->Update(
                    [
                        'first_name'  => $request->data['first_name'],
                        'last_name'   => $request->data['last_name'],
                        'phone'       => $request->data['phone'],
                        'email'       => $request->data['email'],
                        'company_id'  => $request->data['company_id']
                    ],
                );
                $message = 'Employee Updated Successfully.';
                $status = 'success';
        }

           $employees = Employee::get();

            return response()->json(['status' => $status, 'message' => $message, 'data' => $employees]);
    }
    
     public function remove($id)
    {
        Employee::find($id)->delete();

        $projects = Employee::with('Company')->get();

        return response()->json(['status' => 'Success','message' => $projects]);
    }

     public function get($id)
    {
        $project = Employee::where('id',$id)->first();

        return response()->json(['status' => 'Success','message' => $project]);
    }
}
