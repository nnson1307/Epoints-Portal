<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 21/3/2019
 * Time: 15:09
 */

namespace Modules\Admin\Repositories\BannerSlider;


use Modules\Admin\Models\BannerSliderTable;

class BannerSliderRepository implements BannerSliderRepositoryInterface
{
    protected $banner_slider;
    protected $timestamps = true;

    public function __construct(BannerSliderTable $banner_sliders)
    {
        $this->banner_slider = $banner_sliders;
    }

    public function list(array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->banner_slider->getList($filters);
    }

    public function add(array $data)
    {
        // TODO: Implement add() method.
        return $this->banner_slider->add($data);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->banner_slider->getItem($id);
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->banner_slider->edit($data, $id);
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
        return $this->banner_slider->remove($id);
    }
}
