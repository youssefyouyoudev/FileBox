<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'storage_limit',
        'storage_used',
        'stripe_price',
        'stripe_subscription',
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
        ];
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function storageUsed(): int
    {
        return (int) $this->storage_used;
    }

    public function storageRemaining(): int
    {
        return max(0, (int) $this->storage_limit - $this->storageUsed());
    }

    public function storageUsagePercent(): float
    {
        if ($this->storage_limit <= 0) {
            return 0;
        }

        return round(($this->storageUsed() / $this->storage_limit) * 100, 1);
    }

    public function addStorageUsage(int $bytes): void
    {
        $this->increment('storage_used', $bytes);
    }

    public function subtractStorageUsage(int $bytes): void
    {
        $new = max(0, $this->storageUsed() - $bytes);
        $this->forceFill(['storage_used' => $new])->save();
    }
}
