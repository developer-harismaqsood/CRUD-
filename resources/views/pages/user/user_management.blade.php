@extends('layouts.app', ['meDetail' => $me])
 
@section('stylecss') 
@endsection

@section('content')
 	<user-management 
      :users="{{ json_encode($users) }}"
      :me="{{ json_encode($me) }}"
 	></user-management>
@endsection


@section('javascript')
@endsection
