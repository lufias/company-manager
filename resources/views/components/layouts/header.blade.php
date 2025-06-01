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
            <a href="{{ route('login') }}" style="padding: 0.5rem 1rem; background: #2563eb; color: #fff; border-radius: 0.25rem; text-decoration: none;">Login</a>
        </div>
        @endif
    @endguest

    @auth
    <div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" style="padding: 0.5rem 1rem; background: #2563eb; color: #fff; border-radius: 0.25rem; text-decoration: none; border: none; cursor: pointer;">Logout</button>
        </form>
    </div>
    @endauth
</header>