<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'parent_id', 'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function allChildren()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'category_id');
    }

    public function subListings()
    {
        return $this->hasMany(Listing::class, 'subcategory_id');
    }

    // Nova metoda: svi listingi za kategoriju i sve njene podkategorije
    public function allListings()
    {
        $categoryIds = $this->getAllCategoryIds();
        
        return Listing::whereIn('category_id', $categoryIds)
            ->orWhereIn('subcategory_id', $categoryIds);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeChild($query)
    {
        return $query->whereNotNull('parent_id');
    }

    // Helpers
    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    public function hasChildren()
    {
        return $this->children()->exists();
    }

    public function isParent()
    {
        return is_null($this->parent_id);
    }

    public function isChild()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Get all category IDs including this category and all its children recursively
     */
    public function getAllCategoryIds()
    {
        $ids = [$this->id];
        
        foreach ($this->allChildren as $child) {
            $ids = array_merge($ids, $child->getAllCategoryIds());
        }
        
        return $ids;
    }

    /**
     * Get all listings count including subcategories
     */
    public function getAllListingsCount()
{
    try {
        $categoryIds = $this->getAllCategoryIds();
        
        return Listing::whereIn('category_id', $categoryIds)
            ->orWhereIn('subcategory_id', $categoryIds)
            ->where('status', 'active')
            ->count();
    } catch (\Exception $e) {
        return 0; // Vrati 0 ako dodje do greške
    }
}
}