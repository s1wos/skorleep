<?php

namespace App\Http\Resources;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin Message
 */
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'from' => $this->from,
            'subject' => $this->subject,
            'body_text' => $this->body_text,
            'received_at' => $this->received_at?->toIso8601String(),
            'attachments' => collect($this->attachments)->map(function ($item) {
                return [
                    'filename' => $item['filename'] ?? null,
                    'url' => $item['path'] ? Storage::disk('public')->url($item['path']) : null,
                    'mime' => $item['mime'] ?? null,
                    'size' => $item['size'] ?? null,
                ];
            }),
        ];
    }
}
