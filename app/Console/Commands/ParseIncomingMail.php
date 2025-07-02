<?php

namespace App\Console\Commands;

use App\Models\Email;
use App\Models\Message;
use App\Services\MailParserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Events\NewMessageReceived;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ParseIncomingMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse incoming email from STDIN and save to database';

    public function __construct(private readonly MailParserService $parser)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $rawEmail = stream_get_contents(STDIN);

        if (empty($rawEmail)) {
            $this->warn('No input received.');
            return self::FAILURE;
        }

        try {
            $parsed = $this->parser->parse($rawEmail);
        } catch (Throwable $e) {
            Log::error('Failed to parse email', ['exception' => $e]);
            $this->error('Failed to parse email: ' . $e->getMessage());
            return self::FAILURE;
        }

        $matchedEmails = Email::query()->whereIn('email', $parsed->to)->get();

        if ($matchedEmails->isEmpty()) {
            $this->info('No matching temporary email found, skipping.');
            return self::SUCCESS;
        }

        foreach ($matchedEmails as $emailModel) {
            $message = Message::create([
                'email_id' => $emailModel->id,
                'from' => $parsed->from,
                'subject' => $parsed->subject,
                'body_text' => $parsed->bodyText,
                'body_html' => $parsed->bodyHtml,
                'received_at' => $parsed->receivedAt,
            ]);

            if (!empty($parsed->attachments)) {
                $meta = [];
                foreach ($parsed->attachments as $att) {
                    $path = "emails/$message->id/" . $att['filename'];
                    Storage::disk('public')->put($path, $att['content']);

                    $meta[] = [
                        'filename' => $att['filename'],
                        'path' => $path,
                        'mime' => $att['mime'],
                        'size' => $att['size'],
                    ];
                }

                $message->update(['attachments' => $meta]);
            }

            // Передаём событие реальному времени
            event(new NewMessageReceived($emailModel->email, $message->toArray()));
        }

        $this->info('Email processed and stored successfully.');

        return self::SUCCESS;
    }
}
