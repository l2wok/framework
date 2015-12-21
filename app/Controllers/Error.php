<?php
/**
 * Error class - calls a 404 page.
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Sept 19, 2015
 */

namespace App\Controllers;

use Nova\Core\Controller;
use Nova\Core\View;

/**
 * Error class to generate 404 pages.
 */
class Error extends Controller
{
    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Load a 404 page with the error message.
     *
     * @param mixed $error
     */
    public function error404($error = null)
    {
        $data['title'] = '404';
        $data['error'] = $error;

        View::addHeader("HTTP/1.0 404 Not Found");

        View::renderView('error404', $data);
    }
}
