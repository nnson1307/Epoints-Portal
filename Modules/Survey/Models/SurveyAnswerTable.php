<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class SurveyAnswerTable extends Model
{
    use ListTableTrait;

    protected $table = "survey_answer";
    protected $primaryKey = "survey_answer_id";

    /**
     * Danh sách câu hỏi
     * @param array $filter
     * @return mixed
     */
    public function getListCore(&$filter = [])
    {
        $select = $this->select(
            "{$this->table}.survey_answer_id",
            "{$this->table}.branch_id",
            "{$this->table}.survey_answer_status",
            "{$this->table}.total_questions",
            "{$this->table}.num_questions_completed",
            "{$this->table}.accumulation_point",
            "{$this->table}.finished_at",
            "branches.branch_code",
            "branches.representative_code",
            "branches.branch_name",
            \DB::raw('CONCAT(branches.branch_code, "||",
            branches.representative_code,
                "||", survey_answer.survey_answer_id) AS customer_ship_code_sai'),
            \DB::raw('CONCAT(branches.branch_code, "||",
            branches.representative_code) AS customer_ship_code')
        )
            ->join("branches", "branches.branch_id", "{$this->table}.branch_id");
        if (isset($filter['keyword_outlet'])) {
            $select->where(function ($query) use ($filter) {
                $query
                    ->where("branches.branch_code", "LIKE", "%" . $filter['keyword_outlet'] . "%")
                    ->orWhere("branches.representative_code", "LIKE", "%" . $filter['keyword_outlet'] . "%")
                    ->orWhere("branches.branch_name", "LIKE", "%" . $filter['keyword_outlet'] . "%");
            });
            unset($filter['keyword_outlet']);
        }
        if (isset($filter['finished_at_start'])) {
            $select->whereDate("{$this->table}.finished_at", ">=", $filter['finished_at_start']);
            unset($filter['finished_at_start']);
        }
        if (isset($filter['finished_at_end'])) {
            $select->whereDate("{$this->table}.finished_at", "<=", $filter['finished_at_end']);
            unset($filter['finished_at_end']);
        }
        $select->groupBy("{$this->table}.survey_answer_id")
            ->orderBy("{$this->table}.finished_at", 'DESC');
        return $select;
    }

    public function getListCoreNews(&$filters = [])
    {
        $typeUser = $filters['typeUser'] ?? 'staff';
        if ($typeUser == 'customer') {
            $select = $this->select(
                "{$this->table}.*",
                "{$this->table}.branch_id",
                "{$this->table}.user_id",
                "{$this->table}.survey_id",
                "{$this->table}.survey_answer_id",
                "{$this->table}.survey_answer_status",
                "{$this->table}.total_questions",
                "{$this->table}.num_questions_completed",
                "{$this->table}.accumulation_point",
                "{$this->table}.finished_at",
                "{$this->table}.survey_answer_id",
                "{$this->table}.created_at as create_at_survey",
                "customers.customer_code as code",
                "customers.full_name",
                "customers.customer_id as id_user",
                "customers.phone1 as phone",
                "customers.address",
                'survey_answer_question.survey_question_id',
                DB::raw('count(DISTINCT survey_answer_question.survey_question_id) as total_answer')
            );
            if (isset($filters['idSurvey'])) {
                $select->where("{$this->table}.survey_id", $filters['idSurvey']);
            }

            $select
                ->leftJoin('customers', function ($join) {
                    $join->on("{$this->table}.user_id", '=', 'customers.customer_id');
                });
            if (isset($filters['nameCustomerOrStaff'])) {
                $searchCode = $filters['nameCustomerOrStaff'];
                $select->where("customers.full_name", "LIKE", "%" . $searchCode . "%");
                unset($filters['nameCustomerOrStaff']);
            }

            if (isset($filters['codeCustomerOrStaff'])) {
                $searchCode = $filters['codeCustomerOrStaff'];
                $select->where("customers.customer_code", $searchCode);
                unset($filters['codeCustomerOrStaff']);
            }

            if (isset($filters['province'])) {
                $searchProvince = $filters['province'];
                $select->where("customers.province_id", $searchProvince);
                unset($filters['province']);
            }
            if (isset($filters['district'])) {
                $searchDistric = $filters['district'];
                $select->where("customers.district_id", $searchDistric);
                unset($filters['district']);
            }
            if (isset($filters['ward'])) {
                $searchWard = $filters['ward'];
                $select->where("customers.ward_id", $searchWard);
                unset($filters['ward']);
            }
        } else {
            $select = $this->select(
                "{$this->table}.*",
                "{$this->table}.branch_id",
                "{$this->table}.user_id",
                "{$this->table}.survey_id",
                "{$this->table}.survey_answer_id",
                "{$this->table}.survey_answer_status",
                "{$this->table}.total_questions",
                "{$this->table}.num_questions_completed",
                "{$this->table}.accumulation_point",
                "{$this->table}.finished_at",
                "{$this->table}.survey_answer_id",
                "{$this->table}.created_at as create_at_survey",
                "staffs.full_name",
                "staffs.staff_id as id_user ",
                "staffs.phone1 as phone",
                "staffs.address",
                "staffs.staff_code as code",
                'survey_answer_question.survey_question_id',
                DB::raw('count(DISTINCT survey_answer_question.survey_question_id) as total_answer')
            );
            if (isset($filters['idSurvey'])) {
                $select->where("{$this->table}.survey_id", $filters['idSurvey']);
            }

            $select
                ->leftJoin('staffs', function ($join) {
                    $join->on("{$this->table}.user_id", '=', 'staffs.staff_id');
                });
            if (isset($filters['nameCustomerOrStaff'])) {
                $searchCode = $filters['nameCustomerOrStaff'];
                $select->where("staffs.full_name", "LIKE", "%" . $searchCode . "%");
                unset($filters['nameCustomerOrStaff']);
            }
            if (isset($filters['codeCustomerOrStaff'])) {
                $searchCode = $filters['codeCustomerOrStaff'];
                $select->where("staffs.staff_code", $searchCode);
                unset($filters['codeCustomerOrStaff']);
            }
            if (isset($filters['address'])) {
                $searchAddress = $filters['address'];
                $select->where("staffs.address", 'like', '%' . $searchAddress . '%');
                unset($filters['address']);
            }
        }
        if (isset($filters['dateCreatedCustomer'])) {
            $arrFilter = explode(" - ", $filters["dateCreatedCustomer"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrFilter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrFilter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filters['dateCreatedCustomer']);
        }

        if (isset($filters['dateCreatedStaff'])) {
            $arrFilter = explode(" - ", $filters["dateCreatedStaff"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrFilter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrFilter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filters['dateCreatedStaff']);
        }

        $select->leftJoin("survey_answer_question", function ($join) {
            $join->on("survey_answer_question.survey_answer_id", '=', 'survey_answer.survey_answer_id');
            $join->where(function ($query) {
                $query->where("survey_answer_question.survey_question_choice_id", '<>', null);
                $query->orWhere("survey_answer_question.answer_value", '<>', null);
            });
        });
        $select->where('survey_answer_status', 'done');
        $select->orderBy("{$this->table}.survey_answer_id", 'DESC');
        $select->groupBy("{$this->table}.survey_answer_id");
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? PAGING_ITEM_PER_PAGE);
        unset($filters['perpage']);
        unset($filters['page']);
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy danh sách id trả lời khảo sat user 
     * @param $filters
     * @return mixed
     */
    public function getListAnswerBySurvey(&$filters = [])
    {
        $typeUser = $filters['typeUser'] ?? 'staff';
        $select = $this->select(
            "{$this->table}.survey_answer_id",
            "{$this->table}.total_questions",
            "{$this->table}.num_questions_completed",
            "{$this->table}.total_point",
            "{$this->table}.total_answer_success",
            "{$this->table}.total_answer_wrong",
            "{$this->table}.created_at",
            "survey.count_point",
            "staffs.full_name",
            "staffs.staff_id as id_user ",
            "staffs.phone1 as phone",
            "staffs.address",
            "staffs.staff_code as code",
            'survey_answer_question.survey_question_id',
            DB::raw('count(DISTINCT survey_answer_question.survey_question_id) as total_answer')
        );
        if ($typeUser == 'customer') {
            $select = $this->select(
                "{$this->table}.survey_answer_id",
                "{$this->table}.total_questions",
                "{$this->table}.num_questions_completed",
                "{$this->table}.created_at",
                "survey.count_point",
                "customers.customer_code as code",
                "customers.full_name",
                "customers.customer_id as id_user",
                "customers.phone1 as phone",
                "customers.address",
                'survey_answer_question.survey_question_id',
                DB::raw('count(DISTINCT survey_answer_question.survey_question_id) as total_answer')
            );
        }
        $select->where("{$this->table}.survey_id", $filters['survey_id']);
        if ($typeUser == 'customer') {
            $select->leftJoin('customers', function ($join) {
                $join->on("{$this->table}.user_id", '=', 'customers.customer_id');
            });
        } else {
            $select->leftJoin('staffs', function ($join) {
                $join->on("{$this->table}.user_id", '=', 'staffs.staff_id');
            });
        }
        $select->join("survey", 'survey.survey_id', "{$this->table}.survey_id")
            ->leftJoin("survey_answer_question", function ($join) {
                $join->on("survey_answer_question.survey_answer_id", '=', 'survey_answer.survey_answer_id');
                $join->where(function ($query) {
                    $query->where("survey_answer_question.survey_question_choice_id", '<>', null);
                    $query->orWhere("survey_answer_question.answer_value", '<>', null);
                });
            });
        $select->where("{$this->table}.survey_answer_status", 'done');
        $select->orderBy("{$this->table}.survey_answer_id", 'DESC');
        $select->groupBy("{$this->table}.survey_answer_id");
        $page = (int)($filters['page'] ?? 1);
        $display = (int)($filters['perpage'] ?? 1);
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy danh sách câu trả lời của user
     * @param $id_answer
     * @return mixed
     */

    public function listAnswerByUser($id_answer)
    {
        $typeUser = $filters['typeUser'] ?? 'staff';
        $select = $this->select(
            "{$this->table}.survey_answer_id",
            "{$this->table}.total_questions",
            "{$this->table}.num_questions_completed",
            "{$this->table}.created_at",
            "{$this->table}.total_answer_success",
            "{$this->table}.total_answer_wrong",
            "{$this->table}.total_point",
            "survey.count_point",
            "staffs.full_name",
            "staffs.staff_id as id_user ",
            "staffs.phone1 as phone",
            "staffs.address",
            "staffs.staff_code as code",
            'survey_answer_question.survey_question_id',
            DB::raw('count(DISTINCT survey_answer_question.survey_question_id) as total_answer')
        );
        if ($typeUser == 'customer') {
            $select = $this->select(
                "{$this->table}.survey_answer_id",
                "{$this->table}.total_questions",
                "{$this->table}.num_questions_completed",
                "{$this->table}.created_at",
                "survey.count_point",
                "customers.customer_code as code",
                "customers.full_name",
                "customers.customer_id as id_user",
                "customers.phone1 as phone",
                "customers.address",
                'survey_answer_question.survey_question_id',
                DB::raw('count(DISTINCT survey_answer_question.survey_question_id) as total_answer')
            );
        }

        if ($typeUser == 'customer') {
            $select->leftJoin('customers', function ($join) {
                $join->on("{$this->table}.user_id", '=', 'customers.customer_id');
            });
        } else {
            $select->leftJoin('staffs', function ($join) {
                $join->on("{$this->table}.user_id", '=', 'staffs.staff_id');
            });
        }
        $select
            ->join("survey", 'survey.survey_id', "{$this->table}.survey_id")
            ->leftJoin("survey_answer_question", function ($join) {
                $join->on("survey_answer_question.survey_answer_id", '=', 'survey_answer.survey_answer_id');
                $join->where(function ($query) {
                    $query->where("survey_answer_question.survey_question_choice_id", '<>', null);
                    $query->orWhere("survey_answer_question.answer_value", '<>', null);
                });
            });
        $select->orderBy("{$this->table}.survey_answer_id", 'DESC');
        $select->groupBy("{$this->table}.survey_answer_id");

        return $select->find($id_answer);
    }


    /**
     * lấy danh sách id câu trả lời và select cột theo bảng excel 
     * @param $filters
     * @return mixed
     */
    public function getAnswerQuestionExportExcel(&$filters = [])
    {
        $typeUser = $filters['typeUser'] ?? 'staff';
        $select = $this->select(
            "{$this->table}.survey_answer_id",
            "staffs.staff_code as code",
            "staffs.full_name",
            "staffs.phone1 as phone",
            "{$this->table}.created_at",
            "staffs.address"
        );
        if ($typeUser == 'customer') {
            $select = $this->select(
                "{$this->table}.survey_answer_id",
                "customers.customer_code as code",
                "customers.full_name",
                "customers.phone1 as phone",
                "customers.address",
                "{$this->table}.created_at"
            );
        }
        $select->where("{$this->table}.survey_id", $filters['survey_id']);
        $select->where("{$this->table}.survey_answer_status", 'done');
        if ($typeUser == 'customer') {
            $select->leftJoin('customers', function ($join) {
                $join->on("{$this->table}.user_id", '=', 'customers.customer_id');
            });
        } else {
            $select->leftJoin('staffs', function ($join) {
                $join->on("{$this->table}.user_id", '=', 'staffs.staff_id');
            });
        }
        $select->orderBy("{$this->table}.survey_answer_id", 'DESC');
        return $select->get();
    }

    /**
     * hàm lấy các phiên trả lời user mới nhất của khảo sát 
     */

    public function getAnswerNewUser($id_survey)
    {
        $select = $this->where("survey_id", $id_survey)
            ->where('survey_answer_status', 'done');
        return $select->get();
    }

    /**
     * Tổng số câu trả lời khảo sát 
     * @param $idSurvey
     * @return mixed
     */

    public function getListAnswerByIdSurvey($idSurvey)
    {
        return $this->where('survey_id', $idSurvey)->count();
    }
}
