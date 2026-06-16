<header class="site-header">
    <nav class="navbar">
        <div class="brand">
            <img src="{{ asset('images/Logo Robux.jpg') }}" alt="Logo RobuxRadit" class="brand-logo"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
            <div class="brand-badge" style="display:none;">RR</div>

            <div>
                <h1>Toko RobuxRadit</h1>
            </div>
        </div>

        <ul class="nav-menu">
            @auth
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') || request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>

                @if (auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('pengelolaan') }}" class="{{ request()->routeIs('pengelolaan') || request()->routeIs('barang.*') ? 'active' : '' }}">
                            Pengelolaan
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.transaksi.list') }}" class="{{ request()->routeIs('admin.transaksi.*') ? 'active' : '' }}">
                            Transaksi Toko
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('preferensi.index') }}" class="{{ request()->routeIs('preferensi.*') ? 'active' : '' }}">
                            Preferensi
                        </a>
                    </li>
                @endif

                @if (auth()->user()->role === 'customer')
                    <li>
                        <a href="{{ route('customer.transaksi.list') }}" class="{{ request()->routeIs('customer.transaksi.*') ? 'active' : '' }}">
                            Transaksi Saya
                        </a>
                    </li>
                @endif
            @endauth

            <li>
                <a href="{{ route('customer.katalog') }}" class="{{ request()->routeIs('customer.katalog*') ? 'active' : '' }}">
                    Katalog
                </a>
            </li>

            @auth
                @if (auth()->user()->role !== 'admin')
                    <li>
                        <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                            Profil
                        </a>
                    </li>
                @endif
            @endauth

            <li>
                <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">
                    Tentang
                </a>
            </li>

            <li>
                <a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'active' : '' }}">
                    Kontak
                </a>
            </li>

            @guest
                <li>
                    <a href="{{ route('login') }}">Login</a>
                </li>

                <li>
                    <a href="{{ route('register') }}">Register</a>
                </li>
            @endguest

            @auth
                <li>
                    <button type="button" id="toggle-tema" class="theme-toggle-btn" title="Ganti dark mode">
                        <span id="ikon-tema"></span>
                    </button>
                </li>

                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="nav-logout">Keluar</button>
                    </form>
                </li>
            @endauth
        </ul>

        <div class="nav-user">
            @auth
                <span>{{ auth()->user()->name }} • {{ ucfirst(auth()->user()->role) }}</span>
            @else
                <span>Guest</span>
            @endauth
        </div>
    </nav>
</header>


<script>
const toggleTema = document.getElementById('toggle-tema');
const ikonTema = document.getElementById('ikon-tema');

function updateIkonTema() {
    if (!ikonTema) return;
    const isDark = document.documentElement.classList.contains('dark');
    ikonTema.textContent = isDark ? '☀️' : '🌙';
}

toggleTema?.addEventListener('click', async function () {
    const isDark = document.documentElement.classList.toggle('dark');
    updateIkonTema();

    try {
        await fetch('{{ route("preferensi.toggleTema") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
    } catch (e) {
        // Revert jika gagal
        document.documentElement.classList.toggle('dark', !isDark);
        updateIkonTema();
    }
});

updateIkonTema();
</script>
