<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\MaterialDetail;

use Modules\Ticket\Models\MaterialDetailTable;

class MaterialDetailRepository implements MaterialDetailRepositoryInterface
{
    /**
     * @var MaterialDetailTable
     */
    protected $MaterialDetail;
    protected $timestamps = true;

    public function __construct(MaterialDetailTable $materialDetail)
    {
        $this->materialDetail = $materialDetail;
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->materialDetail->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->materialDetail->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->materialDetail->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->materialDetail->getItem($id);
    }

    public function getItemByMaterialId($id)
    {
        return $this->materialDetail->getItemByMaterialId($id);
    }
    public function getListMaterialByTicketId($id)
    {
        return $this->materialDetail->getListMaterialByTicketId($id);
    }

    public function removeByMaterialId($id)
    {
        return $this->materialDetail->removeByMaterialId($id);
    }

}