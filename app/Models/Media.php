<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Support\Facades\Storage;

class Media extends BaseModel
{
    /*
    |--------------------------------------------------------------------------
    | Fillable
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
        'latitude',
        'longitude',
        'is_processed',
        'processed_at',
        'processing_status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function tags()
    {
        return $this->morphToMany(ImageTag::class, 'taggable');
    }

    public function getUrlAttribute()
    {
        if (! $this->path) {
            return null;
        }

        return Storage::disk($this->disk ?? 'public')->url($this->path);
    }

    public function attachTagBySlug(string $slug): void
    {
        $tag = \App\Models\ImageTag::where('slug', $slug)->first();

        if ($tag) {
            $this->tags()->syncWithoutDetaching([$tag->id]);
        }
    }
}
