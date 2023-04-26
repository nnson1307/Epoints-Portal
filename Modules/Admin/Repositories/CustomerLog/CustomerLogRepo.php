<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 12/1/2021
 * Time: 11:07 AM
 * @author nhandt
 */


namespace Modules\Admin\Repositories\CustomerLog;


use Modules\Admin\Models\CustomerLogTable;

class CustomerLogRepo implements CustomerLogRepoInterface
{
    protected $model;
    public function __construct(CustomerLogTable $model)
    {
        $this->model = $model;
    }

    public function list(array &$filters = [])
    {
        $filters['object_type'] = 'customer';
        $list = $this->model->getList($filters);
        return [
            "list" => $list,
        ];
    }


    public function listLogUpdate($input)
    {
        $item = $this->model->getListLogById($input['customer_id'], 'customer');
        $html = \View::make('admin::customer.customer-log.pop.log-update', [
            'item' => $item
        ])->render();

        return [
            'html' => $html
        ];
    }
}