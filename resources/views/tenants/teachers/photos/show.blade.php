@extends('tenants.layouts.app')

@section('content')

    <teachers-photos :available-photos="{{ $photos }}" :zips="{{ $zips }}"></teachers-photos>

@endsection


