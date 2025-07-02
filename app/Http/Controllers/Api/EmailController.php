<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailResource;
use App\Services\EmailGeneratorService;
use App\Services\EmailDeletionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Email;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    public function __construct(private readonly EmailGeneratorService $generator)
    {
    }
    public function store(Request $request): EmailResource
    {
        $email = $this->generator->generate();

        $request->session()->put('email_id', $email->id);

        return new EmailResource($email);
    }

    /**
     * Удаляет указанный временный email и связанные с ним письма.
     */
    public function destroy(Email $email, EmailDeletionService $deleter): Response
    {
        // Middleware EnsureEmailInSession гарантировал, что у пользователя есть доступ
        // Удаляем email и связанные письма
        $deleter->delete($email);

        // Возвращаем 204 No Content
        return response()->noContent();
    }

    /**
     * Вернуть email из сессии или создать новый, если его нет.
     */
    public function getOrCreateEmail(Request $request): JsonResponse
    {
        $emailId = $request->session()->get('email_id');

        if ($emailId) {
            $existing = Email::find($emailId);
            if ($existing) {
                return response()->json($existing);
            }
        }

        $email = $this->generator->generate();
        $request->session()->put('email_id', $email->id);

        return response()->json($email);
    }
}
