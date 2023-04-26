<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 15:10
 */

namespace Modules\Admin\Repositories\RuleMenu;


use Modules\Admin\Models\RuleMenuTable;

class RuleMenuRepository implements RuleMenuRepositoryInterface
{
    protected $rule_menu;
    protected $timestamps = true;

    public function __construct(RuleMenuTable $rule_menu)
    {
        $this->rule_menu = $rule_menu;
    }

    public function list()
    {
        // TODO: Implement list() method.
        return $this->rule_menu->getList();
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->rule_menu->edit($data, $id);
    }
}