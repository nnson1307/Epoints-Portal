<?php


namespace Modules\FNB\Repositories\ProductBranchPrice;


interface ProductBranchPriceRepositoryInterface
{
    public function getItemBranchLimit($branch, $categoryId, $search, $page);

    public function getItemBranchLimitMaster($branch, $categoryId, $search, $page);

    /**
     * Lấy danh sách sản phẩm
     * @param $branch
     * @param $categoryId
     * @param array $arrChildId
     * @return mixed
     */
    public function getItemBranchByChildId($branch, $categoryId = [], $arrChildId = []);

    /**
     * Lấy product child
     * @param array $filter
     * @return mixed
     */
    public function getItemBranchByAttribute($filter = []);

    public function getProductBranchPriceByCode($branch, $code);
}