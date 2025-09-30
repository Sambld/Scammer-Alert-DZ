<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportVote extends Model
{
    /** @use HasFactory<\Database\Factories\ReportVoteFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'report_id',
        'user_id',
        'vote_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * Get the report this vote belongs to.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the user who made this vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get upvotes only.
     */
    public function scopeUpvotes($query)
    {
        return $query->where('vote_type', 'upvote');
    }

    /**
     * Scope to get downvotes only.
     */
    public function scopeDownvotes($query)
    {
        return $query->where('vote_type', 'downvote');
    }

    /**
     * Check if this is an upvote.
     */
    public function isUpvote(): bool
    {
        return $this->vote_type === 'upvote';
    }

    /**
     * Check if this is a downvote.
     */
    public function isDownvote(): bool
    {
        return $this->vote_type === 'downvote';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Update report vote counts when vote is created, updated, or deleted
        static::created(function ($vote) {
            $vote->report->updateVoteCounts();
        });

        static::updated(function ($vote) {
            $vote->report->updateVoteCounts();
        });

        static::deleted(function ($vote) {
            $vote->report->updateVoteCounts();
        });
    }
}
