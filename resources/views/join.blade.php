<x-layouts.guest>
    <div class="min-h-screen bg-white flex flex-col">
        <!-- Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-8">
            <div class="max-w-md mx-auto text-center">
                <div class="mb-6">
                    <img src="https://godeva.com.tr/assets/img/logo_home6.svg" alt="Godeva Logo" class="h-12 w-auto mx-auto mb-4">
                    <h1 class="text-3xl font-bold text-gray-900">Etkinliğe Katılın</h1>
                </div>
                <div class="bg-gray-50 rounded-lg px-4 py-3 inline-block">
                    <p class="text-sm text-gray-600 font-medium">Oda Kodu:</p>
                    <p class="text-2xl font-mono font-bold text-green-600 tracking-wider">{{ $roomCode }}</p>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 px-6 py-8">
            <div class="max-w-md mx-auto">
                <livewire:participant-join :room-code="$roomCode" />
            </div>
        </div>
        
        <!-- Footer -->
        <div class="border-t border-gray-200 px-6 py-4">
            <p class="text-center text-sm text-gray-500">© 2026 Godeva - Canlı Etkinlik Sistemi</p>
        </div>
    </div>
</x-layouts.guest>