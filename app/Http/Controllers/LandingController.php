<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectField;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data Field untuk Dropdown
        $fields = ProjectField::all();

        // 2. Query dasar Project dengan Eager Loading agar cepat (Optimized)
        $projectsQuery = Project::with([
            'owner.profile', 
            'detail.field', 
            'detail.status', 
            'medias'
        ])
        ->whereHas('detail.status', function($q) {
            $q->where('slug', '!=', 'private');
        });

        // 3. Logika Filter Search
        if ($request->filled('search')) {
            $projectsQuery->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 4. Logika Filter Field
        if ($request->filled('field')) {
            $projectsQuery->whereHas('detail.field', function($f) use ($request) {
                $f->where('id', $request->field);
            });
        }

        // 5. Eksekusi Query: Urutkan Like terbanyak & limit 5
        $projects = $projectsQuery->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(6) // Saya sarankan 6 agar grid 3 kolom (Behance style) terlihat penuh
            ->get();

        // 6. Return view dengan variabel yang dibutuhkan
        return view('landing', compact('projects', 'fields'));
    }
}