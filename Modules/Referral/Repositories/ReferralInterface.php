<?php


namespace Modules\Referral\Repositories;


interface ReferralInterface
{
    /**
     * @return mixed
     */
    public function getTest();

    /**
     * @return mixed
     * lay thong tin input them chinh sach
     */
    public function getSelectInfo();

    /**
     * @return mixed
     * lay thong tin hien tai cua cau hinh chung
     */
    public function getInfoOld();

    /**
     * lay thong tin cau hinh chung bat ki theo id
     * @param $id
     * @return mixed
     */
    public  function getInfoOldById($id);


    /**
     * @param $input
     * @return mixed
     * luu cau hinh chung
     */
    public function saveGeneralConfig($input);

    /**
     * @return mixed
     * lay thong tin truoc do cua cau hinh nhieu cap
     */
    public function getOldInfo();

    /**
     * @param $input
     * @return mixed
     * Luu chinh sua cau hinh nhieu cap
     */
    public function saveMultilevelConfig($input);

    /**
     * @param $input
     * @return mixed
     * lay thong tin danh sach chinh sach hoa hong
     */
    public function getListCommission($input);

    /**
     * @param $input
     * @return mixed
     * tao chinh sach moi
     */
    public function createNewCommission($input);

    /**
     * @param $data
     * @param $referral_program_id
     * @return mixed
     * luu dieu kien chinh sach hoa hong
     */
    public function saveNewConditionCPI($data, $referral_program_id);

    /**
     * @param $type_commodity
     * @return mixed
     * lay nhom hang hoa
     */
    public function getGroupCommodity($type_commodity);

    /**
     * @param $commodity
     * @return mixed
     * lay danh sach hang hoa-dv-the dv
     */
    public function getListCommodity($commodity, $commodityNow);

    /**
     * @param $commoditySelected
     * @return mixed
     * lấy dữ liệu cho bảng hàng hóa đã chọn
     */
    public function addCommodity($commoditySelected);

    /**
     * @param $selectCommodity
     * @return mixed
     * thêm hang hoa dã chọn vao db
     */
    public function add($selectCommodity);

    /**
     * @param $referral_program_id
     * @return mixed
     * lấy dữ liệu bảng
     */
    public function getInfoTable($data);

    /**
     * @param $referral_program_id
     * @return mixed
     * lấy sản phẩm đang có của chính sách
     */
    public function getCommodityNow($data);

    /**
     * @param $locate
     * @return mixed
     * xóa sản phẩm đã chọn khỏi bảng
     */
    public function deleteCommodity($locate);

    /**
     * Chuyển trang danh sách sản phẩm
     * @param $locate
     * @return mixed
     */
    public function changePageProduct($data);

    /**
     * lay thong tin chinh sach dax luu ->chinh sua
     * @param $id
     * @return mixed
     */
    public function getInfoById($id);

    /**
     * luu dieu kin tinh hoa hong order price
     * @param $input
     * @return mixed
     */
    public function saveConditionOrderPrice($input);

    /**
     * lấy thông tin điều kiện chính sách
     * @param $id
     * @return mixed
     */
    public function getInfoCondition($id);

    /**
     * chinh sua thong tin chinh sach
     * @param $params
     * @return mixed
     */
    public function editInfoCommission($params);

    /**
     * xóa chinh sách hoa hồng
     * @param $id
     * @return mixed
     */
    public function deleteCommission($id);

    /**
     * lây danh sách lịch sử câu hình chung
     * @return mixed
     */
    public function getHistoryGeneralConfig($params = []);

    /**
     * lây fillter cho tìm kiếm cấu hình chung
     * @return mixed
     */
    public function getFilter();

    /**
     * lay thong tin chi tiet chinh sach hoa hong
     * @param $params
     * @return mixed
     */
    public function getDetailCommission($params);

    /**
     * lây sản phẩm của CPS
     * @param $params
     * @return mixed
     */
    public function getInfoCommodity($params);

    /**
     * lấy thông tin điều kiện CPI
     * @param $id
     * @return mixed
     */
    public function conditioncpi($id);

    /**
     * chuyển trạng thái
     * @param $referral_program_id
     * @return mixed
     */
    public function stateChange($params);

    /**
     * lấy danh sách log của chính sách
     * @param $params
     * @return mixed
     */
    public function getLog($params);

    /**
     * lay thong tin old của cau hinh nhieu cap
     * @return mixed
     */
    public function getInfoOldMulti();

    public function getInfoRate($id);



}
