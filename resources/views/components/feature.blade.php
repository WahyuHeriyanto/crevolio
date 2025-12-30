<section
    {{ isset($id) ? "id=$id" : '' }}
    class="min-h-screen bg-white flex items-center scroll-mt-24"
    x-data="{ show: false }"
    x-intersect.margin.-150px="show = true"
>

    <div class="max-w-7xl mx-auto px-10 grid md:grid-cols-2 gap-20 items-center">
        {{-- IMAGE --}}
        <div
            class="
                relative h-[70vh] flex items-center justify-center
                transform transition-all duration-700 ease-out
                {{ $direction === 'right' ? 'md:order-1' : 'md:order-2' }}
            "
            :class="show
                ? 'opacity-100 translate-x-0'
                : '{{ $direction === 'right' ? '-translate-x-24' : 'translate-x-24' }} opacity-0'"
        >
            {{-- Container dibuat transparan tanpa background dan shadow --}}
            <div class="relative w-full h-full">
                <img 
                    src="{{ asset('assets/images/' . $image) }}" 
                    alt="{{ $title }}" 
                    class="w-full h-full object-contain"
                >
            </div>
        </div>
        {{-- TEXT --}}
        <div
            class="
                transform transition-all duration-700 ease-out delay-150
                {{ $direction === 'right' ? 'md:order-2' : 'md:order-1' }}
            "
            :class="show
                ? 'opacity-100 translate-x-0'
                : '{{ $direction === 'right' ? 'translate-x-24' : '-translate-x-24' }} opacity-0'"
        >
            <h2 class="text-5xl font-bold mb-6 text-gray-900">
                {{ $title }}
            </h2>

            <p class="text-xl text-gray-500 mb-6">
                {{ $subtitle }}
            </p>

            <p class="text-lg text-gray-700 leading-relaxed max-w-xl">
                {{ $description }}
            </p>
        </div>

    </div>
</section>
