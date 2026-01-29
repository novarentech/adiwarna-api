<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserType;
use App\Models\Concerns\Searchable;
use App\Models\Concerns\Transactional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, Searchable, Transactional;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'usertype',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'usertype' => UserType::class,
        ];
    }

    /**
     * Define searchable columns for user search
     */
    protected function searchableColumns(): array
    {
        return ['name', 'email', 'phone'];
    }

    /**
     * Scope: Filter by UserType
     */
    public function scopeByUserType(Builder $query, string $type): Builder
    {
        return $query->where('usertype', $type);
    }

    /**
     * Create user with password hashing
     * Replaces UserService::createUser
     */
    public static function createUser(array $data): self
    {
        // Note: 'password' => 'hashed' cast might handle this, but explicit hashing 
        // ensures consistency if cast behavior varies or input is not handled by cast on create
        // Actually, if cast is 'hashed', we don't need to manually hash if we pass it to create.
        // But UserService was manually hashing. Let's rely on cast if simple, 
        // or keep manual if we want to be safe.
        // Laravel 10+ hashed cast handles it.
        // However, to strictly follow previous logic:
        // if (isset($data['password'])) $data['password'] = Hash::make($data['password']);
        
        // Let's force hash to be safe/explicit or trust the cast? 
        // Trusting the cast (password => hashed) is cleaner.
        // If data['password'] is plain text, Eloquent will hash it on save.
        
        return self::create($data);
    }

    /**
     * Update user with password handling
     * Replaces UserService::updateUser
     */
    public function updateUser(array $data): self
    {
        if (isset($data['password']) && !empty($data['password'])) {
            // Let the cast handle hashing or hash explicitly.
            // With 'hashed' cast, just updating the attribute works.
        } else {
            unset($data['password']);
        }

        $this->update($data);
        return $this->fresh();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->usertype === UserType::ADMIN;
    }

    /**
     * Check if user is teknisi
     */
    public function isTeknisi(): bool
    {
        return $this->usertype === UserType::TEKNISI;
    }
}

