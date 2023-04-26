<?php

namespace Modules\Payment\Repositories\ReportSynthesis;

interface ReportSynthesisRepoInterface
{
    public function dataViewIndex();

    public function filterAction($input);
}