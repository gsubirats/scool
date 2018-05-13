@extends('tenants.layouts.app')

@section('content')

    <pending-teachers
            :teachers="{{ $pendingTeachers }}"
            :specialties="{{ $specialties }}"
            :forces="{{ $forces }}"
            :administrative-statuses="{{ $administrative_statuses }}">
    </pending-teachers>

    <teachers :teachers="{{ $teachers }}"></teachers>



@endsection


