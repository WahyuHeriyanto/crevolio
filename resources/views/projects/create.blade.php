@extends('layouts.dashboard')

@section('content')
<div
    x-data="projectForm()"
    class="max-w-6xl mx-auto py-14"
>

    {{-- HEADER --}}
    <div class="mb-12">
        <h1 class="text-3xl font-semibold text-gray-900">
            Create New Project
        </h1>
        <p class="text-gray-500 mt-2">
            Showcase your work and collaboration experience
        </p>
    </div>

    {{-- FORM --}}
    <form
        action="{{ route('projects.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="bg-white rounded-3xl border shadow-sm overflow-hidden"
    >
        @csrf

        {{-- CONTENT --}}
        <div class="px-12 py-12 space-y-14">

            {{-- PROJECT NAME --}}
            <div>
                <label class="font-medium text-gray-800">Project Name</label>
                <input
                    type="text"
                    name="name"
                    required
                    placeholder="e.g. Design System for SaaS Dashboard"
                    class="mt-3 mb-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black"
                />
            </div>

            {{-- IMAGE UPLOADER --}}
            <div>
                <label class="font-medium text-gray-800">Project Images</label>
                <p class="text-sm text-gray-500 mb-4">
                    Upload up to 5 images (Optimization enabled)
                </p>

                <div x-show="errorMessage" x-text="errorMessage" class="mb-4 text-sm text-red-600 font-medium"></div>

                <input
                    type="file"
                    name="images[]"
                    multiple
                    accept="image/*"
                    class="hidden"
                    id="imageInput"
                    x-ref="imageInput"
                    @change="addImages"
                />
                <label
                    for="imageInput"
                    class="cursor-pointer flex flex-col items-center justify-center h-64 border-2 border-dashed rounded-2xl text-gray-500 hover:border-black transition"
                >
                    <span class="font-medium">Click to upload images</span>
                    <span class="text-sm text-gray-400 mt-1">
                        PNG, JPG — max 5 images
                    </span>
                </label>

                {{-- MAIN PREVIEW --}}
                <div x-show="images.length" class="mt-8">
                    <img
                        :src="images[current].url"
                        class="w-full h-[420px] object-cover rounded-2xl border"
                    />
                </div>

                {{-- THUMBNAILS --}}
                <div
                    x-show="images.length"
                    class="flex gap-4 mt-4 overflow-x-auto pb-2"
                >
                    <template x-for="(img, index) in images" :key="img.id">
                        <div
                            class="relative flex-shrink-0 w-28 h-20 rounded-xl border cursor-pointer"
                            :class="current === index ? 'ring-2 ring-black' : ''"
                            @click="current = index"
                        >
                            <img
                                :src="img.url"
                                class="w-full h-full object-cover rounded-xl"
                            >
                            <button
                                type="button"
                                @click.stop="removeImage(index)"
                                class="absolute -top-2 -right-2 bg-black text-white rounded-full w-6 h-6 text-xs flex items-center justify-center"
                            >
                                ×
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="font-medium text-gray-800">Description</label>
                <textarea
                    name="description"
                    rows="6"
                    maxlength="2000"
                    placeholder="Describe your project in detail..."
                    class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 resize-y"
                ></textarea>
                <p class="text-xs text-gray-400 mt-1">
                    Maximum 2000 characters
                </p>
            </div>

            {{-- FIELD & STATUS --}}
            <div class="grid md:grid-cols-2 gap-10">

                {{-- PROJECT FIELD (SEARCHABLE) --}}
                <div x-data="{ open:false, search:'' }" class="relative">
                    <label class="font-medium text-gray-800">
                        Project Field
                    </label>

                    <input
                        type="hidden"
                        name="project_field_id"
                        :value="selectedField?.id"
                    >

                    <button
                        type="button"
                        @click="open = !open"
                        class="mt-3 w-full rounded-2xl border px-5 py-3 text-left flex justify-between items-center"
                    >
                        <span x-text="selectedField?.name ?? 'Select project field'"></span>
                        <span>⌄</span>
                    </button>

                    <div
                        x-show="open"
                        @click.outside="open=false"
                        class="absolute z-20 mt-2 w-full bg-white border rounded-2xl shadow-lg"
                    >
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search field..."
                            class="w-full px-4 py-3 border-b outline-none"
                        >

                        <div class="max-h-60 overflow-y-auto">
                            @foreach ($fields as $field)
                                <div
                                    x-show="'{{ strtolower($field->name) }}'.includes(search.toLowerCase())"
                                    @click="selectedField = { id: {{ $field->id }}, name: '{{ $field->name }}' }; open=false"
                                    class="px-5 py-3 hover:bg-gray-100 cursor-pointer"
                                >
                                    {{ $field->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- PROJECT STATUS --}}
            <div x-data="{ 
                get statusHint() {
                    if (selectedStatusSlug === 'open') return 'Anyone can view and join this project.';
                    if (selectedStatusSlug === 'public') return 'Anyone can view this project.';
                    if (selectedStatusSlug === 'private') return 'Only you (the owner) can view this project.';
                    return 'Please select a status to see the visibility rules.';
                }
            }">
                <label class="font-medium text-gray-800">
                    Project Status
                </label>
                
                <select
                    name="project_status_id"
                    x-model="selectedStatusId"
                    @change="selectedStatusSlug = $event.target.options[$event.target.selectedIndex].getAttribute('data-slug')"
                    class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black"
                    required
                >
                    <option value="" data-slug="">Select project status</option>
                    @foreach ($statuses as $status)
                        @if ($status->slug === 'draft')
                            @continue
                        @endif
                        <option value="{{ $status->id }}" data-slug="{{ $status->slug }}">
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>

                {{-- DYNAMIC HINT BOX --}}
                <div class="mt-3 p-4 rounded-2xl border bg-gray-50 flex items-start gap-3 transition-all duration-300"
                    :class="{
                        'border-green-200 bg-green-50 text-green-700': selectedStatusSlug === 'open',
                        'border-blue-200 bg-blue-50 text-blue-700': selectedStatusSlug === 'public',
                        'border-amber-200 bg-amber-50 text-amber-700': selectedStatusSlug === 'private',
                        'opacity-60 border-gray-200': !selectedStatusSlug
                    }">
                    
                    {{-- ICONS --}}
                    <div class="mt-0.5 flex-shrink-0">
                        <template x-if="selectedStatusSlug === 'open'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </template>
                        <template x-if="selectedStatusSlug === 'public'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </template>
                        <template x-if="selectedStatusSlug === 'private'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </template>
                        <template x-if="!selectedStatusSlug">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </template>
                    </div>

                    <p class="text-sm font-medium" x-text="statusHint"></p>
                </div>
            </div>

            {{-- DATES --}}
            <div class="grid md:grid-cols-2 gap-10">
                <div>
                    <label class="font-medium text-gray-800">Start Date</label>
                    <input
                        type="date"
                        name="start_date"
                        class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3"
                    />
                </div>

                <div>
                    <label class="font-medium text-gray-800">End Date</label>
                    <input
                        type="date"
                        name="end_date"
                        :disabled="ongoing"
                        class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3"
                    />

                    <label class="flex items-center gap-2 mt-4 text-sm text-gray-600">
                        <input type="checkbox" x-model="ongoing">
                        Currently ongoing
                    </label>
                </div>
            </div>

            {{-- TOOLS --}}
            <div>
                <label class="font-medium text-gray-800">Project Tools</label>

                <select
                    @change="addTool($event)"
                    class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3"
                >
                    <option value="">Select tool</option>
                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                    @endforeach
                </select>

                <div class="flex flex-wrap gap-3 mt-8">
                    <template x-for="tool in selectedTools" :key="tool.id">
                        <span class="px-4 py-2 bg-gray-100 rounded-full flex items-center gap-2 text-sm">
                            <span x-text="tool.name"></span>
                            <button type="button" @click="removeTool(tool.id)">×</button>
                            <input type="hidden" name="tools[]" :value="tool.id">
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- ACTIONS (STICKY) --}}
        <div class="sticky bottom-0 bg-white border-t px-12 py-6 flex justify-end gap-4">
            <a
                href="{{ url()->previous() }}"
                class="px-6 py-2 rounded-full border hover:bg-gray-100"
            >
                Cancel
            </a>

            <button
                type="submit"
                @click="saving = true"
                class="px-8 py-3 rounded-full bg-black text-white hover:bg-gray-800"
            >
                Save Project
            </button>
        </div>
    </form>

    {{-- LOADING OVERLAY --}}
    <div
        x-show="saving"
        class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded-2xl px-8 py-6 flex items-center gap-4">
            <div class="w-6 h-6 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
            <span class="font-medium">Saving project...</span>
        </div>
    </div>
