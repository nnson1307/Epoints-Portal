<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 12/1/2021
 * Time: 11:07 AM
 * @author nhandt
 */


namespace Modules\CustomerLead\Repositories\CustomerLog;


use Modules\CustomerLead\Models\CustomerLogTable;

class CustomerLogRepo implements CustomerLogRepoInterface
{
    protected $model;
    public function __construct(CustomerLogTable $model)
    {
        $this->model = $model;
    }

    public function list(array &$filters = [])
    {
        $filters['object_type'] = 'customer_lead';
        $list = $this->model->getList($filters);
        return [
            "list" => $list,
        ];
    }


    public function listLogUpdate($input)
    {
        $item = $this->model->getListLogById($input['customer_log_id'], 'customer_lead');
        $html = \View::make('customer-lead::customer-log.pop.log-update', [
            'item' => $item
        ])->render();

        return [
            'html' => $html
        ];
    }
}