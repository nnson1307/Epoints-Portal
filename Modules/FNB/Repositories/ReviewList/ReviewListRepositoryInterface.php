<?php


namespace Modules\FNB\Repositories\ReviewList;


interface ReviewListRepositoryInterface
{
    public function getList(array $filter = []);

    public function getAll();
}