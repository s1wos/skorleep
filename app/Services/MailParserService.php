<?php

namespace App\Services;

use App\DTO\ParsedMail;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Carbon\Carbon;
use Symfony\Component\Mime\Part\DataPart;

class MailParserService
{
    /**
     * Парсинг строки электронной почты RFC822 и возврат DTO.
     */
    public function parse(string $raw): ParsedMail
    {
        // Symfony\Component\Mime\Email::fromString преобразует сообщение в экземпляр Email
        /** @var SymfonyEmail $email */
        $email = SymfonyEmail::fromString($raw);

        $fromAddress = $email->getFrom()[0]->getAddress() ?? '';
        $toAddresses = array_map(fn ($addr) => $addr->getAddress(), iterator_to_array($email->getTo()));

        $subject = $email->getSubject();

        $bodyText = null;
        $bodyHtml = null;

        if ($email->getTextBody() !== null) {
            $bodyText = $email->getTextBody();
        }

        if ($email->getHtmlBody() !== null) {
            $bodyHtml = $email->getHtmlBody();
        }

        $dateHeader = $email->getDate();
        $receivedAt = $dateHeader ? Carbon::instance($dateHeader) : Carbon::now();

        $attachments = [];
        foreach ($email->getAttachments() as $attachment) {
            /** @var DataPart $attachment */
            $attachments[] = [
                'filename' => $attachment->getFilename(),
                'mime' => $attachment->getMediaType() . '/' . $attachment->getMediaSubtype(),
                'size' => strlen($attachment->getBody()),
                'content' => $attachment->getBody(),
            ];
        }

        return new ParsedMail(
            from: $fromAddress,
            to: $toAddresses,
            subject: $subject,
            bodyText: $bodyText,
            bodyHtml: $bodyHtml,
            receivedAt: $receivedAt,
            attachments: $attachments,
        );
    }
}
