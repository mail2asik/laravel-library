<?php
/**
 * Class MailerRepository
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Repositories;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class MailerRepository
 *
 * All application emails sending from this class
 */
class MailerRepository
{
    /**
     * Sends the account activation email
     *
     * @param $params
     *
     * @return void
     */
    public function accountActivation($params)
    {
        try {
            $email = $params['email'];
            $name  = $params['first_name'] . ' ' . $params['last_name'];

            Mail::send('emails.account_activation', $params, function ($message) use ($email, $name) {

                $message->from(env('MAIL_FROM'), 'Support');

                $email = (env('APP_DEBUG') == true) ? env('MAIL_DEBUG', $email) : $email;
                $message->to($email, $name)->subject('Please activate your account on '. config('app.site_name'));
            });
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown MailerRepository@accountActivation', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);
        }
    }

    /**
     * Sends the account activated notification email
     *
     * @param $params
     *
     * @return void
     */
    public function accountActivated($params)
    {
        try {
            $email = $params['email'];
            $name  = $params['first_name'] . ' ' . $params['last_name'];

            Mail::send('emails.account_activated', $params, function ($message) use ($email, $name) {

                $message->from(env('MAIL_FROM'), 'Support');

                $email = (env('APP_DEBUG') == true) ? env('MAIL_DEBUG', $email) : $email;
                $message->to($email, $name)->subject('Welcome! Your account has been activated!');
            });
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown MailerRepository@accountActivated', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);
        }
    }

    /**
     * Sends an email for password reminder
     *
     * @param string $email
     * @param string $name
     * @param string $url
     *
     * @return void
     */
    public function passwordReminder($email, $name, $url)
    {
        try {
            $data = ['email' => $email, 'name' => $name, 'url' => $url];

            Mail::send('emails.password_reset', $data, function ($message) use ($email, $name) {
                // Set email sender
                $message->from(env('MAIL_FROM'), 'Support');

                // Set email subject
                $message->subject(config('app.site_name') . '- Password Reset');

                $email = (env('APP_DEBUG') == true) ? env('MAIL_DEBUG', $email) : $email;
                $message->to($email, $name);
            });

            if (count(Mail::failures()) > 0) {
                Log::debug('Data', ['array' => Mail::failures()]);
            }
        } catch (\Exception $e) {
            Log::error(__CLASS__ . ':' . __TRAIT__ . ':' . __FILE__ . ':' . __LINE__ . ':' . __FUNCTION__ . ':' .
                'Unknown Exception thrown MailerRepository@passwordReminder', [
                'exception_type' => get_class($e),
                'message'        => $e->getMessage(),
                'code'           => $e->getCode(),
                'line_no'        => $e->getLine(),
                'params'         => func_get_args()
            ]);
        }
    }
}