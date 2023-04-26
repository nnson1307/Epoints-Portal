<?php

/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/29/2018
 * Time: 10:12 AM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class OrderDetailTable extends Model
{
    use ListTableTrait;
    protected $table = "order_details";
    protected $primaryKey = "order_detail_id";
    protected $fillable = [
        'order_detail_id',
        'order_id',
        'object_id',
        'object_name',
        'object_type',
        'object_code',
        'price',
        'quantity',
        'discount',
        'amount',
        'voucher_code',
        'staff_id',
        'refer_id',
        'updated_at',
        'created_at',
        'created_by',
        'updated_by',
        'is_deleted',
        'quantity_type',
        'case_quantity',
        'saving',
        'is_change_price',
        'is_check_promotion',
        "order_detail_id_parent",
        "created_at_day",
        "created_at_month",
        "created_at_year",
        "delivery_date",
        "note"
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->order_detail_id;
    }

    public function edit(array $data, $id)
    {
        return $this->where('order_detail_id', $id)->update($data);
    }

    /**
     * @param $id
     */
    public function getItem($id)
    {
        $urlImage = asset('/static/backend/images/default-placeholder.png');

        $ds = $this
            ->select(
                'order_details.order_detail_id as order_detail_id',
                'order_details.object_id as object_id',
                'order_details.object_name as object_name',
                'order_details.object_type as object_type',
                'order_details.object_code as object_code',
                'order_details.price as price',
                'order_details.quantity as quantity',
                'order_details.discount as discount',
                'order_details.amount as amount',
                'order_details.voucher_code as voucher_code',
                'order_details.refer_id',
                'order_details.staff_id',
                "{$this->table}.is_change_price",
                "{$this->table}.is_check_promotion",
                "orders.order_code",
                "{$this->table}.tax",
                'product_childs.inventory_management',
                'staffs.full_name',
                DB::raw("(CASE
                    WHEN  {$this->table}.object_type = 'product' && products.avatar IS NOT NULL THEN products.avatar
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NOT NULL THEN services.service_avatar
                    WHEN  {$this->table}.object_type = 'service_card' && service_cards.image IS NOT NULL THEN service_cards.image
                    
                    WHEN  {$this->table}.object_type = 'product' && products.avatar IS NULL THEN '{$urlImage}'
                    WHEN  {$this->table}.object_type = 'service' && services.service_avatar IS NULL THEN '{$urlImage}'
                    WHEN  {$this->table}.object_type = 'service_card' && service_cards.image IS NULL THEN '{$urlImage}'
                       
                    WHEN  {$this->table}.object_type = 'product_gift' && products.avatar IS NOT NULL THEN products.avatar
                    WHEN  {$this->table}.object_type = 'service_gift' && services.service_avatar IS NOT NULL THEN services.service_avatar
                    WHEN  {$this->table}.object_type = 'service_card_gift' && service_cards.image IS NOT NULL THEN service_cards.image
                    
                    WHEN  {$this->table}.object_type = 'product_gift' && products.avatar IS NULL THEN '{$urlImage}'
                    WHEN  {$this->table}.object_type = 'service_gift' && services.service_avatar IS NULL THEN '{$urlImage}'
                    WHEN  {$this->table}.object_type = 'service_card_gift' && service_cards.image IS NULL THEN '{$urlImage}'
                    
                    END
                ) as object_image"),
                "{$this->table}.order_detail_id_parent",
                "{$this->table}.delivery_date",
                "{$this->table}.note"
            )
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
            ->leftJoin("products", "products.product_id", "=", "product_childs.product_id")
            ->leftJoin("services", "services.service_code", "=", "{$this->table}.object_code")
            ->leftJoin("service_cards", "service_cards.code", "=", "{$this->table}.object_code")
            ->leftJoin('staffs', 'staffs.staff_id', '=', "{$this->table}.staff_id")
            ->where('orders.order_id', $id)
            ->where('order_details.is_deleted', 0)
            ->get();
        return $ds;
    }

    /**
     * @param $id_order
     * @return mixed
     */
    public function remove($id_order)
    {
        return $this->where('order_id', $id_order)->delete();
    }

    //Lấy dữ liệu theo năm và objectType
    public function getValueByYearAndObjectType($year, $objectType)
    {
        $select = $this
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'orders.order_id as order_id',
                'order_details.object_id as object_id',
                'order_details.order_detail_id as order_detail_id',
                'order_details.amount as amount',
                'orders.process_status as process_status',
                'order_details.quantity as quantity',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->whereYear('order_details.created_at', $year)->get()->toArray();
        return $select;
    }

    //Lấy dữ liệu theo từ ngày đến ngày và objectType
    public function getValueByDateAndObjectType($startTime, $endTime, $objectType)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'orders.order_id as order_id',
                'order_details.order_detail_id as order_detail_id',
                'order_details.amount as amount',
                'orders.process_status as process_status',
                'order_details.quantity as quantity',
                'order_details.object_id as object_id',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if (Auth::user()->is_admin != 1) {
            $select->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $select->get()->toArray();
    }

    //Lấy ra số tiền của ngày theo objectType
    public function getAmountByDateAndObjectType($date, $objectType)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(DB::raw('sum(order_details.amount) as amount'))
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->where('orders.process_status', 'paysuccess')
            ->whereBetween('order_details.created_at', [$date . " 00:00:00", $date . " 23:59:59"])
            ->get()->toArray();
        return $select;
    }

    //Lấy dịch vụ theo chi nhánh theo năm hoặc từng tháng
    public function fetchServiceByBranch($branch, $year, $month)
    {
        $select = null;
        if ($month != null) {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
                ->select(
                    'services.service_name as service_name',
                    //                'order_details.amount as amount',
                    'order_details.object_id as object_id',
                    'orders.process_status as process_status',
                    'order_details.amount as amount',
                    'order_details.object_type as object_type',
                    DB::raw('sum(order_details.amount) as totalAmount'),
                    'order_details.created_at as created_at'
                )
                ->where('orders.is_deleted', 0)
                ->where('orders.branch_id', $branch)
                ->where('order_details.is_deleted', 0)
                ->where('orders.process_status', 'paysuccess')
                ->whereYear('order_details.created_at', $year)
                ->where('order_details.object_type', 'service')
                ->whereMonth('order_details.created_at', '=', $month)
                ->groupBy('order_details.object_id');
        } else {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
                ->select(
                    'services.service_name as service_name',
                    //                'order_details.amount as amount',
                    'order_details.object_id as object_id',
                    'orders.process_status as process_status',
                    'order_details.amount as amount',
                    'order_details.object_type as object_type',
                    DB::raw('sum(order_details.amount) as totalAmount'),
                    'order_details.created_at as created_at'
                )
                ->where('orders.is_deleted', 0)
                ->where('orders.branch_id', $branch)
                ->where('order_details.is_deleted', 0)
                ->where('orders.process_status', 'paysuccess')
                ->whereYear('order_details.created_at', $year)
                ->where('order_details.object_type', 'service')
                ->groupBy('order_details.object_id');
        }

        return $select->get()->toArray();
    }

    //Truy vấn để lấy tổng số lượng báo cáo doanh thu theo chi nhánh và năm.
    public function fetchTotalBranchService($branch, $year)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.process_status as process_status',
                'order_details.amount as amount',
                'order_details.object_type as object_type',
                'order_details.created_at as created_at',
                'order_details.quantity as quantity'
            )
            ->where('orders.is_deleted', 0)
            ->where('orders.branch_id', $branch)
            ->where('order_details.is_deleted', 0)
            ->whereYear('order_details.created_at', $year)
            ->where('order_details.object_type', 'service')
            ->get()->toArray();
        return $select;
    }

    //Lấy dịch vụ theo nhóm dịch vụ theo năm hoặc từng tháng
    public function fetchServiceByServiceCategory($serviceCategory, $year, $month)
    {
        $select = null;
        if ($month == null) {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
                ->leftJoin('service_categories', 'service_categories.service_category_id', '=', 'services.service_category_id')
                ->select(
                    'services.service_name as service_name',
                    'order_details.object_id as object_id',
                    'orders.process_status as process_status',
                    'order_details.amount as amount',
                    'order_details.object_type as object_type',
                    DB::raw('sum(order_details.amount) as totalAmount'),
                    'order_details.created_at as created_at'
                )
                ->where('service_categories.service_category_id', $serviceCategory)
                ->where('orders.is_deleted', 0)
                ->where('order_details.is_deleted', 0)
                ->where('orders.process_status', 'paysuccess')
                ->whereYear('order_details.created_at', $year)
                ->where('order_details.object_type', 'service')
                ->groupBy('order_details.object_id');
        } else {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
                ->leftJoin('service_categories', 'service_categories.service_category_id', '=', 'services.service_category_id')
                ->select(
                    'services.service_name as service_name',
                    'order_details.object_id as object_id',
                    'orders.process_status as process_status',
                    'order_details.amount as amount',
                    'order_details.object_type as object_type',
                    DB::raw('sum(order_details.amount) as totalAmount'),
                    'order_details.created_at as created_at'
                )
                ->where('service_categories.service_category_id', $serviceCategory)
                ->where('orders.is_deleted', 0)
                ->where('order_details.is_deleted', 0)
                ->where('orders.process_status', 'paysuccess')
                ->whereYear('order_details.created_at', $year)
                ->whereMonth('order_details.created_at', '=', $month)
                ->where('order_details.object_type', 'service')
                ->groupBy('order_details.object_id');
        }
        return $select->get()->toArray();
    }

    //Truy vấn để lấy tổng số lượng báo cáo doanh thu theo nhóm dịch vụ và năm.
    public function fetchTotalServiceCategory($serviceCategory, $year)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
            ->leftJoin('service_categories', 'service_categories.service_category_id', '=', 'services.service_category_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.process_status as process_status',
                'order_details.amount as amount',
                'order_details.object_type as object_type',
                'order_details.created_at as created_at',
                'order_details.quantity as quantity'
            )
            ->where('orders.is_deleted', 0)
            ->where('service_categories.service_category_id', $serviceCategory)
            ->where('order_details.is_deleted', 0)
            ->whereYear('order_details.created_at', $year)
            ->where('order_details.object_type', 'service')
            ->get()->toArray();
        return $select;
    }

    //Lấy dữ liệu từ ngày tới ngày, objectType và chi nhánh.
    public function getValueByDateObjectTypeBranch($startTime, $endTime, $objectType, $branch, $processStatus)
    {
        if (Auth::user()->is_admin != 1) {
            $branch = Auth::user()->branch_id;
        }
        $select = null;
        if ($processStatus == null) {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
                ->select(
                    'order_details.object_id as object_id',
                    'services.service_name as service_name',
                    'orders.order_id as order_id',
                    'order_details.order_detail_id as order_detail_id',
                    'order_details.amount as amount',
                    'orders.process_status as process_status',
                    'order_details.quantity as quantity',
                    'order_details.created_at as created_at'
                )
                ->where('orders.is_deleted', 0)
                ->where('order_details.is_deleted', 0)
                ->where('order_details.object_type', $objectType)
                ->where('orders.branch_id', $branch)
                ->where('orders.process_status', 'paysuccess')
                ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        } else {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->select(
                    'order_details.object_id as object_id',
                    'orders.order_id as order_id',
                    'order_details.order_detail_id as order_detail_id',
                    'order_details.amount as amount',
                    'orders.process_status as process_status',
                    'order_details.quantity as quantity',
                    'order_details.created_at as created_at'
                )
                ->where('orders.is_deleted', 0)
                ->where('order_details.is_deleted', 0)
                ->where('order_details.object_type', $objectType)
                ->where('orders.branch_id', $branch)
                ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        }

        return $select->get()->toArray();
    }

    //Lấy ra số tiền của ngày theo objectType và chi nhánh
    public function getAmountByDateObjectTypeBranch($date, $objectType, $branch)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(DB::raw('sum(order_details.amount) as amount'))
            ->where('orders.is_deleted', 0)
            ->where('orders.branch_id', $branch)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->where('orders.process_status', 'paysuccess')
            ->whereBetween('order_details.created_at', [$date . " 00:00:00", $date . " 23:59:59"])
            ->get()->toArray();
        return $select;
    }

    //Báo cáo doanh thu dịch vụ, sản phẩm, thẻ dịch vụ: Lấy tất cả $objectType theo năm.
    public function fetchValueAllServiceByYear($year, $objectType)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.process_status as process_status',
                'order_details.amount as amount',
                'orders.branch_id as branch_id'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('services.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->whereYear('order_details.created_at', $year);
        return $select->get()->toArray();
    }

    //Lấy ra dữ liệu theo năm và $objectType và $objectId.
    public function fetchValueYearObjTypeObjId($year, $objectType, $objectId)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.process_status as process_status',
                'order_details.amount as amount',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->where('order_details.object_id', $objectId)
            ->whereYear('order_details.created_at', $year);
        return $select->get()->toArray();
    }

    //Lấy ra dữ liệu theo năm, chi nhánh, $objectType và $objectId.
    public function fetchValueYearBranchObjTypeObjId($year, $branch, $objectType, $objectId)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.process_status as process_status',
                'order_details.amount as amount',
                'orders.branch_id as branch_id',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('orders.branch_id', $branch)
            ->where('order_details.object_type', $objectType)
            ->where('order_details.object_id', $objectId)
            ->whereYear('order_details.created_at', $year);
        return $select->get()->toArray();
    }

    //Lấy dữ liệu theo từ ngày đến ngày, $objectType và $objectId.
    public function getValueByDateObjectTypeObjectId($startTime, $endTime, $objectType, $objectId)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'orders.order_id as order_id',
                'order_details.order_detail_id as order_detail_id',
                'order_details.amount as amount',
                'orders.process_status as process_status',
                'order_details.quantity as quantity',
                'order_details.object_id as object_id',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->where('order_details.object_id', $objectId)
            ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->get()->toArray();
        return $select;
    }

    //Lấy giá trị theo ngày, chi nhánh, $objectType, $objectId.
    public function getValueByDate($date, $branch, $objectType, $objectId)
    {
        $select = null;
        if ($branch != null) {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->select('order_details.amount as amount')
                ->where('orders.is_deleted', 0)
                ->where('order_details.is_deleted', 0)
                ->where('order_details.object_type', $objectType)
                ->where('order_details.object_id', $objectId)
                ->where('orders.branch_id', $branch)
                ->where('orders.process_status', 'paysuccess')
                ->whereBetween('order_details.created_at', [$date . " 00:00:00", $date . " 23:59:59"]);
        } else {
            $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
                ->select('order_details.amount as amount')
                ->where('orders.is_deleted', 0)
                ->where('order_details.is_deleted', 0)
                ->where('order_details.object_type', $objectType)
                ->where('order_details.object_id', $objectId)
                ->where('orders.process_status', 'paysuccess')
                ->whereBetween('order_details.created_at', [$date . " 00:00:00", $date . " 23:59:59"]);
        }
        return $select->get()->toArray();
    }

    //Lấy dữ liệu theo từ ngày đến ngày, chi nhánh, $objectType, $objectId.
    public function fetchValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType, $objectId)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('services', 'services.service_id', '=', 'order_details.object_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.branch_id as branch_id',
                'services.service_name as service_name',
                'orders.order_id as order_id',
                'order_details.order_detail_id as order_detail_id',
                'order_details.amount as amount',
                'orders.process_status as process_status',
                'order_details.quantity as quantity',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->where('order_details.object_id', $objectId)
            ->where('orders.branch_id', $branch)
            ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        return $select->get()->toArray();
    }

    //Lấy ra dữ liệu theo năm, chi nhánh, $objectType.
    public function fetchValueYearBranchObjType($year, $branch, $objectType)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.process_status as process_status',
                'order_details.amount as amount',
                'orders.branch_id as branch_id',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('orders.branch_id', $branch)
            ->where('order_details.object_type', $objectType)
            ->whereYear('order_details.created_at', $year);
        return $select->get()->toArray();
    }

    //Lấy ra số lượng của năm theo objectType và/hoặc chi nhánh.
    public function getQuantityByYearObjectTypeBranch($year, $objectType, $branch)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'orders.branch_id as branch_id',
                'orders.customer_id as customer_id',
                'order_details.created_at as created_at'

            );
        if ($branch != null) {
            $select->where('orders.branch_id', $branch);
        }
        $select->where('orders.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->whereYear('order_details.created_at', $year)
            ->whereYear('orders.created_at', $year);
        return $select->get()->toArray();
    }

    //Thống kê: Lấy ra số lượng của objectType theo từ ngày đến ngày và/hoặc $objectId.
    public function getQuantityByObjectTypeTime($objectType, $objectId, $startTime, $endTime, $branch)
    {
        if (Auth::user()->is_admin != 1) {
            $branch = Auth::user()->branch_id;
        }
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'orders.customer_id as customer_id',
                'orders.branch_id as branch_id',
                'order_details.created_at as created_at'
            );
        $select->where('orders.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($objectId != null) {
            $select->where('order_details.object_id', $objectId);
        }
        if ($branch != null) {
            $select->where('orders.branch_id', $branch);
        }
        return $select->get()->toArray();
    }

    //Thống kê: Lấy ra số lượng của objectType và $objectId theo tháng.
    public function getQuantityByObjectTypeObjectIdMonth($objectType, $objectId, $year, $month)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'orders.customer_id as customer_id',
                'orders.branch_id as branch_id',
                'order_details.created_at as created_at'
            );
        $select->where('orders.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.is_deleted', 0)
            ->where('order_details.object_type', $objectType)
            ->where('order_details.object_id', $objectId)
            ->whereYear('order_details.created_at', '=', $year)
            ->whereMonth('order_details.created_at', '=', $month);
        return $select->get()->toArray();
    }

    //Lấy ra dữ liệu theo năm và $objectType và $objectId (process_status = paysuccess).
    public function getValueYearObjTypeObjId($year, $objectType, $objectId)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'orders.customer_id as customer_id',
                'orders.branch_id as branch_id',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.object_type', $objectType)
            ->where('order_details.object_id', $objectId)
            ->whereYear('order_details.created_at', $year);
        return $select->get()->toArray();
    }

    //Lấy dữ liệu theo năm và objectType (process_status = paysuccess ).
    public function getValueByYearAndObjectTypePaysuccess($year, $objectType)
    {
        $select = $this
            ->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'orders.customer_id as customer_id',
                'order_details.quantity as quantity',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.object_type', $objectType)
            ->whereYear('order_details.created_at', $year)->get()->toArray();
        return $select;
    }

    //Lấy dữ liệu theo từ ngày đến ngày, chi nhánh, $objectType,$objectId.
    public function getValueByTimeBranchObjTypeObjId($startTime, $endTime, $branch, $objectType)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.object_type', $objectType)
            ->where('orders.branch_id', $branch)
            ->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        return $select->get()->toArray();
    }

    //Lấy objectType theo chi nhánh và từng tháng của năm
    public function getValueObjTypeByBranchMonth($objectType, $branch, $year, $month)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('orders.branch_id', $branch)
            ->where('order_details.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->whereYear('order_details.created_at', $year)
            ->whereMonth('order_details.created_at', $month)
            ->where('order_details.object_type', $objectType);
        return $select->get()->toArray();
    }

    //Lấy số lượng thẻ dịch vụ đã bán, chi nhánh.
    public function getServiceCardByAllBranch($keyWord, $status, $cardType, $cardGroup, $detail)
    {
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->leftJoin('service_cards', 'service_cards.service_card_id', '=', 'order_details.object_id')
            ->leftJoin('service_card_groups', 'service_card_groups.service_card_group_id', '=', 'service_cards.service_card_group_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'orders.branch_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->select(
                'order_details.object_id as object_id',
                'service_cards.name as name',
                'order_details.quantity as quantity',
                'orders.branch_id as branch_id',
                'service_cards.service_card_type as service_card_type',
                'service_cards.price as price',
                'service_card_groups.service_card_group_id as service_card_group_id',
                'order_details.object_code as service_code',
                'service_cards.is_actived as is_actived',
                'branches.branch_name as branch_name',
                'customers.full_name as full_name',
                'order_details.created_at as created_at'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('service_card_groups.is_deleted', 0)
            ->where('service_cards.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess')
            ->where('order_details.object_type', 'service_card');
        if ($keyWord != null) {
            $select->where('service_cards.name', 'like', '%' . $keyWord . '%');
        }
        if ($status != null) {
            $select->where('service_cards.is_actived', $status);
        }
        if ($cardType != null) {
            $select->where('service_cards.service_card_type', $cardType);
        }
        if ($cardGroup != null) {
            $select->where('service_cards.service_card_group_id', $cardGroup);
        }
        if ($detail != null) {
            $select->where('order_details.object_id', $detail);
        }
        return $select->get()->toArray();
    }

    //Lấy dữ liệu chi tiết hóa đơn theo order_id và object_type
    public function getValueByOrderIdAndObjectType($orderId, $objectType)
    {
        $select = $this->select('order_id', 'object_id', 'object_type', 'object_code', 'price', 'quantity', 'discount', 'amount')
            ->where('order_id', $orderId)
            ->where('object_type', $objectType)->get();
        return $select;
    }

    public function getAll($startTime, $endTime, $branch)
    {
        if (Auth::user()->is_admin != 1) {
            $branch = Auth::user()->branch_id;
        }
        $select = $this->leftJoin('orders', 'orders.order_id', '=', 'order_details.order_id')
            ->select(
                'order_details.object_id as object_id',
                'order_details.quantity as quantity',
                'order_details.created_at as created_at',
                'order_details.voucher_code as voucher_code',
                'order_details.discount as discount',
                'order_details.object_code as object_code',
                'order_details.amount as amount',
                'order_details.price as price',
                'order_details.object_type as object_type'
            )
            ->where('orders.is_deleted', 0)
            ->where('order_details.is_deleted', 0)
            ->where('orders.process_status', 'paysuccess');
        if ($branch != null) {
            $select->where('orders.branch_id', $branch);
        }
        $select->whereBetween('order_details.created_at', [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        return $select->get();
    }

    public function getObjectByCustomer($customerId, $objectType)
    {
        $ds = $this->select(
            'order_details.order_id as order_id',
            'order_details.object_id as object_id',
            'order_details.object_code as object_code',
            'order_details.object_name as object_name',
            'order_details.object_type as object_type',
            'order_details.quantity as quantity',
            'order_details.amount as amount',
            'order_details.price as price',
            'orders.customer_id as customer_id'
        )
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->where("order_details.object_type", $objectType)
            ->where("orders.customer_id", $customerId)
            ->whereIn("orders.process_status", ["paysuccess", "pay-half"])
            ->get();
        return $ds;
    }


    /**
     * số lần sử dụng voucher ở chi tiết đơn hàng
     *
     * @param $customerId
     * @param $voucherCode
     * @return mixed
     */
    public function getOrderDetailOfCustomerUsingVoucherCode($customerId, $voucherCode)
    {
        $select = $this->select(
            'orders.order_id',
            'orders.customer_id'
        )
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->where('orders.customer_id', $customerId)
            ->where('order_details.voucher_code', $voucherCode)
            ->whereNotIn('orders.process_status', ['ordercancle']);
        return $select->get();
    }

    /**
     * Lấy tổng số lượng của đơn hàng
     */
    public function getTotalQuantity($order_id, $productCode)
    {
        $oSelect =  $this
            ->select(
                DB::raw("SUM(quantity) as total_quantity")
            )
            ->where('order_id', $order_id)
            ->where('object_code', $productCode)
            ->first();

        if ($oSelect == null) {
            return 0;
        } else {
            return $oSelect['total_quantity'];
        }
    }

    /**
     * Lấy danh sách sản phẩm so sánh tồn kho
     * @param $order_id
     */
    public function getListProductCheck($order_id)
    {
        return $this
            ->select(
                'product_childs.product_child_name',
                $this->table . '.object_code as product_child_code',
                $this->table . '.quantity',
                'product_inventorys.quantity as product_quantity'
            )
            ->join('product_inventorys', 'product_inventorys.product_id', $this->table . '.object_id')
            ->join('product_childs', 'product_childs.product_child_id', $this->table . '.object_id')
            ->where($this->table . '.order_id', $order_id)
            ->where($this->table . '.object_type', 'product')
            ->where('product_childs.inventory_management', 'serial')
            ->get();
    }
}
