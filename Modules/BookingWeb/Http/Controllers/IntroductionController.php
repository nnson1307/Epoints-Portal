<?php


namespace Modules\BookingWeb\Http\Controllers;


use Illuminate\Http\Request;
use Modules\BookingWeb\Repositories\Introduction\IntroductionRepositoryInterface;

class IntroductionController extends Controller
{
    protected $introduction;
    protected $requuest;
    public function __construct(IntroductionRepositoryInterface $introduction, Request $request)
    {
        $this->introduction = $introduction;
        $this->requuest = $request;
    }

    public function indexAction(){
        $param = $this->requuest->all();
        $data = $this->introduction->getInfo($param)['Result']['Data'][0];
        return view('bookingweb::introduction.index', [
            'introduction' => $data
        ]);
    }
}