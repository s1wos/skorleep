<?php

namespace App\Services;

use App\Models\Email;

class EmailDeletionService
{
    /**
     * Удаляет временный email и все связанные письма.
     */
    public function delete(Email $email): void
    {
        // Сначала удаляем связанные письма
        $email->messages()->delete();

        // Затем удаляем сам email
        $email->delete();
    }
} 