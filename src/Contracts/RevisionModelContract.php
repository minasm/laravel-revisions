<?php

namespace Neurony\Revisions\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

interface RevisionModelContract
{
    public function user(): BelongsTo;

    public function revisionable(): MorphTo;

    public function scopeWhereUser(Builder $query, Authenticatable $user);

    public function scopeWhereRevisionable(Builder $query, int $id, string $type);
}
