<?php


namespace Modules\Survey\Http\Api;

use MyCore\Api\ApiAbstract;

class MyStoreQueue extends ApiAbstract
{
    /**
     * Gọi job export danh sách ở màn hình báo cáo khảo sát
     * @param array $data
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function expReportSurveyResult(array $data = [])
    {
        return $this->baseClientMyStoreQueue('export/exp-report-survey-result', $data, false);
    }
}
