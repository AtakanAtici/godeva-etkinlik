@extends('app')

@section('title', 'Etkinliğe Katıl - Godeva Etkinlik')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 dark:from-gray-900 dark:to-gray-800">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    Etkinliğe Katılın
                </h1>
                <p class="text-gray-600 dark:text-gray-300">
                    Oda Kodu: <span class="font-mono text-lg font-bold text-green-600 dark:text-green-400">{{ $roomCode }}</span>
                </p>
            </div>

            <livewire:participant-join :room-code="$roomCode" />

            <div class="mt-8 text-center">
                <a href="/" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm">
                    ← Ana sayfaya dön
                </a>
            </div>
        </div>
    </div>
</div>
@endsection