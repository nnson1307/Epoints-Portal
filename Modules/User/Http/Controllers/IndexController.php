<?php
namespace Modules\User\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\User\Repositories\User\UserRepositoryInterface;

/**
 * User manager
 * 
 * @author isc-daidp
 * @since Feb 23, 2018
 */
class IndexController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $user;
    
    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }
    
    
    /**
     * Trang chính
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction(Request $request)
    {

        return redirect()->route('dashbroad');

//        return view('user::index.index');

    }
    

}