<div class="min-h-screen bg-white p-8 flex flex-col">
    <div class="flex-1 flex flex-col">
        @if (!$currentQuestion)
            <!-- Waiting State - Full Screen -->
            <div class="flex-1 flex flex-col">
                <!-- Header -->
                <div class="flex justify-between items-center mb-12">
                    <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo" class="h-12 w-auto">
                    <div class="flex items-center space-x-2 text-gray-500">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-lg font-medium">Live</span>
                    </div>
                </div>

                <!-- Main Content - Full Height -->
                <div class="flex-1 grid grid-cols-3 gap-16 items-center">
                    <!-- Left: Large Logo with Animation -->
                    <div class="flex justify-center">
                        <div class="relative">
                            <div class="w-64 h-64 relative">
                                <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo" class="w-full h-full opacity-30 filter drop-shadow-lg">
                                <!-- Animated rings around logo -->
                                <div class="absolute inset-0 border-4 border-blue-100 rounded-full animate-ping opacity-20"></div>
                                <div class="absolute inset-8 border-2 border-blue-200 rounded-full animate-ping opacity-30" style="animation-delay: 1s"></div>
                                <div class="absolute inset-16 border border-blue-300 rounded-full animate-ping opacity-40" style="animation-delay: 2s"></div>
                                <!-- Central glow effect -->
                                <div class="absolute inset-20 bg-blue-50 rounded-full opacity-20 animate-pulse"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Center: Title and Status -->
                    <div class="text-center">
                        <div class="space-y-6">
                            <h1 class="text-7xl font-bold text-gray-800 tracking-tight mb-4">Sunuma Hazır</h1>
                            <p class="text-gray-600 text-2xl font-light leading-relaxed max-w-lg mx-auto">Host bir soru yayınladığında burada görünecek</p>
                            <div class="flex items-center justify-center space-x-3 text-gray-400 text-lg mt-8">
                                <svg class="w-6 h-6 animate-spin text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span class="font-medium">Canlı bağlantı bekleniyor...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Participation Info -->
                    <div class="flex justify-center">
                        <div class="bg-gray-50 rounded-2xl border border-gray-200 p-8 shadow-lg max-w-sm w-full">
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Katılım</h3>
                                <p class="text-gray-600">Cep telefonunuzdan katılın</p>
                            </div>
                            
                            <div class="space-y-6">
                                <!-- Join Code -->
                                <div class="text-center">
                                    <p class="text-gray-600 text-sm mb-3">Katılım Kodu</p>
                                    <div class="bg-white rounded-xl py-4 px-6 border-2 border-blue-100 shadow-sm">
                                        <span class="text-4xl font-bold text-blue-600 tracking-wider">{{ $room->code }}</span>
                                    </div>
                                </div>
                                
                                <!-- QR Code -->
                                <div class="text-center">
                                    <p class="text-gray-600 text-sm mb-3">QR Kod</p>
                                    <div class="bg-white p-6 rounded-xl border-2 border-gray-200 inline-block shadow-sm">
                                        <div class="w-32 h-32 flex items-center justify-center">
                                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(url("/join/{$room->code}")) !!}
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- URL -->
                                <div class="text-center">
                                    <p class="text-gray-600 text-sm mb-2">Web Adresi</p>
                                    <div class="bg-white rounded-lg py-3 px-4 border text-sm text-gray-700 font-mono break-all">
                                        {{ url("/join/{$room->code}") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Question Title with Logo -->
            <div class="mb-6">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4 flex-1">
                            <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo" class="h-10 w-auto flex-shrink-0">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 leading-tight">
                                    {{ $currentQuestion->title }}
                                </h2>
                                <p class="text-gray-600 font-medium text-sm">
                                    {{ $currentQuestion->answers()->count() }} cevap alındı
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-gray-500 ml-4">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <span class="text-sm font-medium">Live</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Answers Grid -->
            <div class="flex-1">
                <div class="grid grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-4" id="answers-feed">
                    @forelse($recentAnswers as $answer)
                        <div class="bg-white rounded-lg p-4 aspect-square flex flex-col justify-center border border-gray-200 hover:border-gray-300 hover:shadow-md transition-all duration-200">
                            <!-- Content -->
                            <div class="flex-1 flex flex-col justify-center">
                                <p class="text-gray-800 text-xs font-medium text-center line-clamp-4 leading-relaxed mb-3">
                                    {{ $answer->content }}
                                </p>
                            </div>
                            
                            <!-- Author -->
                            <div class="mt-auto">
                                <div class="bg-gray-100 rounded-full px-3 py-1">
                                    <p class="text-gray-600 text-xs text-center truncate font-medium">
                                        {{ $answer->participant->nickname }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Henüz cevap gelmedi</h3>
                            <p class="text-gray-500">İlk cevapları bekliyoruz...</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
    
    <script>
        document.addEventListener('livewire:navigated', () => {
            // Auto-refresh every 2 seconds
            setInterval(() => {
                if (typeof Livewire !== 'undefined' && Livewire.find('{{ $this->getId() }}')) {
                    Livewire.find('{{ $this->getId() }}').call('checkForUpdates');
                }
            }, 2000);
        });
        
        // Initial setup for current page
        if (typeof Livewire !== 'undefined') {
            setInterval(() => {
                if (Livewire.find('{{ $this->getId() }}')) {
                    Livewire.find('{{ $this->getId() }}').call('checkForUpdates');
                }
            }, 2000);
        }
    </script>
</div>