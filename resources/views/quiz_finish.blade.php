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

        <!-- Left: Puzzle Display + Final Score -->
        <div class="w-1/2 p-4">
            <h1 class="text-2xl font-bold mb-4">Quiz Finished!</h1>
            
            <div class="mb-4 text-lg">
                @foreach (str_split($puzzleString) as $char)
                    @if(in_array($loop->index, $usedIndexes ?? []))
                        <span class="line-through opacity-50">{{ $char }}</span>
                    @else
                        <span>{{ $char }}</span>
                    @endif
                @endforeach
            </div>

            <p class="text-xl font-semibold mb-2">Your Total Score: <span class="text-green-600">{{ $totalScore }} pts</span></p>

            <a href="{{ route('quiz.index') }}" 
               class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
               Play Again
            </a>
        </div>

        <!-- Right: Words entered -->
        <div class="w-1/2 p-4 border-l border-gray-700">
            <h2 class="text-xl font-bold mb-4">Words You Found</h2>
            <ul class="space-y-1">
                @forelse($words as $entry)
                    <li>{{ $entry['word'] }} - <span class="text-green-400">{{ $entry['score'] }} pts</span></li>
                @empty
                    <li class="text-gray-400">No words submitted.</li>
                @endforelse
            </ul>
        </div>

    </div>
</div>

@include('layouts.footer')
