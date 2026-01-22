@extends('admin.layouts.app')

@section('admin-content')

<h1 class="text-2xl font-bold text-gray-800 mb-8 uppercase tracking-wide">Master Users</h1>

<div class="flex gap-6 mb-10">
    <div class="bg-white p-6 rounded-xl border shadow-sm flex flex-col justify-between h-32 flex-1">
        <p class="text-xs font-bold text-gray-400 uppercase">Total Users</p>
        <p class="text-3xl font-black text-blue-600">{{ number_format($totalUsers) }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm flex flex-col justify-between h-32 flex-1">
        <p class="text-xs font-bold text-gray-400 uppercase">Male</p>
        <p class="text-3xl font-black text-green-500">{{ $genderStats['male'] ?? 0 }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm flex flex-col justify-between h-32 flex-1">
        <p class="text-xs font-bold text-gray-400 uppercase">Female</p>
        <p class="text-3xl font-black text-pink-500">{{ $genderStats['female'] ?? 0 }}</p>
    </div>

    <div class="bg-white p-6 rounded-xl border shadow-sm flex flex-col justify-between h-32 flex-1">
        <p class="text-xs font-bold text-gray-400 uppercase">Completed Profiles</p>
        <p class="text-3xl font-black text-orange-500">{{ $completedProfiles }}</p>
    </div>
</div>


<div class="bg-white rounded-xl border shadow-sm overflow-hidden">
    
    <div class="p-4 bg-gray-50 border-b flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-table-list text-gray-400"></i>
            <span class="font-bold text-sm uppercase text-gray-600">List Users</span>
        </div>
        
        <form method="GET" class="flex gap-2">
            <select name="per_page" onchange="this.form.submit()" class="text-xs border rounded-lg px-2 py-1.5 outline-none focus:ring-2 focus:ring-blue-500">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
            </select>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." 
                       class="text-xs border rounded-lg pl-8 pr-3 py-1.5 w-48 outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-[10px]"></i>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-500 font-bold border-b text-xs uppercase">
                <tr>
                    <th class="px-6 py-4 text-left w-16">No</th>
                    <th class="px-6 py-4 text-left">User</th>
                    <th class="px-6 py-4 text-center">Gender</th>
                    <th class="px-6 py-4 text-left text-nowrap">Email</th>
                    <th class="px-6 py-4 text-left">Carieer Position</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $index => $user)
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-6 py-4 text-gray-400 font-mono">{{ $users->firstItem() + $index }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->profile?->photo_profile ? asset('storage/'.$user->profile->photo_profile) : asset('assets/images/photo-profile-default.png') }}"
                                 class="w-10 h-10 rounded-full object-cover border border-gray-200">
                            <div>
                                <p class="font-bold text-gray-800 leading-none">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase italic">Birth: {{ $user->profile?->birth ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded-md text-[10px] font-bold border {{ $user->profile?->gender == 'female' ? 'bg-pink-50 text-pink-600 border-pink-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                            {{ strtoupper($user->profile?->gender ?? 'N/A') }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-700">{{ $user->email }}</p>
                        <p class="text-[10px] text-gray-400 italic">Seen: {{ $user->last_seen_at?->diffForHumans() ?? 'Never' }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-600 text-xs">{{ $user->profile?->careerPosition?->name ?? 'N/A' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button class="p-1.5 text-blue-500 hover:bg-blue-50 rounded transition-colors"><i class="fa-solid fa-edit"></i></button>
                            <button class="p-1.5 text-red-500 hover:bg-red-50 rounded transition-colors"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-4 bg-gray-50 border-t">
        {{ $users->links() }}
    </div>
</div>

@endsection