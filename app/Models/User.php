<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia{
    use HasApiTokens, HasFactory, Notifiable, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'image',
        'token',
        'token_expiration',
        'is_active',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public  function authorRequest()
    {
        return $this->hasMany(AuthorRequest::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class)
                    ->using(NotificationUser::class)
                    ->withPivot('is_read', 'is_public', 'receiver_id') 
                    ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public  function roles()
    {
        return $this->belongsToMany(Role::class);
    }

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


}
