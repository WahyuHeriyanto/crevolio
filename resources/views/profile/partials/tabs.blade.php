<div class="flex gap-8 border-b mb-8 text-sm font-medium">
    <button
        @click="tab='projects'"
        :class="tab==='projects'
            ? 'border-b-2 border-black pb-3'
            : 'text-gray-400 pb-3'"
    >
        Projects
    </button>

    <button
        @click="tab='portfolios'"
        :class="tab==='portfolios'
            ? 'border-b-2 border-black pb-3'
            : 'text-gray-400 pb-3'"
    >
        Portfolios
    </button>
</div>
