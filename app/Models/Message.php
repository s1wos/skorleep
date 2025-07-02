<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email_id',
        'from',
        'subject',
        'body_text',
        'body_html',
        'received_at',
        'attachments',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'attachments' => 'array',
    ];

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
} 