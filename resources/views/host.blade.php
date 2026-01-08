@extends('app')

@section('title', 'Host Paneli - Godeva Etkinlik')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                Host Paneli
            </h1>
        </div>

        <livewire:host-dashboard :room-id="$roomId" />
    </div>
</div>
@endsection