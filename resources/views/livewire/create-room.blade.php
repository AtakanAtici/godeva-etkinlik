<div>
    <form wire:submit="createRoom" class="space-y-4">
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Sunum Başlığı
            </label>
            <input 
                type="text" 
                id="title"
                wire:model.live="title"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                placeholder="Örn: Programlama Dilleri Anketi"
                maxlength="255"
            >
            @error('title') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <div class="space-y-3">
            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="allow_anonymous" 
                    wire:model="allow_anonymous"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label for="allow_anonymous" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Anonim katılıma izin ver
                </label>
            </div>

            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="profanity_filter" 
                    wire:model="profanity_filter"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
                <label for="profanity_filter" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Küfür filtresi aktif
                </label>
            </div>
        </div>

        <button 
            type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Oda Oluştur</span>
            <span wire:loading>Oluşturuluyor...</span>
        </button>
    </form>
</div>