<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }

    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Check if this role has a specific module action permission
     */
    public function hasPermission(string $module, string $action): bool
    {
        if ($this->status === 'Inactive') {
            return false;
        }

        $perms = $this->permissions ?? [];

        return ! empty($perms[$module][$action]);
    }

    /**
     * Count total granted permissions for badge display
     */
    public function getActivePermissionsCountAttribute(): int
    {
        $perms = $this->permissions ?? [];
        $count = 0;
        foreach ($perms as $module => $actions) {
            if (is_array($actions)) {
                foreach ($actions as $action => $enabled) {
                    if (! empty($enabled)) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }
}