</div>

<script>
function projectForm() {
    return {
        images: [],
        current: 0,
        ongoing: false,
        saving: false,
        selectedTools: [],
        selectedField: null,
        selectedStatusId: '',
        selectedStatusSlug: '',
        fileStore: new DataTransfer(),
        errorMessage: '',

        addImages(e) {
            this.errorMessage = ''
            const files = Array.from(e.target.files)

            files.forEach(file => {
                // Cek limit 5 gambar
                if (this.images.length >= 5) {
                    this.errorMessage = 'Maximum 5 images allowed.';
                    return;
                }

                // Tampilkan loading sederhana jika perlu, tapi Compressor.js sangat cepat
                new Compressor(file, {
                    quality: 0.6,      // Kompres kualitas ke 60% (Sangat efektif mengecilkan size)
                    maxWidth: 1600,    // Resolusi lebar max 1600px (Sangat cukup untuk web)
                    convertSize: 1000000, // File di atas 1MB otomatis di-convert/kompres
                    
                    success: (result) => {
                        // 'result' adalah Blob hasil kompresi
                        // Kita bungkus lagi jadi File object agar bisa masuk ke DataTransfer
                        const compressedFile = new File([result], file.name, {
                            type: result.type,
                            lastModified: Date.now(),
                        });

                        // Tambahkan ke array preview
                        this.images.push({
                            id: Date.now() + Math.random(),
                            file: compressedFile,
                            url: URL.createObjectURL(compressedFile)
                        });

                        // Update DataTransfer (File yang akan dikirim ke Laravel)
                        this.fileStore.items.add(compressedFile);
                        this.$refs.imageInput.files = this.fileStore.files;
                    },
                    error: (err) => {
                        console.error(err.message);
                        this.errorMessage = 'Failed to process image.';
                    },
                });
            });

            // Reset input agar bisa pilih file yang sama jika dihapus
            e.target.value = '';
        },

        removeImage(index) {
            this.images.splice(index, 1)
            this.errorMessage = ''
            this.fileStore = new DataTransfer()
            this.images.forEach(img => {
                this.fileStore.items.add(img.file)
            })

            this.$refs.imageInput.files = this.fileStore.files
            this.current = Math.max(0, this.current - 1)
        },

        addTool(e) {
            const id = e.target.value
            const name = e.target.options[e.target.selectedIndex].text
            if (!id) return

            if (!this.selectedTools.find(t => t.id == id)) {
                this.selectedTools.push({ id, name })
            }
            e.target.value = ''
        },

        removeTool(id) {
            this.selectedTools = this.selectedTools.filter(t => t.id != id)
        }
    }
}
</script>
@endsection
