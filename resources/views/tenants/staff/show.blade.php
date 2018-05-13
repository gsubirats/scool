@extends('tenants.layouts.app')

@section('content')

    <staff-add
            :staff-types="{{ $staffTypes }}"
            teacher-type="Professor/a"
            :specialties="{{ $specialties }}"
            :families="{{ $families }}"
            :users="{{ $users }}"
    ></staff-add>

    <staff-list :staff="{{ $staff }}" ></staff-list>

    <staff-list-by-family :families="{{ $families }}"></staff-list-by-family>

    <staff-list-by-specialty :specialties="{{ $specialties }}"></staff-list-by-specialty>

@endsection


