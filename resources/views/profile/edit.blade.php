@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-6 mb-20">
    <div class="mb-10">
        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
        <p class="text-gray-500 mt-2">Manage your personal information and creative identity.</p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        {{-- MEDIA SECTION --}}
        <div class="bg-white rounded-[32px] border border-gray-100 p-8 shadow-sm">
            <h3 class="text-lg font-bold mb-6">Profile Media</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Photo Profile --}}
                <div x-data="{photoPreview: null}">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Photo Profile</label>
                    <div class="flex items-center gap-5">
                        <img :src="photoPreview ? photoPreview : '{{ asset('storage/'.$user->profile->photo_profile) }}'" class="w-24 h-24 rounded-full object-cover border-4 border-gray-50">
                        <input type="file" name="photo_profile" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" 
                               @change="photoPreview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>

                {{-- Background --}}
                <div x-data="{bgPreview: null}">
                    <label class="block text-sm font-bold text-gray-700 mb-3">Cover Image</label>
                    <div class="relative h-24 w-full rounded-2xl bg-gray-100 overflow-hidden border border-dashed border-gray-300">
                        <img :src="bgPreview ? bgPreview : '{{ asset('storage/'.$user->profile->background_image) }}'" class="w-full h-full object-cover">
                        <input type="file" name="background_image" class="absolute inset-0 opacity-0 cursor-pointer" 
                               @change="bgPreview = URL.createObjectURL($event.target.files[0])">
                    </div>
                </div>
            </div>
        </div>

        {{-- BASIC INFO --}}
        <div class="bg-white rounded-[32px] border border-gray-100 p-8 shadow-sm space-y-6">
            <h3 class="text-lg font-bold mb-2">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-black focus:border-black shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Career Position</label>
                    <select name="career_position_id" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-black">
                        @foreach($careerPositions as $cp)
                            <option value="{{ $cp->id }}" {{ $user->profile->career_position_id == $cp->id ? 'selected' : '' }}>{{ $cp->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Short Bio</label>
                <input type="text" name="short_description" value="{{ old('short_description', $user->profile->short_description) }}" placeholder="Ex: UI/UX Designer based in Jakarta" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-black">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Full Description</label>
                <textarea name="description" rows="4" class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-black">{{ old('description', $user->profile->description) }}</textarea>
            </div>

            {{-- PRIVACY SETTINGS --}}
            <div class="bg-white rounded-[32px] border border-gray-100 p-8 shadow-sm" 
                x-data="{ 
                    isPublic: {{ $user->profile->status === 'public' ? 'true' : 'false' }},
                    get statusLabel() { return this.isPublic ? 'public' : 'private' }
                }">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold">Profile Visibility</h3>
                        <p class="text-sm text-gray-500 mt-1">Control who can follow your creative journey.</p>
                    </div>
                    
                    {{-- Toggle Switch --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold uppercase tracking-wider" 
                            :class="isPublic ? 'text-indigo-600' : 'text-gray-400'" 
                            x-text="statusLabel"></span>
                        
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" class="sr-only peer" 
                                :value="statusLabel"
                                x-model="isPublic"
                                {{ $user->profile->status === 'public' ? 'checked' : '' }}>
                            {{-- Hidden input untuk memastikan nilai terkirim meskipun toggle off (private) --}}
                            <input type="hidden" name="status" :value="statusLabel">
                            
                            <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-black"></div>
                        </label>
                    </div>
                </div>

                {{-- ALERT MESSAGE --}}
                <div class="mt-6 p-4 rounded-2xl border transition-all duration-300 flex gap-3 items-start"
                    :class="isPublic ? 'bg-indigo-50 border-indigo-100 text-indigo-700' : 'bg-amber-50 border-amber-100 text-amber-700'">
                    
                    <div class="mt-0.5">
                        <template x-if="isPublic">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                        <template x-if="!isPublic">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </template>
                    </div>

                    <div>
                        <p class="text-sm font-bold" x-text="isPublic ? 'Your profile is Public' : 'Your profile is Private'"></p>
                        <p class="text-xs mt-1 leading-relaxed">
                            <template x-if="isPublic">
                                <span>Everyone can follow you directly without needing your approval.</span>
                            </template>
                            <template x-if="!isPublic">
                                <span>Only people you approve can follow you.</span>
                            </template>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- SKILLS & TOOLS SECTION --}}
        <div class="bg-white rounded-[40px] border border-gray-100 p-10 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-12">
            
            {{-- 1. EXPERTISES --}}
            <div x-data="{ 
                open: false,
                selected: {{ json_encode($userExpertiseIds) }},
                options: [
                    @foreach($expertises as $exp)
                        { id: {{ $exp->id }}, name: '{{ $exp->name }}' },
                    @endforeach
                ],
                toggle(id) {
                    if (this.selected.includes(id)) {
                        this.selected = this.selected.filter(i => i !== id);
                    } else {
                        this.selected.push(id);
                    }
                },
                getName(id) {
                    return this.options.find(o => o.id == id)?.name;
                }
            }" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa- star text-indigo-500 text-sm"></i> Expertises
                </h3>
                
                {{-- Area Label yang Terpilih --}}
                <div class="flex flex-wrap gap-2 min-h-[50px] p-3 rounded-2xl bg-gray-50 border border-dashed border-gray-200">
                    <template x-for="id in selected" :key="id">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-black text-white rounded-lg text-xs font-bold animate-in fade-in zoom-in duration-300">
                            <span x-text="getName(id)"></span>
                            <button type="button" @click="toggle(id)" class="hover:text-red-400 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            {{-- Input dikirim sebagai array ke Controller --}}
                            <input type="hidden" name="expertises[]" :value="id">
                        </div>
                    </template>
                    <template x-if="selected.length === 0">
                        <span class="text-gray-400 text-xs italic p-1 px-2">No expertise selected yet...</span>
                    </template>
                </div>

                {{-- Custom Dropdown --}}
                <div class="relative" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 flex justify-between items-center hover:border-indigo-500 transition group">
                        <span class="text-gray-500 text-sm group-hover:text-indigo-600 transition">Select Expertises</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute z-30 mt-2 w-full max-h-60 overflow-y-auto rounded-2xl bg-white border border-gray-100 shadow-2xl py-2">
                        <template x-for="opt in options" :key="opt.id">
                            <div @click="toggle(opt.id)"
                                class="px-5 py-3 cursor-pointer hover:bg-indigo-50 transition flex justify-between items-center"
                                :class="selected.includes(opt.id) ? 'bg-indigo-50/50 text-indigo-600' : 'text-gray-600'">
                                <span class="text-sm font-medium" x-text="opt.name"></span>
                                <template x-if="selected.includes(opt.id)">
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- 2. SOFTWARE TOOLS --}}
            <div x-data="{ 
                open: false,
                selected: {{ json_encode($userToolIds) }},
                options: [
                    @foreach($tools as $tool)
                        { id: {{ $tool->id }}, name: '{{ $tool->name }}' },
                    @endforeach
                ],
                toggle(id) {
                    if (this.selected.includes(id)) {
                        this.selected = this.selected.filter(i => i !== id);
                    } else {
                        this.selected.push(id);
                    }
                },
                getName(id) {
                    return this.options.find(o => o.id == id)?.name;
                }
            }" class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-screwdriver-wrench text-indigo-500 text-sm"></i> Software Tools
                </h3>
                
                <div class="flex flex-wrap gap-2 min-h-[50px] p-3 rounded-2xl bg-gray-50 border border-dashed border-gray-200">
                    <template x-for="id in selected" :key="id">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold animate-in fade-in zoom-in duration-300">
                            <span x-text="getName(id)"></span>
                            <button type="button" @click="toggle(id)" class="hover:text-indigo-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            <input type="hidden" name="tools[]" :value="id">
                        </div>
                    </template>
                    <template x-if="selected.length === 0">
                        <span class="text-gray-400 text-xs italic p-1 px-2">No tools selected yet...</span>
                    </template>
                </div>

                <div class="relative" @click.outside="open = false">
                    <button type="button" @click="open = !open"
                        class="w-full px-5 py-4 rounded-2xl bg-gray-50 border border-gray-100 flex justify-between items-center hover:border-indigo-500 transition group">
                        <span class="text-gray-500 text-sm group-hover:text-indigo-600 transition">Select Tools</span>
                        <svg class="w-5 h-5 text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-transition class="absolute z-30 mt-2 w-full max-h-60 overflow-y-auto rounded-2xl bg-white border border-gray-100 shadow-2xl py-2">
                        <template x-for="opt in options" :key="opt.id">
                            <div @click="toggle(opt.id)"
                                class="px-5 py-3 cursor-pointer hover:bg-indigo-50 transition flex justify-between items-center"
                                :class="selected.includes(opt.id) ? 'bg-indigo-50/50 text-indigo-600' : 'text-gray-600'">
                                <span class="text-sm font-medium" x-text="opt.name"></span>
                                <template x-if="selected.includes(opt.id)">
                                    <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <button type="button" onclick="window.history.back()" class="px-8 py-4 rounded-2xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 transition">Cancel</button>
            <button type="submit" class="px-8 py-4 rounded-2xl bg-black text-white font-bold hover:shadow-xl hover:-translate-y-1 transition duration-300">Save Changes</button>
        </div>
    </form>
</div>
@endsection