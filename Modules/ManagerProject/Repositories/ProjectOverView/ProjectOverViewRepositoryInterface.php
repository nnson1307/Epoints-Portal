<?php
namespace Modules\ManagerProject\Repositories\ProjectOverView;

interface ProjectOverViewRepositoryInterface
{
    /**
     * danh sách phòng ban
     * @return mixed
     */
    public function getDepartment();

    /**
     * danh sách trạng thái dự án
     * @return mixed
     */
    public function getStatus();

    /**
     * Danh sách quản trị viên dự án
     * @return mixed
     */
    public function getManager();

    /**
     * Danh sách thành viên dự án
     * @return mixed
     */
    public function getStaffProject();

    /**
     * trạng thái dự án trong kì
     * @param $input
     * @return mixed
     */
    public function overViewProjects($input);
    /**
     * @param $input
     * @return mixed
     */
    public function dataAllProject($input);

    /**
     * chart trạng thái
     * @param $input
     * @return mixed
     */
    public function chartStatus($input);

    /**
     * chart rui ro
     * @param $input
     * @return mixed
     */
    public function chartRisk($input);


    /**
     * chart quản trị
     * @param $input
     * @return mixed
     */
    public function chartManager($input);

    /**
     * chart phòng ban
     * @param $input
     * @return mixed
     */
    public function chartDepartment($input);

    /**
     * chart ngân sách
     * @param $input
     * @return mixed
     */
    public function chartBudget($input);

    /**
     * chart nguồn lực
     * @param $input
     * @return mixed
     */
    public function chartResource($input);

    /**
     * Dự án rủi ro cao
     * @param $input
     * @return mixed
     */
    public function projectHighRisk($input);

    /**Chart dự án lâu không hoạt động
     * @param $input
     * @return mixed
     */
    public function longTimeInactiveProject($input);

    /**
     * Danh sách vấn đề
     * @param $input
     * @return mixed
     */
    public function listIssue($input);

}