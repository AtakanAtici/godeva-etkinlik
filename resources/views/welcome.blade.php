@extends('app')

@section('title', 'Ana Sayfa - Godeva Etkinlik')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center">
            <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-6">
                Godeva Etkinlik
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-300 mb-12 max-w-2xl mx-auto">
                Canlı sunum ve etkileşim platformu. QR kod ile katılın, sorulara cevap verin, 
                gerçek zamanlı geri bildirim alın.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12 max-w-4xl mx-auto">
            <!-- Host Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Sunum Başlat
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        Yeni bir oda oluşturun ve katılımcılarınızla etkileşime geçin.
                    </p>
                    <livewire:create-room />
                </div>
            </div>

            <!-- Join Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Etkinliğe Katıl
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        QR kodu tarayın veya oda kodunu girerek katılın.
                    </p>
                    <livewire:join-room />
                </div>
            </div>
        </div>

        <div class="text-center mt-16">
            <div class="inline-flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                <span>Powered by</span>
                <span class="font-semibold">Laravel Reverb</span>
                <span>•</span>
                <span class="font-semibold">Livewire</span>
                <span>•</span>
                <span class="font-semibold">TailwindCSS</span>
            </div>
        </div>
    </div>
</div>
@endsection