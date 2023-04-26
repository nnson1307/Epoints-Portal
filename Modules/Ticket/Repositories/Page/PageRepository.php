<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/8/2019
 * Time: 2:00 PM
 */

namespace Modules\Ticket\Repositories\Page;

use Modules\Ticket\Models\PageTable;

class PageRepository implements PageRepositoryInterface
{
    protected $page;

    public function __construct(PageTable $page)
    {
        $this->page = $page;
    }

    public function add(array $data)
    {
        return $this->page->add($data);
    }

    public function getList(){
        return $this->page->getList();
    }
}