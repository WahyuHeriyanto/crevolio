@extends('layouts.dashboard')

@section('content')
<div 
    x-data="projectForm()" 
    class="max-w-6xl mx-auto py-14"
>

    {{-- HEADER --}}
    <div class="mb-12">
        <h1 class="text-3xl font-semibold text-gray-900">Edit Project: {{ $project->name }}</h1>
        <p class="text-gray-500 mt-2">Update your project information and media.</p>
    </div>

    {{-- FORM --}}
    <form 
        action="{{ route('projects.update', $project) }}" 
        method="POST" 
        enctype="multipart/form-data"
        class="bg-white rounded-3xl border shadow-sm overflow-hidden"
    >
        @csrf
        @method('PUT')

        <div class="px-12 py-12 space-y-14">

            {{-- PROJECT NAME --}}
            <div>
                <label class="font-medium text-gray-800">Project Name</label>
                <input 
                    type="text" 
                    name="name" 
                    value="{{ old('name', $project->name) }}"
                    required 
                    class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black"
                />
            </div>

            {{-- IMAGE UPLOADER --}}
            <div>
                <label class="font-medium text-gray-800">Project Images</label>
                <p class="text-sm text-gray-500 mb-4">You can add up to {{ 5 - $project->medias->count() }} more images.</p>
                
                <input 
                    type="file" name="images[]" multiple accept="image/*" class="hidden" id="imageInput"
                    x-ref="imageInput" @change="addImages"
                />
                <label 
                    for="imageInput" 
                    class="cursor-pointer flex flex-col items-center justify-center h-48 border-2 border-dashed rounded-2xl text-gray-500 hover:border-black transition"
                >
                    <span class="font-medium">Click to add more images</span>
                </label>

                {{-- PREVIEW (Combined Existing & New) --}}
                <div x-show="images.length > 0" class="mt-8">
                    <img :src="images[current].url" class="w-full h-[420px] object-cover rounded-2xl border" />
                </div>

                {{-- THUMBNAILS --}}
                <div x-show="images.length > 0" class="flex gap-4 mt-4 overflow-x-auto pb-2">
                    <template x-for="(img, index) in images" :key="index">
                        <div 
                            class="relative flex-shrink-0 w-28 h-20 rounded-xl border cursor-pointer"
                            :class="current === index ? 'ring-2 ring-black' : ''"
                            @click="current = index"
                        >
                            <img :src="img.url" class="w-full h-full object-cover rounded-xl">
                            <button 
                                type="button" @click.stop="removeImage(index)"
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs flex items-center justify-center"
                            >×</button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="font-medium text-gray-800">Description</label>
                <textarea 
                    name="description" rows="6" maxlength="2000"
                    class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3"
                >{{ old('description', $project->detail->description) }}</textarea>
            </div>

            {{-- FIELD & STATUS --}}
            <div class="grid md:grid-cols-2 gap-10">
                {{-- FIELD --}}
                <div x-data="{ open:false, search:'' }" class="relative">
                    <label class="font-medium text-gray-800">Project Field</label>
                    <input type="hidden" name="project_field_id" :value="selectedField?.id">
                    <button 
                        type="button" @click="open = !open"
                        class="mt-3 w-full rounded-2xl border px-5 py-3 text-left flex justify-between items-center"
                    >
                        <span x-text="selectedField?.name ?? 'Select project field'"></span>
                        <span>⌄</span>
                    </button>
                    <div x-show="open" @click.outside="open=false" class="absolute z-20 mt-2 w-full bg-white border rounded-2xl shadow-lg">
                        <input type="text" x-model="search" placeholder="Search field..." class="w-full px-4 py-3 border-b outline-none">
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

                {{-- STATUS --}}
                <div>
                    <label class="font-medium text-gray-800">Project Status</label>
                    <select name="project_status_id" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3">
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ $project->detail->project_status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- DATES --}}
            <div class="grid md:grid-cols-2 gap-10">
                <div>
                    <label class="font-medium text-gray-800">Start Date</label>
                    <input type="date" name="start_date" value="{{ $project->detail->start_date }}" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3" />
                </div>
                <div>
                    <label class="font-medium text-gray-800">End Date</label>
                    <input 
                        type="date" name="end_date" value="{{ $project->detail->end_date }}"
                        :disabled="ongoing" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3" 
                    />
                    <label class="flex items-center gap-2 mt-4 text-sm text-gray-600">
                        <input type="checkbox" name="ongoing" x-model="ongoing" {{ is_null($project->detail->end_date) ? 'checked' : '' }}>
                        Currently ongoing
                    </label>
                </div>
            </div>

            {{-- TOOLS --}}
            <div>
                <label class="font-medium text-gray-800">Project Tools</label>
                <select @change="addTool($event)" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3">
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

        {{-- ACTIONS --}}
        <div class="sticky bottom-0 bg-white border-t px-12 py-6 flex justify-end gap-4">
            <a href="{{ route('projects.show', $project) }}" class="px-6 py-2 rounded-full border hover:bg-gray-100">Cancel</a>
            <button type="submit" @click="saving = true" class="px-8 py-3 rounded-full bg-black text-white hover:bg-gray-800">
                Update Project
            </button>
        </div>
    </form>

    {{-- LOADING OVERLAY (Same as Create) --}}
    <div x-show="saving" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl px-8 py-6 flex items-center gap-4">
            <div class="w-6 h-6 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
            <span class="font-medium">Updating project...</span>
        </div>
    </div>
