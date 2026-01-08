<div>
    <form wire:submit="joinRoom" class="space-y-4">
        <div>
            <label for="room_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Oda Kodu
            </label>
            <input 
                type="text" 
                id="room_code"
                wire:model.live="room_code"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white text-center text-xl font-mono uppercase tracking-widest"
                placeholder="ABC123"
                maxlength="6"
                style="text-transform: uppercase;"
            >
            @error('room_code') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <button 
            type="submit"
            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Katıl</span>
            <span wire:loading>Katılıyor...</span>
        </button>

        <div class="text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                QR kodunuz var mı?<br>
                <span class="text-xs">Kamerayı açarak QR kodu tarayabilirsiniz</span>
            </p>
        </div>
    </form>
</div>