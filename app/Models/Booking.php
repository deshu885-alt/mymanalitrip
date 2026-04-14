<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_ref', 'package_id', 'full_name', 'phone', 'email', 'city',
        'adults', 'children', 'room_type', 'pickup_point', 'special_requests',
        'travel_date', 'total_amount', 'advance_paid', 'balance_due',
        'payment_type', 'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
        'status', 'admin_notes', 'email_sent', 'whatsapp_sent',
    ];

    protected $casts = [
        'travel_date'  => 'date',
        'email_sent'   => 'boolean',
        'whatsapp_sent'=> 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $b) {
            if (empty($b->booking_ref)) {
                $b->booking_ref = 'MMT-' . date('Y') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'confirmed'  => '<span class="badge badge-green">Confirmed</span>',
            'pending'    => '<span class="badge badge-yellow">Pending</span>',
            'cancelled'  => '<span class="badge badge-red">Cancelled</span>',
            'completed'  => '<span class="badge badge-blue">Completed</span>',
            default      => '<span class="badge">Unknown</span>',
        };
    }
}
