@extends('tenants.layouts.app')

@section('content')

    <lessons-manager :subjects="{{ $subjects }}" :lessons="{{$lessons}}"></lessons-manager>

@endsection


