<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/21/2018
 * Time: 10:29 AM
 */

namespace Modules\Admin\Repositories\Voucher;


use Modules\Admin\Models\Voucher;

class VoucherRepository implements VoucherRepositoryInterface
{
    private $voucher;

    public function __construct(Voucher $voucher)
    {
        $this->voucher = $voucher;
    }

    public function list(array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->voucher->getList($filters);
    }

    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->voucher->add($data);
    }

    public function edit($id, array $data)
    {
        // TODO: Implement edit() method.
        return $this->voucher->edit($id, $data);
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
        return $this->voucher->remove($id);
    }

    public function detail($id)
    {
        // TODO: Implement detail() method.
        return $this->voucher->getDetail($id);
    }

    public function changeStatus($id)
    {
        // TODO: Implement changeStatus() method.
        return $this->voucher->changeStatus($id);

    }

    public function getCodeOrder($code, $type)
    {
        return $this->voucher->getCodeOrder($code, $type);
    }

    public function editVoucherOrder(array $data, $code)
    {
        return $this->voucher->editVoucherOrder($data, $code);
    }

    public function getCodeItem($code)
    {
        // TODO: Implement getCodeItem() method.
        return $this->voucher->getCodeItem($code);
    }

    public function checkSlug($slug, $id)
    {
        return $this->voucher->checkSlug($slug, $id);
    }
}