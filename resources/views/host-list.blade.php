@extends('app')

@section('title', 'Etkinliklerim - Godeva Etkinlik')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-8 flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Etkinliklerim
                </h1>
            </div>

            <livewire:host-room-list />
        </div>
    </div>
@endsection