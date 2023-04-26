<?php

namespace Modules\FNB\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FNB\Http\Requests\ProductAttribute\UpdateRequest;
use Modules\FNB\Repositories\ProductAttribute\ProductAttributeRepositoryInterface;

class ProductAttributeController extends Controller
{
    private $rProductAttribute;

    public function __construct(ProductAttributeRepositoryInterface $rProductAttribute)
    {
        $this->rProductAttribute = $rProductAttribute;
    }

    /**
     * Cập nhật attribute group
     * @param Request $request
     */
    public function edit(Request $request){
        $param = $request->all();
        $data = $this->rProductAttribute->getDetail($param['id']);
        return \response()->json($data);
    }

    public function update(UpdateRequest $request){
        $param = $request->all();
        $data = $this->rProductAttribute->update($param);
        return \response()->json($data);
    }
}
