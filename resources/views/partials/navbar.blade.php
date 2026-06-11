<header class="site-header">
  <nav class="navbar">
    <div class="brand">
      <img src="{{ asset('images/Logo Robux.jpg') }}" alt="Logo RobuxRadit" class="brand-logo"
           onerror="this.style.display='none'; this.nextElementSibling.style.display='grid';">
      <div class="brand-badge" style="display:none;">RR</div>
      <div>
        <h1>Inventaris RobuxRadit</h1>
        <p>Kelola data barang dengan lebih rapi dan cepat</p>
      </div>
    </div>

    <ul class="nav-menu">
      @auth
        <li>
          <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Dashboard
          </a>
        </li>

        <li>
          <a href="{{ route('pengelolaan') }}" class="{{ request()->routeIs('pengelolaan') || request()->routeIs('barang.*') ? 'active' : '' }}">
            Pengelolaan
          </a>
        </li>

        <li>
          <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
            Profil
          </a>
        </li>

        <li>
          <a href="{{ route('preferensi.index') }}" class="{{ request()->routeIs('preferensi.*') ? 'active' : '' }}">
            Preferensi
          </a>
        </li>
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
            <span id="ikon-tema">🌙</span>
          </button>
        </li>
        <li>
          <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="nav-logout">Keluar</button>
          </form>
        </li>
      @endauth
    </ul>

    <div class="nav-user">
      @auth
        <span>👤 {{ auth()->user()->name }}</span>
      @else
        <span>👤 Guest</span>
      @endauth
    </div>
  </nav>
</header>

<script>
function setCookie(name, value, days = 30) {
  const expires = new Date();
  expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);

  document.cookie = `${name}=${encodeURIComponent(value)};expires=${expires.toUTCString()};path=/;SameSite=Lax`;
}

function getCookie(name) {
  const cookies = document.cookie.split(';');

  for (const cookie of cookies) {
    const [key, value] = cookie.trim().split('=');

    if (key === name) {
      return decodeURIComponent(value || '');
    }
  }

  return null;
}

function deleteCookie(name) {
  document.cookie = `${name}=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/`;
}

const toggleTema = document.getElementById('toggle-tema');
const ikonTema = document.getElementById('ikon-tema');

function updateIkonTema() {
  if (!ikonTema) {
    return;
  }

  const isDark = document.documentElement.classList.contains('dark');
  ikonTema.textContent = isDark ? '☀️' : '🌙';
}

toggleTema?.addEventListener('click', function () {
  const isDark = document.documentElement.classList.toggle('dark');

  setCookie('tema', isDark ? 'dark' : 'light', 30);
  updateIkonTema();
});

updateIkonTema();
</script>
