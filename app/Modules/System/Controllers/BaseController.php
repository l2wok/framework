<?php
/**
 * BackendController - A backend Controller for the included example Modules.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

namespace App\Modules\System\Controllers;

use Nova\Support\Facades\Auth;
use Nova\Support\Facades\Request;
use Nova\Support\Facades\View;

use App\Controllers\BaseController as Controller;
use App\Modules\System\Support\EventedMenu;


abstract class BaseController extends Controller
{
    /**
     * The currently used Theme.
     *
     * @var string
     */
    protected $theme = 'AdminLite';

    /**
     * The currently used Layout.
     *
     * @var mixed
     */
    protected $layout = 'Frontend';


    /**
     * Method executed before any action.
     */
    protected function initialize()
    {
        parent::initialize();

        //
    }
}
