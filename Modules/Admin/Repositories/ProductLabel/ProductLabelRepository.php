<?php

namespace Modules\Admin\Repositories\ProductLabel;

use Modules\Admin\Models\ProductLabelTable;

/**
 * Product label repository
 *
 * @author ledangsinh
 * @since march 13, 2018
 */
class ProductLabelRepository implements ProductLabelRepositoryInterface
{
    /**
     * @var ProductLabelTable
     */
    protected $productLabel;
    protected $timestamps=true;

    public function __construct(ProductLabelTable $productLabel)
    {
        $this->productLabel=$productLabel;
    }

    /**
     * Lấy danh sách product label
     */
    public function list(array $filters = [])
    {
        return $this->productLabel->getList($filters);
    }

    /**
     * Thêm product label.
     */
    public function add(array $data)
    {
        return $this->productLabel->add($data);
    }
    /**
     * Sửa product label
     */
    public function edit(array $data,$id)
    {
        return $this->productLabel->edit($data,$id);
    }

    /**
     * Xóa product label
     */
    public function remove($id)
    {
        return $this->productLabel->remove($id);
    }

    /**
     * Get item
     */
    public function getItem($id)
    {
        return $this->productLabel->getItem($id);
    }

}