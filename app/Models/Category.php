<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'alias',
        'parent_id',
        'description',
        'image_path',
        'modelable_id',
        'modelable_type',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function modelable()
    {
        return $this->morphTo();
    }

    public static function formatCategories($categories, $prefix = '')
    {
        $options = [];
        foreach ($categories as $category) {
            $options[$category->id] = $prefix . $category->name;
            if ($category->subCategories->count()) {
                $options += self::formatCategories($category->subCategories, $prefix . '-- ');
            }
        }
        return $options;
    }

    public function scopeOfType($query, string $model)
    {
        return $query->where('modelable_type', $model);
    }
}
