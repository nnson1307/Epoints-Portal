<?php
namespace Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Api;

/**
 * Api interface
 * 
 * @author ISC--DAIDP
 * @since 22/09/2015
 */
interface ApiInterface
{
    public function toArray();
    
    public function getAction();
}