<div class="bg-white rounded-2xl p-6 shadow-sm">
    <h3 class="font-semibold text-lg mb-1">
        Personal Finance Dashboard
    </h3>

    <p class="text-gray-600 mb-4">
        A clean dashboard concept to help people track expenses.
    </p>

    <div class="flex gap-3 text-sm">
        <a href="#" class="underline">View</a>

        @auth
            <button class="text-green-600">Join</button>
        @endauth

        @if ($isOwner)
            <a href="#" class="text-blue-600">Edit</a>
            <button class="text-red-500">Delete</button>
        @endif
    </div>
</div>
