<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 17/1/2019
 * Time: 14:16
 */

namespace Modules\Admin\Repositories\AppointmentSource;


use Modules\Admin\Models\AppointmentSourceTable;

class AppointmentSourceRepository implements AppointmentSourceRepositoryInterface
{
    protected $appointment_source;
    protected $timestamps=true;
    public function __construct(AppointmentSourceTable $appointment_sources)
    {
        $this->appointment_source=$appointment_sources;
    }
    public function getOption()
    {
        // TODO: Implement getOption() method.
        $array=array();
        foreach ($this->appointment_source->getOption() as $item)
        {
            $array[$item['appointment_source_id']]=$item['appointment_source_name'];

        }
        return $array;
    }
}