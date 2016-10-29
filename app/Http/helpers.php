<?php
/**
 * Class helpers
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */


/**
 * Class helpers
 */
class helpers
{
    /**
     * Checks if user is logged in
     *
     * @return boolean
     */
    public static function isLoggedIn()
    {
        $check_session = Session::get('logged_in_user');
        if (empty($check_session)) {
            return false;
        }

        return true;
    }

    /**
     * Save the logged in user info
     *
     * @param array $params
     * @return void
     */
    public static function saveUser($params)
    {
        Session::put('logged_in_user', $params);
    }

    /**
     * Get user data from existing session
     *
     * @return mixed
     */
    public static function getUser()
    {
        $check_session = Session::get('logged_in_user');
        if (empty($check_session)) {
            return false;
        }

        return Session::get('logged_in_user');
    }

    /**
     * Prepare redirect to "not found" page url
     *
     * @return string
     */
    public static function getNotFoundPageURL()
    {
        return 'not-found';
    }

    /**
     * Get general error message (or) field error message
     *
     * @param string $message
     * @return string
     */
    public static function errorMessage($message)
    {
        $return_message = ["message" => "", "error" => ""];
        if(($jsonObj = json_decode($message, true)) && json_last_error() == JSON_ERROR_NONE) {
            $return_message['message']  = '';
            $return_message['error']    = $jsonObj;

            return $return_message;
        }

        $return_message['message'] = $message;

        return $return_message;
    }
}