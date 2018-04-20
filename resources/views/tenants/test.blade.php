<h1>Tenant: {{ $tenant->name  }}</h1>
<h2>Tenant info:</h2>
<ul>
    <li>Id: {{ $tenant->id  }}</li>
    <li>Name: {{ $tenant->name  }}</li>
    <li>Subdomain: {{ $tenant->subdomain  }}</li>
    <li>Hostname: {{ $tenant->hostname  }}</li>
    <li>Username: {{ $tenant->username  }}</li>
    <li>Password: {{ $tenant->password  }}</li>
    <li>Database: {{ $tenant->database  }}</li>

</ul>

<p>Tenant object:</p>

{{ $tenant }}

<h2>Database configuration</h2>

<p>database.default: {{ config('database.default') }}</p>

Connections:
<ul>
    @foreach (config('database.connections') as $connection => $connection_data)
        <li>{{$connection}}</li>
    @endforeach
</ul>

Tenant connection:
<ul>
    @foreach (config('database.connections.tenant') as $tenant_key => $tenant_value)
        <li>{{$tenant_key}} : {{$tenant_value}}</li>
    @endforeach
</ul>


<h2>Environment</h2>



