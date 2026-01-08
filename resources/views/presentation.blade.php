@extends('app')

@section('title', 'Sunum Ekranı - Godeva Etkinlik')

@section('content')
<livewire:presentation-display :room-code="$roomCode" />
@endsection