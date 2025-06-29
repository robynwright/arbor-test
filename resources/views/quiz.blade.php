@include('layouts.header')

@if($alert ?? false)
<div 
    id="alert-message" 
    class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-4 py-3 rounded shadow-lg flex items-center space-x-4 z-50"
    role="alert"
>
    <span>{{ $alert }}</span>
    <button 
        onclick="document.getElementById('alert-message').remove()"
        class="ml-auto text-white hover:text-gray-200 focus:outline-none"
        aria-label="Close alert"
    >
        &times;
    </button>
</div>

<script>
    setTimeout(() => {
        const alertEl = document.getElementById('alert-message');
        if (alertEl) alertEl.remove();
    }, 30000); // 30 seconds
</script>
@endif

<div class="flex items-center justify-center min-h-[84vh]">
    <div class="bg-stone-200 rounded-lg shadow-lg p-6 w-full max-w-4xl flex">

        <!-- Left: Puzzle + input -->
        <div class="w-1/2 p-4">
            <h1 class="text-2xl font-bold mb-4">Puzzle Time</h1>
            
            <div class="mb-4 text-lg">
                @foreach (str_split($puzzleString) as $char)
                    @if(in_array($loop->index, $usedIndexes ?? []))
                        <span class="line-through opacity-50">{{ $char }}</span>
                    @else
                        <span>{{ $char }}</span>
                    @endif
                @endforeach
            </div>

            <form method="POST" action="{{ route('quiz.play') }}">
                @csrf
                <input type="hidden" name="student_id" value="{{ session('student.id') }}">
                <input type="text" name="word" placeholder="Enter a word" class="w-full mb-4 p-2 rounded border border-gray-700 bg-stone-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 p-2 bg-[#adc928] rounded hover:bg-[#7cb93d]">
                        Submit Word
                    </button>
                    <button type="submit" formaction="{{ route('quiz.finish') }}"
                        class="flex-1 p-2 bg-[#f39225] rounded hover:bg-[#d67a1c]">
                        Finish Quiz
                    </button>
                </div>
            </form>
        </div>

        <!-- Right: Words entered -->
        <div class="w-1/2 p-4 border-l border-gray-700">
            <h2 class="text-xl font-bold mb-4">Your Words</h2>
            <ul class="space-y-1">
                @forelse($words as $entry)
                    <li>{{ $entry['word'] }} - <span class="text-[#7cb93d]">{{ $entry['score'] }} pts</span></li>
                @empty
                    <li class="text-gray-400">No words submitted yet.</li>
                @endforelse
            </ul>
        </div>

    </div>
</div>

@include('layouts.footer')
