@extends('tenants.layouts.app')

@section('content')

    <user-add :user-types="{{ $userTypes }}" :roles="{{ $roles }}"></user-add>

    <users-list :users="{{ $users }}"></users-list>

    <users-dashboard></users-dashboard>

@endsection


