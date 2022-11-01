<?php

namespace App\Listeners;

use App\Event\TeacherAssignedToStudent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AssignTeacherNotification;


class SendEmailNotificationToTeacher
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    public function __construct()
    {
        //no value to use
    }

    /**
     * Handle the event.
     *
     * @param  \App\Event\TeacherAssignedToStudent  $event
     * @return void
     */
    public function handle(TeacherAssignedToStudent $event)
    {
        try {
            $mailData = [
                'name' => $event->teacher->name,
                'body' => 'Meet your new student : ' . $event->student->name,
                'thanks' => 'Thank you',
            ];

            Notification::send($event->teacher, new AssignTeacherNotification($mailData));
        } catch (\Exception $e) {
            return  ['result' => 'Error Exception : Bad Request', 'status' => '400', 'data' => $e,];
        }
    }
}
