<header style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-bottom: 1px solid #e5e7eb;">
    <div class="flex items-center">
        <a href="{{route('home')}}" class="flex items-center justify-between">
            <x-icons.office-logo />

            <h1 style="margin: 0; font-size: 1.5rem;" class="text-2xl font-bold text-primary-orange">Company Manager</h1>
        </a>
    </div>

    @guest
        @if (!request()->routeIs('login'))
        <div>
            <x-ui.button href="{{ route('login') }}">Login</x-ui.button>
        </div>
        @endif
    @endguest

    @auth
    <div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <x-ui.button type="submit">Logout</x-ui.button>
        </form>
    </div>
    @endauth
</header>