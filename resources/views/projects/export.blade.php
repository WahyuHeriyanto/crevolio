<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export - {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .print-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-white p-10">
    <div class="max-w-4xl mx-auto">
        <div class="mb-10 text-center">
            <h1 class="text-4xl font-bold mb-2">{{ $project->name }}</h1>
            <p class="text-gray-500 uppercase tracking-widest text-sm">
                {{ $project->detail->field->name }} | {{ $project->detail->status->name }}
            </p>
        </div>

        {{-- Images --}}
        <div class="grid grid-cols-1 gap-4 mb-10">
            @foreach($project->medias as $media)
                <img src="{{ asset('storage/' . $media->url) }}" class="w-full rounded-2xl">
            @endforeach
        </div>

        <div class="mb-10">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Overview</h2>
            <div class="text-gray-700 leading-relaxed text-justify">
                {!! nl2br(e($project->detail->description)) !!}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-10 mb-10">
            <div>
                <h3 class="font-bold text-sm text-gray-400 uppercase mb-3">Project Dates</h3>
                <p class="font-medium">
                    {{ \Carbon\Carbon::parse($project->detail->start_date)->format('d M Y') }} - 
                    {{ $project->detail->end_date ? \Carbon\Carbon::parse($project->detail->end_date)->format('d M Y') : 'Present' }}
                </p>
            </div>
            <div>
                <h3 class="font-bold text-sm text-gray-400 uppercase mb-3">Tools Used</h3>
                <p class="font-medium">{{ $project->detail->tools->pluck('tool.name')->implode(', ') }}</p>
            </div>
        </div>

        <div>
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Collaborators</h2>
            <div class="space-y-4">
                @foreach($project->detail->collaborators as $access)
                    <div class="flex justify-between items-center border-b border-gray-50 py-2">
                        <span class="font-bold">{{ $access->user->name }}</span>
                        <span class="text-gray-500">{{ $access->project_role }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-20 text-center no-print">
            <button onclick="window.print()" class="px-8 py-3 bg-black text-white rounded-full font-bold">
                Click to Print / Save as PDF
            </button>
        </div>
    </div>
</body>
</html>