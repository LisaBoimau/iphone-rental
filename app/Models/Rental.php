<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rental extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tgl_pinjam' => 'datetime',
        'tgl_kembali' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function iphone(): BelongsTo
    {
        return $this->belongsTo(Iphone::class);
    }
}
