<div class="space-y-6">
    <!-- Room Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $room->title }}</h2>
                <p class="text-gray-600 dark:text-gray-300">Oda Kodu: 
                    <span class="font-mono text-lg font-bold text-blue-600 dark:text-blue-400">{{ $room->code }}</span>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Katılımcı Sayısı: <span class="font-semibold">{{ $total_participants }}</span>
                </p>
            </div>
            <div class="text-right space-y-2">
                <div class="flex flex-col gap-2">
                    <a href="/presentation/{{ $room->code }}" target="_blank" 
                       class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                        📺 Sunum Ekranı
                    </a>
                    <a href="/join/{{ $room->code }}" target="_blank"
                       class="inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        📱 Katılımcı Linki
                    </a>
                    <form action="/logout" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                            🚪 Çıkış Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Question Management -->
        <div class="space-y-6">
            <!-- Create New Question -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Yeni Soru Oluştur</h3>
                
                <form wire:submit="createQuestion" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Soru Metni
                        </label>
                        <textarea 
                            wire:model="new_question_title"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                            placeholder="Örn: En sevdiğiniz programlama dili nedir?"
                            maxlength="500"
                        ></textarea>
                        @error('new_question_title') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Soru Tipi
                        </label>
                        <select wire:model="question_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="open_text">Açık Uçlu</option>
                            <option value="multiple_choice" disabled>Çoktan Seçmeli (Yakında)</option>
                        </select>
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg disabled:opacity-50"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Soru Oluştur</span>
                        <span wire:loading>Oluşturuluyor...</span>
                    </button>
                </form>
            </div>

            <!-- Questions List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sorularım</h3>
                
                <div class="space-y-3">
                    @forelse($questions as $question)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 @if($question->status === 'published') bg-blue-50 dark:bg-blue-900 @endif">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $question->title }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Durum: 
                                        @if($question->status === 'published')
                                            <span class="text-green-600 dark:text-green-400">🟢 Yayında</span>
                                        @elseif($question->status === 'closed')
                                            <span class="text-red-600 dark:text-red-400">🔴 Kapalı</span>
                                        @else
                                            <span class="text-gray-600 dark:text-gray-400">⚪ Taslak</span>
                                        @endif
                                        • {{ $question->answers()->count() }} cevap
                                    </p>
                                </div>
                                
                                <div class="flex gap-2">
                                    @if($question->status === 'draft')
                                        <button 
                                            wire:click="publishQuestion('{{ $question->id }}')"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs"
                                        >
                                            Yayınla
                                        </button>
                                    @elseif($question->status === 'published')
                                        <button 
                                            wire:click="closeQuestion('{{ $question->id }}')"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                        >
                                            Kapat
                                        </button>
                                    @elseif($question->status === 'closed')
                                        <button 
                                            wire:click="reopenQuestion('{{ $question->id }}')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                        >
                                            Yeniden Aç
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                            Henüz soru oluşturmadınız
                        </p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Live Answers -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                Canlı Cevaplar
                @if($current_question)
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        ({{ $current_question->title }})
                    </span>
                @endif
            </h3>
            
            @if($current_question && count($recent_answers) > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($recent_answers as $answer)
                        <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-3 bg-gray-50 dark:bg-gray-700">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $answer->content }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $answer->participant->nickname }} • {{ $answer->submitted_at->diffForHumans() }}
                                    </p>
                                </div>
                                
                                <button 
                                    wire:click="hideAnswer('{{ $answer->id }}')"
                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded text-xs"
                                    title="Cevabı gizle"
                                >
                                    Gizle
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                    @if($current_question)
                        Henüz cevap gelmedi
                    @else
                        Aktif soru yok
                    @endif
                </p>
            @endif
        </div>
    </div>
    
    <script>
        document.addEventListener('livewire:navigated', () => {
            // Auto-refresh every 3 seconds
            setInterval(() => {
                if (typeof Livewire !== 'undefined' && Livewire.find('{{ $this->getId() }}')) {
                    Livewire.find('{{ $this->getId() }}').call('refreshStats');
                }
            }, 3000);
        });
        
        // Initial setup for current page
        if (typeof Livewire !== 'undefined') {
            setInterval(() => {
                if (Livewire.find('{{ $this->getId() }}')) {
                    Livewire.find('{{ $this->getId() }}').call('refreshStats');
                }
            }, 3000);
        }
    </script>
</div>