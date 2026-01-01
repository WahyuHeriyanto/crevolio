@forelse ($projects as $project)
    @include('dashboard.partials.project-card', ['project' => $project])
@empty
    {{-- Hanya tampil jika benar-benar kosong di page 1 --}}
    @if(request()->page <= 1)
    <div class="text-center py-20 bg-white rounded-[40px] border-2 border-dashed border-gray-100">
        <p class="text-gray-400 font-medium">No projects found in your feed.</p>
    </div>
    @endif
@endforelse