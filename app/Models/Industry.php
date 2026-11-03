<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
        'icon',
        'tags',
        'overview',
        'challenges',
        'services',
        'cybersecurity',
        'technology',
        'benefits',
        'image_path',
        'image_alt',
        'seo_title',
        'meta_description',
        'is_active',
        'show_on_homepage',
        'sort_order',
    ];

    protected $casts = [
        'tags' => 'array',
        'challenges' => 'array',
        'services' => 'array',
        'cybersecurity' => 'array',
        'technology' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOnHomepage($query)
    {
        return $query->where('show_on_homepage', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (Str::startsWith($this->image_path, ['http://', 'https://', '/'])) {
            return $this->image_path;
        }

        return Storage::disk('public')->url($this->image_path);
    }

    /**
     * Normalised array consumed by the public views. Existing static sectors
     * have the same shape, so the Blade templates stay simple.
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => $this->category,
            'description' => $this->description,
            'icon' => $this->icon,
            'tags' => $this->tags ?? [],
            'overview' => $this->overview,
            'challenges' => $this->challenges ?? [],
            'services' => $this->services ?? [],
            'cybersecurity' => $this->cybersecurity ?? [],
            'technology' => $this->technology ?? [],
            'benefits' => $this->benefits ?? [],
            'image_url' => $this->imageUrl(),
            'image_alt' => $this->image_alt ?: $this->name,
            'seo_title' => $this->seo_title,
            'meta_description' => $this->meta_description,
            'sort_order' => $this->sort_order,
            'created_at' => optional($this->created_at)->toDateString(),
        ];
    }
}
