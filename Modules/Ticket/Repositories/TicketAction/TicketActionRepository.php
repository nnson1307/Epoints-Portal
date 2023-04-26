<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\Ticket\Repositories\TicketAction;

use Modules\Ticket\Models\TicketActionTable;

class TicketActionRepository implements TicketActionRepositoryInterface
{
    /**
     * @var TicketActionTable
     */
    protected $ticketAction;
    protected $timestamps = true;

    public function __construct(TicketActionTable $ticketAction)
    {
        $this->ticketAction = $ticketAction;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->ticketAction->getList($filters);
    }

    public function getAll(array $filters = [])
    {
        return $this->ticketAction->getAll($filters);
    }
    public function getName(){
        return $this->ticketAction->getName();
    }
    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->ticketAction->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->ticketAction->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->ticketAction->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->ticketAction->getItem($id);
    }
    
}