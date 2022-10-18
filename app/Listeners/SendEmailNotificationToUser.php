<?php

namespace App\Listeners;

use App\Event\UserApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserApprovalNotification;

class SendEmailNotificationToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Event\UserApproved  $event
     * @return void
     */
    public function handle(UserApproved $event)
    {
        try {

            $mailData = [
                'name' => $event->user->name,
                'body' => 'Your profile has been approved. ',
                'thanks' => 'Thank you',
            ];

            // Notification::route('mail', $event->user->email)->notify(
            //     new UserApprovalNotification($mailData)
            // );
            Notification::send($event->user, new UserApprovalNotification($mailData));
        } catch (\Exception $e) {
            return  ['result' => 'Error Exception : Bad Request', 'status' => '400', 'data' => $e,];
        }
    }
}
