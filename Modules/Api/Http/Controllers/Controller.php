<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use MyCore\Http\Response\ResponseFormatTrait;

class Controller extends BaseController
{
    use ValidatesRequests, ResponseFormatTrait;
}
