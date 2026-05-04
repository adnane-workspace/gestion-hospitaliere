@extends('layouts.app')

@section('title', 'Messagerie')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-800">Messagerie securisee</h1>
    <p class="text-slate-500">Echangez avec {{ auth()->user()->isPatient() ? 'vos medecins' : 'vos patients' }}.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white border border-slate-200 rounded-2xl p-4">
        <h3 class="text-sm font-bold text-slate-700 mb-3">Contacts</h3>
        <div class="space-y-2">
            @forelse($contacts as $contact)
                <a href="{{ route('messages.index', ['contact' => $contact->id]) }}" class="block p-3 rounded-xl {{ request('contact') == $contact->id ? 'bg-indigo-50 text-indigo-700' : 'hover:bg-slate-50 text-slate-700' }}">
                    {{ $contact->name }}
                </a>
            @empty
                <p class="text-sm text-slate-400">Aucun contact disponible.</p>
            @endforelse
        </div>
    </div>

    <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-4 flex flex-col min-h-[28rem]">
        @if($selectedUser)
            <h3 class="text-sm font-bold text-slate-700 mb-3">Conversation avec {{ $selectedUser->name }}</h3>
            <div class="flex-1 overflow-auto space-y-3 p-2 bg-slate-50 rounded-xl">
                @forelse($messages as $message)
                    <div class="{{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        <div class="inline-block max-w-[80%] px-3 py-2 rounded-xl text-sm {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-white border border-slate-200 text-slate-700' }}">
                            {{ $message->contenu }}
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Aucun message pour le moment.</p>
                @endforelse
            </div>

            <form method="POST" action="{{ route('messages.store') }}" class="mt-3 flex gap-2">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                <input type="text" name="contenu" class="flex-1 rounded-xl border-slate-200" placeholder="Ecrire un message..." required>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded-xl">Envoyer</button>
            </form>
        @else
            <div class="flex items-center justify-center flex-1 text-slate-400">
                Selectionnez un contact pour commencer.
            </div>
        @endif
    </div>
</div>
@endsection
