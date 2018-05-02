@extends('tenants.layouts.app')

@section('content')

    <staff-add
            :staff-types="{{ $staffTypes }}"
            :specialties="{{ $specialties }}"
            :families="{{ $families }}"
    ></staff-add>

    <staff-list :staff="{{ $staff }}" ></staff-list>

@endsection


