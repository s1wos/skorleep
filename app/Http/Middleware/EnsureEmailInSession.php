<?php

namespace App\Http\Middleware;

use App\Models\Email;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailInSession
{
    /**
     * Обрабатывает входящий запрос и проверяет, что email в маршруте принадлежит текущей сессии.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeEmail = $request->route('email');
        $routeEmailId = null;

        if ($routeEmail instanceof Email) {
            $routeEmailId = $routeEmail->id;
        } elseif ($routeEmail !== null) {
            // Если маршрутизатор не использует автоматическое разрешение модели
            $routeEmailId = (int) $routeEmail;
        }

        // ID email, сохранённый в сессии
        $sessionEmailId = Session::get('email_id');

        if ($sessionEmailId === null || (int) $sessionEmailId !== (int) $routeEmailId) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
