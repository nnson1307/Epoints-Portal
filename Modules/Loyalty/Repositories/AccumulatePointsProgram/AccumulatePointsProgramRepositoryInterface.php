<?php

namespace Modules\Loyalty\Repositories\AccumulatePointsProgram;

interface AccumulatePointsProgramRepositoryInterface
{
    /**
     * get list visibility rule
     * @param array $filters
     * @return mixed
     */
    public function getList(array $filters = []);

    /**
     * get list survey
     * @return mixed
     */
    public function getListSurvey();

    /**
     * get all list rank
     * @return mixed
     */

    public function getAllRank();

    /**
     * store loyalty 
     * @param $params
     * @return mixed
     */

    public function store($params);

    /**
     * get item program
     * @param $idProgram
     * @return mixed
     */

    public function findItem($idProgram);

    /**
     * update item program 
     * @param $params
     * @return mixed
     */

    public function update($params);

    /**
     * show config notification 
     * @return mixed
     */

    public function showConfigNotification();

    /**
     * Cập nhật cấu hình thông báo 
     * @param $params
     * @return mixed
     */

    public function updateSettingNotification($params);

    /**
     * Show item xoá tích điểm
     * @param int $idLoyalty
     * @return mixed
     */

    public function showItemDestroy($idLoyalty);

    /**
     * destroy loyalty 
     * @param $request
     * @return mixed
     */

     public function destroy($idLoyalty);
     
}
