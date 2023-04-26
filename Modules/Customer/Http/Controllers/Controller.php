<?php
namespace Modules\Customer\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Modules\User\Http\Controllers\Controller as LaravelController;

/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 29/03/2018
 * Time: 1:22 SA
 */
class Controller extends  LaravelController
{
    use ValidatesRequests;
}