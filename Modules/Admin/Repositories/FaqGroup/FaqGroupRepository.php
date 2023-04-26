<?php

namespace Modules\Admin\Repositories\FaqGroup;

use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\FaqGroupTable;
use Modules\Admin\Models\FaqTable;

class FaqGroupRepository implements FaqGroupRepositoryInterface
{
    /**
     * @var FaqGroupTable
     */
    protected $faqGroup;

    protected $faq;

    public function __construct(
        FaqGroupTable $faqGroup,
        FaqTable $faq
    ) {
        $this->faqGroup = $faqGroup;
        $this->faq = $faq;
    }

    /**
     * Lấy danh sách faq group có phân trang
     *
     * @param array $filters
     * @return mixed
     */
    public function getListNew(array $filters = [])
    {
        $listFaqGroup = $this->faqGroup->getListNew($filters);

        return [
            'filter' => $filters,
            'listFaqGroup' => $listFaqGroup
        ];
    }

    /**
     * Lấy toàn bộ danh sách faq group không phân trang
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
     * Lấy thông tin chi tiết faq group
     *
     * @param int $faq_group_id
     * @return mixed
     */
    public function detail($faq_group_id)
    {
        $result = $this->faqGroup->detail($faq_group_id);

        return $result;
    }

    /**
     * Thêm faq group
     *
     * @param array $data
     * @return array
     */
    public function add(array $data)
    {
        try {
            $dataFaqGroup = [
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : 0,
                'faq_group_title_vi' => strip_tags($data['faq_group_title_vi']),
                'faq_group_title_en' => strip_tags($data['faq_group_title_en']),
                'faq_group_position' => $data['faq_group_position'],
                'faq_group_type' => 'basic',
                'is_actived' => isset($data['is_actived']) ? 1 : 0,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
            $result = $this->faqGroup->add($dataFaqGroup);

            return [
                'error' => 0,
                'message' => __('admin::faq-group.popup.CREATED'),
                'faq_group_id' => $result
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('admin::faq-group.popup.CREATE_FAILED'),
            ];
        }
    }

    /**
     * Chỉnh sửa nhóm nội dung
     *
     * @param array $data
     * @param int $id
     * @return array
     */
    public function edit(array $data, $id)
    {
        try {
            $dataFaqGroup = [
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : 0,
                'faq_group_title_vi' => strip_tags($data['faq_group_title_vi']),
                'faq_group_title_en' => strip_tags($data['faq_group_title_en']),
                'faq_group_position' => $data['faq_group_position'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::id(),
            ];

            if (!$this->checkDefaultGroup($id)) {
                $dataFaqGroup['is_actived'] = isset($data['is_actived']) ? 1 : 0;
            }

            $result = $this->faqGroup->edit($dataFaqGroup, $id);

            if ($result) {
                return [
                    'error' => 0,
                    'message' => __('admin::faq-group.popup.UPDATED'),
                ];
            }

            return [
                'error' => 1,
                'message' => __('admin::faq-group.popup.UPDATE_FAILED'),
            ];
        } catch (\Exception $e) {
            return [
                'error' => 1,
                'message' => __('admin::faq-group.popup.UPDATE_FAILED'),
            ];
        }
    }

    /**
     * Cập nhật trạng thái hiển thị
     *
     * @param int $status
     * @param int $id
     * @return mixed
     */
    public function updateStatus($status, $id)
    {
        if ($this->checkDefaultGroup($id)) {
            return false;
        } else {
            return $this->faqGroup->edit(['is_actived' => $status], $id);
        }
    }

    /**
     * Đánh dấu xóa nhóm nội dung
     *
     * @param int $id
     * @return mixed
     */
    public function remove($id)
    {
        if ($this->checkDefaultGroup($id)) {
            return false;
        } else {
            $this->updateParent($id);
            $this->faq->edit(['faq_group' => 1], [
                ['faq_group', '=', $id]
            ]);
            return $this->faqGroup->remove($id);
        }
    }

    /**
     * Kiểm tra danh mục mặc định
     *
     * @param $id
     * @return bool
     */
    private function checkDefaultGroup($id)
    {
        return $this->faqGroup->checkDefault($id);
    }

    private function updateParent($parent_id)
    {
        $result = $this->faqGroup->edit(['parent_id' => 1], [['parent_id', '=', $parent_id]]);
    }
}
