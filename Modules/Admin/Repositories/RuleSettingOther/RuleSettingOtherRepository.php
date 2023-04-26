<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 17:19
 */

namespace Modules\Admin\Repositories\RuleSettingOther;


use Modules\Admin\Models\RuleSettingOtherTable;

class RuleSettingOtherRepository implements RuleSettingOtherRepositoryInterface
{
    protected $rule_setting_other;
    protected $timestamps = true;

    public function __construct(RuleSettingOtherTable $rule_setting_other)
    {
        $this->rule_setting_other = $rule_setting_other;
    }

    public function list()
    {
        // TODO: Implement list() method.
        return $this->rule_setting_other->getList();
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->rule_setting_other->edit($data, $id);
    }
}