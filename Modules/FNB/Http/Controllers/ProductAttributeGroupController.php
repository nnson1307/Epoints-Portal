<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FNB\Http\Requests\ProductAttributeGroup\UpdateRequest;
use Modules\FNB\Models\ProductChildTable;
use Modules\FNB\Repositories\Product\ProductRepositoryInterface;
use Modules\FNB\Repositories\ProductAttributeGroup\ProductAttributeGroupRepositoryInterface;
use Modules\FNB\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\FNB\Repositories\ProductTopping\ProductToppingRepositoryInterface;
use Modules\FNB\Repositories\PromotionMaster\PromotionMasterRepositoryInterface;

class ProductAttributeGroupController extends Controller
{
    private $rProductAttributeGroup;

    public function __construct(ProductAttributeGroupRepositoryInterface $rProductAttributeGroup)
    {
        $this->rProductAttributeGroup = $rProductAttributeGroup;
    }

    /**
     * Cập nhật attribute group
     * @param Request $request
     */
    public function edit(Request $request){
        $param = $request->all();
        $data = $this->rProductAttributeGroup->getDetail($param['id']);
        return \response()->json($data);
    }

    public function update(UpdateRequest $request){
        $param = $request->all();
        $data = $this->rProductAttributeGroup->update($param);
        return \response()->json($data);
    }
}
