@extends('tenants.layouts.app')

@section('content')

    <teachers-photos
            :available-photos="{{ $photos }}"
            :zips="{{ $zips }}"
            :teachers="{{ $teachers }}"
    ></teachers-photos>

@endsection


