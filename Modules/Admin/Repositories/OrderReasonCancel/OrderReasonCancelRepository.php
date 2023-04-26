<?php
/**
 *Use Repository
 * @author ledangsinh
 * @since March 20, 2018
 */

namespace Modules\Admin\Repositories\OrderReasonCancel;

use Modules\Admin\Models\OrderReasonCancelTable;

class OrderReasonCancelRepository implements OrderReasonCancelRepositoryInterface
{
    /**
     * @var OrderReasonCancelTable
     */
    protected $orderReasonCancel;
    protected $timestamps = true;

    public function __construct(OrderReasonCancelTable $orderReasonCancel)
    {
        $this->orderReasonCancel = $orderReasonCancel;
    }

    /**
     * Get list order reason cancel
     */
    public function list(array $filters = [])
    {
        return $this->orderReasonCancel->getList($filters);
    }

    /**
     * Get item order reason cancel
     */
    public function getItem($id)
    {
        return $this->orderReasonCancel->getItem($id);
    }

    /**
     * Add order reason cancel
     */
    public function add(array $data)
    {
        return $this->orderReasonCancel->add($data);
    }

    /**
     * Remove order reason cancel
     */
    public function remove($id)
    {
        return $this->orderReasonCancel->remove($id);
    }

    /**
     * Edit order reason cancel
     */
    public function edit(array $data, $id)
    {
        try {
            if ($this->orderReasonCancel->edit($data, $id) === false) {
                throw new \Exception();
            }
        } catch (\Exception $ex) {
            $ex->getMessage();
        }
        return false;
    }
}