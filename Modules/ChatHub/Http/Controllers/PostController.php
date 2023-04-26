<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\Post\PostRepositoryInterface;
use Modules\ChatHub\Repositories\Brand\BrandRepositoryInterface;
use Modules\ChatHub\Repositories\SubBrand\SubBrandRepositoryInterface;
use Modules\ChatHub\Repositories\Sku\SkuRepositoryInterface;
use Modules\ChatHub\Repositories\Attribute\AttributeRepositoryInterface;
use Auth;


class PostController extends Controller
{
    protected $post;
    protected $brand;
    protected $sub_brand;
    protected $sku;
    protected $attribute;

    public function __construct(
        PostRepositoryInterface $post,
        BrandRepositoryInterface $brand,
        SubBrandRepositoryInterface $sub_brand,
        SkuRepositoryInterface $sku,
        AttributeRepositoryInterface $attribute
    ) {
        $this->post = $post;
        $this->brand = $brand;
        $this->sub_brand = $sub_brand;
        $this->sku = $sku;
        $this->attribute = $attribute;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $post=$this->post->getList($filters);
            return view('chathub::post.index',[
                'LIST' => $post
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $post=$this->post->getList($filters);
        return view('chathub::post.list',
            [
                'LIST' => $post,
                'page' => $filters['page']
            ]);
    }
    public function addKeyAction(Request $request){
        $data  = $request->all();
        $post = $this->post->getPost($data['id']);
        $listBrand= $this->brand->getActive();
        $listSku = $this->sku->getActive();
        $listSubBrand = $this->sub_brand->getActive();
        $listAttribute = $this->attribute->getActive();
        return view('chathub::post.key',[
            'post'=>$post,
            'listBrand'=>$listBrand,
            'listSku'=>$listSku,
            'listSubBrand'=>$listSubBrand,
            'listAttribute'=>$listAttribute
        ]);
    }
    public function updateKeyAction(Request $request){
        $data  = $request->all();
        $post = $this->post->updateKey($data['id'], $data['brand'], $data['sku'], '', '');
        return response()->json([
            'error' => false,
            'message' => __('chathub::post.update.ADD_SUCCESS')
        ]);
    }
    public function subcribeAction(Request $request){
        $data  = $request->all();
        $post = $this->post->subcribe($data['id']);
        return response()->json([
            'error' => false,
            'message' => __('chathub::post.update.ADD_SUCCESS')
        ]);
    }
    public function unsubcribeAction(Request $request){
        $data  = $request->all();
        $post = $this->post->unsubcribe($data['id']);
        return response()->json([
            'error' => false,
            'message' => __('chathub::post.update.ADD_SUCCESS')
        ]);
    }
}