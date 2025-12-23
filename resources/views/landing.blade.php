<x-public-layout>

<section
    class="min-h-screen flex items-center justify-center text-center px-6 bg-black text-white"
    x-data="typingHero()"
    x-init="start()"
>

    <div class="max-w-3xl">
        <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 text-white">
            <span x-text="displayText"></span>
            <span class="border-r-2 border-white animate-pulse ml-1"></span>
        </h1>

        <p class="text-lg text-gray-300 mb-10">
            Find people worth building with.
        </p>

        <a href="{{ route('login') }}"
           class="inline-block px-8 py-3 rounded-xl bg-white text-black text-base font-medium hover:bg-gray-200 transition">
            Get Started
        </a>
    </div>
</section>

<div class="h-20 bg-black"></div>

<x-feature
    id="features"
    title="Show what you build"
    subtitle="Your work, your story."
    description="Create a clean, focused portfolio that highlights your best work and share it with the world."
    direction="right"
/>


<x-feature
    title="Find people worth building with"
    subtitle="Collaboration starts here."
    description="Discover people who share your vision and grow together through real collaboration."
    direction="left"
/>

</x-public-layout>
