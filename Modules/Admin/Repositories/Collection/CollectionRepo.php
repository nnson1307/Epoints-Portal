<?php
/**
 * Created by PhpStorm.
 * User: Huniel
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\Admin\Repositories\Collection;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\ChenkinCollectionTable;
use Modules\Admin\Repositories\Upload\UploadRepo;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ZNS\Models\ProvinceTable;

class CollectionRepo implements CollectionRepoIf
{
    protected $collection;

    public function __construct(
        ChenkinCollectionTable $collection
    )
    {
        $this->collection = $collection;
    }

    // collection
    public function getPaginate($param = [])
    {
        return $this->collection->getPaginate($param);
    }

    public function getItem($param = [])
    {
        $result = $this->collection->getPaginate($param + ['perpage' => '1'])->items();

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

            $checkin_collection_id = $this->collection->insertGetId($param);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
            ];

        }

        if ($checkin_collection_id ?? false) {
            return [
                'status' => 'success',
                'success' => __('Thêm collection thành công'),
            ];
        } else {
            return false;
        }
    }

    public function actionEdit($param = [])
    {
        $checkin_collection_id = $param['checkin_collection_id'];
        unset($param['checkin_collection_id']);

        try {
            DB::beginTransaction();

            $result = $this->collection->where(['checkin_collection_id' => $checkin_collection_id])->update($param);


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
            'success' => __('Sửa collection thành công'),
        ];
    }


    public function actionDelete($param = [])
    {
        // check deletable

        $result = $this->collection->where("checkin_collection_id", $param['checkin_collection_id'])->update(['is_deleted' => 1]);


        if ($result) {
            return [
                'status' => 'success',
                'success' => __('Đã xóa collection thành công'),
            ];
        } else {
            return false;
        }

    }

}