<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Waterhole\Models\Concerns\HasLikes;
use Waterhole\Models\Concerns\HasBody;

class Comment extends Model
{
    use HasLikes;
    use HasBody;
    use HasRecursiveRelationships;

    const UPDATED_AT = null;

    protected $casts = [
        'edited_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    // Prevent recursion during serialization
    protected $hidden = ['parent'];

    protected static function booted(): void
    {
        $refreshMetadata = function (self $comment) {
            $comment->post->refreshCommentMetadata()->save();

            if ($comment->parent) {
                $comment->parent->refreshReplyMetadata()->save();
            }
        };

        static::created($refreshMetadata);
        static::deleted($refreshMetadata);
    }

    public static function byUser(User $user, array $attributes = []): static
    {
        return new static(array_merge(['user_id' => $user->id], $attributes));
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id');//->with('replies', 'replies.user', 'replies.likedBy', 'replies.parent', 'replies.post');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');//->with('parent');
    }

    public static function rules(): array
    {
        return [
            'parent_id' => ['nullable', Rule::exists('comments', 'id')],
            'body' => ['required', 'string'],
        ];
    }

    public function getUrlAttribute(): string
    {
        return route('waterhole.posts.comments.show', ['post' => $this->post, 'comment' => $this]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.posts.comments.edit', ['post' => $this->post, 'comment' => $this]);
    }

    public function wasEdited(): static
    {
        $this->edited_at = now();

        return $this;
    }

    public function refreshReplyMetadata(): static
    {
        $this->reply_count = $this->replies()->count();

        return $this;
    }

    public function getPerPage(): int
    {
        return config('waterhole.forum.comments_per_page', $this->perPage);
    }
}
