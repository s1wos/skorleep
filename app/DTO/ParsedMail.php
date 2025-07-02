<?php

namespace App\DTO;

use Carbon\CarbonInterface;

class ParsedMail
{
    public function __construct(
        public readonly string $from,
        public readonly array $to,
        public readonly ?string $subject,
        public readonly ?string $bodyText,
        public readonly ?string $bodyHtml,
        public readonly CarbonInterface $receivedAt,
        public readonly array $attachments = [],
    ) {
    }
} 