@extends('layouts.app', ['meDetail' => $me])
 
@section('stylecss') 
@endsection

@section('content')
 	<password-setting 
            :me="{{ json_encode($me) }}"
 	></password-setting>
@endsection


@section('javascript')
@endsection
