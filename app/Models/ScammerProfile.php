<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScammerProfile extends Model
{
    /** @use HasFactory<\Database\Factories\ScammerProfileFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'phone',
        'social_handle',
        'name',
        'bank_identifier',
        'reports_count',
        'last_reported_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reports_count' => 'integer',
            'last_reported_at' => 'datetime',
        ];
    }

    /**
     * Scope to search by phone number.
     */
    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', 'like', '%' . $phone . '%');
    }

    /**
     * Scope to search by social handle.
     */
    public function scopeBySocialHandle($query, $handle)
    {
        return $query->where('social_handle', 'like', '%' . $handle . '%');
    }

    /**
     * Scope to search by bank identifier.
     */
    public function scopeByBankIdentifier($query, $identifier)
    {
        return $query->where('bank_identifier', 'like', '%' . $identifier . '%');
    }

    /**
     * Scope to search by name.
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }

    /**
     * Scope for general search across all fields.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('phone', 'like', '%' . $term . '%')
              ->orWhere('social_handle', 'like', '%' . $term . '%')
              ->orWhere('name', 'like', '%' . $term . '%')
              ->orWhere('bank_identifier', 'like', '%' . $term . '%');
        });
    }

    /**
     * Scope to get most reported scammers.
     */
    public function scopeMostReported($query, $limit = 10)
    {
        return $query->orderBy('reports_count', 'desc')->limit($limit);
    }

    /**
     * Scope to get recently reported scammers.
     */
    public function scopeRecentlyReported($query, $days = 30)
    {
        return $query->where('last_reported_at', '>=', now()->subDays($days))
                    ->orderBy('last_reported_at', 'desc');
    }

    /**
     * Get all reports associated with this scammer profile.
     */
    public function getAssociatedReports()
    {
        $query = Report::query();

        if ($this->phone) {
            $query->orWhere('scammer_phone', 'like', '%' . $this->phone . '%');
        }

        if ($this->social_handle) {
            $query->orWhere('scammer_social_handle', 'like', '%' . $this->social_handle . '%');
        }

        if ($this->name) {
            $query->orWhere('scammer_name', 'like', '%' . $this->name . '%');
        }

        if ($this->bank_identifier) {
            $query->orWhere('scammer_bank_identifier', 'like', '%' . $this->bank_identifier . '%');
        }

        return $query->get();
    }

    /**
     * Update reports count and last reported date.
     */
    public function updateStats()
    {
        $reports = $this->getAssociatedReports();
        
        $this->update([
            'reports_count' => $reports->count(),
            'last_reported_at' => $reports->max('created_at') ?: now(),
        ]);
    }

    /**
     * Find or create scammer profile from report data.
     */
    public static function findOrCreateFromReport(Report $report)
    {
        // Try to find existing profile by any matching identifier
        $query = static::query();
        $conditions = [];

        if ($report->scammer_phone) {
            $conditions[] = ['phone', 'like', '%' . $report->scammer_phone . '%'];
        }

        if ($report->scammer_social_handle) {
            $conditions[] = ['social_handle', 'like', '%' . $report->scammer_social_handle . '%'];
        }

        if ($report->scammer_bank_identifier) {
            $conditions[] = ['bank_identifier', 'like', '%' . $report->scammer_bank_identifier . '%'];
        }

        foreach ($conditions as $i => $condition) {
            if ($i === 0) {
                $query->where($condition);
            } else {
                $query->orWhere($condition);
            }
        }

        $profile = $query->first();

        if (!$profile && !empty($conditions)) {
            $profile = static::create([
                'phone' => $report->scammer_phone,
                'social_handle' => $report->scammer_social_handle,
                'name' => $report->scammer_name,
                'bank_identifier' => $report->scammer_bank_identifier,
                'reports_count' => 1,
                'last_reported_at' => $report->created_at,
            ]);
        } elseif ($profile) {
            $profile->updateStats();
        }

        return $profile;
    }

    /**
     * Get the threat level based on reports count.
     */
    public function getThreatLevelAttribute(): string
    {
        return match (true) {
            $this->reports_count >= 10 => 'high',
            $this->reports_count >= 5 => 'medium',
            $this->reports_count >= 2 => 'low',
            default => 'minimal',
        };
    }

    /**
     * Get threat level color for UI.
     */
    public function getThreatColorAttribute(): string
    {
        return match ($this->threat_level) {
            'high' => 'red',
            'medium' => 'orange',
            'low' => 'yellow',
            default => 'gray',
        };
    }
}
