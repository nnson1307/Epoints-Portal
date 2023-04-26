<?php


namespace Modules\FNB\Repositories\ReviewListDetail;


interface ReviewListDetailRepositoryInterface
{
    public function getList(array $filter = []);

    public function showPopup($data);

    public function saveReviewListDetail($data);

    public function removeReviewListDetail($data);
}