@extends('tenants.layouts.app')

@section('content')

    <staff-add></staff-add>

    <staff-list :staff="{{ $staff }}"></staff-list>

@endsection


