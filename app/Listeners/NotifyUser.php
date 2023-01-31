<?php

namespace App\Listeners;

use App\Events\PostCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use App\Mail\UserMail;

class NotifyUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        \Log::info("post_listener....");
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\PostCreated  $event
     * @return void
     */
    public function handle(PostCreated $event)
    {
        $users = User::where('id', '!=', auth()->id())->get();
        \Log::info("post_listener....");
        foreach($users as $user)
        {
            \Mail::to($user->email)->send(new UserMail($event->post));
        }
    }
}
