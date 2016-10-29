<?php
/**
 * RuleTrait
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */


namespace App\Repositories;

/**
 * Trait RuleTrait
 *
 * Allows Repositories to fetch rules and validate them.
 *
 * ### Usage
 *
 * ```
 */
trait RuleTrait
{
    /**
     * Paginate the given data
     *
     * @param string $action the name of the action to check with
     * @param array|object $data the data to verify with
     *
     * @return mixed
     */
    public function verifyRules($action, $data)
    {
        //
    }
}
