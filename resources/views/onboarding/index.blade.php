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

    {{-- STEP INDICATOR --}}
    <div class="flex justify-center gap-2 mb-8">
        <template x-for="i in 7">
            <div class="h-1 w-8 rounded-full"
                 :class="step >= i ? 'bg-indigo-500' : 'bg-white/20'"></div>
        </template>
    </div>

    {{-- STEP 1 : GENDER --}}
    <div x-show="step === 1" x-transition class="text-center">
        <h2 class="text-3xl font-bold mb-8">What is your gender?</h2>

        <div class="flex gap-10 justify-center">
            <button type="button"
                @click="form.gender='male'; next()"
                class="px-10 py-6 rounded-3xl bg-indigo-600 hover:scale-110 transition">
                🚀 Male
            </button>

            <button type="button"
                @click="form.gender='female'; next()"
                class="px-10 py-6 rounded-3xl bg-pink-600 hover:scale-110 transition">
                🌙 Female
            </button>
        </div>

        <input type="hidden" name="gender" :value="form.gender">
    </div>

    {{-- STEP 2 : BIRTH --}}
    <div x-show="step === 2" x-transition class="text-center">
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

    {{-- STEP 3 : CAREER POSITION --}}
    <div x-show="step === 3" x-transition>
        <h2 class="text-3xl font-bold mb-6 text-center">Your current position</h2>

        <select
            name="career_position_id"
            x-model="form.career_position_id"
            class="w-full rounded-xl bg-black/40 px-6 py-4">
            <option value="">Select position</option>
            @foreach($careerPositions as $pos)
                <option value="{{ $pos->id }}">{{ $pos->name }}</option>
            @endforeach
        </select>

        <div class="mt-8 flex justify-between">
            <button type="button" @click="back()">Back</button>
            <button type="button" @click="next()">Next</button>
        </div>
    </div>

    {{-- STEP 4 : EXPERTISE --}}
    <div x-show="step === 4" x-transition>
        <h2 class="text-3xl font-bold mb-6 text-center">Your expertise</h2>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($expertises as $exp)
                <label class="cursor-pointer">
                    <input type="checkbox"
                        class="hidden"
                        @change="
                            form.expertises.includes({{ $exp->id }})
                            ? form.expertises.splice(form.expertises.indexOf({{ $exp->id }}),1)
                            : form.expertises.push({{ $exp->id }})
                        ">
                    <div class="p-4 rounded-xl border border-white/20 text-center
                        transition hover:scale-105"
                        :class="form.expertises.includes({{ $exp->id }})
                            ? 'bg-indigo-600'
                            : ''">
                        {{ $exp->name }}
                    </div>
                </label>
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

    {{-- STEP 5 : TOOLS --}}
    <div x-show="step === 5" x-transition>
        <h2 class="text-3xl font-bold mb-6 text-center">Tools you use</h2>

        <select multiple
            class="w-full h-40 bg-black/40 rounded-xl px-6 py-4"
            @change="form.tools = Array.from($event.target.selectedOptions).map(o => o.value)">
            @foreach($tools as $tool)
                <option value="{{ $tool->id }}">{{ $tool->name }}</option>
            @endforeach
        </select>

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

    {{-- STEP 6 : PROFILE --}}
    <div x-show="step === 6" x-transition>
        <h2 class="text-3xl font-bold mb-6 text-center">Introduce yourself</h2>

        <input type="file" name="background_image" class="mb-4">
        <input type="file" name="photo_profile" class="mb-4">

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

    {{-- STEP 7 : SUBMIT --}}
    <div x-show="step === 7" x-transition class="text-center">
        <button type="submit"
            class="px-12 py-5 rounded-full bg-indigo-600 hover:scale-110 transition">
            🚀 Become a Crevolian
        </button>
    </div>

</div>
</form>

<script>
function onboarding() {
    return {
        step: 1,
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
        next() { if (this.step < 7) this.step++ },
        back() { if (this.step > 1) this.step-- },
    }
}
</script>

</body>
</html>
