<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:17 PM
 */

namespace Modules\Admin\Repositories\Action;

use Modules\Admin\Models\ActionTable;

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