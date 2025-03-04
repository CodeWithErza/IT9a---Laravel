<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <header class="text-center mb-6">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100">
                NOTES
            </h1>
        </header>

        <form 
            hx-post="{{ route('notes.store') }}" 
            hx-target="body" 
            hx-swap="outerHTML"
            class="space-y-6 transform hover:scale-[1.01] transition-all duration-300">
            @csrf
            <div class="group relative">
                <input
                    type="text"
                    name="title"
                    placeholder="Give your note a catchy title! ✨"
                    value="{{ old('title') }}"
                    class="block w-full px-4 py-3 bg-white/50 backdrop-blur-sm border-2 border-indigo-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-300 focus:ring-opacity-50 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md"
                />
                <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>

            <div class="relative group">
                <textarea
                    name="content"
                    placeholder="Time to unleash your thoughts! 🚀"
                    class="block w-full px-4 py-3 min-h-[200px] bg-white/50 backdrop-blur-sm border-2 border-indigo-200 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-300 focus:ring-opacity-50 rounded-xl shadow-sm transition-all duration-300 hover:shadow-md resize-none"
                    rows="6"
                >{{ old('content') }}</textarea>
                <div class="absolute bottom-4 right-4 text-gray-400 text-sm">
                    <i class="fas fa-magic"></i>
                </div>
                <x-input-error :messages="$errors->get('content')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end space-x-4">
                <button type="reset" class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors duration-300 border-2 border-gray-300 rounded-lg shadow-md hover:shadow-lg">
                    Clear ✨
                </button>
                <button type="submit" class="bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 transform hover:scale-105 transition-all duration-300 rounded-xl px-6 py-3 text-lg font-medium text-white border-2 border-indigo-500 shadow-md hover:shadow-lg">
                    Save Note 📝
                </button>
            </div>
        </form>
        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
    @foreach ($notes as $note)
        <div class="p-6 flex space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <div class="flex-1">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-gray-800">{{ $note->user->name }}</span>
                        <small class="ml-2 text-sm text-gray-600">&middot; {{ $note->created_at->format('j M Y, g:i a') }}</small>
                    </div>
                    @if ($note->user->is(auth()->user()))
                        <x-dropdown>
                            <x-slot name="trigger">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('notes.edit', $note)"
                                    hx-boost="true"
                                    hx-push-url="true">
                                    {{ __('Edit') }}
                                </x-dropdown-link>
                                <form 
                                    hx-delete="{{ route('notes.destroy', $note) }}"
                                    hx-target="body" 
                                    hx-swap="outerHTML"
                                    hx-indicator="#progress-bar">
                                    @csrf
                                    @method('DELETE')
                                    <x-dropdown-link>
                                        <button type="submit">
                                            {{ __('Delete') }}
                                        </button>
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>
                <h3 class="mt-2 text-xl font-semibold text-gray-900">{{ $note->title }}</h3>
                <p class="mt-4 text-lg text-gray-900">{{ $note->content }}</p>
            </div>
        </div>
    @endforeach
</div>
    </div>

    <!-- Add progress bar -->
    <div id="progress-bar" class="htmx-indicator fixed top-0 left-0 w-full h-1 bg-indigo-500 transform origin-left scale-x-0 transition-transform duration-300"></div>

    @push('scripts')
    <script>
        // Add a fun animation when focusing on input fields
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.style.transform = 'translateY(-2px)';
            });
            input.addEventListener('blur', () => {
                input.style.transform = 'translateY(0)';
            });
        });

        // Progress bar animation
        document.body.addEventListener('htmx:beforeRequest', function() {
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.transform = 'scaleX(0.3)';
        });

        document.body.addEventListener('htmx:beforeSwap', function() {
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.transform = 'scaleX(0.9)';
        });

        document.body.addEventListener('htmx:afterSwap', function() {
            const progressBar = document.getElementById('progress-bar');
            progressBar.style.transform = 'scaleX(1)';
            setTimeout(() => {
                progressBar.style.transform = 'scaleX(0)';
            }, 200);
        });
    </script>
    @endpush
</x-app-layout>