<?php


namespace Modules\Loyalty\Http\Api;

use MyCore\Api\ApiAbstract;

class MyStoreQueue extends ApiAbstract
{
    /**
     * Gọi queue import outlet.
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function queueExportOutlet(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/outlet', $data, false);
    }

    public function queueExportOutletDetail(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/outlet-detail', $data, false);
    }

    public function queueExportOutletReward(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/outlet-reward', $data, false);
    }

    public function queueResetRank(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/reset-rank', $data, false);
    }

    public function queueUpdateRank(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/update-rank', $data, false);
    }

    public function queueManagePoint(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/manage-point', $data, false);
    }

    public function insertAdjustment( array $data = [])
    {
        return $this->baseClient('/brandapi/loy-adjustment/insert-adjustment', $data, false);
    }

    public function removeAdjustmentBackOffice( array $data = [])
    {
        return $this->baseClient('/brandapi/loy-adjustment/remove-adjustment', $data, false);
    }

    public function queueExportGameLog(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/game-log', $data, false);
    }

    public function releaseOrder(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/order/release-program', $data, false);
    }

    public function addOutletToOrder(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/order/add-outlet', $data, false);
    }

    public function exportTabBudgetDetail(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/budget-detail', $data, false);
    }

    public function exportTabOrderBudget(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/order-budget', $data, false);
    }

    public function exportOrderListLoyalty(array $data = [])
    {
        return $this->baseClientMyStoreQueue('/loyalty/export/order-list-loyalty', $data, false);
    }
}
