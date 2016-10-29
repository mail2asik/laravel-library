<?php
/**
 * MailEventHandler Class
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Listeners;

use App\Repositories\MailerRepository;

/**
 * Class MailEventHandler
 *
 * Listens for Mail Events fired
 *
 * ### Usage
 * Event::fire('mailActivateAccount', [
 *     'email' => 'email@mail.com',
 *     'name'  => 'John Doe',
 *     'activation_code' => ''
 * ]);
 */
class MailEventHandler
{
    public function __construct(MailerRepository $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Event handler for sending activation email when an account is created
     *
     * @param $params
     */
    public function mailActivateAccount($params)
    {
        $this->mailer->accountActivation($params);
    }

    /**
     * Event handler for sending activated email notification
     *
     * @param $params
     */
    public function mailActivatedAccount($params)
    {
        $this->mailer->accountActivated($params);
    }

    /**
     * Event handler for sending email when a password remainder requested
     *
     * @param $email
     * @param $name
     * @param $url
     */
    public function mailPasswordReminder($email, $name, $url)
    {
        $this->mailer->passwordReminder($email, $name, $url);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('mailActivateAccount', 'App\Listeners\MailEventHandler@mailActivateAccount');
        $events->listen('mailActivatedAccount', 'App\Listeners\MailEventHandler@mailActivatedAccount');
        $events->listen('mailPasswordReminder', 'App\Listeners\MailEventHandler@mailPasswordReminder');
    }
}
