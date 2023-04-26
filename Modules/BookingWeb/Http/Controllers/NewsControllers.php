<?php


namespace Modules\BookingWeb\Http\Controllers;


use Illuminate\Http\Request;
use Modules\BookingWeb\Repositories\News\NewsRepositoryInterface;

class NewsControllers extends Controller
{
    protected $news;
    protected $request;
    public function __construct(NewsRepositoryInterface $news , Request $request)
    {
        $this->news = $news;
        $this->request = $request;
    }

    public function indexAction(){

    }
}