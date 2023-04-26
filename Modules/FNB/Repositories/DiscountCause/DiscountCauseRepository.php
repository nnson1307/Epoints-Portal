<?php


namespace Modules\FNB\Repositories\DiscountCause;


use Modules\FNB\Models\DiscountCauseTable;

class DiscountCauseRepository implements DiscountCauseRepositoryInterface
{
    private $discountCause;

    public function __contruct(DiscountCauseTable $discountCause){
        $this->discountCause = $discountCause;
    }

    public function getOption(){
        $discountCause = app()->get(DiscountCauseTable::class);
        return $discountCause->getOption();
    }
}