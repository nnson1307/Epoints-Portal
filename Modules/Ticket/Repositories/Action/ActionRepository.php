<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:17 PM
 */

namespace Modules\Ticket\Repositories\Action;

use Modules\Ticket\Models\ActionTable;

class ActionRepository implements ActionRepositoryInterface
{
    protected $action;
    protected $timestamps = true;

    public function __construct(ActionTable $action)
    {
        $this->action = $action;
    }

    public function add(array $data)
    {
        return $this->action->add($data);
    }

    public function getList()
    {
        return $this->action->getList();
    }
}