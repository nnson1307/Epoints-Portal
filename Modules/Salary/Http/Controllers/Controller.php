<?php
namespace Modules\Salary\Http\Controllers;

use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends LaravelController
{
    use ValidatesRequests;
}