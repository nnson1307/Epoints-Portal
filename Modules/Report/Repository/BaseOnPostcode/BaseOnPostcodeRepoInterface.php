<?php


namespace Modules\Report\Repository\BaseOnPostcode;


interface BaseOnPostcodeRepoInterface
{
    /**
     * Load biểu đồ báo cáo dựa trên postcode
     *
     * @param $input
     * @return mixed
     */
    public function loadChart($input);

    /**
     * dữ liệu cho màn hình báo cáo
     *
     * @return mixed
     */
    public function dataView();
}