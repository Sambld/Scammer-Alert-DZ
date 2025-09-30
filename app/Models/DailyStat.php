<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStat extends Model
{
    /** @use HasFactory<\Database\Factories\DailyStatFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'date',
        'reports_count',
        'verified_reports_count',
        'new_users_count',
        'total_amount_lost',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'reports_count' => 'integer',
            'verified_reports_count' => 'integer',
            'new_users_count' => 'integer',
            'total_amount_lost' => 'decimal:2',
        ];
    }

    /**
     * Scope to get stats for a specific date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope to get stats for the current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    /**
     * Scope to get stats for the current year.
     */
    public function scopeCurrentYear($query)
    {
        return $query->whereYear('date', now()->year);
    }

    /**
     * Get stats for today or create if not exists.
     */
    public static function today()
    {
        return static::firstOrCreate([
            'date' => now()->toDateString(),
        ]);
    }

    /**
     * Get formatted total amount lost.
     */
    public function getFormattedAmountLostAttribute(): string
    {
        return number_format($this->total_amount_lost, 2) . ' DZD';
    }

    /**
     * Get verification rate percentage.
     */
    public function getVerificationRateAttribute(): float
    {
        if ($this->reports_count === 0) {
            return 0;
        }

        return round(($this->verified_reports_count / $this->reports_count) * 100, 2);
    }

    /**
     * Update today's stats.
     */
    public static function updateTodayStats()
    {
        $today = now()->toDateString();
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $stats = static::firstOrCreate(['date' => $today]);

        $stats->update([
            'reports_count' => Report::whereBetween('created_at', [$todayStart, $todayEnd])->count(),
            'verified_reports_count' => Report::whereBetween('created_at', [$todayStart, $todayEnd])
                                            ->where('status', 'verified')->count(),
            'new_users_count' => User::whereBetween('created_at', [$todayStart, $todayEnd])->count(),
        ]);

        return $stats;
    }
}
