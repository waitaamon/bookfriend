<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Pivot\BookUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class)
            ->using(BookUser::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    public function friends()
    {
        return $this->mergedRelationWithModel(User::class, 'friends_view');
    }

    public function addFriend(User $friend)
    {
        $this->friendsOfMine()->syncWithoutDetaching($friend, [
            'accepted' => false
        ]);
    }

    public function acceptFriend(User $friend)
    {
        $friend->friendsOfMine()->updateExistingPivot($this->id, [
            'accepted' => true
        ]);
    }

    public function removeFriend(User $friend)
    {
        $this->friendsOfMine()->detach($friend);
        $this->friendsOf()->detach($friend);
    }

    public function pendingFriendsOfMine(): BelongsToMany
    {
        return $this->friendsOfMine()->wherePivot('accepted', false);
    }

    public function acceptedFriendsOfMine(): BelongsToMany
    {
        return $this->friendsOfMine()->wherePivot('accepted', true);
    }

    public function acceptedFriendsOf(): BelongsToMany
    {
        return $this->friendsOf()->wherePivot('accepted', true);
    }

    public function friendsOfMine(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }

    public function pendingFriendsOf(): BelongsToMany
    {
        return $this->friendsOf()->wherePivot('accepted', false);
    }

    public function friendsOf(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->withPivot('accepted')
            ->withTimestamps();
    }

}