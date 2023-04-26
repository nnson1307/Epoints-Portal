<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 10:22 AM
 */

namespace Modules\Admin\Repositories\PointRewardRule;


interface PointRewardRuleRepositoryInterface
{
    public function getAll();

    public function edit(array $data = []);

    public function getConfig();

    public function updateConfig(array $data = []);

    public function updateEvent(array $data = []);

    /**
     * @param $rule_code
     * @return mixed
     */
    public function getRuleByCode($rule_code);
}