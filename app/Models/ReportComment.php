<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    /** @use HasFactory<\Database\Factories\ReportCommentFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'report_id',
        'user_id',
        'parent_id',
        'content',
        'is_from_victim',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_from_victim' => 'boolean',
        ];
    }

    /**
     * Get the report this comment belongs to.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the user who made this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (for threaded comments).
     */
    public function parent()
    {
        return $this->belongsTo(ReportComment::class, 'parent_id');
    }

    /**
     * Get the child comments (replies).
     */
    public function children()
    {
        return $this->hasMany(ReportComment::class, 'parent_id')->where('status', 'active');
    }

    /**
     * Get all child comments including moderated ones.
     */
    public function allChildren()
    {
        return $this->hasMany(ReportComment::class, 'parent_id');
    }

    /**
     * Scope to get only active comments.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get top-level comments (no parent).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get replies to a specific comment.
     */
    public function scopeRepliesTo($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Check if this is a top-level comment.
     */
    public function isTopLevel(): bool
    {
        return $this->parent_id === null;
    }

    /**
     * Check if this is a reply.
     */
    public function isReply(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Check if comment is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if comment is hidden.
     */
    public function isHidden(): bool
    {
        return $this->status === 'hidden';
    }

    /**
     * Check if comment is deleted.
     */
    public function isDeleted(): bool
    {
        return $this->status === 'deleted';
    }
}
