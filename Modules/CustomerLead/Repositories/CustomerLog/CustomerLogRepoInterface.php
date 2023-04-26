<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 12/1/2021
 * Time: 11:07 AM
 * @author nhandt
 */

namespace Modules\CustomerLead\Repositories\CustomerLog;


interface CustomerLogRepoInterface
{
    public function list(array &$filters = []);

    public function listLogUpdate($input);
}