<?php


namespace Modules\FNB\Repositories\ProductBranchPrice;


use Illuminate\Support\Facades\Auth;
use Modules\FNB\Models\ProductBranchPriceTable;

class ProductBranchPriceRepository implements ProductBranchPriceRepositoryInterface
{
    private $productBranchPrice;

    public function __construct(ProductBranchPriceTable $productBranchPrice)
    {
        $this->productBranchPrice = $productBranchPrice;
    }

    public function getItemBranchLimit($branch, $categoryId, $search, $page){
        return $this->productBranchPrice->getItemBranchLimit($branch, $categoryId, $search, $page);
    }

    public function getItemBranchLimitMaster($branch, $categoryId, $search, $page)
    {
        return $this->productBranchPrice->getItemBranchLimitMaster($branch, $categoryId, $search, $page);
    }

    public function getItemBranchByChildId($branch, $categoryId = [], $arrChildId = [])
    {
        return $this->productBranchPrice->getItemBranchByChildId($branch, $categoryId);
    }

    public function getItemBranchByAttribute($filter = []){
        $filter['branch_id'] = Auth::user()->branch_id;
        return $this->productBranchPrice->getItemBranchByAttribute($filter);
    }

    public function getProductBranchPriceByCode($branch, $code) {
        return $this->productBranchPrice->getProductBranchPriceByCode($branch, $code);
    }
}