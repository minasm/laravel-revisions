<?php

namespace Neurony\Revisions\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Neurony\Revisions\Contracts\RevisionModelContract;

class Revision extends Model implements RevisionModelContract
{
    /**
     * The database table.
     *
     * @var string
     */
    protected $table = 'revisions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'revisionable_id',
        'revisionable_type',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Revision belongs to user.
     */
    public function user(): BelongsTo
    {
        $user = config('revisions.user_model', null);

        if ($user && class_exists($user)) {
            return $this->belongsTo($user, 'user_id');
        }

    }

    /**
     * Get all of the owning revisionable models.
     */
    public function revisionable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Filter the query by the given user id.
     *
     * @param  Authenticatable|int  $user
     */
    public function scopeWhereUser(Builder $query, Authenticatable $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * Filter the query by the given revisionable params (id, type).
     */
    public function scopeWhereRevisionable(Builder $query, int $id, string $type): void
    {
        $query->where([
            'revisionable_id' => $id,
            'revisionable_type' => $type,
        ]);
    }
}
