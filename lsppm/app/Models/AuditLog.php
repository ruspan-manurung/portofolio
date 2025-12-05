<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
    'user_id', 'action', 'target', 'details', 'status', 'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function getRecentLogs($limit = 10)
    {
        return self::with('user')->latest()->limit($limit)->get();
    }
}