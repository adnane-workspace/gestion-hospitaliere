<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(
        private readonly MessageService $messageService
    ) {
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', Message::class);
        $user = $request->user();

        $contacts = $this->messageService->contactsFor($user);

        $selectedUser = null;
        $messages = collect();

        if ($request->filled('contact')) {
            $selectedUser = User::find($request->integer('contact'));

            if ($selectedUser) {
                $messages = $this->messageService->conversation($user, $selectedUser);
            }
        }

        return view('messages.index', compact('contacts', 'messages', 'selectedUser'));
    }

    public function store(StoreMessageRequest $request)
    {
        $data = $request->validated();
        $receiver = User::findOrFail($data['receiver_id']);

        $this->authorize('create', [Message::class, $receiver]);

        $this->messageService->send($request->user(), (int) $data['receiver_id'], $data['contenu']);

        return redirect()->route('messages.index', ['contact' => $data['receiver_id']])
            ->with('success', 'Message envoye.');
    }
}
