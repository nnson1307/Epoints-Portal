<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 10:03 AM
 */

namespace Modules\Admin\Repositories\Transport;

use Modules\Admin\Models\TransportTable;
class TransportRepository implements TransportRepositoryInterface
{
    protected $transport;
    protected $timestamps=true;
    public function __construct(TransportTable $transports)
    {
        $this->transport=$transports;
    }
    //Hàm lấy danh sách
    public function list(array $filters=[])
    {
        return $this->transport->getList($filters);
    }
    //function add
    public function add(array $data)
    {
        return $this->transport->add($data);
    }
    //function get item edit
    public function getItem($id)
    {
        return $this->transport->getItem($id);
    }


    //function edit
    public function edit(array $data,$id)
    {
        return $this->transport->edit($data,$id);
    }
    //function remove
    public function remove($id)
    {
        $this->transport->remove($id);
    }
    //function test name
    public function testName($name, $id)
    {
        return $this->transport->testName($name,$id);
    }
}