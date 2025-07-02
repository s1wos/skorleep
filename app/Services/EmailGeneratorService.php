<?php

namespace App\Services;

use App\Models\Email;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class EmailGeneratorService
{
    public function generate(): Email
    {
        $attempts = 0;
        while ($attempts < 5) {
            $attempts++;

            $emailAddress = Str::lower(Str::random(8)) . '@mailmm.ru';

            try {
                return Email::create(['email' => $emailAddress]);
            } catch (QueryException $e) {
                // Код ошибки 23000 — нарушение ограничений целостности (дубликат и т.д.)
                if ($e->getCode() !== '23000') {
                    throw $e;
                }
            }
        }
        throw new \RuntimeException('Не удалось сгенерировать уникальный email.');
    }
}
