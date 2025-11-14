<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Guest extends Model
{
    use HasFactory;
    protected $fillable = [
        'full_name',
        'phone',
        'status',
        'number',
        'qr_token',
        'invited_at',
        'arrived_at'
    ];

    protected $casts = [
        'status' => 'boolean',
        'invited_at' => 'datetime',
        'arrived_at' => 'datetime',
    ];

    // sinh token khi tạo nếu chưa có
    public static function booted()
    {
        static::creating(function ($guest) {
            if (empty($guest->qr_token)) {
                $guest->qr_token = Str::uuid()->toString(); // an toàn, không dùng id nguyên
            }
        });
    }

    public function scopeArrived($q)
    {
        return $q->where('status', true);
    }
    public function scopePending($q)
    {
        return $q->where('status', false);
    }

    public function getQrUrlAttribute()
    {
        // URL để client quét — route bảo mật
        return route('guests.qr.show', ['qr_token' => $this->qr_token]);
    }
}
