<div class="space-y-6" wire:poll.500ms="checkForQuestionUpdates">
    @if (!$joined)
        <!-- Join Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    {{ $room->title }}
                </h2>
                <p class="text-gray-600 dark:text-gray-300">
                    Bu etkinliğe katılmak için bir takma ad seçin
                </p>
            </div>

            <form wire:submit="joinRoom" class="space-y-4">
                <div>
                    <label for="nickname" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Takma Ad (Opsiyonel)
                    </label>
                    <input 
                        type="text" 
                        id="nickname"
                        wire:model="nickname"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Örn: Ahmet K."
                        maxlength="50"
                    >
                    <p class="text-xs text-gray-500 mt-1">
                        Boş bırakırsanız "Anonim" olarak görüneceksiniz
                    </p>
                    @error('nickname') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                >
                    Etkinliğe Katıl
                </button>
            </form>
        </div>
    @else
        <!-- Participant Interface -->
        <div class="space-y-6">
            <!-- Room Info -->
            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4 text-center">
                <p class="text-green-800 dark:text-green-200 font-medium">
                    ✓ {{ $room->title }} etkinliğine katıldınız
                </p>
                <p class="text-green-600 dark:text-green-300 text-sm">
                    Katılımcı: {{ $participant->nickname }}
                </p>
            </div>

            @if ($waiting_for_question)
                <!-- Waiting for Question -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 text-center">
                    <div class="animate-pulse">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                        Soru Bekleniyor
                    </h3>
                    <p class="text-gray-600 dark:text-gray-300">
                        Host bir soru yayınladığında burada görünecek
                    </p>
                </div>
            @else
                <!-- Active Question -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center">
                            {{ $currentQuestion->title }}
                        </h3>
                        
                        @if (session()->has('answer_submitted'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('answer_submitted') }}
                            </div>
                        @endif
                        
                        @if ($errorMessage)
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ $errorMessage }}
                            </div>
                        @endif
                    </div>

                    <form wire:submit="submitAnswer" class="space-y-4">
                        @if($currentQuestion->type === 'multiple_choice' && $currentQuestion->options)
                            <!-- Multiple Choice Options -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Seçiminizi yapın
                                </label>
                                <div class="space-y-3">
                                    @foreach($currentQuestion->options as $index => $option)
                                        <label class="flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg hover:border-blue-300 dark:hover:border-blue-500 cursor-pointer transition-colors
                                            {{ $answer_content == $index ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                            <input 
                                                type="radio" 
                                                wire:model.live="answer_content" 
                                                value="{{ $index }}"
                                                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600"
                                            >
                                            <div class="ml-3 flex-1">
                                                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ chr(65 + $index) }}.</span>
                                                <span class="text-gray-900 dark:text-white ml-2">{{ $option }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('answer_content') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                        @else
                            <!-- Open Text Answer -->
                            <div>
                                <label for="answer_content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Cevabınız
                                </label>
                                <textarea 
                                    id="answer_content"
                                    wire:model.live="answer_content"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white resize-none"
                                    placeholder="Cevabınızı buraya yazın..."
                                    maxlength="1000"
                                ></textarea>
                                @error('answer_content') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">
                                    <span x-text="$wire.answer_content.length"></span>/1000 karakter
                                </p>
                            </div>
                        @endif

                        <button 
                            type="submit"
                            class="w-full text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                            :class="($wire.answer_content !== '' && $wire.answer_content !== null && !$wire.submitting) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed'"
                            :disabled="$wire.answer_content === '' || $wire.answer_content === null || $wire.submitting"
                            wire:loading.attr="disabled"
                            wire:target="submitAnswer"
                        >
                            <span wire:loading.remove wire:target="submitAnswer">
                                @if($submitting) Gönderiliyor... @else Cevabı Gönder @endif
                            </span>
                            <span wire:loading wire:target="submitAnswer">Gönderiliyor...</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @endif
</div>