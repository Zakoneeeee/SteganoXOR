<nav class="navbar navbar-expand-lg navbar-dark shadow-sm mb-5" style="background-color: var(--primary-color);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}" style="letter-spacing: 1px;">SteganoXOR</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item">
                    <a class="nav-link text-white {{ request()->routeIs('cara.kerja') ? 'fw-bold' : '' }}" href="{{ route('cara.kerja') }}">Cara Kerja</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn fw-bold px-3" style="background-color: var(--bg-color); color: var(--primary-color); border-radius: 8px;" href="{{ route('register') }}">Register</a>
                    </li>
                @endguest

                @auth
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard History</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger fw-bold px-3" style="border-radius: 8px;">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>