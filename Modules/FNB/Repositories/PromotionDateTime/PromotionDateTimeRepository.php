<?php


namespace Modules\FNB\Repositories\PromotionDateTime;


use Modules\FNB\Models\PromotionDateTimeTable;
use Modules\FNB\Repositories\PromotionDailyTime\PromotionDailyTimeRepositoryInterface;

class PromotionDateTimeRepository implements PromotionDailyTimeRepositoryInterface
{
    private $promotionDateTime;

    public function __construct(PromotionDateTimeTable $promotionDateTime)
    {
        $this->promotionDateTime = $promotionDateTime;
    }
}