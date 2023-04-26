<?php


namespace Modules\FNB\Repositories\SmsConfig;


interface SmsConfigRepositoryInterface
{
    public function getItemByType($type);
}