@extends('layouts.app')
 
@section('stylecss') 
@endsection
@section('content')
 	<employee-management 
      :companies="{{ json_encode($companies) }}"
      :employees="{{ json_encode($employee) }}"
      :me="{{ json_encode($me) }}"
 	></employee-management>
@endsection


@section('javascript')
@endsection
