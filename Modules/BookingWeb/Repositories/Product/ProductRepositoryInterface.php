<?php


namespace Modules\BookingWeb\Repositories\Product;


interface ProductRepositoryInterface
{
    public function list(array $data = []);
    public function getProduct(array $data= []);
    public function getProductDetailGroup(array $data=[]);
}