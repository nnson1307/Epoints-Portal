<?php


namespace Modules\FNB\Repositories\ProductAttribute;


interface ProductAttributeRepositoryInterface
{
    public function getDetail($id);

    public function update($data);

    public function getNameAttribute($arrAttributeId = []);
}