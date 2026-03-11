<div class="space-y-6">
    <!-- Upload Form -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Sunum Yükle</h3>

        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="upload">
            <div class="space-y-4">
                <!-- Title Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Sunum Başlığı (Opsiyonel)
                    </label>
                    <input
                        type="text"
                        wire:model="title"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Dosya adı kullanılacak"
                    >
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        PowerPoint Dosyası (.ppt, .pptx)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition">
                        <input
                            type="file"
                            wire:model="file"
                            accept=".ppt,.pptx"
                            class="hidden"
                            id="file-upload"
                        >
                        <label for="file-upload" class="cursor-pointer">
                            <div class="space-y-2">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                @if ($file)
                                    <p class="text-sm text-gray-600">{{ $file->getClientOriginalName() }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($file->getSize() / 1024 / 1024, 2) }} MB</p>
                                @else
                                    <p class="text-sm text-gray-600">Dosya seçmek için tıklayın veya sürükleyin</p>
                                    <p class="text-xs text-gray-500">Maksimum 50MB</p>
                                @endif
                            </div>
                        </label>
                    </div>
                    @error('file') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Upload Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    wire:target="file, upload"
                >
                    <span wire:loading.remove wire:target="upload">Yükle ve İşle</span>
                    <span wire:loading wire:target="upload">Yükleniyor...</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Presentations List -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-lg font-semibold mb-4">Sunumlar</h3>

        @if ($presentations->isEmpty())
            <p class="text-gray-500 text-center py-8">Henüz sunum yüklenmedi.</p>
        @else
            <div class="space-y-4">
                @foreach ($presentations as $presentation)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">{{ $presentation->title }}</h4>
                                <div class="mt-2 flex items-center gap-4 text-sm">
                                    <span class="flex items-center gap-1">
                                        @if ($presentation->status === 'processing')
                                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-blue-600">İşleniyor...</span>
                                        @elseif ($presentation->status === 'ready')
                                            <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-green-600">Hazır</span>
                                        @else
                                            <svg class="h-4 w-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-red-600">Hata</span>
                                        @endif
                                    </span>
                                    <span class="text-gray-600">{{ $presentation->total_slides }} slayt</span>
                                    <span class="text-gray-400">{{ $presentation->created_at->diffForHumans() }}</span>
                                </div>
                                @if ($presentation->error_message)
                                    <p class="mt-2 text-sm text-red-600">{{ $presentation->error_message }}</p>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                @if ($presentation->isReady())
                                    <button
                                        wire:click="$dispatch('activate-presentation', { presentationId: {{ $presentation->id }} })"
                                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm"
                                    >
                                        Başlat
                                    </button>
                                @endif
                                <button
                                    wire:click="deletePresentation({{ $presentation->id }})"
                                    wire:confirm="Bu sunumu silmek istediğinize emin misiniz?"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm"
                                >
                                    Sil
                                </button>
                            </div>
                        </div>

                        <!-- Slide Thumbnails -->
                        @if ($presentation->isReady() && $presentation->slides->count() > 0)
                            <div class="mt-4 grid grid-cols-6 gap-2">
                                @foreach ($presentation->slides->take(6) as $slide)
                                    <div class="aspect-video bg-gray-100 rounded overflow-hidden">
                                        <img
                                            src="{{ $slide->thumbnail_url }}"
                                            alt="Slayt {{ $slide->slide_number }}"
                                            class="w-full h-full object-cover"
                                        >
                                    </div>
                                @endforeach
                                @if ($presentation->slides->count() > 6)
                                    <div class="aspect-video bg-gray-100 rounded flex items-center justify-center text-gray-500 text-sm">
                                        +{{ $presentation->slides->count() - 6 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
