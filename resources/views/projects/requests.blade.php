@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <h1 class="text-3xl font-bold mb-8">Join Requests</h1>

    <div class="space-y-4">
        @forelse($requests as $req)
        <div class="bg-white p-6 rounded-[24px] border shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset('storage/' . ($req->requester->profile->photo_profile ?? 'default.jpg')) }}" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <h4 class="font-bold">{{ $req->requester->name }}</h4>
                    <p class="text-sm text-gray-500">Wants to join <span class="font-semibold text-indigo-600">{{ $req->project->name }}</span></p>
                </div>
            </div>

            @if($req->status === 'pending')
            <div class="flex gap-2">
                <form action="{{ route('projects.handle-request', [$req, 'approve']) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-black text-white rounded-xl text-sm font-bold hover:bg-gray-800">Approve</button>
                </form>
                <form action="{{ route('projects.handle-request', [$req, 'reject']) }}" method="POST">
                    @csrf
                    <button class="px-4 py-2 bg-gray-100 text-red-500 rounded-xl text-sm font-bold hover:bg-gray-200">Reject</button>
                </form>
            </div>
            @else
            <span class="px-4 py-2 rounded-xl text-sm font-bold {{ $req->status === 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }}">
                {{ ucfirst($req->status) }}
            </span>
            @endif
        </div>
        @empty
        <div class="text-center py-20 bg-gray-50 rounded-[32px] border-2 border-dashed">
            <p class="text-gray-400">No pending requests found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection