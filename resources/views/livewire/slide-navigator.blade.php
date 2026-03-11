<div class="space-y-4">
    @if (!$presentation)
        <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Sunum Seçilmedi</h3>
            <p class="mt-2 text-sm text-gray-500">Başlamak için yukarıdan bir sunum seçin ve "Başlat" butonuna tıklayın.</p>
        </div>
    @else
        <!-- Main Slide Display -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="relative bg-gray-900 aspect-video">
                @if ($currentSlide)
                    <img
                        src="{{ $currentSlide->image_url }}"
                        alt="Slayt {{ $currentSlide->slide_number }}"
                        class="w-full h-full object-contain"
                    >
                    <!-- Slide Counter -->
                    <div class="absolute bottom-4 right-4 bg-black bg-opacity-75 text-white px-4 py-2 rounded-lg">
                        <span class="font-semibold">{{ $currentSlideNumber }}</span>
                        <span class="text-gray-300"> / {{ $presentation->total_slides }}</span>
                    </div>
                @endif
            </div>

            <!-- Navigation Controls -->
            <div class="bg-gray-50 p-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <button
                        wire:click="previousSlide"
                        @disabled($currentSlideNumber <= 1)
                        class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Önceki
                    </button>

                    <div class="text-center">
                        <p class="text-sm text-gray-600">{{ $presentation->title }}</p>
                        <p class="text-xs text-gray-400 mt-1">Klavye: ← → ok tuşları</p>
                    </div>

                    <button
                        wire:click="nextSlide"
                        @disabled($currentSlideNumber >= $presentation->total_slides)
                        class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Sonraki
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Thumbnail Grid -->
        <div class="bg-white rounded-lg shadow p-4">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">Tüm Slaytlar</h4>
            <div class="grid grid-cols-8 gap-2 max-h-64 overflow-y-auto">
                @foreach ($slides as $slide)
                    <button
                        wire:click="goToSlide({{ $slide->slide_number }})"
                        class="relative aspect-video bg-gray-100 rounded overflow-hidden border-2 transition hover:border-blue-500
                            {{ $slide->slide_number === $currentSlideNumber ? 'border-blue-600 ring-2 ring-blue-300' : 'border-transparent' }}"
                    >
                        <img
                            src="{{ $slide->thumbnail_url }}"
                            alt="Slayt {{ $slide->slide_number }}"
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 text-white text-xs py-1 text-center">
                            {{ $slide->slide_number }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</div>

@script
<script>
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            @this.call('nextSlide');
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            @this.call('previousSlide');
        }
    });
</script>
@endscript
