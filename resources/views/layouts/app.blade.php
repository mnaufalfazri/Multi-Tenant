<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-gray-50 to-white text-gray-900">
  <!-- Top Bar -->
  <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
      <!-- Brand -->
      <a href="{{ route('home') }}" class="flex items-center gap-2 group">
        <div class="h-9 w-9 rounded-xl bg-gray-900 text-white flex items-center justify-center font-bold shadow-sm">
          {{ strtoupper(substr(config('app.name'), 0, 1)) }}
        </div>
        <div class="leading-tight">
          <div class="font-semibold text-gray-900 group-hover:text-gray-800">
            {{ config('app.name') }}
          </div>
          <div class="text-xs text-gray-500 -mt-0.5">Multi-tenant helpdesk</div>
        </div>
      </a>

      <!-- Actions -->
      <nav class="flex items-center gap-2">
        @auth
          <div class="hidden sm:flex items-center gap-3">
            <div class="text-right leading-tight">
              <div class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</div>
              <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="h-9 w-9 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-sm font-semibold text-gray-700">
              {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
          </div>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
              type="submit"
              class="inline-flex items-center justify-center rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-medium shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-300"
            >
              Logout
            </button>
          </form>
        @else
          <a
            href="{{ route('login.form') }}"
            class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
          >
            Login
          </a>

          <a
            href="{{ route('register.form') }}"
            class="inline-flex items-center justify-center rounded-lg bg-gray-900 text-white px-4 py-2 text-sm font-medium shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-300"
          >
            Register
          </a>
        @endauth
      </nav>
    </div>
  </header>

  <!-- Page -->
  <main class="max-w-5xl mx-auto px-4 py-8">
    @if (session('status'))
      <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900 shadow-sm">
        <div class="font-medium">Success</div>
        <div class="text-green-800">{{ session('status') }}</div>
      </div>
    @endif

    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="border-t border-gray-200 mt-12">
    <div class="max-w-5xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-gray-500">
      <div>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</div>
      <div class="flex items-center gap-3">
        <span class="text-gray-400">•</span>
        <span>Laravel</span>
        <span class="text-gray-400">•</span>
        <span>MySQL</span>
      </div>
    </div>
  </footer>
</body>
</html>
