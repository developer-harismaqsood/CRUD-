@extends('layouts.app', ['meDetail' => $me])
 
@section('stylecss') 
@endsection

@section('content')
 	<dashboard
      :me="{{ json_encode($me) }}"
 	></dashboard>
@endsection


@section('javascript')
@endsection
