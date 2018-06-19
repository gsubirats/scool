<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="/manifest.json">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <link href="/tenant/css/app.css" rel="stylesheet">
</head>
<body>
<div id="app" v-cloak>
    <v-app>
        <v-content>
            <jobs-sheet-holder :jobs="{{$jobs}}"></jobs-sheet-holder>
        </v-content>
    </v-app>
</div>

@stack('beforeScripts')
<script src="{{ mix('tenant/js/app.js') }}"></script>
@stack('afterScripts')
</body>
</html>