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
                    Upload up to 5 images
                </p>

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
                <div>
                    <label class="font-medium text-gray-800">
                        Project Status
                    </label>
                    <select
                        name="project_status_id"
                        class="mt-3 w-full rounded-2xl border-gray-300 px-5 py-3"
                    >
                        <option value="">Select project status</option>
                        @foreach ($statuses as $status)
                            @if ($status->slug === 'draft')
                                @continue
                            @endif
                            <option value="{{ $status->id }}">
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
        fileStore: new DataTransfer(),

        addImages(e) {
            const files = Array.from(e.target.files)

            files.forEach(file => {
                if (this.images.length >= 5) return

                this.images.push({
                    id: Date.now() + Math.random(),
                    file: file,
                    url: URL.createObjectURL(file)
                })

                this.fileStore.items.add(file)
            })
            this.$refs.imageInput.files = this.fileStore.files
        },

        removeImage(index) {
            this.images.splice(index, 1)
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
