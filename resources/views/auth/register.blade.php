@extends('layouts.guest', [
  'title' => 'Register - ' . config('app.name'),
  'heading' => 'Register',
  'subheading' => 'Buat akun baru untuk mulai'
])

@section('content')
  <form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf

    <div>
      <label class="block text-sm font-medium text-gray-700">Name</label>
      <input
        type="text"
        name="name"
        value="{{ old('name') }}"
        required
        autofocus
        class="mt-1 w-full rounded-xl border border-gray-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-200"
        placeholder="Nama kamu"
      >
      @error('name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Email</label>
      <input
        type="email"
        name="email"
        value="{{ old('email') }}"
        required
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
        placeholder="min 8 characters"
      >
      @error('password')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Confirm password</label>
      <input
        type="password"
        name="password_confirmation"
        required
        class="mt-1 w-full rounded-xl border border-gray-300 px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-gray-200"
        placeholder="repeat password"
      >
    </div>

    <button
      type="submit"
      class="w-full rounded-xl bg-gray-900 text-white py-2.5 font-medium shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-300"
    >
      Register
    </button>

    <p class="text-sm text-gray-600 text-center">
      Already have an account?
      <a class="text-gray-900 font-medium hover:underline" href="{{ route('login.form') }}">Login</a>
    </p>
  </form>
@endsection
