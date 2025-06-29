<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Word Puzzle Time</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="https://arbor-education.com/wp-content/uploads/2024/10/Arbor.svg" sizes="32x32">
</head>
<body class="bg-[#fefcf8] text-zinc-800">
    <header class="p-4 flex items-center justify-between">
        <a href=" {{ route('home') }}" >
            <img src="https://arbor-education.com/wp-content/uploads/2024/10/arbor-education-logo.svg" alt="Arbor Education Logo" class="inline-block h-8 mr-2">
        </a>
        
        @if(session('student'))
            <div class="font-light text-sm">
                Hi {{ session('student.name') }}!
            </div>
        @endif
    </header>

    <main class="p-4">
