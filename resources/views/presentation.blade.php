@extends('app')

@section('title', 'Sunum Ekranı - Godeva Etkinlik')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 to-indigo-900 text-white">
    <livewire:presentation-display :room-code="$roomCode" />
</div>
@endsection