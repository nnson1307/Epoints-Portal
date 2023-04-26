<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\Attribute;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubAttributeTable;

class AttributeRepository implements AttributeRepositoryInterface
{
    protected $attribute;
    public function __construct(
        ChatHubAttributeTable $attribute
    )
    {
        $this->attribute = $attribute;
    }
    public function create($data){
        $data['created_at']=date('Y-m-d H:i:s');
        $this->attribute->store($data);
    }
    public function getList($filters = null){
        return $this->attribute->getList($filters);
    }
    public function delete($attribute_id){
        $this->attribute->remove($attribute_id);
    }

    public function getAttribute($attribute_id){
        return $this->attribute->getAttribute($attribute_id);
    }

    public function update($data){
        $data['updated_at']=date('Y-m-d H:i:s');
        $attribute_id=$data['attribute_id'];
        $this->attribute->edit($data, $attribute_id);
    }
    public function getActive(){
        return $this->attribute->getActive();
    }
}