</div>

<script>
function projectForm() {
    return {
        // Inisialisasi dengan data existing dari Laravel
        images: [
            @foreach($project->medias as $media)
            { id: 'old-{{ $media->id }}', url: '{{ asset('storage/' . $media->url) }}', isExisting: true },
            @endforeach
        ],
        current: 0,
        ongoing: {{ is_null($project->detail->end_date) ? 'true' : 'false' }},
        saving: false,
        selectedTools: [
            @foreach($project->detail->tools as $pt)
            { id: {{ $pt->tool->id }}, name: '{{ $pt->tool->name }}' },
            @endforeach
        ],
        selectedField: { 
            id: {{ $project->detail->field->id }}, 
            name: '{{ $project->detail->field->name }}' 
        },
        fileStore: new DataTransfer(),

        addImages(e) {
            const files = Array.from(e.target.files)
            files.forEach(file => {
                // Cek total gambar (Existing + New)
                if (this.images.length >= 10) {
                    this.errorMessage = 'Maximum 10 images allowed.';
                    return;
                }

                // Jalankan Kompresi
                new Compressor(file, {
                    quality: 0.6,
                    maxWidth: 1600,
                    success: (result) => {
                        const compressedFile = new File([result], file.name, {
                            type: result.type,
                        });

                        // Tambah ke array preview
                        this.images.push({
                            id: Date.now() + Math.random(),
                            file: compressedFile,
                            url: URL.createObjectURL(compressedFile),
                            isExisting: false
                        });

                        // Masukkan ke fileStore untuk dikirim ke backend
                        this.updateFileStore();
                    },
                    error(err) {
                        console.error(err.message);
                    },
                });
            });
            // Reset input agar bisa pilih file yang sama
            e.target.value = '';
        },

        removeImage(index) {
            const img = this.images[index];
            
            // Konfirmasi jika menghapus gambar lama yang sudah ada di server
            if(img.isExisting) {
                if(!confirm('This will remove the image forever. Continue?')) return;
            }
            
            this.images.splice(index, 1);
            this.updateFileStore();
            this.current = Math.max(0, this.current - 1);
        },

        // Helper untuk sinkronisasi fileStore dengan array images
        updateFileStore() {
            this.fileStore = new DataTransfer();
            this.images.forEach(img => {
                if (!img.isExisting && img.file) {
                    this.fileStore.items.add(img.file);
                }
            });
            this.$refs.imageInput.files = this.fileStore.files;
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