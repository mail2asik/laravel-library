<?php
/**
 * Application Router Loader
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

$files = File::allFiles(app_path('/Http/Routes'));

foreach ($files as $file) {
    require $file;
}