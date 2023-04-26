<?php


namespace Modules\FNB\Repositories\ReviewList;


use Modules\FNB\Models\FNBReviewListTable;

class ReviewListRepository implements ReviewListRepositoryInterface
{
    public function getList(array $filter = [])
    {
        $mReviewList = app()->get(FNBReviewListTable::class);
        return $mReviewList->getList($filter);
    }

    public function getAll()
    {
        $mReviewList = app()->get(FNBReviewListTable::class);
        return $mReviewList->getAll();
    }
}