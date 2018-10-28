<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionsUsers
{
    /**
     * Handle the event.
     *
     * @param ThreadHasNewReply $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        User::whereIn('name', $event->reply->mentionedUsers())
            ->get()
            ->each
            ->notify(new YouWereMentioned($event->reply));
    }
}
