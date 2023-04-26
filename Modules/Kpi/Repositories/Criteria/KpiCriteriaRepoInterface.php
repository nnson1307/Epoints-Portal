<?php
namespace Modules\Kpi\Repositories\Criteria;

/**
 * Interface KpiCriteriaRepoInterface
 * @author HaoNMN
 * @since Jun 2022
 */
interface KpiCriteriaRepoInterface
{
    // Danh sách tiêu chí kpi
    public function listAction($param = []);

    // Lưu dữ liệu tiêu chí kpi
    public function save($data);

    // Cập nhật dữ liệu tiêu chí kpi
    public function update($data);

    // Xóa tiêu chí kpi
    public function destroy($id);

    // Lấy dữ liệu option pipeline & hành trình tiêu chí lead quan tâm
    public function getLeadOption($param);

    // Lấy danh sách đơn vị cho tiêu chí người dùng tạo
    public function listUnit();
}