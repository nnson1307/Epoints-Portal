<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class SurveyReportExportTable extends Model
{
    use ListTableTrait;

    protected $table = "survey_report_export";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        "id",
        "export_id",
        "company_branch_code",
        "company_branch_name",
        "customer_code",
        "ship_to_code",
        "ship_to_name",
        "address",
        "created_at",
        "survey_question_type",
        "survey_question",
        "answer_value",
    ];

    public function addInsert($data)
    {
        $this->insert($data);
    }

    public function getAll()
    {

    }
}
