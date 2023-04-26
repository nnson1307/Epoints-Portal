<?php


namespace Modules\FNB\Repositories\Config;


use Modules\FNB\Models\ConfigTable;

class ConfigRepository implements ConfigRepositoryInterface
{
    private $config;

    public function __contruct(ConfigTable $config){
        $this->config = $config;
    }

    public function getInfoByKey($key)
    {
        $config = app()->get(ConfigTable::class);
        return $config->getInfoByKey($key);
    }
}