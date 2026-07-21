<x-public-layout>
    @section('title', 'Page Locked - ' . $page->title)

    <div class="min-h-[60vh] flex items-center justify-center px-6 py-12">
        <div class="max-w-md w-full bg-white border border-slate-100 p-8 rounded-3xl shadow-xl shadow-slate-100/50 text-center">
            
            <!-- Lock Icon -->
            <div class="mx-auto w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">Access Code Required</h1>
            <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                The page <span class="font-semibold text-slate-800">"{{ $page->title }}"</span> is restricted. Please enter the required access code to view its content.
            </p>

            <form action="{{ route('page.unlock', $page->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="code" class="sr-only">Access Code</label>
                    <input type="password" name="code" id="code" required placeholder="Enter access code" 
                           class="w-full px-5 py-3 border border-slate-200 rounded-2xl text-center text-lg tracking-widest font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:tracking-normal placeholder:font-normal" />
                    
                    @error('code')
                        <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-semibold px-6 py-3 rounded-2xl shadow-md shadow-indigo-100 hover:shadow-lg hover:shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                    Unlock Content
                </button>
            </form>
        </div>
    </div>
</x-public-layout>
