@extends('tenants.layouts.app')

@section('content')
 <teacher-profile :teacher="{{ $teacher }}" :teachers="{{ $teachers }}"></teacher-profile>
@endsection


