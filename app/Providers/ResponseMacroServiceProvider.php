<?php
/**
 * Response Macro Service Provider
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

/**
 * Response Macro Service Provider
 *
 * *** Usage
 * ```
 * // Success API Response
 * Response::api(string|array $data, string $success_message, string $http_response_code, string|array $extra);
 *
 * // Error API Response
 * Response::error(string $error_message, string $error_message_detail, string $custom_error_code, string $http_response_code,
 * array $extra);
 * ```
 */
class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Register the Macros
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('api', function ($data, $message = 'Success', $http_response_code = 200, $extra = []) {
            return Response::json([
                "status"   => "OK",
                "message"  => $message,
                "response" => $data
            ], $http_response_code, ['Content-Type' => 'application/json'], JSON_PRETTY_PRINT);
        });

        Response::macro('error', function (
            $error_message,
            $error_message_detail,
            $custom_error_code = 'FB000001',
            $http_response_code = 500,
            $extra = []
        ) {
            $valid_http_response_code = array(
                "100",
                "101",
                "200",
                "201",
                "202",
                "203",
                "204",
                "205",
                "206",
                "300",
                "301",
                "302",
                "303",
                "304",
                "305",
                "306",
                "307",
                "400",
                "401",
                "402",
                "403",
                "404",
                "405",
                "406",
                "407",
                "408",
                "409",
                "410",
                "411",
                "412",
                "413",
                "414",
                "415",
                "416",
                "417",
                "500",
                "501",
                "502",
                "503",
                "504",
                "505"
            );

            $http_response_code = $http_response_code == 500 ? ((int)$custom_error_code > 0 ? substr($custom_error_code, 0,
                3) : 500) : $http_response_code;

            if (!in_array($http_response_code, $valid_http_response_code)) {
                $http_response_code = 500;
            }

            return Response::json([
                "status" => "FAIL",
                "error"  => [
                    "code"           => $custom_error_code,
                    "message"        => $error_message,
                    "message_detail" => (($jsonObj = json_decode($error_message_detail)) && json_last_error() == JSON_ERROR_NONE) ? $jsonObj : $error_message_detail
                ]
            ], $http_response_code, ['Content-Type' => 'application/json'], JSON_PRETTY_PRINT);
        });
    }

    /**
     * Do Nothing!
     *
     * @return void
     */
    public function register()
    {
    }
}
