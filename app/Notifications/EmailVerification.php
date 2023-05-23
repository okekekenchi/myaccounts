<?php

namespace App\Notifications;

use App\Mail\EmailVerification as EmailVerificationMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use App\Models\User;
use Illuminate\Support\HtmlString;

class EmailVerification extends Notification
//  implements ShouldQueue
{
    // use Queueable;

    protected $user;

    protected $otp;

    protected $verification_url;

    public function __construct(User $user, string $otp, $url)
    {
			$this->user = $user;
			$this->otp = $otp;
			$this->verification_url = $url;
    }

    /**
     * The callback that should be used to create the verify email URL.
     *
     * @var \Closure|null
     */
    public static $createUrlCallback;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
			return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
			return (new EmailVerificationMailable($this->user, $this->otp, $this->verification_url))
							->to($notifiable->getEmailForVerification());
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
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
}
