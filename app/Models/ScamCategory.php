<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScamCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ScamCategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'name_ar',
        'name_fr',
        'description',
        'description_fr',
        'description_ar',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the reports in this category.
     */
    public function reports()
    {
        return $this->hasMany(Report::class, 'category_id');
    }

    /**
     * Scope to get only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get localized name based on current locale.
     */
    public function getLocalizedNameAttribute()
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'ar' => $this->name_ar ?: $this->name,
            'fr' => $this->name_fr ?: $this->name,
            default => $this->name,
        };
    }

    /**
     * Get localized description based on current locale.
     */
    public function getLocalizedDescriptionAttribute()
    {
        $locale = app()->getLocale();
        
        return match($locale) {
            'ar' => $this->description_ar ?: $this->description,
            'fr' => $this->description_fr ?: $this->description,
            default => $this->description,
        };
    }
}
