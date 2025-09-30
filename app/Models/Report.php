<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'platform_id',
        'title',
        'description',
        'scammer_name',
        'scammer_phone',
        'scammer_social_handle',
        'scammer_profile_url',
        'scammer_bank_identifier',
        'incident_date',
        'status',
        'moderator_id',
        'moderator_notes',
        'moderated_at',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'ip_address',
        'user_agent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'incident_date' => 'date',
            'moderated_at' => 'datetime',
            'views_count' => 'integer',
            'upvotes_count' => 'integer',
            'downvotes_count' => 'integer',
        ];
    }

    /**
     * Get the user who created this report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category of this report.
     */
    public function category()
    {
        return $this->belongsTo(ScamCategory::class, 'category_id');
    }

    /**
     * Get the platform where the scam occurred.
     */
    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Get the moderator who reviewed this report.
     */
    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    /**
     * Get the media files attached to this report.
     */
    public function media()
    {
        return $this->hasMany(ReportMedia::class)->orderBy('sort_order');
    }

    /**
     * Get the comments on this report.
     */
    public function comments()
    {
        return $this->hasMany(ReportComment::class)->where('status', 'active');
    }

    /**
     * Get all comments including moderated ones.
     */
    public function allComments()
    {
        return $this->hasMany(ReportComment::class);
    }

    /**
     * Get the votes on this report.
     */
    public function votes()
    {
        return $this->hasMany(ReportVote::class);
    }

    /**
     * Get upvotes only.
     */
    public function upvotes()
    {
        return $this->hasMany(ReportVote::class)->where('vote_type', 'upvote');
    }

    /**
     * Get downvotes only.
     */
    public function downvotes()
    {
        return $this->hasMany(ReportVote::class)->where('vote_type', 'downvote');
    }

    /**
     * Scope to get verified reports.
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Scope to get pending reports.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get reports by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if report is verified.
     */
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    /**
     * Check if report is pending moderation.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Update vote counts.
     */
    public function updateVoteCounts(): void
    {
        $this->upvotes_count = $this->upvotes()->count();
        $this->downvotes_count = $this->downvotes()->count();
        $this->save();
    }
}
