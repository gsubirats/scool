<h1>HOME PAGE</h1>
HELLO tenant!! Welcome to your home page!

<p>User:</p>

<ul>
    <li>Logged: {{ Auth::check() ? 'sí' : 'no' }}</li>
    @if( Auth::check())
        <li>Name: {{ Auth::user()->name }}</li>
    @endif
</ul>

Navegació:

<ul>
    <li><a href="/login">Login</a></li>
    <li><a href="/register">Register</a></li>
    <li><a href="/home">Home</a></li>
    <li><a href="/">Welcome</a></li>
    <li><a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a></li>
    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
        <input type="submit" value="logout" style="display: none;">
    </form>
</ul>


