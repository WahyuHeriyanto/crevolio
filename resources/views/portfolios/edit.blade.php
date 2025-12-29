@extends('layouts.dashboard')

@section('content')
<div x-data="portfolioForm()" class="max-w-6xl mx-auto py-14">
    <div class="mb-12 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-semibold text-gray-900">Edit Portfolio</h1>
            <p class="text-gray-500 mt-2">Update your achievement details.</p>
        </div>
        <a href="{{ route('profile.show', auth()->user()->username) }}" class="text-sm font-medium text-gray-400 hover:text-black transition">Back to Profile</a>
    </div>

    <form action="{{ route('portfolios.update', $portfolio->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2.5rem] border shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="px-12 py-12 space-y-14">
            
            {{-- NAME --}}
            <div>
                <label class="font-medium text-gray-800">Portfolio Title</label>
                <input type="text" name="name" required value="{{ old('name', $portfolio->name) }}"
                       class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black" />
            </div>

            {{-- IMAGES --}}
            <div>
                <label class="font-medium text-gray-800 text-lg">Portfolio Images</label>
                <p class="text-sm text-gray-500 mb-4">Add up to 5 images. New uploads will be added to the collection.</p>
                
                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="hidden" x-ref="imageInput" @change="addImages">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label for="imageInput" class="cursor-pointer flex flex-col items-center justify-center h-48 border-2 border-dashed rounded-3xl text-gray-400 hover:border-indigo-500 hover:text-indigo-600 hover:bg-indigo-50/30 transition duration-300">
                        <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2"></i>
                        <span class="font-bold">Add More Visuals</span>
                    </label>

                    {{-- PREVIEW & OLD IMAGES --}}
                    <div class="col-span-1 md:col-span-1 grid grid-cols-2 gap-4" x-show="images.length > 0">
                        <template x-for="(img, index) in images" :key="img.id">
                            <div class="relative aspect-video rounded-2xl border border-gray-100 overflow-hidden group shadow-sm">
                                <img :src="img.url" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <button type="button" @click="removeImage(index)" 
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition shadow-lg">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="font-medium text-gray-800">Description</label>
                <textarea name="description" rows="5" maxlength="2000" placeholder="Tell the story behind this work..."
                          class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 resize-none focus:ring-black focus:border-black">{{ old('description', $portfolio->description) }}</textarea>
            </div>

            <div class="grid md:grid-cols-2 gap-10">
                {{-- PROJECT FIELD --}}
                <div>
                    <label class="font-medium text-gray-800">Project Field</label>
                    <input type="text" name="project_field" value="{{ old('project_field', $portfolio->project_field) }}"
                           placeholder="e.g. Web Development"
                           class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black">
                </div>

                {{-- STATUS PROGRESS --}}
                <div>
                    <label class="font-medium text-gray-800">Current Status</label>
                    <select name="progress_status_id" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black appearance-none">
                        <option value="">Select Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}" {{ $portfolio->progress_status_id == $status->id ? 'selected' : '' }}>
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-10">
                {{-- DATES --}}
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="font-medium text-gray-800 text-sm">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $portfolio->start_date ? \Carbon\Carbon::parse($portfolio->start_date)->format('Y-m-d') : '') }}"
                               class="mt-2 w-full rounded-xl border-gray-300 px-4 py-2">
                    </div>
                    <div class="flex-1">
                        <label class="font-medium text-gray-800 text-sm">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $portfolio->end_date ? \Carbon\Carbon::parse($portfolio->end_date)->format('Y-m-d') : '') }}"
                               class="mt-2 w-full rounded-xl border-gray-300 px-4 py-2">
                    </div>
                </div>

                {{-- ACCESS LINK --}}
                <div>
                    <label class="font-medium text-gray-800">Access Link (URL)</label>
                    <input type="url" name="access_link" value="{{ old('access_link', $portfolio->access_link) }}"
                           placeholder="https://..."
                           class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black">
                </div>
            </div>

            {{-- TOOLS --}}
            <div>
                <label class="font-medium text-gray-800">Tools Used</label>
                <select @change="addTool($event)" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black">
                    <option value="">Choose tools...</option>
                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                    @endforeach
                </select>

                <div class="flex flex-wrap gap-2 mt-4">
                    <template x-for="tool in selectedTools" :key="tool.id">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-sm">
                            <span x-text="tool.name"></span>
                            <button type="button" @click="removeTool(tool.id)" class="hover:text-red-300 transition-colors">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            <input type="hidden" name="tools[]" :value="tool.id">
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="bg-gray-50 px-12 py-8 flex justify-end items-center gap-6">
            <a href="{{ route('profile.show', auth()->user()->username) }}" class="font-bold text-gray-400 hover:text-gray-900 transition">Discard Changes</a>
            <button type="submit" @click="saving = true" 
                    class="px-12 py-4 bg-gray-900 text-white rounded-2xl font-bold hover:bg-indigo-600 hover:shadow-xl hover:shadow-indigo-200 transition-all active:scale-95 disabled:opacity-50">
                <span x-show="!saving">Save Changes</span>
                <span x-show="saving">Saving...</span>
            </button>
        </div>
    </form>
</div>

<script>
function portfolioForm() {
    return {
        // Load data lama dari PHP ke Alpine
        images: [
            @foreach($portfolio->medias as $media)
            { id: {{ $media->id }}, url: '{{ asset("storage/" . $media->url) }}', isOld: true },
            @endforeach
        ],
        selectedTools: [
            @foreach($portfolio->tools as $pTool)
            { id: {{ $pTool->tool_id }}, name: '{{ $pTool->tool->name }}' },
            @endforeach
        ],
        saving: false,
        fileStore: new DataTransfer(),

        addImages(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (this.images.length >= 5) return;
                const newImg = {
                    id: Date.now() + Math.random(),
                    file: file,
                    url: URL.createObjectURL(file),
                    isOld: false
                };
                this.images.push(newImg);
                this.fileStore.items.add(file);
            });
            this.$refs.imageInput.files = this.fileStore.files;
        },

        removeImage(index) {
            const img = this.images[index];
            this.images.splice(index, 1);
            
            // Re-sync file input jika bukan gambar lama
            if(!img.isOld) {
                this.fileStore = new DataTransfer();
                this.images.forEach(i => {
                    if(!i.isOld) this.fileStore.items.add(i.file);
                });
                this.$refs.imageInput.files = this.fileStore.files;
            }
        },

        addTool(e) {
            const id = e.target.value;
            const name = e.target.options[e.target.selectedIndex].text;
            if (id && !this.selectedTools.find(t => t.id == id)) {
                this.selectedTools.push({ id, name });
            }
            e.target.value = '';
        },

        removeTool(id) {
            this.selectedTools = this.selectedTools.filter(t => t.id != id);
        }
    }
}
</script>
@endsection