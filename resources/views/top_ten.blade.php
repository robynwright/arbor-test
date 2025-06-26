@include('layouts.header')

<div class="flex items-center justify-center min-h-[84vh]">
    <div class="bg-stone-200 rounded-lg shadow-lg p-6 w-full max-w-2xl">
        <h1 class="text-2xl font-bold mb-4">Top 10 High Scores</h1>

        <ol class="space-y-2">
            @forelse ($scores as $score)
                <li>
                    <strong>{{ $score->word }}</strong> - {{ $score->score }} pts
                    <span class="text-sm text-gray-600">({{ $score->student->name }} {{ $score->student->surname }})</span>
                </li>
            @empty
                <li class="text-gray-500">No scores yet.</li>
            @endforelse
        </ol>

        <a href="/" class="block mt-4 underline hover:font-bold">Back to Home</a>
    </div>
</div>

@include('layouts.footer')
