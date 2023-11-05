@extends('layouts.app', ['meDetail' => $me])
 
@section('stylecss') 
@endsection

@section('content')
 	<company-management 
      :users="{{ json_encode($users) }}"
      :companies="{{ json_encode($companies) }}"
      :me="{{ json_encode($me) }}"
 	></company-management>
@endsection


@section('javascript')
@endsection
