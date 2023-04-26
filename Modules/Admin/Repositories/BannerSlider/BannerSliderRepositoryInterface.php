<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 21/3/2019
 * Time: 15:09
 */

namespace Modules\Admin\Repositories\BannerSlider;


interface BannerSliderRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function edit(array $data, $id);

    public function getItem($id);

    public function remove($id);
}
