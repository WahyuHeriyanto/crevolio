@forelse ($crevolians as $crevolian)
    @include('dashboard.partials.collaborator-card', ['user' => $crevolian])
@empty
    @if($crevolians->currentPage() <= 1)
        <div class="col-span-full text-center py-20 bg-white rounded-[40px] border-2 border-dashed border-gray-100">
            <p class="text-gray-400 font-medium">No other creators found.</p>
        </div>
    @endif
@endforelse