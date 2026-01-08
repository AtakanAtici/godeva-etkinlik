@extends('app')

@section('title', $room->title . ' - Rapor')

@section('content')
<div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $room->title }} - Etkinlik Raporu</h1>
                    <p class="text-gray-600 mt-2">Oda Kodu: {{ $room->code }}</p>
                </div>
                <div class="flex space-x-4">
                    <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Yazdır
                    </button>
                </div>
            </div>
        </div>

        <!-- Genel İstatistikler -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Toplam Katılımcı</p>
                        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['total_participants'] }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Toplam Soru</p>
                        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['total_questions'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Toplam Cevap</p>
                        <p class="text-3xl font-bold text-purple-600 mt-1">{{ $stats['total_answers'] }}</p>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Ortalama Katılım</p>
                        <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['avg_response_rate'] }}%</p>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- En Çok/Az Cevaplanan Sorular -->
        @if($stats['most_answered'] || $stats['least_answered'])
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            @if($stats['most_answered'])
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-2">En Çok Cevaplanan Soru</h3>
                <p class="text-gray-700 font-medium">{{ $stats['most_answered']['title'] }}</p>
                <p class="text-green-600 mt-2">{{ $stats['most_answered']['answer_count'] }} cevap ({{ $stats['most_answered']['response_rate'] }}% katılım)</p>
            </div>
            @endif
            
            @if($stats['least_answered'])
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-red-800 mb-2">En Az Cevaplanan Soru</h3>
                <p class="text-gray-700 font-medium">{{ $stats['least_answered']['title'] }}</p>
                <p class="text-red-600 mt-2">{{ $stats['least_answered']['answer_count'] }} cevap ({{ $stats['least_answered']['response_rate'] }}% katılım)</p>
            </div>
            @endif
        </div>
        @endif

        <!-- Soru Detayları -->
        <div class="space-y-8">
            @foreach($stats['questions_data'] as $index => $question)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="mb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">Soru {{ $index + 1 }}: {{ $question['title'] }}</h3>
                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $question['type'] === 'multiple_choice' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $question['type'] === 'multiple_choice' ? 'Çoktan Seçmeli' : 'Açık Uçlu' }}
                                </span>
                                <span>{{ $question['answer_count'] }} cevap</span>
                                <span>{{ $question['response_rate'] }}% katılım</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($question['type'] === 'multiple_choice')
                    <!-- Çoktan Seçmeli Sonuçlar -->
                    <div class="space-y-4">
                        @foreach($question['results'] as $result)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium">{{ $result['letter'] }}. {{ $result['option'] }}</span>
                                <span class="text-sm text-gray-600">{{ $result['count'] }} oy ({{ $result['percentage'] }}%)</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4">
                                <div class="h-4 rounded-full transition-all duration-500" 
                                     style="width: {{ $result['percentage'] }}%; background-color: {{ ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#06B6D4'][$loop->index] ?? '#6B7280' }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <!-- Açık Uçlu Cevaplar -->
                    <div class="max-h-96 overflow-y-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($question['answers'] as $answer)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-gray-700 mb-2">{{ $answer['content'] }}</p>
                                <p class="text-xs text-gray-500">
                                    <span class="font-medium">{{ $answer['participant'] }}</span> • 
                                    {{ \Carbon\Carbon::parse($answer['submitted_at'])->format('d.m.Y H:i') }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-gray-500 text-sm">
            <p>Rapor oluşturulma tarihi: {{ now()->format('d.m.Y H:i:s') }}</p>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
    @media print {
        body { 
            background: white; 
            margin: 0;
        }
        .no-print { 
            display: none !important; 
        }
        .shadow-md, .shadow-lg {
            box-shadow: none !important;
            border: 1px solid #e5e7eb;
        }
        .bg-gray-100 {
            background: white !important;
        }
    }
</style>
@endsection