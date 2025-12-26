<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Your Profile — Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-black via-indigo-900 to-black text-white overflow-hidden">

<form
    method="POST"
    action="{{ route('onboarding.store') }}"
    enctype="multipart/form-data"
    x-data="onboarding()"
    class="min-h-screen flex items-center justify-center px-6"
>
@csrf

<div class="w-full max-w-3xl relative">

    <!-- STEP INDICATOR -->
    <div class="flex justify-center gap-2 mb-10">
        <template x-for="i in 7" :key="i">
            <div class="h-1 w-8 rounded-full transition-all duration-300"
                 :class="step >= i ? 'bg-indigo-500' : 'bg-white/20'"></div>
        </template>
    </div>

    <!-- STEP 1 : GENDER -->
    <div x-show="step === 1"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         class="text-center">

        <h2 class="text-3xl font-bold mb-8">What is your gender?</h2>

        <div class="flex gap-10 justify-center">
            <button type="button"
                @click="form.gender='male'; next()"
                class="px-10 py-6 rounded-3xl bg-indigo-600 transition-all hover:scale-110">
                🚀 Male
            </button>

            <button type="button"
                @click="form.gender='female'; next()"
                class="px-10 py-6 rounded-3xl bg-pink-600 transition-all hover:scale-110">
                🌙 Female
            </button>
        </div>

        <input type="hidden" name="gender" :value="form.gender">
    </div>

    <!-- STEP 2 : BIRTH -->
    <div x-show="step === 2"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="text-center">

        <h2 class="text-3xl font-bold mb-6">When were you born?</h2>

        <input type="date"
            name="birth"
            x-model="form.birth"
            class="bg-black/30 rounded-xl px-6 py-3 text-white">

        <div class="mt-8 flex justify-between">
            <button type="button" @click="back()">Back</button>
            <button type="button" @click="next()">Next</button>
        </div>
    </div>

    <!-- STEP 3 : CAREER POSITION -->
    <div x-show="step === 3" class="space-y-6">

        <!-- TITLE -->
        <h2
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="text-3xl font-bold text-center"
        >
            Your current position
        </h2>

        <!-- CONTENT -->
        <div
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-6"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <!-- CUSTOM DROPDOWN -->
            <div class="relative" @click.outside="open=false">
                <button type="button"
                    @click="open=!open"
                    class="w-full px-6 py-4 rounded-xl bg-black/50 border border-white/20
                           flex justify-between items-center hover:border-indigo-500 transition">
                    <span x-text="selectedPosition ?? 'Select position'"></span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     x-transition
                     class="absolute z-20 mt-2 w-full max-h-60 overflow-y-auto
                            rounded-xl bg-black/90 border border-white/20 shadow-xl">

                    @foreach($careerPositions as $pos)
                        <div
                            @click="
                                form.career_position_id={{ $pos->id }};
                                selectedPosition='{{ $pos->name }}';
                                open=false
                            "
                            class="px-6 py-4 cursor-pointer hover:bg-indigo-600 transition">
                            {{ $pos->name }}
                        </div>
                    @endforeach
                </div>
            </div>

            <input type="hidden" name="career_position_id" :value="form.career_position_id">

            <div class="mt-8 flex justify-between">
                <button type="button" @click="back()">Back</button>
                <button type="button" @click="next()">Next</button>
            </div>
        </div>
    </div>

    <!-- STEP 4 : EXPERTISE -->
    <div x-show="step === 4"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0">

        <h2 class="text-3xl font-bold mb-6 text-center">Your expertise</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($expertises as $exp)
                <div
                    @click="
                        form.expertises.includes({{ $exp->id }})
                        ? form.expertises.splice(form.expertises.indexOf({{ $exp->id }}),1)
                        : form.expertises.push({{ $exp->id }})
                    "
                    class="cursor-pointer p-4 rounded-xl border border-white/20 text-center
                           transition-all duration-300 ease-out
                           hover:scale-110 hover:shadow-xl"
                    :class="form.expertises.includes({{ $exp->id }})
                        ? 'bg-indigo-600 scale-105'
                        : 'bg-white/5'">
                    {{ $exp->name }}
                </div>
            @endforeach
        </div>

        <input type="hidden" name="expertises" :value="JSON.stringify(form.expertises)">

        <input type="text"
            name="custom_expertise"
            x-model="form.custom_expertise"
            placeholder="Other expertise (optional)"
            class="mt-6 w-full bg-black/30 rounded-xl px-6 py-3">

        <div class="mt-8 flex justify-between">
            <button type="button" @click="back()">Back</button>
            <button type="button" @click="next()">Next</button>
        </div>
    </div>

    <!-- STEP 5 : TOOLS -->
    <div x-show="step === 5"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0">

        <h2 class="text-3xl font-bold mb-6 text-center">Tools you use</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($tools as $tool)
                <div
                    @click="
                        form.tools.includes({{ $tool->id }})
                        ? form.tools.splice(form.tools.indexOf({{ $tool->id }}),1)
                        : form.tools.push({{ $tool->id }})
                    "
                    class="cursor-pointer p-4 rounded-xl border border-white/20 text-center
                           transition-all duration-300 ease-out
                           hover:scale-110 hover:shadow-xl"
                    :class="form.tools.includes({{ $tool->id }})
                        ? 'bg-indigo-600 scale-105'
                        : 'bg-white/5'">
                    {{ $tool->name }}
                </div>
            @endforeach
        </div>

        <input type="hidden" name="tools" :value="JSON.stringify(form.tools)">

        <input type="text"
            name="custom_tool"
            x-model="form.custom_tool"
            placeholder="Other tools (optional)"
            class="mt-6 w-full bg-black/30 rounded-xl px-6 py-3">

        <div class="mt-8 flex justify-between">
            <button type="button" @click="back()">Back</button>
            <button type="button" @click="next()">Next</button>
        </div>
    </div>

    <!-- STEP 6 : PROFILE -->
    <div x-show="step === 6" class="space-y-6">

        <!-- TITLE -->
        <h2
            x-transition:enter="transition ease-out duration-500"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="text-3xl font-bold text-center"
        >
            Introduce yourself
        </h2>

        <!-- CONTENT -->
        <div
            x-transition:enter="transition ease-out duration-500 delay-200"
            x-transition:enter-start="opacity-0 translate-y-6"
            x-transition:enter-end="opacity-100 translate-y-0"
        >
            <!-- COVER -->
            <label class="block cursor-pointer">
                <input type="file" name="background_image" class="hidden"
                       accept="image/*"
                       @change="previewImage($event,'cover')">

                <div class="h-44 rounded-2xl bg-white/10 overflow-hidden
                            flex items-center justify-center
                            hover:ring-2 hover:ring-indigo-500 transition">
                    <template x-if="!preview.cover">
                        <span class="text-white/60">Click to upload cover</span>
                    </template>
                    <img x-show="preview.cover" :src="preview.cover"
                         class="w-full h-full object-cover">
                </div>
            </label>

            <!-- AVATAR -->
            <div class="flex justify-center -mt-14 mb-6">
                <label class="cursor-pointer">
                    <input type="file" name="photo_profile" class="hidden"
                           accept="image/*"
                           @change="previewImage($event,'avatar')">

                    <div class="w-28 h-28 rounded-full bg-white/10 border-4 border-black
                                overflow-hidden flex items-center justify-center
                                hover:ring-2 hover:ring-indigo-500 transition">
                        <template x-if="!preview.avatar">
                            <span class="text-xs text-white/60 text-center px-2">
                                Click to upload
                            </span>
                        </template>
                        <img x-show="preview.avatar" :src="preview.avatar"
                             class="w-full h-full object-cover">
                    </div>
                </label>
            </div>

            <textarea name="short_description"
                x-model="form.short_description"
                placeholder="Short description"
                class="w-full bg-black/30 rounded-xl px-6 py-3 mb-4"></textarea>

            <textarea name="description"
                x-model="form.description"
                placeholder="Tell your story"
                rows="4"
                class="w-full bg-black/30 rounded-xl px-6 py-3"></textarea>

            <div class="mt-8 flex justify-between">
                <button type="button" @click="back()">Back</button>
                <button type="button" @click="next()">Next</button>
            </div>
        </div>
    </div>

    <!-- STEP 7 : SUBMIT -->
    <div x-show="step === 7"
         x-transition.opacity
         class="text-center">

        <button type="submit"
            @click="success = true"
            class="px-12 py-5 rounded-full bg-indigo-600 transition-all hover:scale-110">
            🚀 Become a Crevolian
        </button>
    </div>

</div>

<!-- SUCCESS OVERLAY -->
<div x-show="success"
     x-transition.opacity
     class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">

    <div class="text-center animate-pulse">
        <div class="text-6xl mb-6">🎉</div>
        <h2 class="text-3xl font-bold mb-2">Welcome to Crevolio</h2>
        <p class="text-white/70">Preparing your dashboard...</p>
    </div>
</div>

</form>

<script>
function onboarding() {
    return {
        step: 1,
        open: false,
        selectedPosition: null,
        success: false,

        preview: {
            cover: null,
            avatar: null,
        },

        form: {
            gender: null,
            birth: null,
            career_position_id: null,
            expertises: [],
            custom_expertise: '',
            tools: [],
            custom_tool: '',
            short_description: '',
            description: '',
        },

        previewImage(e, type) {
            const file = e.target.files[0]
            if (!file) return
            const reader = new FileReader()
            reader.onload = () => this.preview[type] = reader.result
            reader.readAsDataURL(file)
        },

        next() { if (this.step < 7) this.step++ },
        back() { if (this.step > 1) this.step-- },
    }
}
</script>

</body>
</html>
