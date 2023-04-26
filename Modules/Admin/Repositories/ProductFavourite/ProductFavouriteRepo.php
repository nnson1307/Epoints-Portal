<?php
/**
 * Created by PhpStorm.
 * User: Huniel
 * Date: 05/04/2022
 * Time: 10:49
 */

namespace Modules\Admin\Repositories\ProductFavourite;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\ProductFavouriteTable;
use Modules\Admin\Repositories\Upload\UploadRepo;

use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\ZNS\Models\ProvinceTable;

class ProductFavouriteRepo implements ProductFavouriteRepoIf
{
    protected $productFavourite;

    public function __construct(
        ProductFavouriteTable $productFavourite
    )
    {
        $this->productFavourite = $productFavourite;
    }

    // product Favourite
    public function getPaginate($param = [])
    {
        return $this->productFavourite->getPaginate($param);
    }

    public function getItem($param = [])
    {
        $result = $this->productFavourite->getPaginate($param + ['perpage' => '1'])->items();

        if($result){
            $data = $result[0]->toArray();
        }else{
            $data=[];
        }
        return $data;
    }

}