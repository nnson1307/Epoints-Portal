<?php


namespace Modules\Survey\Repositories\Survey;


interface SurveyRepositoryInterface
{
    /**
     * Danh sách khảo sát
     * @param $filters
     * @return mixed
     */
    public function getList($filters = []);

    /**
     * Tạo khảo sát
     * @param $params
     * @return mixed
     */
    public function store($params);

    /**
     * Chi tiết khảo sát
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * chi tiết khảo sát bao gồm các thông tin liên quan 
     * @param $id
     * @return mixed
     */

    public function getItemNews($id);

    /**
     * RET-1757
     * [Brand portal] Chỉnh sửa thông tin chung khảo sát
     * @param $params
     * @return mixed
     */
    public function update($params);

    /**
     * Tạo session default cho tab câu hỏi khảo sát
     * @param $id
     * @param $unique
     * @return mixed
     */
    public function setSessionDefaultQuestion($id, $unique);

    /**
     * RET-1761
     * [Brand portal] Thêm, sửa và xóa nhóm câu hỏi (block) trong khảo sát
     * @param $params
     * @return mixed
     */
    public function addBlock($params);

    /**
     * Load html Block
     * @param $params
     * @return array
     * @throws \Throwable
     */
    public function loadBlock($params);

    /**
     * Thay đổi gì đó ở block
     * @param $params
     * @return mixed
     */
    public function onChangeBlock($params);

    /**
     * Render html để thêm câu hỏi vào block
     * @param $params
     * @return mixed
     */
    public function addQuestion($params);

    /**
     * Render html để thêm câu hỏi vào block
     * @param $params
     * @return mixed
     */
    public function loadQuestionInBlock($params);

    /**
     * Xóa câu hỏi trong block
     * @param $params
     * @return mixed
     */
    public function removeQuestion($params);

    /**
     * Thay đổi vị trí của câu hỏi trong block
     * @param $params
     * @return mixed
     */
    public function changeQuestionPosition($params);

    /**
     * Chi tiết cài đặt của câu hỏi
     * @param $params
     * @return mixed
     */
    public function showConfigQuestion($params);

    /**
     * Thay đổi gì đó của câu hỏi
     * @param $params
     * @return mixed
     */
    public function onChangeQuestion($params);

    /**
     * Submit save câu hỏi khảo sát
     * @param $params
     * @return mixed
     */
    public function updateSurveyQuestion($params);

    /**
     * Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param $params
     * @return mixed
     */
    public function showModalConfigPoint($params);


    /**
     * Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param $params
     * @return mixed
     */
    public function showModalNotification($params);

    /**
     * Update template Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param $params
     * @return mixed
     */
    public function updateTemplate($params);

    /**
     * Update template Cài đặt cấu hình khảo sát có tính điểm
     * @param $params
     * @return mixed
     */
    public function updateConfigPoint($params);


    /**
     * Option load more
     * @param $params
     * @return mixed
     */
    public function optionLoadMore($params);

    /**
     * Gán outlet trong db vào session
     * @param $id
     * @param $unique
     * @return mixed
     */
    public function setSessionOutletDefault($id, $unique);


    /**
     * Xóa khảo sát
     * @param $id
     * @return mixed
     */
    public function destroy($id);

    /**
     * Thay đổi trạng thái khảo sát
     * @param $param
     * @return mixed
     */
    public function changeStatus($param);

    /**
     * Option question
     * @param $params
     * @return mixed
     */
    public function optionQuestion($params);

    /**
     * Data report survey
     * @param array $params
     * @return mixed
     */
    public function getListReport($params = []);

    /**
     * Export danh sách ở màn hình báo cáo khảo sát
     * @param $idSurvey
     * @return mixed
     */
    public function exportReport($idSurvey);

    /**
     * lấy tất cả danh sách báo cáo của khảo sát 
     * @param $params
     * @return mixed
     */
    public function getListReportAll($params);

    /**
     * lây tất cả danh sách câu trả lời của từng user khảo sát
     * @param $params
     * @return mixed
     */
    public function getListAllReportUser($params);

    /**
     * load item đầu tiên câu trả lời khảo sát user 
     * @param $params
     * @return mixed
     */

    public function getItemFirstReportUser($params);

    /**
     * Lấy tất cả danh sách câu hỏi báo cáo  của survey
     * @param $id_survey
     * @return mixed
     */
    public function getAllQuestionReport($id_survey);

    /**
     * Lấy tất cả thông tin khách hàng (loại khách hàng, nhóm khách hàng ...)
     * @return mixed
     */
    public function getAllInfoCustomer();

    /**
     * lấy thông tin các câu trả lời của khách hàng
     * @param $idAnswer
     * @return mixed
     */

    public function showAnswerByUser($idAnswer);


    /**
     * Hiển thị mẫu list câu hỏi
     * @param $params
     * @return mixed
     */

    public function loadTemplateQuestion($params);

    /**
     * Coppy survey
     * @param $idSurvey
     * @return mixed
     */

    public function coppySurvey($idSurvey);
    
}
