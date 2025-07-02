<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Email;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $emailId = $request->session()->get('email_id');
        if (!$emailId) {
            return MessageResource::collection([]);
        }

        $email = Email::find($emailId);
        if (!$email) {
            return MessageResource::collection([]);
        }

        $messages = $email->messages()->latest('received_at')->get();

        return MessageResource::collection($messages);
    }
}
