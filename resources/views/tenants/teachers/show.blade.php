@extends('tenants.layouts.app')

@section('content')

    <pending-teachers :teachers="{{ $pendingTeachers }}"></pending-teachers>

    <teachers :teachers="{{ $teachers }}"></teachers>



@endsection


