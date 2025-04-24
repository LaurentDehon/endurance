<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BannedIp extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ip_address',
        'reason',
        'banned_by',
    ];

    /**
     * Récupère l'utilisateur qui a banni cette adresse IP.
     */
    public function bannedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'banned_by');
    }
    
    /**
     * Vérifie si une adresse IP est bannie.
     *
     * @param string $ipAddress
     * @return bool
     */
    public static function isIpBanned(string $ipAddress): bool
    {
        return static::where('ip_address', $ipAddress)->exists();
    }
}
