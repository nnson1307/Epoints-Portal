<?php
/**
 * Created by PhpStorm.
 * User: Huniel
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\Admin\Repositories\ProductCategoryParent;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\ChenkinCollectionTable;
use Modules\Admin\Models\ProductCategoryParentTable;
use Modules\Admin\Repositories\Upload\UploadRepo;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ZNS\Models\ProvinceTable;

class ProductCategoryParentRepo implements ProductCategoryParentRepoIf
{
    protected $productCategoryParent;

    public function __construct(
        ProductCategoryParentTable $productCategoryParent
    )
    {
        $this->productCategoryParent = $productCategoryParent;
    }

    // product Category Parent
    public function getPaginate($param = [])
    {
        return $this->productCategoryParent->getPaginate($param);
    }

    public function getItem($param = [])
    {
        $result = $this->productCategoryParent->getPaginate($param + ['perpage' => '1'])->items();

        if($result){
            $data = $result[0]->toArray();
        }else{
            $data=[];
        }
        return $data;
    }

    public function actionAdd($param = [])
    {
        try {
            DB::beginTransaction();

            $data = [];

            $product_category_parent_id = $this->productCategoryParent->insertGetId($param);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];

        }

        if ($product_category_parent_id ?? false) {
            return [
                'status' => 'success',
                'success' => __('Thêm danh mục cha thành công'),
            ];
        } else {
            return false;
        }
    }

    public function actionEdit($param = [])
    {
        $product_category_parent_id = $param['product_category_parent_id'];
        unset($param['product_category_parent_id']);

        try {
            DB::beginTransaction();

            $result = $this->productCategoryParent->where(['product_category_parent_id' => $product_category_parent_id])->update($param);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];

        }

        return [
            'status' => 'success',
            'success' => __('Sửa danh mục cha thành công'),
        ];
    }


    public function actionDelete($param = [])
    {
        // check deletable

        $result = $this->productCategoryParent->where("product_category_parent_id", $param['product_category_parent_id'])->update(['is_deleted' => 1]);


        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Đã xóa danh mục cha thành công'),
            ];
        } else {
            return false;
        }

    }

}