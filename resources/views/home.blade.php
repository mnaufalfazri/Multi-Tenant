@extends('layouts.app')

@section('content')
  <div class="bg-white border border-gray-200 rounded-xl p-6">
    <h1 class="text-2xl font-semibold text-gray-900">Home</h1>

    @auth
      <p class="mt-2 text-gray-700">
        You are logged in as <span class="font-medium">{{ auth()->user()->email }}</span>.
      </p>
    @else
      <p class="mt-2 text-gray-700">You are not logged in.</p>
    @endauth
  </div>
@endsection
