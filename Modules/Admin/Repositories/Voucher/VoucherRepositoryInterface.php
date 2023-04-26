<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/21/2018
 * Time: 10:30 AM
 */

namespace Modules\Admin\Repositories\Voucher;


interface VoucherRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function edit($id, array $data);

    public function delete($id);

    public function detail($id);

    public function changeStatus($id);

    public function getCodeOrder($code, $type);

    public function editVoucherOrder(array $data, $code);

    public function getCodeItem($code);

    public function checkSlug($slug, $id);
}