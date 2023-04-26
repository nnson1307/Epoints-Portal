<?php


namespace Modules\Booking\Repositories\BannerSlider;


use Modules\Booking\Models\BannerSliderTable;
use Modules\Booking\Models\SpaInfoTable;

class BannerSliderRepository implements BannerSliderRepositoryInterface
{
    protected $bannerSlider;
    protected $spaInfo;
    protected $timestamps = true;

    public function __construct(BannerSliderTable $bannerSlider, SpaInfoTable $spaInfo)
    {
        $this->bannerSlider = $bannerSlider;
        $this->spaInfo = $spaInfo;
    }
    public function getSliderHeader()
    {
        // TODO: Implement getSliderHeader() method.
        return $this->bannerSlider->getAllSlider();
    }

    public function getLogoSpa()
    {
        // TODO: Implement getLogoSpa() method.
        return $this->spaInfo->getLogo();
    }
}