<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Your Profile — Crevolio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Animasi roket meluncur */
        .rocket-launch {
            animation: launch 1.5s forwards cubic-bezier(0.42, 0, 0.58, 1);
        }

        @keyframes launch {
            0% { transform: translateY(0) scale(1); opacity: 1; }
            20% { transform: translateY(10px) scale(1.1); } /* Efek sedikit turun sebelum melesat */
            100% { transform: translateY(-1000px) scale(1.5); opacity: 0; }
        }

        /* Animasi getar halus saat idle (menandakan roket siap) */
        .rocket-ready {
            animation: floating 2s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
    </style>
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
                class="px-10 py-6 rounded-3xl bg-indigo-600 text-white transition-all hover:scale-110">
                <i class="fa-solid fa-mars text-xl"></i> 
                <span class="font-bold">Male</span>
            </button>

            <button type="button"
                @click="form.gender='female'; next()"
                class="px-10 py-6 rounded-3xl bg-pink-600 text-white transition-all hover:scale-110">
                <i class="fa-solid fa-venus text-xl"></i> 
                <span class="font-bold">Female</span>
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
            <button type="button" @click="next()" class="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-amber-500 hover:text-white transition">Next</button>
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
                <button type="button" @click="next()" class="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-amber-500 hover:text-white transition">Next</button>
            </div>
        </div>
    </div>

    <!-- STEP 4 : EXPERTISE -->
    <div x-show="step === 4" class="space-y-6"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-0">

        <h2 class="text-3xl font-bold text-center">Your expertise</h2>

        <div x-data="{ openExp: false }">
            <div class="flex flex-wrap gap-2 mb-4 min-h-[40px] p-2 rounded-xl bg-white/5 border border-dashed border-white/20">
                <template x-for="id in form.expertises" :key="id">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-indigo-600 rounded-lg text-sm font-bold animate-in fade-in zoom-in duration-300">
                        <span x-text="getExpertiseName(id)"></span>
                        <button type="button" @click="toggleExpertise(id)" class="hover:text-red-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <template x-if="form.expertises.length === 0">
                    <span class="text-white/30 text-sm italic p-1">No expertise selected yet...</span>
                </template>
            </div>

            <div class="relative" @click.outside="openExp = false">
                <button type="button" @click="openExp = !openExp"
                    class="w-full px-6 py-4 rounded-xl bg-black/50 border border-white/20 flex justify-between items-center hover:border-indigo-500 transition">
                    <span class="text-white/60">Search & Select Expertise</span>
                    <svg class="w-5 h-5 transition-transform" :class="openExp ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="openExp" x-transition class="absolute z-30 mt-2 w-full max-h-60 overflow-y-auto rounded-xl bg-gray-900 border border-white/20 shadow-2xl">
                    @foreach($expertises as $exp)
                        <div @click="toggleExpertise({{ $exp->id }})"
                            class="px-6 py-3 cursor-pointer hover:bg-indigo-600 transition flex justify-between items-center"
                            :class="form.expertises.includes({{ $exp->id }}) ? 'bg-indigo-600/30' : ''">
                            <span>{{ $exp->name }}</span>
                            <template x-if="form.expertises.includes({{ $exp->id }})">
                                <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <input type="hidden" name="expertises" :value="JSON.stringify(form.expertises)">
        <!-- <input type="text" name="custom_expertise" x-model="form.custom_expertise" placeholder="Other expertise (optional)" class="w-full bg-black/30 rounded-xl px-6 py-3 border border-white/10"> -->

        <div class="mt-8 flex justify-between">
            <button type="button" @click="back()" class="text-white/50 hover:text-white transition">Back</button>
            <button type="button" @click="next()" class="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-indigo-500 hover:text-white transition">Next</button>
        </div>
    </div>

    <!-- STEP 5 : TOOLS -->
    <div x-show="step === 5" class="space-y-6"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-6"
        x-transition:enter-end="opacity-100 translate-y-0">

        <h2 class="text-3xl font-bold text-center">Tools you use</h2>

        <div x-data="{ openTools: false }">
            <div class="flex flex-wrap gap-2 mb-4 min-h-[40px] p-2 rounded-xl bg-white/5 border border-dashed border-white/20">
                <template x-for="id in form.tools" :key="id">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-600 rounded-lg text-sm font-bold">
                        <span x-text="getToolName(id)"></span>
                        <button type="button" @click="toggleTool(id)" class="hover:text-red-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>

            <div class="relative" @click.outside="openTools = false">
                <button type="button" @click="openTools = !openTools"
                    class="w-full px-6 py-4 rounded-xl bg-black/50 border border-white/20 flex justify-between items-center hover:border-amber-500 transition">
                    <span class="text-white/60">Search & Select Tools</span>
                    <svg class="w-5 h-5 transition-transform" :class="openTools ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="openTools" x-transition class="absolute z-30 mt-2 w-full max-h-60 overflow-y-auto rounded-xl bg-gray-900 border border-white/20 shadow-2xl">
                    @foreach($tools as $tool)
                        <div @click="toggleTool({{ $tool->id }})"
                            class="px-6 py-3 cursor-pointer hover:bg-amber-600 transition flex justify-between items-center"
                            :class="form.tools.includes({{ $tool->id }}) ? 'bg-amber-600/30' : ''">
                            <span>{{ $tool->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <input type="hidden" name="tools" :value="JSON.stringify(form.tools)">
        <!-- <input type="text" name="custom_tool" x-model="form.custom_tool" placeholder="Other tools (optional)" class="w-full bg-black/30 rounded-xl px-6 py-3 border border-white/10"> -->

        <div class="mt-8 flex justify-between">
            <button type="button" @click="back()" class="text-white/50 hover:text-white transition">Back</button>
            <button type="button" @click="next()" class="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-amber-500 hover:text-white transition">Next</button>
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
                <button type="button" @click="next()" class="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-amber-500 hover:text-white transition">Next</button>
            </div>
        </div>
    </div>

    <!-- STEP 7 : SUBMIT -->
    <div x-show="step === 7"
         x-transition.opacity
         x-data="{ launched: false }" 
        class="text-center relative">
         

        <div class="flex justify-center mb-4">
            <div :class="launched ? 'rocket-launch' : 'rocket-ready'" class="text-5xl transition-all">
                 <img src="{{ asset('assets/logo/rocket.png') }}" class="h-20" />
            </div>
        </div>

        <button type="submit"
            @click="launched = true; setTimeout(() => { success = true }, 500)"
            class="px-12 py-5 rounded-full bg-indigo-600 text-white font-bold text-xl shadow-lg shadow-indigo-500/50 transition-all hover:scale-105 active:scale-95">
            Become a Crevolian
        </button>
    </div>

</div>

<!-- SUCCESS OVERLAY -->
<div x-show="success"
     x-transition.opacity
     class="fixed inset-0 bg-black/90 flex items-center justify-center z-50">

    <div class="text-center animate-pulse">
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

        expertisesMaster: @json($expertises),
        toolsMaster: @json($tools),

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

        getExpertiseName(id) {
            const item = this.expertisesMaster.find(i => i.id == id);
            return item ? item.name : '';
        },
        getToolName(id) {
            const item = this.toolsMaster.find(i => i.id == id);
            return item ? item.name : '';
        },

        // Toggle Expertise
        toggleExpertise(id) {
            if (this.form.expertises.includes(id)) {
                this.form.expertises = this.form.expertises.filter(i => i !== id);
            } else {
                this.form.expertises.push(id);
            }
        },

        // Toggle Tools
        toggleTool(id) {
            if (this.form.tools.includes(id)) {
                this.form.tools = this.form.tools.filter(i => i !== id);
            } else {
                this.form.tools.push(id);
            }
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
