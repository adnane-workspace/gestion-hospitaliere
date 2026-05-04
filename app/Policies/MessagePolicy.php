<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isPatient() || $user->isMedecin();
    }

    public function view(User $user, Message $message): bool
    {
        return $message->sender_id === $user->id || $message->receiver_id === $user->id;
    }

    public function create(User $user, User $receiver): bool
    {
        if (!$user->isPatient() && !$user->isMedecin()) {
            return false;
        }

        if ($receiver->id === $user->id) {
            return false;
        }

        if ($user->isPatient()) {
            return $receiver->isMedecin();
        }

        return $user->isMedecin() && $receiver->isPatient();
    }
}
