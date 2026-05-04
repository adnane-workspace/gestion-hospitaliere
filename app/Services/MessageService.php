<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class MessageService
{
    public function contactsFor(User $user): Collection
    {
        return User::query()
            ->where('id', '!=', $user->id)
            ->when($user->isPatient(), fn ($q) => $q->where('role', 'medecin')->where('is_active', true))
            ->when($user->isMedecin(), fn ($q) => $q->where('role', 'patient'))
            ->orderBy('name')
            ->get();
    }

    public function conversation(User $user, User $selectedUser): Collection
    {
        return Message::with(['sender', 'receiver'])
            ->where(function ($q) use ($user, $selectedUser) {
                $q->where('sender_id', $user->id)->where('receiver_id', $selectedUser->id);
            })
            ->orWhere(function ($q) use ($user, $selectedUser) {
                $q->where('sender_id', $selectedUser->id)->where('receiver_id', $user->id);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function send(User $sender, int $receiverId, string $contenu): Message
    {
        return Message::create([
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'contenu' => $contenu,
        ]);
    }
}
