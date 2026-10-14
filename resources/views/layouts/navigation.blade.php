
<nav class="navbar" aria-label="Navegación principal"
     x-data="{ open: false }">

    <div class="navbar__contenedor">

        <a href="{{ route('dashboard') }}" class="navbar__logo">
            BikeRent
        </a>

        <button
            type="button"
            class="navbar__toggle"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-controls="navbar-menu"
            aria-label="Mostrar u ocultar menú"
        >
            <svg aria-hidden="true" viewBox="0 0 24 24"
                 width="24" height="24"
                 fill="none" stroke="currentColor"
                 stroke-width="2">
                <path x-show="!open"
                      d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" x-cloak
                      d="M6 6l12 12M18 6L6 18" />
            </svg>
        </button>

        <div id="navbar-menu"
             class="navbar__menu"
             :class="{ 'navbar__menu--abierto': open }">

            <a href="{{ route('dashboard') }}"
               class="navbar__enlace"
               @if(request()->routeIs('dashboard'))
                   aria-current="page"
               @endif>
                Dashboard
            </a>

            <div class="navbar__usuario">
                <span class="navbar__nombre">
                    {{ Auth::user()->name }}
                </span>

                <a href="{{ route('profile.edit') }}"
                   class="navbar__enlace">
                    Mi perfil
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="navbar__enlace navbar__salir">
                        Cerrar sesión
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>
