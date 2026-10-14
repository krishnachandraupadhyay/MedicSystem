<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class, 'hospital_id');
    }

    /**
     * Send notification to Super Admin(s)
     */
    public static function notifySuperAdmins(
        string $title,
        string $message,
        string $type = 'general',
        ?int $hospitalId = null,
        array $data = [],
        ?int $senderId = null
    ): self {
        return self::create([
            'sender_id' => $senderId ?? auth()->id(),
            'recipient_id' => null, // null targets all Super Admins
            'hospital_id' => $hospitalId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Query notifications accessible to a specific user
     */
    public function scopeForUser($query, $user)
    {
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        $isSuperAdmin = ($user->role === 'Super Admin' || ($user->role_slug ?? '') === 'superadmin');

        if ($isSuperAdmin) {
            return $query->where(function ($q) use ($user) {
                $q->whereNull('recipient_id')
                    ->orWhere('recipient_id', $user->id);
            });
        }

        return $query->where('recipient_id', $user->id);
    }
}
