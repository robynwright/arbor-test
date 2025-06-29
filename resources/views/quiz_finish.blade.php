@include('layouts.header')

<div class="flex items-center justify-center min-h-[84vh]">
    <div class="bg-stone-200 rounded-lg shadow-lg p-6 w-full max-w-4xl flex">

        <!-- Left Column: Summary + Remaining Letters + Play Again -->
        <div class="w-1/2 p-4">
            <h1 class="text-2xl font-bold mb-4">Quiz Complete!</h1>

            <p class="mb-4 text-lg">
                @if($totalScore === 0)
                    You didn't score any points. Better luck next time!
                @else 
                    Well done! You scored <span class="font-bold text-green-600">{{ $totalScore }}</span> points.
                @endif
            </p>

            @if(count($words) > 0)
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-2">Your Words</h2>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($words as $entry)
                        <li>{{ $entry['word'] }} - <span class="text-[#7cb93d]">{{ $entry['score'] }} pts</span></li>
                    @endforeach
                </ul>
            </div>
            @endif

            <a href="{{ route('quiz.index') }}" class="inline-block mt-4 underline hover:font-bold">Play Again</a>
        </div>

        <!-- Right Column: Your Words + Other Possible Words -->
        <div class="w-1/2 p-4 border-l border-gray-700 overflow-auto max-h-[70vh]">
            @if (isset($remainingLetters) && count($remainingLetters) > 0)
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-2">Remaining Letters</h2>
                    <p class="text-lg font-mono tracking-wider">
                        {{ implode(' ', $remainingLetters) }}
                    </p>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-bold mb-2">Other Words You Could Have Guessed</h2>
                @if (isset($remainingWords) && count($remainingWords) > 0)
                    <ul class="list-disc list-inside space-y-1">
                        @foreach (collect($remainingWords)->sortByDesc('score') as $entry)
                            <li>{{ $entry['word'] }} - <span class="text-[#f39225]">{{ $entry['score'] }} pts</span></li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600">There were no other valid words left from the remaining letters.</p>
                @endif
            </div>
        </div>

    </div>
</div>

@include('layouts.footer')
