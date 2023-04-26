<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\FNB\Http\Requests\Promotion\UpdateRequest;
use Modules\FNB\Repositories\PromotionMaster\PromotionMasterRepositoryInterface;

class PromotionController extends Controller
{
    private $promotionMaster;

    public function __construct(PromotionMasterRepositoryInterface $promotionMaster)
    {
        $this->promotionMaster = $promotionMaster;
    }

    /**
     * Giao diện chỉnh sửa tên tiếng anh của promotion
     */
    public function edit($promotionId){
        $data = $this->promotionMaster->dataEdit($promotionId);

        return view('fnb::promotion.edit', ['item'=> $data]);
    }

    public function update(UpdateRequest $request){
        $param = $request->all();
        $data = $this->promotionMaster->update($param);
        return \response()->json($data);
    }
}
