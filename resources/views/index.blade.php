@include('layouts.header')

<div class="flex items-center justify-center min-h-[84vh]">
    <div class="bg-stone-200 rounded-lg shadow-lg p-6 flex w-full max-w-3xl">
        
        <!-- Left Column -->
        <div class="w-1/2 flex flex-col justify-center p-4">
            <h1 class="text-3xl font-bold mb-4">PUZZLE TIME</h1>
            <p class="mb-4">
                Welcome to the Word Puzzle game! Enter your student ID to start playing. <br>
                This game is designed to enhance your vocabulary and problem-solving skills.
            </p>
            @if(session('alert'))
                <div class="bg-red-200 text-red-800 p-3 rounded mb-4">
                    {{ session('alert') }}
                </div>
            @endif
            <form method="POST" action="{{ route('quiz.index') }}">
                <!-- CSRF Token -->
                @csrf
                <input 
                    type="text" 
                    name="student_id" 
                    placeholder="Enter Student ID" 
                    class="w-full mb-4 p-2 rounded border border-gray-700 bg-stone-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    type="submit" 
                    class="w-full p-2 bg-[#adc928] rounded hover:bg-[#7cb93d]"
                >
                    Play Quiz
                </button>
            </form>
        </div>

        <!-- Right Column -->
        <div class="w-1/2 p-4 flex items-center justify-center">
            <img 
                src="{{ asset('images/word-play.jpg') }}" 
                alt="Word Play" 
                class="rounded-lg shadow"
            >
        </div>
        
    </div>
</div>

@include('layouts.footer')
