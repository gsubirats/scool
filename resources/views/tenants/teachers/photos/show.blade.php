@extends('tenants.layouts.app')

@section('content')

    <teachers-photos :available-photos="{{ $photos }}"></teachers-photos>

@endsection


