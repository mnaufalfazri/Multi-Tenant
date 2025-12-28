<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? config('app.name') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-gray-50 to-white text-gray-900">
  <main class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
      <!-- Brand -->
      <div class="flex items-center justify-center gap-3 mb-6">
        <div class="h-11 w-11 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-bold shadow-sm">
          {{ strtoupper(substr(config('app.name'), 0, 1)) }}
        </div>
        <div class="leading-tight">
          <div class="font-semibold text-gray-900">{{ config('app.name') }}</div>
          <div class="text-xs text-gray-500 -mt-0.5">Multi-tenant helpdesk</div>
        </div>
      </div>

      <!-- Card -->
      <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
        <h1 class="text-2xl font-semibold">{{ $heading ?? 'Welcome' }}</h1>
        @if(!empty($subheading))
          <p class="text-sm text-gray-600 mt-1">{{ $subheading }}</p>
        @endif

        @if (session('status'))
          <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-900">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900">
            <div class="font-medium mb-1">Ada error:</div>
            <ul class="list-disc pl-5">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="mt-6">
          @yield('content')
        </div>
      </div>

      <p class="text-xs text-gray-500 mt-6 text-center">
        © {{ date('Y') }} {{ config('app.name') }}
      </p>
    </div>
  </main>
</body>
</html>
