<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 17:21
 */

namespace Modules\Admin\Repositories\BookingExtra;


use Modules\Admin\Models\BookingExtraTable;

class BookingExtraRepository implements BookingExtraRepositoryInterface
{
    protected $booking_extra;
    protected $timestamps = true;

    public function __construct(BookingExtraTable $booking_extra)
    {
        $this->booking_extra = $booking_extra;
    }

    public function list()
    {
        // TODO: Implement list() method.
        return $this->booking_extra->getList();
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->booking_extra->edit($data, $id);
    }
}