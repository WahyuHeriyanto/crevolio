@extends('layouts.dashboard')

@section('content')
<div x-data="portfolioForm()" class="max-w-6xl mx-auto py-14">
    <div class="mb-12">
        <h1 class="text-3xl font-semibold text-gray-900">Add Portfolio</h1>
        <p class="text-gray-500 mt-2">Highlight your personal achievements and independent projects.</p>
    </div>

    <form action="{{ route('portfolios.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border shadow-sm overflow-hidden">
        @csrf
        <div class="px-12 py-12 space-y-14">
            
            {{-- NAME --}}
            <div>
                <label class="font-medium text-gray-800">Portfolio Title</label>
                <input type="text" name="name" required placeholder="e.g. Mobile Banking App UI Redesign" 
                       class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black" />
            </div>

            {{-- IMAGES --}}
            <div>
                <label class="font-medium text-gray-800 text-lg">Portfolio Images</label>
                <p class="text-sm text-gray-500 mb-4">Maximum 5 images (PNG, JPG)</p>
                
                <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="hidden" x-ref="imageInput" @change="addImages">
                
                <label for="imageInput" class="cursor-pointer flex flex-col items-center justify-center h-48 border-2 border-dashed rounded-2xl text-gray-400 hover:border-black hover:text-gray-600 transition">
                    <i class="fa-solid fa-cloud-arrow-up text-2xl mb-2"></i>
                    <span class="font-medium">Upload Project Visuals</span>
                </label>

                {{-- PREVIEW --}}
                <div x-show="images.length" class="mt-8 grid grid-cols-5 gap-4">
                    <template x-for="(img, index) in images" :key="img.id">
                        <div class="relative aspect-video rounded-xl border overflow-hidden group">
                            <img :src="img.url" class="w-full h-full object-cover">
                            <button type="button" @click="removeImage(index)" 
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition">
                                ×
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="font-medium text-gray-800">Description</label>
                <textarea name="description" rows="5" maxlength="2000" placeholder="Tell the story behind this work..."
                          class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 resize-none focus:ring-black focus:border-black"></textarea>
                <div class="flex justify-between mt-2">
                    <p class="text-xs text-gray-400">Max 2000 characters</p>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-10">
                {{-- PROJECT FIELD --}}
                <div>
                    <label class="font-medium text-gray-800">Project Field</label>
                    <input type="text" name="project_field" placeholder="e.g. Web Development"
                           class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black">
                </div>

                {{-- STATUS PROGRESS --}}
                <div>
                    <label class="font-medium text-gray-800">Current Status</label>
                    <select name="progress_status_id" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3">
                        <option value="">Select Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}">{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-10">
                {{-- DATES --}}
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label class="font-medium text-gray-800 text-sm">Start Date</label>
                        <input type="date" name="start_date" class="mt-2 w-full rounded-xl border-gray-300 px-4 py-2">
                    </div>
                    <div class="flex-1">
                        <label class="font-medium text-gray-800 text-sm">End Date</label>
                        <input type="date" name="end_date" class="mt-2 w-full rounded-xl border-gray-300 px-4 py-2">
                    </div>
                </div>

                {{-- ACCESS LINK --}}
                <div>
                    <label class="font-medium text-gray-800">Access Link (URL)</label>
                    <input type="url" name="access_link" placeholder="https://..."
                           class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3 focus:ring-black focus:border-black">
                </div>
            </div>

            {{-- TOOLS --}}
            <div>
                <label class="font-medium text-gray-800">Tools Used</label>
                <select @change="addTool($event)" class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3">
                    <option value="">Choose tools...</option>
                    @foreach ($tools as $tool)
                        <option value="{{ $tool->id }}">{{ $tool->name }}</option>
                    @endforeach
                </select>

                <div class="flex flex-wrap gap-2 mt-4">
                    <template x-for="tool in selectedTools" :key="tool.id">
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-full text-sm">
                            <span x-text="tool.name"></span>
                            <button type="button" @click="removeTool(tool.id)" class="hover:text-red-400">×</button>
                            <input type="hidden" name="tools[]" :value="tool.id">
                        </span>
                    </template>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="bg-gray-50 px-12 py-8 flex justify-end gap-4">
            <a href="{{ url()->previous() }}" class="px-8 py-3 rounded-full font-medium text-gray-600 hover:bg-gray-200 transition">Cancel</a>
            <button type="submit" @click="saving = true" class="px-10 py-3 bg-black text-white rounded-full font-semibold hover:bg-gray-800 transition">
                Create Portfolio
            </button>
        </div>
    </form>
</div>

<script>
function portfolioForm() {
    return {
        images: [],
        selectedTools: [],
        saving: false,
        fileStore: new DataTransfer(),

        addImages(e) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (this.images.length >= 5) return;
                this.images.push({
                    id: Date.now() + Math.random(),
                    file: file,
                    url: URL.createObjectURL(file)
                });
                this.fileStore.items.add(file);
            });
            this.$refs.imageInput.files = this.fileStore.files;
        },

        removeImage(index) {
            this.images.splice(index, 1);
            this.fileStore = new DataTransfer();
            this.images.forEach(img => this.fileStore.items.add(img.file));
            this.$refs.imageInput.files = this.fileStore.files;
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