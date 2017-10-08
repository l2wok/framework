<?php
/**
 * Settings - Implements a simple Administration Settings.
 *
 * @author Virgil-Adrian Teaca - virgil@giulianaeassociati.com
 * @version 3.0
 */

namespace App\Modules\System\Controllers\Admin;

use Nova\Auth\Access\AuthorizationException;
use Nova\Support\Facades\Cache;
use Nova\Support\Facades\Config;
use Nova\Support\Facades\Gate;
use Nova\Support\Facades\Input;
use Nova\Support\Facades\Redirect;
use Nova\Support\Facades\Validator;
use Nova\Support\Facades\View;

use App\Modules\System\Controllers\Admin\BaseController;
use App\Models\Option;


class Settings extends BaseController
{

    protected function validator(array $data)
    {
        // Validation rules
        $rules = array(
            // The Application.
            'siteName'        => 'required|max:100',
            'siteSkin'        => 'required|alpha_dash',

            // The Mailer
            'mailDriver'      => 'required|alpha',
            'mailHost'        => 'url',
            'mailPort'        => 'numeric',
            'mailFromAddress' => 'required|email',
            'mailFromName'    => 'required|max:100',
            'mailEncryption'  => 'alpha',
            'mailUsername'    => 'max:100',
            'mailPassword'    => 'max:100',
        );

        $attributes = array(
            // The Application.
            'siteName'        => __d('system', 'Site Name'),
            'siteSkin'        => __d('system', 'Site Skin'),

            // The Mailer
            'mailDriver'      => __d('system', 'Mail Driver'),
            'mailHost'        => __d('system', 'Server Name'),
            'mailPort'        => __d('system', 'Server Port'),
            'mailFromAddress' => __d('system', 'Mail from Adress'),
            'mailFromName'    => __d('system', 'Mail from Name'),
            'mailEncryption'  => __d('system', 'Encryption'),
            'mailUsername'    => __d('system', 'Server Username'),
            'mailPassword'    => __d('system', 'Server Password'),
        );

        return Validator::make($data, $rules, array(), $attributes);
    }

    public function index()
    {
        // Authorize the current User.
        if (Gate::denies('manage', Option::class)) {
            throw new AuthorizationException();
        }

        // Load the Options from database.
        $options = array(
            // The Application.
            'siteName'        => Input::old('siteName', Config::get('app.name')),
            'siteSkin'        => Input::old('siteSkin', Config::get('app.color_scheme')),

            // The Mailer
            'mailDriver'      => Input::old('mailDriver',      Config::get('mail.driver')),
            'mailHost'        => Input::old('mailHost',        Config::get('mail.host')),
            'mailPort'        => Input::old('mailPort',        Config::get('mail.port')),
            'mailFromAddress' => Input::old('mailFromAddress', Config::get('mail.from.address')),
            'mailFromName'    => Input::old('mailFromName',    Config::get('mail.from.name')),
            'mailEncryption'  => Input::old('mailEncryption',  Config::get('mail.encryption')),
            'mailUsername'    => Input::old('mailUsername',    Config::get('mail.username')),
            'mailPassword'    => Input::old('mailPassword',    Config::get('mail.password')),
        );

        return $this->createView()
            ->shares('title', __d('system', 'Settings'))
            ->withOptions($options);
    }

    public function store()
    {
        // Authorize the current User.
        if (Gate::denies('manage', Option::class)) {
            throw new AuthorizationException();
        }

        // Validate the Input data.
        $input = Input::all();

        $validator = $this->validator($input);

        if($validator->passes()) {
            // The Application.
            Option::set('app.name',          $input['siteName']);
            Option::set('app.color_scheme',  $input['siteSkin']);

            // The Mailer
            Option::set('mail.driver',       $input['mailDriver']);
            Option::set('mail.host',         $input['mailHost']);
            Option::set('mail.port',         $input['mailPort']);
            Option::set('mail.from.address', $input['mailFromAddress']);
            Option::set('mail.from.name',    $input['mailFromName']);
            Option::set('mail.encryption',   $input['mailEncryption']);
            Option::set('mail.username',     $input['mailUsername']);
            Option::set('mail.password',     $input['mailPassword']);

            // Invalidate the cached system options.
            Cache::forget('system_options');

            // Prepare the flash message.
            $status = __d('system', 'The Settings was successfully updated.');

            return Redirect::to('admin/settings')->withStatus($status);
        }

        // Errors occurred on Validation.
        $status = $validator->errors();

        return Redirect::back()->withInput()->withStatus($status, 'danger');
    }

}
