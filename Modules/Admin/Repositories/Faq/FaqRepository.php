<?php

namespace Modules\Admin\Repositories\Faq;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\FaqGroupTable;
use Modules\Admin\Models\FaqTable;

class FaqRepository implements FaqRepositoryInterface
{
    /**
     * @var FaqTable
     */
    protected $faq;
    protected $faqGroup;

    public function __construct(FaqTable $faq, FaqGroupTable $faqGroup)
    {
        $this->faq = $faq;
        $this->faqGroup = $faqGroup;
    }

    /**
     * Lấy danh sách câu hỏi hỗ trợ có phân trang
     *
     * @param array $filters
     * @return array
     */
    public function getListNew(array $filters = [])
    {
        if (!isset($filters['sort_faq$faq_title_vi'])) {
            $filters['sort_faq$faq_title_vi'] = null;
        }

        if (!isset($filters['sort_faq$faq_title_en'])) {
            $filters['sort_faq$faq_title_en'] = null;
        }

        if (!isset($filters['sort_faq$faq_position'])) {
            $filters['sort_faq$faq_position'] = null;
        }

        if (!isset($filters['sort_faq$is_actived'])) {
            $filters['sort_faq$is_actived'] = null;
        }

        if (!isset($filters[getValueByLang('sort_fgr$faq_group_title_')])) {
            $filters[getValueByLang('sort_fgr$faq_group_title_')] = null;
        }

        if (!isset($filters['keyword_faq$faq_title_vi'])) {
            $filters['keyword_faq$faq_title_vi'] = null;
        }

        if (!isset($filters['keyword_faq$faq_title_en'])) {
            $filters['keyword_faq$faq_title_en'] = null;
        }

        if (!isset($filters['keyword_faq$faq_group'])) {
            $filters['keyword_faq$faq_group'] = null;
        }

        if (!isset($filters['keyword_faq$is_actived'])) {
            $filters['keyword_faq$is_actived'] = null;
        }

//        $filters['faq$faq_type'] = 'faq';

        $data = $this->faq->getListNew($filters);
        return [
            'filter' => $filters,
            'listFaq' => $data,
        ];
    }

    /**
     * Lấy danh sách chính sách bảo mật và điều khoản sử dụng
     *
     * @param array $filters
     * @return mixed
     */
    public function getListPolicyTerms(array $filters = [])
    {
        if (!isset($filters['sort_faq$faq_title'])) {
            $filters['sort_faq$faq_title'] = null;
        }

        if (!isset($filters['sort_faq$faq_type'])) {
            $filters['sort_faq$faq_type'] = null;
        }

        if (!isset($filters['keyword_faq$faq_title'])) {
            $filters['keyword_faq$faq_title'] = null;
        }

        if (!isset($filters['keyword_faq$faq_type'])) {
            $filters['keyword_faq$faq_type'] = null;
        }

        $check = $filters;
        $check = array_filter($check);

        if (count($check) == 0) {
            $filters['sort_faq$faq_title'] = 'asc';
        }

        $data = $this->faq->getListPolicyTerms($filters);

        return [
            'filter' => $filters,
            'listFaq' => $data,
        ];
    }

    /**
     * Lấy danh sách câu hỏi hỗ trợ không phân trang
     *
     * @param array $filters
     * @return array
     */
    public function getListAll(array $filters = [])
    {
        $result = $this->faqGroup->getListAll($filters)->toArray();

        return $result;
    }

    /**
     * Thêm câu hỏi hỗ trợ
     *
     * @param array $data
     * @param string $faqType
     * @return array
     */
    public function add(array $data, $faqType = 'faq')
    {
        try {
            $dataFaq = [
                'faq_title_vi' => strip_tags($data['faq_title_vi']),
                'faq_title_en' => strip_tags($data['faq_title_en']),
                'faq_group' => isset($data['faq_group']) ? strip_tags($data['faq_group']) : null,
                'faq_type' => $faqType,
                'faq_content_vi' => $data['faq_content_vi'],
                'faq_content_en' => $data['faq_content_en'],
                'is_actived' => isset($data['is_actived']) ? 1 : 0,
                'faq_position' => isset($data['faq_position']) ? $data['faq_position'] : 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
            $result = $this->faq->add($dataFaq);

            return [
                'error' => 0,
                'message' => __('admin::faq.popup.CREATED'),
                'faq_id' => $result
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('admin::faq.popup.CREATE_FAILED'),
            ];
        }
    }

    /**
     * Lấy chi tiết câu hỏi hỗ trợ
     *
     * @param int $id
     * @return mixed
     */
    public function detail($id)
    {
        $result = $this->faq->detail($id);

        return $result;
    }

    /**
     * Chỉnh sửa thông tin câu hỏi hỗ trợ
     *
     * @param array $data
     * @return array
     */
    public function edit(array $data)
    {
        try {
            $dataFaq = [
                'faq_title_vi' => strip_tags($data['faq_title_vi']),
                'faq_title_en' => strip_tags($data['faq_title_en']),
                'faq_group' => isset($data['faq_group']) ? strip_tags($data['faq_group']) : null,
                'faq_content_vi' => $data['faq_content_vi'],
                'faq_content_en' => $data['faq_content_en'],
                'is_actived' => isset($data['is_actived']) ? 1 : 0,
                'faq_position' => isset($data['faq_position']) ? $data['faq_position'] : 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::id(),
            ];
            $result = $this->faq->edit($dataFaq, $data['faq_id']);

            if ($result) {
                return [
                    'error' => 0,
                    'message' => __('admin::faq.popup.UPDATED'),
                ];
            }

            return [
                'error' => 1,
                'message' => __('admin::faq.popup.UPDATE_FAILED'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('admin::faq.popup.UPDATE_FAILED'),
            ];
        }
    }

    /**
     * Đánh dấu xóa câu hỏi hỗ trợ
     *
     * @param int $id
     * @return bool
     */
    public function remove($id)
    {
        $result = $this->faq->remove($id);

        return $result;
    }

    /**
     * Kiểm tra chi tiết nội dung thuộc loại trang đã tồn tại chưa
     *
     * @param $faqType
     * @return boolean
     */
    public function checkFaqType($faqType)
    {
        $result = $this->faq->checkFaqType($faqType);

        return ($result->count() > 0);
    }

    /**
     * Cập nhật trạng thái hiển thị
     *
     * @param int $status
     * @param int $id
     * @return bool
     */
    public function updateStatus($status, $id)
    {
        $result = $this->faq->edit(['is_actived' => $status], $id);

        return $result;
    }
}
