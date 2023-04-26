<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 31/3/2019
 * Time: 14:54
 */

namespace Modules\Admin\Repositories\ConfigPrintServiceCard;


use Modules\Admin\Models\ConfigPrintServiceCardTable;

class ConfigPrintServiceCardRepository implements ConfigPrintServiceCardRepositoryInterface
{
    protected $config_print_service_card;
    protected $timestamps = true;

    public function __construct(ConfigPrintServiceCardTable $config_print_service_card)
    {
        $this->config_print_service_card = $config_print_service_card;
    }

    public function list()
    {
        // TODO: Implement list() method.
        return $this->config_print_service_card->getList();
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->config_print_service_card->edit($data, $id);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->config_print_service_card->getItem($id);
    }
}