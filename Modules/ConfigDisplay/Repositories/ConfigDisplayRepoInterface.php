<?php


namespace Modules\ConfigDisplay\Repositories;


interface ConfigDisplayRepoInterface
{
    /**
     * Lấy tất cả cấu hình hiển thị 
     * @param array $fillters
     * @return mixed
     */

    public function getAll(array $params);


    /**
     * Get type template 
     * @return array
     */

    public function getTypeTemplate();
}
