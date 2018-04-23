@extends('adminlte::layouts.app')

@section('htmlheader_title')
    Organitzacions de l'usuari
@endsection


@section('main-content')
    <div class="container-fluid spark-screen">
        <tenants :tenants="{{ $tenants }}"></tenants>
    </div>
@endsection
