@extends('tenants.layouts.app')

@section('content')

    <job-add
            :job-types="{{ $jobTypes }}"
            teacher-type="Professor/a"
            :specialties="{{ $specialties }}"
            :families="{{ $families }}"
            :users="{{ $users }}"
            proposed-code="{{ $firstAvailableCode }}"
    ></job-add>

    <jobs-list :jobs="{{ $jobs }}" :job-types="{{ $jobTypes }}"></jobs-list>

    <jobs-list-by-family :families="{{ $families }}"></jobs-list-by-family>

    <jobs-list-by-specialty :specialties="{{ $specialties }}"></jobs-list-by-specialty>

@endsection


