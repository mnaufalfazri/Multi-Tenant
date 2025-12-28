@extends('layouts.guest', [
  'title' => 'Login - ' . config('app.name'),
  'heading' => 'Login',
  'subheading' => 'Masuk pakai akun kamu'
])

@section('content')
  <form method="POST" action="{{ route('login') }}" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700">Email</label>
      <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
        autofocus
        class="mt-1 w-full rounded-xl border border-gray-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-200"
        placeholder="you@example.com"
      >
      @error('email')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Password</label>
      <input
        type="password"
        name="password"
        required
        class="mt-1 w-full rounded-xl border border-gray-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-200"
        placeholder="••••••••"
      >
      @error('password')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center justify-between">
      <label class="inline-flex items-center gap-2 text-sm text-gray-700">
        <input type="checkbox" name="remember" class="rounded border-gray-300">
        Remember me
      </label>
    </div>

    <button
      type="submit"
      class="w-full rounded-xl bg-gray-900 text-white py-2.5 font-medium shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-300"
    >
      Login
    </button>

    <p class="text-sm text-gray-600 text-center">
      Don’t have an account?
      <a class="text-gray-900 font-medium hover:underline" href="{{ route('register.form') }}">Register</a>
    </p>
  </form>
@endsection
