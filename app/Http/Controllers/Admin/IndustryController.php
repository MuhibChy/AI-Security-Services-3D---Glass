<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class IndustryController extends Controller
{
    public function index()
    {
        $items = Industry::orderBy('sort_order')->orderBy('name')->paginate(30);

        return view('admin.industries.index', compact('items'));
    }

    public function create()
    {
        $industry = new Industry(['is_active' => true, 'show_on_homepage' => true, 'sort_order' => 0]);

        return view('admin.industries.form', compact('industry'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['slug'] = $this->uniqueSlug($data['slug']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('industries', 'public');
        }

        Industry::create($data);

        return redirect()->route('admin.industries.index')->with('success', 'Industry saved.');
    }

    public function edit(Industry $industry)
    {
        return view('admin.industries.form', compact('industry'));
    }

    public function update(Request $request, Industry $industry)
    {
        $data = $this->validated($request, $industry);

        if (empty($data['slug'])) {
            $data['slug'] = $industry->slug;
        }
        $data['slug'] = $this->uniqueSlug($data['slug'], $industry->id);

        if ($request->boolean('remove_image') && $industry->image_path) {
            Storage::disk('public')->delete($industry->image_path);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            if ($industry->image_path) {
                Storage::disk('public')->delete($industry->image_path);
            }
            $data['image_path'] = $request->file('image')->store('industries', 'public');
        }

        $industry->update($data);

        return redirect()->route('admin.industries.index')->with('success', 'Industry updated.');
    }

    public function destroy(Industry $industry)
    {
        if ($industry->image_path) {
            Storage::disk('public')->delete($industry->image_path);
        }

        $industry->delete();

        return redirect()->route('admin.industries.index')->with('success', 'Industry deleted.');
    }

    protected function validated(Request $request, ?Industry $industry = null): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable', 'string', 'max:255', 'alpha_dash',
                Rule::unique('industries', 'slug')->ignore($industry?->id),
            ],
            'category' => 'nullable|string|max:120',
            'description' => 'required|string|max:600',
            'icon' => 'nullable|string|max:2000',
            'tags' => 'nullable|string',
            'overview' => 'nullable|string',
            'challenges' => 'nullable|string',
            'services' => 'nullable|string',
            'cybersecurity' => 'nullable|string',
            'technology' => 'nullable|string',
            'benefits' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'image_alt' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
            'show_on_homepage' => 'nullable|boolean',
        ]);

        return [
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? null,
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'],
            'icon' => $validated['icon'] ?? null,
            'tags' => $this->splitList($validated['tags'] ?? null, '/[,\r\n]+/'),
            'overview' => $validated['overview'] ?? null,
            'challenges' => $this->splitList($validated['challenges'] ?? null),
            'services' => $this->splitList($validated['services'] ?? null),
            'cybersecurity' => $this->splitList($validated['cybersecurity'] ?? null),
            'technology' => $this->splitList($validated['technology'] ?? null),
            'benefits' => $this->splitList($validated['benefits'] ?? null),
            'image_alt' => $validated['image_alt'] ?? null,
            'seo_title' => $validated['seo_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
            'show_on_homepage' => $request->boolean('show_on_homepage'),
        ];
    }

    protected function splitList(?string $value, string $pattern = '/\r\n|\r|\n/'): array
    {
        return collect(preg_split($pattern, (string) $value) ?: [])
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    protected function uniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug) ?: 'industry';
        $candidate = $base;
        $suffix = 1;

        while (Industry::where('slug', $candidate)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base.'-'.$suffix++;
        }

        return $candidate;
    }
}
