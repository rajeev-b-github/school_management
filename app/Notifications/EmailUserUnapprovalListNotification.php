<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailUserUnapprovalListNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $mailData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $fileName = $this->createCsvFile($this->mailData);

        return (new MailMessage())
            ->from('xyz@mail.com')
            ->subject('Test mail')
            ->line('Admin')
            ->line('Please find attach the list of unapproved users')
            ->line('Thanks')
            ->attach(public_path('export/' . $fileName), [
                'as' => $fileName,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
    public function createCsvFile($user)
    {
        $fileName = 'ListUnaaprovedUser.csv';
        $columnNames = ['ID', 'name', 'email', 'user_type'];

        $file = fopen('public/export/' . $fileName, 'w');
        fputcsv($file, $columnNames);

        foreach ($user as $report) {
            fputcsv($file, [
                $report->id,
                $report->name,
                $report->email,
                $report->user_type,
            ]);
        }
        fclose($file);
        return $fileName;
    }
}
