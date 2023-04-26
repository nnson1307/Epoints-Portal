<?php


namespace Modules\FNB\Repositories\SmsConfig;



use Modules\FNB\Models\SmsConfigTable;

class SmsConfigRepository implements SmsConfigRepositoryInterface
{
    protected $smsConfig;
    protected $timestamps = true;

    public function __construct(SmsConfigTable $smsConfig)
    {
        $this->smsConfig = $smsConfig;
    }
    public function getItemByType($type){
        return $this->smsConfig->getItemByType($type);
    }
}