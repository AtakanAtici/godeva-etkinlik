<div class="space-y-6">
    <!-- Room Header -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                @if($editing_title)
                    <!-- Editing Mode -->
                    <div class="space-y-3">
                        <input 
                            type="text" 
                            wire:model="room_title"
                            class="text-2xl font-bold bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-gray-900 dark:text-white w-full max-w-md focus:ring-2 focus:ring-blue-500"
                            placeholder="Etkinlik başlığı"
                            wire:keydown.enter="saveRoomTitle"
                            wire:keydown.escape="cancelEditingTitle"
                            x-init="$nextTick(() => $el.focus())"
                        >
                        @error('room_title')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        <div class="flex gap-2">
                            <button 
                                wire:click="saveRoomTitle"
                                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium"
                            >
                                ✓ Kaydet
                            </button>
                            <button 
                                wire:click="cancelEditingTitle"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm font-medium"
                            >
                                ✕ İptal
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Display Mode -->
                    <div class="flex items-center gap-3 group">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $room->title }}</h2>
                        <button 
                            wire:click="startEditingTitle"
                            class="opacity-0 group-hover:opacity-100 transition-opacity p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                            title="Başlığı düzenle"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                    </div>
                @endif
                
                <p class="text-gray-600 dark:text-gray-300 mt-2">Oda Kodu: 
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
                    <a href="/host/room/{{ $room->id }}/report" target="_blank"
                       class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                        📊 Rapor Görüntüle
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

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" x-data="{ activeTab: 'questions' }">
                <button
                    @click="activeTab = 'questions'"
                    :class="activeTab === 'questions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm"
                >
                    Sorular
                </button>
                <button
                    @click="activeTab = 'presentation'"
                    :class="activeTab === 'presentation' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="py-4 px-1 border-b-2 font-medium text-sm"
                >
                    Sunum
                </button>
            </nav>
        </div>

        <div class="p-6">
            <!-- Questions Tab -->
            <div x-show="activeTab === 'questions'" x-cloak>
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
                        <select wire:model.live="question_type" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                            <option value="open_text">Açık Uçlu</option>
                            <option value="multiple_choice">Çoktan Seçmeli</option>
                            <option value="number">Sayı</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cevap Gösterim Gecikmesi
                        </label>
                        <div class="flex items-center gap-3">
                            <input
                                type="range"
                                wire:model.live="answer_reveal_delay"
                                min="0"
                                max="600"
                                step="5"
                                class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600"
                            >
                            <span class="text-lg font-bold text-gray-900 dark:text-white w-20 text-center">
                                @if($answer_reveal_delay >= 60)
                                    {{ floor($answer_reveal_delay / 60) }}dk {{ $answer_reveal_delay % 60 }}s
                                @else
                                    {{ $answer_reveal_delay }}s
                                @endif
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Soru yayınlandıktan sonra cevaplar
                            @if($answer_reveal_delay >= 60)
                                <span class="font-semibold">{{ floor($answer_reveal_delay / 60) }} dakika {{ $answer_reveal_delay % 60 }} saniye</span>
                            @else
                                <span class="font-semibold">{{ $answer_reveal_delay }} saniye</span>
                            @endif
                            sonra gösterilecek
                        </p>
                    </div>

                    @if($question_type === 'multiple_choice')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Seçenekler
                            </label>
                            <div class="space-y-2">
                                @foreach($question_options as $index => $option)
                                    <div class="flex gap-2 items-center">
                                        <span class="text-sm font-medium text-gray-500 w-6">{{ chr(65 + $index) }}.</span>
                                        <input 
                                            type="text"
                                            wire:model="question_options.{{ $index }}"
                                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Seçenek {{ chr(65 + $index) }}"
                                            maxlength="200"
                                        >
                                        @if(count($question_options) > 2)
                                            <button 
                                                type="button"
                                                wire:click="removeOption({{ $index }})"
                                                class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg"
                                                title="Seçeneği sil"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                                
                                @error('question_options.*')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                                
                                @if(count($question_options) < 12)
                                    <button 
                                        type="button"
                                        wire:click="addOption"
                                        class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Seçenek Ekle
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

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
                                        •
                                        @if($question->type === 'multiple_choice')
                                            <span class="text-blue-600 dark:text-blue-400">Çoktan Seçmeli</span>
                                        @elseif($question->type === 'number')
                                            <span class="text-purple-600 dark:text-purple-400">Sayı</span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Açık Uçlu</span>
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
            </div>

            <!-- Presentation Tab -->
            <div x-show="activeTab === 'presentation'" x-cloak>
                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Upload & Manage -->
                    <div>
                        <livewire:manage-presentation :room="$room" />
                    </div>

                    <!-- Slide Navigator -->
                    <div>
                        <livewire:slide-navigator :room="$room" />
                    </div>
                </div>
            </div>
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