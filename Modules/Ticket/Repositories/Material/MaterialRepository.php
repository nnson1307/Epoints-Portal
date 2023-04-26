<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\Material;

use Modules\Ticket\Models\MaterialTable;
use Modules\Ticket\Models\TicketProcessorTable;
use Modules\Ticket\Models\TicketTable;

class MaterialRepository implements MaterialRepositoryInterface
{
    /**
     * @var MaterialTable
     */
    protected $material;
    protected $timestamps = true;

    public function __construct(MaterialTable $material)
    {
        $this->material = $material;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->material->listMatrerial($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->material->getAll($filters);
    }

   

    public function getName()
    {
        return $this->material->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->material->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->material->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->material->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->material->getItem($id);
    }
    public function getItemByTicketId($ticket_id)
    {
        return $this->material->getItemByTicketId($ticket_id);
    }
    public function getMaterialDetailByTicketId($ticket_id)
    {
        return $this->material->getMaterialDetailByTicketId($ticket_id);
    }

    public function getListStaff($ticket_id){
        $mTicket = new TicketTable();
        $mProcessor = new TicketProcessorTable();

//        Lấy danh sách người chủ trì
        $getOperater = $mTicket->ticketDetailByTicket($ticket_id);

        if ($getOperater != null && $getOperater['operate_by'] != null){
            $listOperater = [$getOperater['operate_by']];
        } else {
            $listOperater = [];
        }
        $listProcessor = $mProcessor->getListProcessor($ticket_id);
        if (count($listProcessor) != 0) {
            $listProcessor = collect($listProcessor)->pluck('staff_id');
        }

        $listArr = collect($listOperater)->merge($listProcessor)->toArray();
        $listArr = array_unique($listArr);

        return $listArr;
    }
}