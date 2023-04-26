<?php


namespace Modules\Survey\Repositories\Survey;


use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportSurveyExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\StaffsTable;
use Modules\Survey\Models\SurveyTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DepartmentTable;
use Modules\Admin\Models\StaffTitleTable;
use Modules\Survey\Http\Api\MyStoreQueue;
use Modules\ManagerWork\Models\BranchTable;
use Modules\Survey\Models\SurveyBlockTable;
use Modules\Survey\Models\ImportExportTable;
use Modules\Survey\Models\SurveyAnswerTable;
use Modules\Survey\Models\SurveyBranchTable;
use Modules\Survey\Models\TemplateBlockTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Survey\Models\TemplateQuestionTable;
use Modules\Survey\Models\SurveyConfigPointTable;
use Modules\Admin\Models\CustomerGroupFilterTable;
use Modules\Admin\Models\CustomerCustomDefineTable;
use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyConditionApplyTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Admin\Models\ConfigCustomerParameterTable;
use Modules\Admin\Models\CustomerGroupDefineDetailTable;
use Modules\Survey\Models\SurveyTemplateNotificationTable;
use Modules\Product\Repositories\Store\StoreRepositoryInterface;
use Modules\Admin\Repositories\CustomerGroup\CustomerGroupRepositoryInterface;
use Modules\Admin\Repositories\CustomerSource\CustomerSourceRepositoryInterface;
use Modules\Payment\Repositories\CompanyBranch\CompanyBranchRepositoryInterface;
use Modules\Admin\Repositories\CustomerGroupFilter\CustomerGroupFilterRepository;

class SurveyRepository implements SurveyRepositoryInterface
{
    use SurveyTrait;
    const NEW = 'N';
    // single_choice: Trắc nghiệm - Chỉ chọn được 1 đáp án
    const SINGLE_CHOICE = 'single_choice';
    // multi_choice: Trắc nghiệm - Có thể chọn nhiều đáp án
    const MULTI_CHOICE = 'multi_choice';
    // matrix_single: Bảng ma trận - Chọn 1 đáp án
    // matrix_multi: Bảng ma trận - Chọn nhiều đáp án
    // matrix_entry: Nhập đáp án
    // text: Tự luận
    const TEXT = 'text';
    // photo_tracking:
    // page_picture
    const PAGE_PICTURE = 'page_picture';
    // Văn bản mô tả
    const PAGE_TEXT = 'page_text';
    // description
    const DESCRIPTION = 'description';

    const KEY_NOTI_TEMPLATE_SURVEY_SUCCESS = 'survey_success';

    // -- Loại câu hỏi "Tự luận"
    //-- Cấu hình "Loại xác nhận"
    const VALID_TYPE_NONE = 'none';
    const VALID_TYPE_MIN = 'min';
    const VALID_TYPE_MAX = 'max';
    const VALID_TYPE_DIGITS_BETWEEN = 'digits_between';
    const VALID_TYPE_EMAIL = 'email';
    const VALID_TYPE_PHONE = 'phone';
    const VALID_TYPE_DATE_FORMAT = 'date_format';
    const VALID_TYPE_NUMERIC = 'numeric';
    const INPUT_TYPE_TEXT = 'text';
    const INPUT_TYPE_NUMBER = 'number';
    // RET-8593 Hiện tại với yêu cầu xuất dữ liệu kết quả xét duyệt sẽ có loại chứng từ là Exp.Report.SurveyResult
    const Exp_Report_SurveyResult = 'Exp.Report.SurveyResult';

    protected $customer_group;
    protected $customer_source;

    public function __construct(
        CustomerGroupRepositoryInterface $customer_groups,
        CustomerSourceRepositoryInterface $customer_sources
    ) {
        $this->customer_group = $customer_groups;
        $this->customer_source = $customer_sources;
    }

    /**
     * Danh sách khảo sát
     * @param $filters
     * @return mixed
     */
    public function getList($filters = [])
    {
        $mSurvey = new SurveyTable();
        $list = $mSurvey->getListNew($filters);
        // Khi ngày hiện tại > ngày kết thúc thì đóng khảo sát lại
        $this->closeSurvey();
        return $list;
    }

    /**
     * Tạo khảo sát
     * @param $params
     * @return mixed
     */
    public function store($params)
    {
        try {
            stripTagParam($params);
            $error = $this->validateDataInfo($params);
            if ($error) {
                return ['error' => true, 'array_error' => $error];
            }
            $data = [
                'survey_name' => $params['survey_name'],
                'survey_code' => $params['survey_code'],
                'survey_description' => $params['survey_description'],
                'survey_banner' => $params['survey_banner'],
                'is_exec_time' => $params['is_exec_time'] ?? 0,
                'start_date' => $params['is_exec_time'] == 1 ? $params['start_date'] : null,
                'end_date' => $params['is_exec_time'] == 1 ? $params['end_date'] : null,
                'close_date' => $params['is_exec_time'] == 1 ? $params['end_date'] : null,
                'frequency' => $params['frequency'],
                'is_limit_exec_time' => $params['is_limit_exec_time'],
                'max_times' => $params['max_times'] == 0 ? 0 : $params['max_times'],
                'allow_all_branch' => 1,
                'is_short_link' => $params['public_link'],
                'count_point' => $params['count_point'],
                'status' => self::NEW,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ];
            $frequencyValue = null;
            // Tần suất thực hiện khảo sát:
            // Hàng tuần - Lặp lại vào thứ: 0,1,2,3,4,5,6
            if ($params['frequency'] == 'weekly') {
                $data['frequency_value'] = $params['frequency_value_weekly'];
            } elseif ($params['frequency'] == 'monthly') {
                // Hàng tháng - Lặp lại vào tháng: 1-12
                $data['frequency_value'] = $params['frequency_value_monthly'];
                // Hàng tháng - Ngày trong tháng/ Ngày trong tuần
                $data['frequency_monthly_type'] = $params['frequency_monthly_type'];
                if ($params['frequency_monthly_type'] == 'day_in_month') {
                    // Ngày trong tháng
                    $data['day_in_monthly'] = $params['day_in_monthly'];
                } else if ($params['frequency_monthly_type'] == 'day_in_week') {
                    // Ngày trong tuần
                    // Lặp lại vào tuần
                    $data['day_in_week'] = $params['day_in_week'];
                    // Lặp lại vào thứ
                    $data['day_in_week_repeat'] = $params['day_in_week_repeat'];
                }
            }
            // Thời gian thực hiện trong ngày
            if ($params['is_limit_exec_time'] == 1) {
                $data['exec_time_from'] = $params['exec_time_from'];
                $data['exec_time_to'] = $params['exec_time_to'];
            }
            $mSurvey = new SurveyTable();
            $id = $mSurvey->add($data);
            // survey branch //
            $this->addSurveyBranch($id);
            // Cài đặt trang hoàn thành mặc định
            $this->addSurveyTemplateNotification($id);
            // Cài đặt cấu hình tính điểm mặc định //
            if ($params['count_point']) {
                $this->addSurveyConfigPoint($id);
            }
            // tạo câu hỏi mặc định cho public link khảo sát //
            if ($params['public_link']) {
                $data =  $this->templateQuestionSurveyPublicLink($id);
            }
            return ['error' => false, 'array_error' => $error, 'id' => $id];
        } catch (\Exception $exception) {
            dd($exception->getMessage(), $exception->getLine());
        }
    }

    /**
     * Thêm toàn bộ chi nhánh vào khảo sát
     * @param $id
     */
    private function addSurveyBranch($id)
    {
        $mBranch = new BranchTable();
        $mSurveyBranch = new SurveyBranchTable();
        $branch = $mBranch->getAll();
        $dataSurveyBranch = [];
        $now = Carbon::now();
        $authId = Auth::id();
        foreach ($branch as $item) {
            $dataSurveyBranch[] = [
                'survey_id' => $id,
                'branch_id' => $item['branch_id'],
                'created_at' => $now,
                'created_by' => $authId,
                'updated_at' => $now,
                'updated_by' => $authId,
            ];
        }
        if (count($dataSurveyBranch) >= MAX_SIZE_INSERT_ARRAY) {
            $mSurveyBranch->addInsert($dataSurveyBranch);
            $dataSurveyBranch = [];
        }
        if ($dataSurveyBranch != []) {
            $mSurveyBranch->addInsert($dataSurveyBranch);
        }
    }

    /**
     * Chi tiết khảo sát
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $mSurvey = new SurveyTable();
        $result = $mSurvey->getItem($id);
        if ($result) {
            $result = $result->toArray();
            $result['frequency_value'] = explode(',', $result['frequency_value']);
            $result['day_in_monthly'] = explode(',', $result['day_in_monthly']);
            $result['day_in_week'] = explode(',', $result['day_in_week']);
            $result['day_in_week_repeat'] = explode(',', $result['day_in_week_repeat']);
            if ($result['is_exec_time'] == 1) {
                $result['start_date_format'] = Carbon::createFromFormat('Y-m-d H:i:s', $result['start_date'])
                    ->format('H:i:s d/m/Y');
                $result['end_date_format'] = Carbon::createFromFormat('Y-m-d H:i:s', $result['end_date'])
                    ->format('H:i:s d/m/Y');
            }
        }
        return $result;
    }

    /**
     * RET-1757
     * [Brand portal] Chỉnh sửa thông tin chung khảo sát
     * @param $params
     * @return mixed
     */
    public function update($params)
    {
        try {
            stripTagParam($params);
            $error = $this->validateDataInfo($params);
            if ($error) {
                return ['error' => true, 'array_error' => $error];
            }
            $data = [
                'survey_name' => $params['survey_name'],
                'survey_code' => $params['survey_code'],
                'survey_description' => $params['survey_description'],
                'survey_banner' => $params['survey_banner'],
                'is_exec_time' => $params['is_exec_time'] ?? 0,
                'start_date' => $params['is_exec_time'] == 1 ? $params['start_date'] : null,
                'end_date' => $params['is_exec_time'] == 1 ? $params['end_date'] : null,
                'close_date' => $params['is_exec_time'] == 1 ? $params['end_date'] : null,
                'frequency' => $params['frequency'],
                'count_point' => $params['count_point'],
                'is_short_link' => $params['public_link'],
                'is_limit_exec_time' => $params['is_limit_exec_time'],
                'max_times' => $params['max_times'] == 0 ? 0 : $params['max_times'],
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ];
            $frequencyValue = null;
            // Tần suất thực hiện khảo sát:
            // Hàng tuần - Lặp lại vào thứ: 0,1,2,3,4,5,6
            if ($params['frequency'] == 'weekly') {
                $data['frequency_value'] = $params['frequency_value_weekly'];
            } elseif ($params['frequency'] == 'monthly') {
                // Hàng tháng - Lặp lại vào tháng: 1-12
                $data['frequency_value'] = $params['frequency_value_monthly'];
                // Hàng tháng - Ngày trong tháng/ Ngày trong tuần
                $data['frequency_monthly_type'] = $params['frequency_monthly_type'];
                if ($params['frequency_monthly_type'] == 'day_in_month') {
                    // Ngày trong tháng
                    $data['day_in_monthly'] = $params['day_in_monthly'];
                } else if ($params['frequency_monthly_type'] == 'day_in_week') {
                    // Ngày trong tuần
                    // Lặp lại vào tuần
                    $data['day_in_week'] = $params['day_in_week'];
                    // Lặp lại vào thứ
                    $data['day_in_week_repeat'] = $params['day_in_week_repeat'];
                }
            }
            // Thời gian thực hiện trong ngày
            if ($params['is_limit_exec_time'] == 1) {
                $data['exec_time_from'] = $params['exec_time_from'];
                $data['exec_time_to'] = $params['exec_time_to'];
            }
            $mSurvey = new SurveyTable();
            $mSurvey->edit($params['survey_id'], $data);
            return ['error' => false, 'array_error' => $error];
        } catch (\Exception $exception) {
            dd($exception->getMessage(), $exception->getLine());
        }
    }

    /**
     * Tạo session default cho tab câu hỏi khảo sát
     * @param $id
     * @param $unique
     * @return mixed
     */
    public function setSessionDefaultQuestion($id, $unique)
    {
        $mSurveyBlock = app()->get(SurveyBlockTable::class);
        $mSurveyQuestion = app()->get(SurveyQuestionTable::class);
        $mSurveyQuestionChoice = app()->get(SurveyQuestionChoiceTable::class);
        $surveyBlock = $mSurveyBlock->getBySurveyId($id);
        $mTemplateBlock = app()->get(TemplateBlockTable::class);
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->getItem($id);
        $totalPointDefault = 0;
        $mConfigPoint = app()->get(SurveyConfigPointTable::class);
        $configPoint = $mConfigPoint->getConfigBySurvey($id);
        if ($configPoint) {
            $totalPointDefault = $configPoint->point_default;
        }
        if (!$itemSurvey) return abort(404);
        $isCountPoint = $itemSurvey->count_point;
        $data = [];
        $totalPoint = 0;
        // kiểm tra khao sát có các câu hỏi mặc định public link
        $isQuestionPulicLink = $itemSurvey->is_short_link;
        if (count($surveyBlock) > 0) {
            foreach ($surveyBlock as $key => $item) {
                if ($isQuestionPulicLink && $key == 0) {

                    $listTemplateQuestion = $mTemplateBlock->where('survey_block_id', $item['survey_block_id'])->pluck('key_template')->toArray();
                    $temp = [
                        'block_name' => $item['survey_block_name'],
                        'position' => $item['survey_block_position'],
                        'question' => [],
                        'totalPointDefault' => $totalPointDefault,
                        'template' => $listTemplateQuestion,
                        'question_pl_defaul' => true
                    ];
                    $question = $mSurveyQuestion->getBySurveyBlockId($item['survey_block_id']);
                    if (count($question) > 0) {
                        foreach ($question as $key => $itemQuestion) {
                            if ($key <= 4) {
                                if ($itemQuestion['survey_question_type'] == self::SINGLE_CHOICE || $itemQuestion['survey_question_type'] == self::MULTI_CHOICE) {
                                    // lấy danh sách câu trả lời không tính điểm 
                                    $answer = $mSurveyQuestionChoice->getBySurveyQuestionId($itemQuestion['survey_question_id'])
                                        ->pluck('survey_question_choice_title')->toArray();
                                    // lấy danh sách câu trả lời tính điểm
                                    if ($isCountPoint) {
                                        $listAnswer = $mSurveyQuestionChoice->getBySurveyQuestionId($itemQuestion['survey_question_id']);
                                        $answer = [];
                                        foreach ($listAnswer as $item) {
                                            $answer[$item->survey_question_choice_id] = $item->survey_question_choice_title;
                                        }
                                    }
                                    if ($itemQuestion['survey_question_type'] == self::SINGLE_CHOICE) {
                                        $answerSuccess = $mSurveyQuestionChoice->getAnswerSuccessByQuestionSingleChoice($itemQuestion['survey_question_id']);
                                    } else {
                                        $answerSuccess = $mSurveyQuestionChoice->getAnswerSuccessByQuestionMuitipleChoice($itemQuestion['survey_question_id']);
                                    }
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'is_required' => $itemQuestion['is_required'],
                                        'question' => $itemQuestion['survey_question_description'],
                                        'answer_success' => 1,
                                        'answer' => $answer,
                                        'countPoint' => 0,
                                        'question_pl_defaul' => true
                                    ];
                                    $totalPoint += $itemQuestion['value_point'];
                                } elseif ($itemQuestion['survey_question_type'] == self::TEXT) {
                                    $surveyQuestionConfig = json_decode($itemQuestion['survey_question_config']);
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'question' => $itemQuestion['survey_question_description'],
                                        'is_required' => $itemQuestion['is_required'],
                                        'confirm_type' => $surveyQuestionConfig->valid_type,
                                        'min_value' => $surveyQuestionConfig->valid_option->min ?? '',
                                        'max_value' => $surveyQuestionConfig->valid_option->max ?? '',
                                        'value_text' => $itemQuestion['survey_answer_text'],
                                        'countPoint' => 0,
                                        'question_pl_defaul' => true
                                    ];
                                    $totalPoint += $itemQuestion['value_point'];
                                } elseif ($itemQuestion['survey_question_type'] == self::PAGE_TEXT) {
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'question' => $itemQuestion['survey_question_description'],
                                    ];
                                } elseif ($itemQuestion['survey_question_type'] == self::PAGE_PICTURE) {
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'question' => $itemQuestion['survey_question_description'],
                                        'image' => json_decode($itemQuestion['survey_question_config'])->image
                                    ];
                                }
                            } else {
                                if ($itemQuestion['survey_question_type'] == self::SINGLE_CHOICE || $itemQuestion['survey_question_type'] == self::MULTI_CHOICE) {
                                    // lấy danh sách câu trả lời không tính điểm 
                                    $answer = $mSurveyQuestionChoice->getBySurveyQuestionId($itemQuestion['survey_question_id'])
                                        ->pluck('survey_question_choice_title')->toArray();
                                    // lấy danh sách câu trả lời tính điểm
                                    if ($isCountPoint) {
                                        $listAnswer = $mSurveyQuestionChoice->getBySurveyQuestionId($itemQuestion['survey_question_id']);
                                        $answer = [];
                                        foreach ($listAnswer as $item) {
                                            $answer[$item->survey_question_choice_id] = $item->survey_question_choice_title;
                                        }
                                    }
                                    if ($itemQuestion['survey_question_type'] == self::SINGLE_CHOICE) {
                                        $answerSuccess = $mSurveyQuestionChoice->getAnswerSuccessByQuestionSingleChoice($itemQuestion['survey_question_id']);
                                    } else {
                                        $answerSuccess = $mSurveyQuestionChoice->getAnswerSuccessByQuestionMuitipleChoice($itemQuestion['survey_question_id']);
                                    }
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'is_required' => $itemQuestion['is_required'],
                                        'question' => $itemQuestion['survey_question_description'],
                                        'answer' => $answer,
                                        'answer_success' => $answerSuccess,
                                        'countPoint' => $isCountPoint,
                                        'totalPoint' => (int)$itemQuestion['value_point']
                                    ];
                                    $totalPoint += $itemQuestion['value_point'];
                                } elseif ($itemQuestion['survey_question_type'] == self::TEXT) {
                                    $surveyQuestionConfig = json_decode($itemQuestion['survey_question_config']);
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'question' => $itemQuestion['survey_question_description'],
                                        'is_required' => $itemQuestion['is_required'],
                                        'confirm_type' => $surveyQuestionConfig->valid_type,
                                        'min_value' => $surveyQuestionConfig->valid_option->min ?? '',
                                        'max_value' => $surveyQuestionConfig->valid_option->max ?? '',
                                        'value_text' => $itemQuestion['survey_answer_text'],
                                        'countPoint' => $isCountPoint,
                                        'totalPoint' => $itemQuestion['value_point']
                                    ];
                                    $totalPoint += $itemQuestion['value_point'];
                                } elseif ($itemQuestion['survey_question_type'] == self::PAGE_TEXT) {
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'question' => $itemQuestion['survey_question_description'],
                                    ];
                                } elseif ($itemQuestion['survey_question_type'] == self::PAGE_PICTURE) {
                                    $temp['question'][] = [
                                        'survey_question_type' => $itemQuestion['survey_question_type'],
                                        'position' => $itemQuestion['survey_question_position'],
                                        'question' => $itemQuestion['survey_question_description'],
                                        'image' => json_decode($itemQuestion['survey_question_config'])->image
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    $listTemplateQuestion = $mTemplateBlock->where('survey_block_id', $item['survey_block_id'])->pluck('key_template')->toArray();
                    $temp = [
                        'block_name' => $item['survey_block_name'],
                        'position' => $item['survey_block_position'],
                        'question' => [],
                        'totalPointDefault' => $totalPointDefault,
                        'template' => $listTemplateQuestion
                    ];
                    $question = $mSurveyQuestion->getBySurveyBlockId($item['survey_block_id']);
                    if (count($question) > 0) {
                        foreach ($question as $itemQuestion) {
                            if ($itemQuestion['survey_question_type'] == self::SINGLE_CHOICE || $itemQuestion['survey_question_type'] == self::MULTI_CHOICE) {
                                // lấy danh sách câu trả lời không tính điểm 
                                $answer = $mSurveyQuestionChoice->getBySurveyQuestionId($itemQuestion['survey_question_id'])
                                    ->pluck('survey_question_choice_title')->toArray();
                                // lấy danh sách câu trả lời tính điểm
                                if ($isCountPoint) {
                                    $listAnswer = $mSurveyQuestionChoice->getBySurveyQuestionId($itemQuestion['survey_question_id']);
                                    $answer = [];
                                    foreach ($listAnswer as $item) {
                                        $answer[$item->survey_question_choice_id] = $item->survey_question_choice_title;
                                    }
                                }
                                if ($itemQuestion['survey_question_type'] == self::SINGLE_CHOICE) {
                                    $answerSuccess = $mSurveyQuestionChoice->getAnswerSuccessByQuestionSingleChoice($itemQuestion['survey_question_id']);
                                } else {
                                    $answerSuccess = $mSurveyQuestionChoice->getAnswerSuccessByQuestionMuitipleChoice($itemQuestion['survey_question_id']);
                                }
                                $temp['question'][] = [
                                    'survey_question_type' => $itemQuestion['survey_question_type'],
                                    'position' => $itemQuestion['survey_question_position'],
                                    'is_required' => $itemQuestion['is_required'],
                                    'question' => $itemQuestion['survey_question_description'],
                                    'answer' => $answer,
                                    'answer_success' => $answerSuccess,
                                    'countPoint' => $isCountPoint,
                                    'totalPoint' => (int)$itemQuestion['value_point']
                                ];
                                $totalPoint += $itemQuestion['value_point'];
                            } elseif ($itemQuestion['survey_question_type'] == self::TEXT) {
                                $surveyQuestionConfig = json_decode($itemQuestion['survey_question_config']);
                                $temp['question'][] = [
                                    'survey_question_type' => $itemQuestion['survey_question_type'],
                                    'position' => $itemQuestion['survey_question_position'],
                                    'question' => $itemQuestion['survey_question_description'],
                                    'is_required' => $itemQuestion['is_required'],
                                    'confirm_type' => $surveyQuestionConfig->valid_type,
                                    'min_value' => $surveyQuestionConfig->valid_option->min ?? '',
                                    'max_value' => $surveyQuestionConfig->valid_option->max ?? '',
                                    'value_text' => $itemQuestion['survey_answer_text'],
                                    'countPoint' => $isCountPoint,
                                    'totalPoint' => $itemQuestion['value_point']
                                ];
                                $totalPoint += $itemQuestion['value_point'];
                            } elseif ($itemQuestion['survey_question_type'] == self::PAGE_TEXT) {
                                $temp['question'][] = [
                                    'survey_question_type' => $itemQuestion['survey_question_type'],
                                    'position' => $itemQuestion['survey_question_position'],
                                    'question' => $itemQuestion['survey_question_description'],
                                ];
                            } elseif ($itemQuestion['survey_question_type'] == self::PAGE_PICTURE) {
                                $temp['question'][] = [
                                    'survey_question_type' => $itemQuestion['survey_question_type'],
                                    'position' => $itemQuestion['survey_question_position'],
                                    'question' => $itemQuestion['survey_question_description'],
                                    'image' => json_decode($itemQuestion['survey_question_config'])->image
                                ];
                            }
                        }
                    }
                }
                $data[] = $temp;
            }
        }
        $this->putDataSessionBlock($unique, $data);
        if ($isCountPoint) {
            $this->putDataSessionTotalPoint($unique, $totalPoint);
        }
    }

    /**
     * RET-1761
     * [Brand portal] Thêm, sửa và xóa nhóm câu hỏi (block) trong khảo sát
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function addBlock($params)
    {
        $data = $this->getDataSessionBlock($params['unique']);
        $this->sortBlock($data, $params['number']);
        $totalPointDefault = 0;
        $mConfigPoint = app()->get(SurveyConfigPointTable::class);
        $configPoint = $mConfigPoint->getConfigBySurvey($params['id']);
        if ($configPoint) {
            $totalPointDefault = $configPoint->point_default;
        }
        $data[] = [
            'block_name' => '',
            'position' => (int) ($params['number'] + 1),
            'question' => [],
            'totalPointDefault' => $totalPointDefault
        ];
        // Sắp xếp block theo position (desc) -> Đảo ngược các phần tử trong mảng
        $data = array_reverse(collect($data)->sortByDesc(['position'])->toArray());
        $this->putDataSessionBlock($params['unique'], $data);
        return $data;
    }

    /**
     * Load html Block
     * @param $params
     * @return array
     * @throws \Throwable
     */
    public function loadBlock($params)
    {
        $data = $this->getDataSessionBlock($params['unique']);
        $mTemplateQuestion = app()->get(TemplateQuestionTable::class);
        $templateQuestion = $mTemplateQuestion->get();
        return [
            'html' => view('survey::survey.question.block.block', [
                'data' => $data,
                'params' => $params,
                'templateQuestion' => $templateQuestion
            ])->render(),
            'data' => $data,
        ];
    }

    /**
     * Thay đổi gì đó ở block
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function onChangeBlock($params)
    {
        try {

            $data = $this->getDataSessionBlock($params['unique']);
            $totalPoint = $this->getDataSessionTotalPoint($params['unique']) ?? 0;
            $mSurvey = app()->get(SurveyTable::class);
            $itemSurvey = $mSurvey->getItem($params['id']);
            $isCountPoint = $itemSurvey->count_point;
            $number = $params['number'];

            // Thực hiện xóa block
            if ($params['action'] == 'remove') {
                if ($itemSurvey->is_short_link && $number == 0) {
                    return $data;
                }
                if ($isCountPoint) {
                    $totalOld = 0;
                    foreach ($data[$number]['question'] as $item) {
                        if (isset($item['totalPoint'])) {
                            $totalOld += $item['totalPoint'];
                        }
                    }
                    $totalPoint = $totalPoint - $totalOld;
                    $this->putDataSessionTotalPoint($params['unique'], $totalPoint);
                }
                unset($data[$number]);
            } elseif ($params['action'] == 'collapse') {
                $data[$number][$params['element']] = (int) $params['value'] ?? 0;
            } elseif ($params['action'] == 'change') {
                // Thay đổi tên block
                $data[$number][$params['element']] = $params['value'] ?? '';
            } else {

                // Phải có hơn 2 block mới xử lý case này
                if (count($data) == 1) {
                    return;
                }
                // Thay đổi vị trí của block
                // Di chuyển block lên phía trên: Trừ block trên cùng
                if ($params['value'] == 'up' && $number > 0) {
                    $data[$number - 1]['position'] = (int) ($data[$number - 1]['position'] + 1);
                    $data[$number]['position'] = (int) $data[$number]['position'] - 1;
                } elseif ($params['value'] == 'down' && $number < count($data) - 1) {
                    // Di chuyển block xuống phía dưới: Trừ block dưới cùng
                    $data[$number + 1]['position'] = (int) ($data[$number + 1]['position'] - 1);
                    $data[$number]['position'] = (int) $data[$number]['position'] + 1;
                }
            }
            // Sắp xếp block theo position (desc) -> Đảo ngược các phần tử trong mảng
            $data = array_reverse(collect($data)->sortByDesc(['position'])->toArray());
            $this->putDataSessionBlock($params['unique'], $data);
            return $data;
        } catch (\Exception $exception) {
        }
    }

    /**
     * Render html để thêm câu hỏi vào block
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function addQuestion($params)
    {
        $data = $this->getDataSessionBlock($params['unique']);
        // tổng điểm của câu hỏi tính điểm //
        $totalCountPoint = (int) $this->getDataSessionTotalPoint($params['unique']) ?? 0;
        $mConfigPoint = app()->get(SurveyConfigPointTable::class);
        $totalPointDefault = 10;
        $itemConfigPoint = $mConfigPoint->getConfigBySurvey($params['id']);
        if ($itemConfigPoint) {
            $totalPointDefault = $itemConfigPoint->point_default;
        }
        $surveyQuestionType = $params['survey_question_type'];
        // kiểm tra có tính điểm //
        $countPoint = $params['countPoint'] ?? "";
        // tổng điểm //
        $totalPoint = $params['totalPoint'] ?? $totalPointDefault;
        // Phải có block
        if (!empty($data[$params['block_number']])) {
            if (isset($params['change_question']) && $params['change_question'] == 1) {
                // Thay đổi loại câu hỏi
                $temp = $data[$params['block_number']]['question'][$params['question_number']];
                if ($surveyQuestionType != $temp['survey_question_type']) {
                    $temp['survey_question_type'] = $surveyQuestionType;
                    if ($surveyQuestionType == self::SINGLE_CHOICE || $surveyQuestionType == self::MULTI_CHOICE) {
                        // Loại câu hỏi: Trắc nghiệm
                        unset($temp['confirm_type']);
                        $temp['is_required'] = 1;
                        $temp['question'] = '';
                        $temp['answer'] = ['', '', ''];
                        $temp['answer_success'] = [];
                        $temp['countPoint'] = $countPoint;
                        $temp['totalPoint'] = $totalPoint;
                        $totalCountPoint += $totalCountPoint;
                    } elseif ($surveyQuestionType == self::TEXT) {
                        // Loại câu hỏi: Tự luận
                        unset($temp['answer']);
                        // Loại xác nhận
                        $temp['confirm_type'] = 'none';
                        $temp['is_required'] = 1;
                        $temp['min_value'] = '';
                        $temp['max_value'] = '';
                        $temp['countPoint'] = $countPoint;
                        $temp['totalPoint'] = $totalPoint;
                        $temp['value_text'] = '';
                        $totalCountPoint += $totalCountPoint;
                    } elseif ($surveyQuestionType == self::PAGE_TEXT) {
                        $temp = [
                            'survey_question_type' => $surveyQuestionType,
                            'position' => $temp['position'],
                            'question' => '',
                        ];
                    } elseif ($surveyQuestionType == self::PAGE_PICTURE) {
                        $temp = [
                            'survey_question_type' => $surveyQuestionType,
                            'position' => $temp['position'],
                            'question' => '',
                            'image' => [
                                ''
                            ]
                        ];
                    }
                    $data[$params['block_number']]['question'][$params['question_number']] = $temp;
                    $this->putDataSessionBlock($params['unique'], $data);
                    $this->putDataSessionTotalPoint($params['unique'], $totalCountPoint);
                }
            } else if (strpos($surveyQuestionType, 'coppy') !== false) {

                $surveyQuestionType = explode('-', $surveyQuestionType)[1];
                // Vị trí block 
                $block = $data[$params['block_number']];
                // danh sách câu hỏi 
                $question = $block['question'];
                // Vị trí câu hỏi coppy trong danh sách câu hỏi block
                $positionCoppy = $params['position'] - 2;
                // Câu hỏi coppy //
                $questionCoppy = $question[$positionCoppy];
                // sort đổi vị trí câu hỏi
                if ($question) {
                    foreach ($question as $key => $value) {
                        // Câu hỏi nào có vị trí >= vị trí muốn thêm thì += 1
                        if ($value['position'] >= $params['position']) {
                            $question[$key]['position'] += 1;
                        }
                    }
                }
                if ($surveyQuestionType == self::SINGLE_CHOICE || $surveyQuestionType == self::MULTI_CHOICE) {
                    /**
                     * RET-1746
                     * [Brand portal] Cài đặt câu hỏi khảo sát - Multiple choice
                     * Trắc nghiệm
                     */
                    $question[] = [
                        'survey_question_type' => $surveyQuestionType,
                        'position' => $questionCoppy['position'] + 1,
                        'is_required' => $questionCoppy['is_required'],
                        'question' => $questionCoppy['question'],
                        'answer' => $questionCoppy['answer'],
                        'countPoint' => $questionCoppy['countPoint'],
                        'totalPoint' => $questionCoppy['totalPoint'],
                        'answer_success' => $questionCoppy['answer_success']
                    ];
                    $totalCountPoint += $questionCoppy['totalPoint'];
                    // dd($question);
                } elseif ($surveyQuestionType == self::TEXT) {
                    /**
                     * RET-1753
                     * [Bran portal] Cài đặt câu hỏi khảo sát - Text entry
                     * Tự luận
                     */
                    $question[] = [
                        'survey_question_type' => $surveyQuestionType,
                        'position' => $questionCoppy['position'] + 1,
                        'question' => $questionCoppy['question'],
                        'is_required' => $questionCoppy['is_required'],
                        'confirm_type' => $questionCoppy['confirm_type'],
                        'min_value' => $questionCoppy['min_value'],
                        'max_value' => $questionCoppy['max_value'],
                        'countPoint' => $questionCoppy['countPoint'],
                        'totalPoint' => $questionCoppy['totalPoint'],
                        'value_text' => $questionCoppy['value_text']
                    ];
                    $totalCountPoint += $questionCoppy['totalPoint'];
                } elseif ($surveyQuestionType == self::PAGE_TEXT) {
                    /**
                     * RET-1754
                     * [Brand portal] Cài đặt câu hỏi khảo sát - Descriptive text
                     * Văn bản mô tả
                     */
                    $question[] = [
                        'survey_question_type' => $surveyQuestionType,
                        'position' => $questionCoppy['position'] + 1,
                        'question' => $questionCoppy['question'],
                    ];
                } elseif ($surveyQuestionType == self::PAGE_PICTURE) {
                    /**
                     * RET-1760
                     * [Brand portal] Cài đặt câu hỏi khảo sát - Graphic
                     * Hình ảnh minh họa
                     */
                    $question[] = [
                        'survey_question_type' => $surveyQuestionType,
                        'position' => $questionCoppy['position'] + 1,
                        'question' => $questionCoppy['question'],
                        'image' => $questionCoppy['image']
                    ];
                }

                // meger vào list question của block
                $data[$params['block_number']]['question'] = $question;
                $this->sortQuestion($data);
                $this->putDataSessionBlock($params['unique'], $data);
                $this->putDataSessionTotalPoint($params['unique'], $totalCountPoint);
            } else {
                $block = $data[$params['block_number']];
                // RET-1761 Trong scope hiện tại cho phép thêm tối đa 20 câu hỏi mỗi block
                if (count($block['question']) < 20) {
                    // Thêm câu hỏi ở vị trí cuối cùng
                    if ($params['add_custom'] == 0) {
                        if ($surveyQuestionType == self::SINGLE_CHOICE || $surveyQuestionType == self::MULTI_CHOICE) {
                            /**
                             * RET-1746
                             * [Brand portal] Cài đặt câu hỏi khảo sát - Multiple choice
                             * Trắc nghiệm
                             */
                            $block['question'][] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => count($block['question']) + 1,
                                'is_required' => 1,
                                'question' => '',
                                'answer' => ['', '', ''],
                                'answer_success' => [],
                                'countPoint' => $countPoint,
                                'totalPoint' => $totalPoint
                            ];
                            $totalCountPoint += $totalPoint;
                        } elseif ($surveyQuestionType == self::TEXT) {
                            /**
                             * RET-1753
                             * [Bran portal] Cài đặt câu hỏi khảo sát - Text entry
                             * Tự luận
                             */
                            $block['question'][] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => count($block['question']) + 1,
                                'question' => '',
                                'is_required' => 1,
                                'confirm_type' => 'none',
                                'min_value' => '',
                                'max_value' => '',
                                'countPoint' => $countPoint,
                                'totalPoint' => $totalPoint,
                                'value_text' => ''
                            ];
                            $totalCountPoint += $totalPoint;
                        } elseif ($surveyQuestionType == self::PAGE_TEXT) {
                            /**
                             * RET-1754
                             * [Brand portal] Cài đặt câu hỏi khảo sát - Descriptive text
                             * Văn bản mô tả
                             */
                            $block['question'][] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => count($block['question']) + 1,
                                'question' => '',
                            ];
                        } elseif ($surveyQuestionType == self::PAGE_PICTURE) {
                            /**
                             * RET-1760
                             * [Brand portal] Cài đặt câu hỏi khảo sát - Graphic
                             * Hình ảnh minh họa
                             */
                            $block['question'][] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => count($block['question']) + 1,
                                'question' => '',
                                'image' => [
                                    ''
                                ]
                            ];
                        }
                    } else {
                        $question = $block['question'];
                        if ($question) {
                            foreach ($question as $key => $value) {
                                // Câu hỏi nào có vị trí >= vị trí muốn thêm thì += 1
                                if ($value['position'] >= $params['position']) {
                                    $question[$key]['position'] += 1;
                                }
                            }
                        }
                        if ($surveyQuestionType == self::SINGLE_CHOICE || $surveyQuestionType == self::MULTI_CHOICE) {
                            /**
                             * RET-1746
                             * [Brand portal] Cài đặt câu hỏi khảo sát - Multiple choice
                             * Trắc nghiệm
                             */
                            // Thêm 1 câu hỏi vào list câu hỏi của block
                            $question[] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => (int) $params['position'],
                                'is_required' => 1,
                                'question' => '',
                                'answer' => ['', '', '']
                            ];
                            $block['question'] = $question;
                        } elseif ($surveyQuestionType == self::TEXT) {
                            /**
                             * RET-1753
                             * [Bran portal] Cài đặt câu hỏi khảo sát - Text entry
                             * Tự luận
                             * confirm_type: Loại xác nhận
                             * - none - Không
                             * - min - Số ký tự tối thiểu(radio)
                             * - min_value - Số ký tự tối thiểu
                             * - max - Số ký tự tối đa(radio)
                             * - max_value - Số ký tự tối đa
                             * - digits_between - Chọn số ký tự(radio)
                             * - email - Xác nhận nội dung - Địa chỉ email(radio)
                             * - phone - Xác nhận nội dung - Số điện thoại(radio)
                             * - date_format - Xác nhận nội dung - Định dạng ngày(radio)
                             * - number - Xác nhận nội dung - Number(radio)
                             * - min_value - Xác nhận nội dung - Number - Giá trị nhỏ nhất
                             * - max_value - Xác nhận nội dung - Number - Giá trị lớn nhất
                             */
                            // Thêm 1 câu hỏi vào list câu hỏi của block
                            $question[] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => (int) $params['position'],
                                'question' => '',
                                'is_required' => 1,
                                'confirm_type' => 'none',
                                'min_value' => null,
                                'max_value' => null,
                            ];
                            $block['question'] = $question;
                        } elseif ($surveyQuestionType == self::PAGE_TEXT) {
                            /**
                             * RET-1754
                             * [Brand portal] Cài đặt câu hỏi khảo sát - Descriptive text
                             * Văn bản mô tả
                             */
                            // Thêm 1 câu hỏi vào list câu hỏi của block
                            $question[] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => (int) $params['position'],
                                'question' => '',
                            ];
                            $block['question'] = $question;
                        } elseif ($surveyQuestionType == self::PAGE_PICTURE) {
                            /**
                             * RET-1760
                             * [Brand portal] Cài đặt câu hỏi khảo sát - Graphic
                             * Hình ảnh minh họa
                             */
                            // Thêm 1 câu hỏi vào list câu hỏi của block
                            $question[] = [
                                'survey_question_type' => $surveyQuestionType,
                                'position' => (int) $params['position'],
                                'question' => '',
                                'image' => [
                                    ''
                                ],
                            ];
                            $block['question'] = $question;
                        }
                    }
                    $data[$params['block_number']] = $block;
                    $this->sortQuestion($data);
                    $this->putDataSessionBlock($params['unique'], $data);
                    $this->putDataSessionTotalPoint($params['unique'], $totalCountPoint);
                }
            }
            return $data;
        }
    }

    /**
     * Render html để thêm câu hỏi vào block
     * @param $params
     * @return array
     * @throws \Throwable
     */
    public function loadQuestionInBlock($params)
    {
        $data = $this->getDataSessionBlock($params['unique'])[$params['block_number']];
        return [
            'html' => view('survey::survey.question.block.question-item.list', [
                'data' => $data,
                'params' => $params,
            ])->render(),
            'data' => $data,
        ];
    }

    /**
     * Xóa câu hỏi trong block
     * @param $params
     * @return mixed
     */
    public function removeQuestion($params)
    {
        $data = $this->getDataSessionBlock($params['unique']);
        // tính lại tổng điểm tính điểm //
        $totalpoint = $this->getDataSessionTotalPoint($params['unique']) ?? 0;

        // Nếu tồn tại câu hỏi trong block thì xóa đi
        if (isset($data[$params['block_number']]['question'][$params['question_number']])) {
            if (isset($data[$params['block_number']]['question'][$params['question_number']]['totalPoint']) && $data[$params['block_number']]['question'][$params['question_number']]['totalPoint'] > 0) {
                $totalpoint -= $data[$params['block_number']]['question'][$params['question_number']]['totalPoint'];
            }
            unset($data[$params['block_number']]['question'][$params['question_number']]);
            // Reset key của câu hỏi trong array là stt tăng dần 0, 1,...
            sort($data[$params['block_number']]['question']);
            // Sắp xếp lại câu hỏi
            $this->sortQuestion($data);
            $this->putDataSessionBlock($params['unique'], $data);
            $this->putDataSessionTotalPoint($params['unique'], $totalpoint);

            return ['data' => $data];
        }
    }

    /**
     * Thay đổi vị trí của câu hỏi trong block
     * @param $params
     * @return mixed
     */
    public function changeQuestionPosition($params)
    {
        $data = $this->getDataSessionBlock($params['unique']);
        $question = $data[$params['block_number']]['question'];
        $question = collect($question)->keyBy('position')->toArray();
        $questionSort = [];
        foreach ($params['arrayPosition'] as $key => $value) {
            $question[$value]['position'] = (int) $key;
            $questionSort[] = $question[$value];
        }
        $data[$params['block_number']]['question'] = $questionSort;
        // Sắp xếp lại câu hỏi
        $this->sortQuestion($data);
        $this->putDataSessionBlock($params['unique'], $data);
        return $data;
    }

    /**
     * Chi tiết cài đặt của câu hỏi
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function showConfigQuestion($params)
    {
        // Chi tiết của câu hỏi
        $data = $this->getDataSessionBlock($params['unique'])[$params['block_number']]['question'][$params['question_number']];
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->getItem($params['id']);
        if (!$itemSurvey) return abort(404);
        $isPoint = $itemSurvey->count_point;
        $totalPoint  = $this->getDataSessionTotalPoint($params['unique']);
        return [
            'html' => view('survey::survey.question.block.config.index', [
                'data' => $data,
                'params' => $params,
                'totalPoint' => $totalPoint,
                'isPoint' => $isPoint
            ])->render(),
            'data' => $data,
            'params' => $params,
            'totalPoint' => $totalPoint,
            'isPoint' => $isPoint
        ];
    }

    /**
     * Thay đổi gì đó của câu hỏi
     * @param $params
     * @return mixed
     */
    public function onChangeQuestion($params)
    {

        $data = $this->getDataSessionBlock($params['unique']);
        $totalCountPoint = $this->getDataSessionTotalPoint($params['unique']) ?? 0;
        $question = $data[$params['block_number']]['question'][$params['question_number']];
        switch ($params['element']) {
            case 'question':
                // Thay đổi nội dung câu hỏi
                $question['question'] = $params['value'];
                break;
            case 'answer':
                // Thay đổi nội dung câu trả lời
                $question['answer'][$params['answer_number']] = $params['value'];
                break;
            case 'up':
                if (
                    $question['survey_question_type'] == self::SINGLE_CHOICE
                    || $question['survey_question_type'] == self::MULTI_CHOICE
                ) {
                    // Loại câu hỏi: Trắc nghiệm
                    // Tùy chỉnh đáp án += 1
                    $question['answer'][] = '';
                } else {
                    $question['image'][] = '';
                }
                break;
            case 'down':
                if (
                    $question['survey_question_type'] == self::SINGLE_CHOICE
                    || $question['survey_question_type'] == self::MULTI_CHOICE
                ) {
                    // Loại câu hỏi: Trắc nghiệm
                    // Tùy chỉnh đáp án += 1
                    // Tùy chỉnh đáp án -= 1
                    array_pop($question['answer']);
                } else {
                    array_pop($question['image']);
                }
                break;
            case 'survey_question_type':
                // Hình thức trả lời
                $question['survey_question_type'] = $params['value'];
                break;
            case 'is_required':
                // Bắt buộc trả lời?
                $question['is_required'] = (int) $params['value'];
                break;
            case 'confirm_type':
                /**
                 * RET-1753
                 * [Bran portal] Cài đặt câu hỏi khảo sát - Text entry
                 */
                // Loại xác nhận
                $question['confirm_type'] = $params['value'];
                // Khi thay đổi "Loại xác nhận" thì reset min/ max value về giá trị mặc định (null)
                $question['max_value'] = '';
                $question['min_value'] = '';
                break;
            case 'max_value':
                // Số kí tự tối thiểu/ Giá trị nhỏ nhất
                $question['max_value'] = $params['value'] ?? '';
                break;
            case 'min_value':
                // Số kí tự tối đa/ Giá trị lớn nhất
                $question['min_value'] = $params['value'] ?? '';
                break;
            case 'image':
                // Số kí tự tối đa/ Giá trị lớn nhất
                $question['image'][$params['image_number']] = $params['value'];
                break;
            case 'checked':
                // kiểm tra và sửa trạng thái chọn đáp án đúng 
                if ($question['survey_question_type'] == 'single_choice') {
                    $question['answer_success'] = $params['answer_number'];
                } elseif ($question['survey_question_type'] == 'multi_choice') {
                    if (isset($params['checked'])) {
                        if ($params['checked'] == 1) {
                            if (!is_array($question['answer_success'])) {
                                unset($question['answer_success']);
                            }
                            $question['answer_success'][$params['answer_number']] = $params['answer_number'];
                        } else {
                            foreach ($question['answer_success'] as $key => $value) {
                                if ($params['answer_number'] == $value) {
                                    unset($question['answer_success'][$key]);
                                }
                            }
                            unset($question['answer_success'][$params['answer_number']]);
                        }
                    }
                }
                break;
            case 'total_point':
                // kiểm tra và sửa trạng thái chọn đáp án đúng 
                $pointOld = $question['totalPoint'];
                $totalCountPoint = (int) (($totalCountPoint - $pointOld) + $params['value']);
                $this->putDataSessionTotalPoint($params['unique'], $totalCountPoint);
                $question['totalPoint'] = $params['value'];
                break;
            case 'value_text':
                // kiểm tra và sửa trạng thái chọn đáp án đúng 
                $question['value_text'] = $params['value'];
                break;
        }
        $data[$params['block_number']]['question'][$params['question_number']] = $question;
        $this->putDataSessionBlock($params['unique'], $data);
        return $data;
    }

    /**
     * Hiển thị mẫu list câu hỏi
     * @param $params
     * @return mixed
     */

    public function loadTemplateQuestion($params)
    {
        $mSurvey = app()->get(SurveyTable::class);
        $mSurveyConfigPoint = app()->get(SurveyConfigPointTable::class);
        $totalPoint = 10;
        $itemSurveyConfigPoint = $mSurveyConfigPoint->getConfigBySurvey($params['id']);
        if ($itemSurveyConfigPoint) {
            $totalPoint = $itemSurveyConfigPoint->point_default;
        }
        $itemSurvey = $mSurvey->getItem($params['id']);
        $isCountPoint = $itemSurvey->count_point;
        switch ($params['template']) {
            case 'template_1':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionOne(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_2':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionTwo(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_3':
                $kedding = $params['keeding'] ?? "";
                return  $this->templateQuestionThree(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_4':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionFour(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_5':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionFive(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_6':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionSix(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_7':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionSeven(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_8':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionEight(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_9':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionNine(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            case 'template_10':
                $kedding = $params['keeding'] ?? "";
                return $this->templateQuestionTen(
                    $params['key'],
                    $params['unique'],
                    $params['template'],
                    $kedding,
                    $totalPoint,
                    $isCountPoint
                );
                break;
            default:

                break;
        }
    }

    /**
     * template_1
     * @param $key
     * @param $unique
     */

    public function templateQuestionOne($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Mức độ hài lòng với mức lương hiện tại ?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Mức lương tại công ty khá cạnh tranh so với những công ty cùng lĩnh vực trên thị trường?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Công ty có những tiêu chí đánh giá năng lực nhân viên để xét tăng lương rất hợp lý?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với việc tăng lương dựa trên năng lực là cách động viên để nhân viên phát huy khả năng của mình?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Cách chia thưởng của công ty có hợp lý và đúng tiến độ?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],

            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với các đợt khảo sát về lương hàng năm của công ty?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_2
     * @param $key
     * @param $unique
     */

    public function templateQuestionTwo($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với sự quan tâm, động viên của các cấp trên khi gặp khó khăn trong công việc lẫn cuộc sống?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Các đồng nghiệp luôn là những người bạn hoà đồng, thân thiện, hỗ trợ nhau trong công việc?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Công ty có hiện tượng chia rẽ theo nhóm không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng về việc công ty hỗ trợ đào tạo và hướng dẫn những kỹ năng cần thiết cho công việc không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với cơ sở vật chất công ty đã trang bị cho bạn không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có được trang bị đầy đủ các vật dụng hỗ trợ trong công việc không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng về việc công ty thường tổ chức những hoạt động vui chơi phù hợp với văn hoá doanh nghiệp của công ty không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với các hoạt động của công ty: vui chơi, sinh nhật, du lịch hàng năm không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có luôn được đóng góp ý kiến và được ghi nhận ý kiến từ cấp trên và đồng nghiệp?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_3
     * @param $key
     * @param $unique
     */
    public function templateQuestionThree($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có yêu thích và hài lòng với công việc hiện tại không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Công việc luôn có nhiều đổi mới hàng ngày và bạn có mục tiêu công việc rõ ràng?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng và hiểu rõ về mục tiêu công ty đã được phổ biến không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với chính sách của công ty trong việc bạn sẽ được đón nhận ở một vị trí khác cao hơn nếu bạn có năng lực?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Công ty luôn có các chế độ cũng như các hình thức bảo vệ sự an toàn cho mọi nhân viên?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn luôn được góp ý thẳng thắn khi phạm lỗi trong công việc?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);

        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_4
     * @param $key
     * @param $unique
     */
    public function templateQuestionFour($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn biết đến sản phẩm/dịch vụ của công ty qua phương tiện nào?'),
                'answer' => [
                    __('Tivi, báo chí'),
                    __('Internet'),
                    __('Bạn bè giới thiệu'),
                    __('Mục khác'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []

            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Tần suất sử dụng sản phẩm/dịch vụ?'),
                'answer' => [
                    __('Hàng ngày'),
                    __('Hàng tuần'),
                    __('Hàng tháng'),
                    __('Chưa sử dụng bao giờ'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Dựa trên những lần sử dụng sản phẩm trước, bạn có sẵn sàng giới thiệu sản phẩm này đến người khác không?'),
                'answer' => [
                    __('Không giới thiệu'),
                    __('Sẽ giới thiệu')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với chất lượng sản phẩm/dịch vụ?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với mẫu mã, bao bì của sản phẩm không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với giá cả của sản phẩm/dịch vụ không?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với chất lượng giải quyết khiếu nại của Công ty?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với thái độ nhân viên chăm sóc khách hàng của Công ty/Cửa hàng?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Thái độ nhân viên CSKH có lịch sự không?'),
                'answer' => [
                    __('Rất không lịch sự'),
                    __('Không lịch sự'),
                    __('Bình thường'),
                    __('Lịch sự'),
                    __('Rất lịch sự')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Những phẩm chất nào của nhân viên CSKH khiến bạn hài lòng (Có thể chọn nhiều đáp án)?'),
                'answer' => [
                    __('Kiên nhẫn'),
                    __('Nhiệt tình'),
                    __('Thân thiện'),
                    __('Lắng nghe'),
                    __('Phản ứng nhanh nhẹn')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Điều gì khiến bạn không hài lòng với nhân viên CSKH?'),
                'answer' => [
                    __('Phản hồi thông tin chậm'),
                    __('Phải giải thích nhiều lần'),
                    __('Nói không rõ ràng'),
                    __('Không biết cách xử lý vấn đề'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với thời gian giải quyết vấn đề khiếu nại của Công ty?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn đánh giá thế nào về chất lượng dịch vụ khách hàng của Công ty?'),
                'answer' => [
                    __('Cung cấp thông tin sai'),
                    __('Không hiểu câu hỏi của khách hàng'),
                    __('Trả lời không rõ ràng'),
                    __('Không thể giải quyết vấn đề'),
                    __('Vô trách nhiệm'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Trong 6 tháng tiếp theo, bạn có sẵn sàng thay thế sản phẩm/dịch vụ bằng sản phẩm/dịch vụ khác không?'),
                'answer' => [
                    __('Chắc chắn'),
                    __('Có thể thay đổi'),
                    __('Chưa nghĩ đến'),
                    __('Khả năng thay đổi thấp'),
                    __('Không thay đổi'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }

        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_5
     * @param $key
     * @param $unique
     */
    public function templateQuestionFive($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Độ tuổi của Quý khách?'),
                'answer' => [
                    __('Từ 10 - 30 tuổi'),
                    __('Từ 31 - 40 tuổi'),
                    __('Từ 41 - 50 tuổi'),
                    __('Từ 51 - 60 tuổi'),
                    __('Trên 60 tuổi')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách biết đến chúng tôi qua phương tiện thông tin nào?'),
                'answer' => [
                    __('Internet'),
                    __('Báo chí, tạp chí'),
                    __('Website:'),
                    __('Gia đình, bạn bè'),
                    __('Tivi')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Tại sao Quý khách hàng lựa chọn sử dụng dịch vụ của chúng tôi?'),
                'answer' => [
                    __('Dịch vụ tốt'),
                    __('Địa điểm thuận tiện'),
                    __('Giá hợp lý'),
                    __('Nhân viên chuyên nghiệp'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo Quý khách, thái độ phục vụ của nhân viên chúng tôi là:'),
                'answer' => [
                    __('Thân thiện, lịch sự, có thái độ tốt, phục vụ công bằng với tất cả các khách hàng'),
                    __('Thiếu tính chuyên nghiệp và không quan tâm tới khách hàng'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo Quý khách, môi trường công ty là:'),
                'answer' => [
                    __('Hấp dẫn, thoải mái'),
                    __('Không thoải mái')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Cơ sở vật chất tại chúng tôi:'),
                'answer' => [
                    __('Sang trọng, tiện nghi, chất lượng tốt'),
                    __('Cũ, thiếu tiện nghi, chất lượng kém'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Các dịch vụ:'),
                'answer' => [
                    __('Đa dạng, phong phú, đáp ứng nhu cầu của khách hàng, đạt chuẩn'),
                    __('Nghèo nàn, kém phong phú, không đáp ứng được nhu cầu, không đạt chuẩn'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Thời gian chờ của Quý khách đối với mỗi dịch vụ:'),
                'answer' => [
                    __('Rất lâu'),
                    __('Lâu'),
                    __('Bình thường'),
                    __('Nhanh')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách có cảm thấy thuận tiện và đơn giản khi sử dụng dịch vụ tại?'),
                'answer' => [
                    __('Có'),
                    __('Không'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Khách hàng được giải quyết phàn nàn như thế nào?'),
                'answer' => [
                    __('Nhanh chóng, hài lòng'),
                    __('Chậm trễ, không hài lòng'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Để đánh giá tổng quát chất lượng dịch vụ của chúng tôi thì Quý khách sẽ đánh giá là?'),
                'answer' => [
                    __('Vô cùng hài lòng'),
                    __('Rất hài lòng'),
                    __('Hài lòng'),
                    __('Không hài lòng'),
                    __('Rất không hài lòng'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_6
     * @param $key
     * @param $unique
     */
    public function templateQuestionSix($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách đã từng sử dụng sản phẩm của chúng tôi chưa?'),
                'answer' => [
                    __('Chưa từng dùng'),
                    __('Đang dùng'),
                    __('Đã từng dùng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách có dự định sẽ tiếp tục sử dụng sản phẩm của chúng tôi không?'),
                'answer' => [
                    __('Sẽ tiếp tục sử dụng'),
                    __('Chưa có kế hoạch'),
                    __('Sẽ dùng sản phẩm của cửa hàng khác')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo quý khách thái độ phục vụ và tư vấn của nhân viên chúng tôi như thế nào?'),
                'answer' => [
                    __('Thân thiện, lịch sự, có thái độ tốt, quan tâm đến khách hàng'),
                    __('Chấp nhận được'),
                    __('Thiếu tính chuyên nghiệp, không chấp nhận được')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo quý khách chất lượng và thời gian xử lý hỗ trợ, giải quyết các sự cố của chúng tôi là:'),
                'answer' => [
                    __('Nhanh chóng, kịp thời, thấu đáo'),
                    __('Chấp nhận được'),
                    __('Chưa kịp thời, chậm'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo quý khách giá sản phẩm của chúng tôi như thế nào?'),
                'answer' => [
                    __('Giá cao'),
                    __('Giá hợp lí'),
                    __('Giá rẻ')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khác có gặp trở ngại gì trong quá trình mua - nhận sản phẩm không?'),
                'answer' => [
                    __('Dễ dàng và nhanh chóng'),
                    __('Bình thường'),
                    __('Còn gặp vấn đề')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách có gặp trở ngại gì trong quá trình thanh toán không?'),
                'answer' => [
                    __('Có'),
                    __('Không')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo quý khách nội dung của chúng tôi đã đầy đủ chưa?'),
                'answer' => [
                    __('Nội dung đầy đủ và đẹp'),
                    __('Mơ hồ chưa chi tiết, thiếu chuyên nghiệp')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "text",
                'position' => 1,
                'is_required' => 0,
                'question' => __('Nếu cho thang điểm từ 01 đến 10 để đánh giá chất lượng sản phẩm và dịch vụ của chúng tôi thì quý khách sẽ chấm bao nhiêu điểm?'),
                'confirm_type' => "none",
                'min_value' => '',
                'max_value' => '',
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'value_text' => ""
            ],
            [
                'survey_question_type' => "text",
                'position' => 1,
                'is_required' => 0,
                'question' => __('Quý khách vui lòng cung cấp thông tin cá nhân và đóng góp ý kiến về sản phẩm và dịch vụ chúng tôi đang cung cấp, đây là cơ sở để quý khách nhận được ưu đãi giảm giá từ chúng tôi.'),
                'confirm_type' => "none",
                'min_value' => '',
                'max_value' => '',
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'value_text' => ""
            ],

        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_7
     * @param $key
     * @param $unique
     */
    public function templateQuestionSeven($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách hàng nhận thấy mức giá đối với các báo cáo của chúng tôi hiện nay đã phù hợp chưa?'),
                'answer' => [
                    __('Phù hợp'),
                    __('Nên giảm khoảng 10-15%'),
                    __('Có thể tăng giá nếu đáp ứng các yêu cầu')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách hàng lựa chọn các báo cáo theo tần suất?'),
                'answer' => [
                    __('Tuần'),
                    __('Tháng'),
                    __('Quý'),
                    __('Năm')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách hàng sử dụng các thông tin trong báo cáo cho mục đích gì?'),
                'answer' => [
                    __('Xây dựng kế hoạch kinh doanh hàng quý/hàng năm'),
                    __('Xây dựng dự án đầu tư'),
                    __('Viết báo cáo tư vấn/ báo cáo phân tích'),
                    __('Tham khảo cho đề tài/luận văn/luận án/bài báo'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Bạn có hài lòng với việc tăng lương dựa trên năng lực là cách động viên để nhân viên phát huy khả năng của mình?'),
                'answer' => [
                    __('Rất không hài lòng'),
                    __('Không hài lòng'),
                    __('Bình thường'),
                    __('Hài lòng'),
                    __('Rất hài lòng')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "multi_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Theo Quý khách hàng, các báo cáo cần bổ sung, điều chỉnh như thế nào?'),
                'answer' => [
                    __('Phân tích chuyên sâu hơn'),
                    __('Giảm phân tích, tăng số liệu'),
                    __('Tăng thông tin thế giới'),
                    __('Tăng thông tin trong nước'),
                    __('Cung cấp thông tin doanh nghiệp'),
                    __('Số liệu cần cập nhật hơn'),
                    __('Báo cáo cần dài hơn'),
                    __('Báo cáo cần súc tích hơn')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách hàng chọn hình thức mua hàng nào:'),
                'answer' => [
                    __('Hợp đồng theo năm (gồm báo cáo tháng, quí, năm) với mức giá thấp hơn 20% so với giá bán lẻ'),
                    __('Hợp đồng theo năm với giá thấp hơn 15% so với bán lẻ và ưu đãi giảm giá 50% đối với các báo'),
                    __('cáo gia tăng như: Báo cáo tổng hợp chính sách về ngành hàng trong năm; Báo cáo tổng hợp về hàng rào kỹ thuật đối với ngành hàng trong năm; Báo cáo tổng hợp về các kinh nghiệm sản xuất, kinh doanh thành công đối với ngành hàng trong năm…'),
                    __('Mua lẻ')
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Quý khách hàng có muốn cập nhật thông tin thường xuyên về các sản phẩm mới của chúng tôi hay không'),
                'answer' => [
                    __('Có'),
                    __('Không'),
                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_8
     * @param $key
     * @param $unique
     */
    public function templateQuestionEight($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Hoạt động dịch vụ/sản xuất/kinh doanh của doanh nghiệp có ổn định trong vòng sáu tháng gần đây không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Tình hình nhân sự của Doanh nghiệp có ổn định không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có mong muốn hoạt động dịch vụ/sản xuất/kinh doanh của mình ổn định không? (Về Phương pháp hoạt động, máy móc thiết bị, nhân sự, nguyên vật liệu, v.v.)'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có gặp khó khăn liên quan đến hoạt động quản lý về chất lượng, bảo vệ môi trường, an toàn vệ sinh thực phẩm, an toàn thông tin, v.v.?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có theo dõi các vấn đề không phù hợp phát sinh trong quá trình sản xuất không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có mong muốn liên tục cải thiện tỷ lệ các vấn đề không phù hợp trong hoạt động sản xuất kinh doanh này không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Khách hàng/đối tác của DN có yêu cầu triển khai một hệ thống quản lý mới nào không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ]
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_9
     * @param $key
     * @param $unique
     */
    public function templateQuestionNine($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có quy định, quy chế khuyến khích CBCNV đưa ra các ý tưởng, sáng kiến không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có gặp khó khăn gì trong việc huy động, tổ chức các ý tưởng, sáng kiến của CBCNV trong việc giải quyết vấn đề không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []

            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Có hiện tượng thắt cổ chai trong Doanh nghiệp không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Phương pháp đào tạo, hướng dẫn công việc tại Doanh nghiệp (bao gồm cả thời gian đào tạo thành nghề) có thuận lợi và hiệu quả không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Đánh giá việc tuân thủ các quy định/quy chế của CBCNV trong Doanh nghiệp như thế nào?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có bị gặp khó khăn trong việc phụ thuộc vào kinh nghiệm của “thợ cả” không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có gặp khó khăn gì trong xử lý mối quan hệ giữa các CBCNV trong Doanh nghiệp không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ]
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }

    /**
     * template_10
     * @param $key
     * @param $unique
     */
    public function templateQuestionTen($key, $unique, $template, $keeding, $totalPoint, $countPoint)
    {
        $dataListQuestion = $this->getDataSessionBlock($unique);
        $listQuestion = [
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có phương pháp theo dõi, thống kê các dạng đầu vào/phế phẩm/sản phẩm phát sinh trong quá trình hoạt động không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có biện pháp giảm thiểu thất thoát nguyên vật liệu trong quá trình lưu kho và sản xuất không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có đánh giá về việc sử dụng hiệu quả nguyên nhiên vật liệu và năng lượng trong doanh nghiệp thế nào?'),
                'answer' => [
                    __('Có'),
                    __('Chưa tốt, cần cải tiến'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có gặp khó khăn gì trong việc luân chuyển nguyên vật liệu trong quá trình sản xuất không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Các công đoạn, khu vực làm việc có được bố trí hợp lý nhằm giảm tối đa việc di chuyển không tạo giá trị gia tăng trong quá trình sản xuất chưa?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có sử dụng công cụ hay phương pháp nào để theo dõi việc thực hiện kế hoạch không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có quy định định mức thời gian hay phương pháp tính thời gian chuẩn để hoàn thành xong một công việc nào đó không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Hiện tại phương pháp hoàn thành một sản phẩm/công việc có được thống nhất trong toàn bộ Doanh nghiệp không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Công nhân viên trong DN có phàn nàn rằng nhiều công việc và có yêu cầu tuyền thêm nhân sự không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Có trường hợp CBCNV chờ (nguyên vật liệu, máy vận hành, v.v.) trong quá trình làm việc không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Doanh nghiệp có biện pháp hay áp dụng phương pháp theo dõi hiệu quả hoạt động của máy móc thiết bị và người lao động trong quá trình sản xuất không?'),
                'answer' => [
                    __('Có'),
                    __('Không'),

                ],
                'totalPoint' => $totalPoint,
                'countPoint' => $countPoint,
                'answer_success' => []
            ],
        ];
        $templateQuestion = [
            'block_name' => '',
            'totalPointDefault' => $totalPoint,
            'position' => $key,
            'question' => $listQuestion,
            'template' => [$template]
        ];
        if (!empty($dataListQuestion[$key]['question']) && count($dataListQuestion[$key]['question']) >= 20) {
            return [
                'error' => true,
                'is_modal' => true,
                'message' => __("Số câu hỏi trong block đã vượt quá số câu hỏi quy định")
            ];
        }
        $totalDataListQuestionCurren = count($dataListQuestion[$key]['question']);
        $totalQuesitonTemp = count($listQuestion);
        if (!empty($dataListQuestion[$key]['question'])) {
            $dataListQuestion[$key]['question'] = array_merge($dataListQuestion[$key]['question'], $listQuestion);
            $dataListQuestion[$key]['template'][] = $template;
        } else {
            $dataListQuestion[$key] = $templateQuestion;
        }
        if (count($dataListQuestion[$key]['question']) > 20) {
            $totalQuesitonTemp = 20 - $totalDataListQuestionCurren;
            if (!$keeding) {
                $view = view('survey::survey.question.modal.selected_template_question', [
                    'template' => $template,
                    'key' => $key
                ])->render();
                return [
                    'error' => false,
                    'is_modal' => true,
                    'view' => $view
                ];
            }
            $dataListQuestion[$key]['question'] =  array_slice($dataListQuestion[$key]['question'], 0, 20);
        }
        if ($countPoint) {
            $totalCountPoint = $this->getDataSessionTotalPoint($unique) ?? 0;
            $totalCountPoint =  $totalCountPoint +  ($totalQuesitonTemp * $totalPoint);
            $this->putDataSessionTotalPoint($unique, $totalCountPoint);
        }
        $this->putDataSessionBlock($unique, $dataListQuestion);
        return [
            'error' => false,
            'message' => '',
            'is_modal' => false
        ];
    }


    /**
     * Submit save câu hỏi khảo sát
     * @param $params
     * @return mixed
     */
    public function updateSurveyQuestion($params)
    {
        DB::beginTransaction();
        try {
            stripTagParam($params);
            $id = $params['id'];
            $data = $this->getDataSessionBlock($params['unique']);
            $mSurveyBlock = new SurveyBlockTable();
            $mSurveyQuestion = new SurveyQuestionTable();
            $mSurveyQuestionChoice = new SurveyQuestionChoiceTable();
            $mSurvey = app()->get(SurveyTable::class);
            $itemSurvey = $mSurvey->getItem($id);
            if (!$itemSurvey) return abort(404);
            $countPoint = $itemSurvey->count_point;
            // Nếu không có block nào
            if ($data == []) {
                // Xóa dữ liệu của tab câu hỏi khảo sát
                $this->removeSurveyBlockQuestion($id);
                DB::commit();
                return [
                    'error' => false,
                    'id' => $id,
                    'warning' => ''
                ];
            } else {
                // kiểm tra chấp nhận không chọn đáp án đúng cho câu hỏi tính điểm
                if (!isset($params['warning'])) {
                    // Validate
                    $error = $this->validateDataSurveyQuestion($data, $countPoint);

                    // kiểm tra nếu không chọn đáp án đúng cho câu hỏi tính điểm
                    if (count($error) == 1 && isset($error['select_answer_success'])) {
                        return ['error' => false, 'warning' => $error['select_answer_success']];
                    }
                    if ($error) {
                        if (isset($error['select_answer_success'])) {
                            unset($error['select_answer_success']);
                        }
                        return ['error' => true, 'array_error' => $error];
                    }
                }
            }
            // Xóa dữ liệu của tab câu hỏi khảo sát
            $this->removeSurveyBlockQuestion($id);
            // Lưu dữ liệu
            $now = Carbon::now();
            $authId = auth()->user()->id;
            $bockPosition = 0;
            foreach ($data as $key => $block) {
                $bockPosition++;
                $dataBlock = [
                    'survey_id' => $id,
                    'survey_block_name' => $block['block_name'],
                    'survey_block_position' => $bockPosition,
                    'created_at' => $now,
                    'created_by' => $authId,
                    'updated_at' => $now,
                    'updated_by' => $authId,
                ];
                $surveyBlockId = $mSurveyBlock->add($dataBlock);
                $templateQuestion = $block['template'] ?? [];
                $this->syncTemplateQuestion($surveyBlockId, $templateQuestion);
                $questionPosition = 0;
                foreach ($block['question'] as $k => $question) {
                    // kiểm tra câu hỏi có được tính điểm
                    $valuePoint = 0;
                    if (!empty($question['answer_success']) || $question['survey_question_type'] == self::TEXT) {
                        $valuePoint = $question['totalPoint'] ?? 0;
                    }
                    $questionPosition++;
                    $dataSurveyQuestion = [
                        //'parent_id',
                        'survey_id' => $id,
                        'survey_block_id' => $surveyBlockId,
                        'survey_question_title' => '',
                        'survey_question_description' => $question['question'],
                        'survey_question_type' => $question['survey_question_type'],
                        'is_required' => $question['is_required'] ?? 0,
                        'value_point' => $valuePoint,
                        'is_combine_question' => 0,
                        'survey_question_position' => $questionPosition,
                        'created_at' => $now,
                        'created_by' => $authId,
                        'updated_at' => $now,
                        'updated_by' => $authId,
                    ];
                    if (
                        $question['survey_question_type'] == self::SINGLE_CHOICE
                        || $question['survey_question_type'] == self::MULTI_CHOICE
                    ) {
                        // Trắc nghiệm
                        // $dataSurveyQuestion['survey_question_title'] = $question['question'];
                        $dataSurveyQuestion['num_choice'] = (int) count($question['answer']);
                    } elseif ($question['survey_question_type'] == self::TEXT) {
                        // Tự luận
                        // $dataSurveyQuestion['survey_question_title'] = $question['question'];
                        $dataSurveyQuestion['survey_answer_text'] = $question['value_text'] ??  null;
                        $temp = ['valid_type' => $question['confirm_type']];
                        if ($question['confirm_type'] == self::VALID_TYPE_MIN) {
                            $temp['valid_option'] = [
                                'input_type' => self::INPUT_TYPE_TEXT,
                                'min' => (int) $question['min_value']
                            ];
                        } elseif ($question['confirm_type'] == self::VALID_TYPE_MAX) {
                            $temp['valid_option'] = [
                                'input_type' => self::INPUT_TYPE_TEXT,
                                'max' => (int) $question['max_value']
                            ];
                        } elseif ($question['confirm_type'] == self::VALID_TYPE_DIGITS_BETWEEN) {
                            $temp['valid_option'] = [
                                'input_type' => self::INPUT_TYPE_TEXT,
                                'min' => (int) $question['min_value'],
                                'max' => (int) $question['max_value']
                            ];
                        } elseif ($question['confirm_type'] == self::VALID_TYPE_NUMERIC) {
                            $temp['valid_option'] = [
                                'input_type' => self::INPUT_TYPE_NUMBER,
                                'min' => (int) $question['min_value'],
                                'max' => (int) $question['max_value']
                            ];
                        }
                        $dataSurveyQuestion['survey_question_config'] = json_encode($temp);
                    } elseif ($question['survey_question_type'] == self::PAGE_PICTURE) {
                        // Hình ảnh minh họa
                        $dataSurveyQuestion['is_combine_question'] = 1;
                        $temp = [
                            'num_image' => (int)count($question['image']),
                            'image' => $question['image']
                        ];
                        $dataSurveyQuestion['survey_question_config'] = json_encode($temp);
                    } elseif ($question['survey_question_type'] == self::PAGE_TEXT) {
                        $dataSurveyQuestion['is_combine_question'] = 1;
                    }
                    $surveyQuestionId = $mSurveyQuestion->add($dataSurveyQuestion);
                    if (
                        $question['survey_question_type'] == self::SINGLE_CHOICE
                        || $question['survey_question_type'] == self::MULTI_CHOICE
                    ) {
                        // Trắc nghiệm
                        // Lưu câu trả lời
                        $dataSurveyQuestionChoice = [];
                        foreach ($question['answer'] as $ka => $vAnswer) {
                            $valueQuestionChoice = null;
                            if (!is_array($question['answer_success'])) {
                                if ($question['answer_success'] == $ka) {
                                    $valueQuestionChoice = 1;
                                }
                            } else {
                                if (in_array($ka, $question['answer_success'])) {
                                    $valueQuestionChoice = 1;
                                }
                            }
                            $dataSurveyQuestionChoice[] = [
                                'survey_question_id' => $surveyQuestionId,
                                'survey_id' => $id,
                                'survey_question_choice_title' => $vAnswer,
                                // 'survey_question_choice_value',
                                'survey_question_choice_position' => $ka + 1,
                                'survey_question_choice_config' => '{}',
                                'survey_question_choice_value' => $valueQuestionChoice
                            ];
                        }
                        $mSurveyQuestionChoice->addInsert($dataSurveyQuestionChoice);
                    }
                }
            }
            DB::commit();
            return [
                'error'   => false,
                'warning' => '',
                'id'   => $id,
            ];
        } catch (\Exception $exception) {
          
            DB::rollBack();
            return [
                'error'   => true,
                'getLine'   => $exception->getLine(),
                'getMessage'   => $exception->getMessage(),
            ];
        }
    }

    /**
     * Đồng bộ template block 
     * @param $idBlock
     * @param $listTemplate
     * @return mixed
     */

    public function syncTemplateQuestion($idBlock, $listTemplate)
    {
        $mTemplateBlock = app()->get(TemplateBlockTable::class);
        $itemTempBlock = $mTemplateBlock->where('survey_block_id', $idBlock)->delete();
        $dataInsert = [];

        foreach ($listTemplate as $item) {
            $dataInsert[] = [
                'survey_block_id' => $idBlock,
                'key_template' => $item
            ];
        }
        $mTemplateBlock->insert($dataInsert);
    }

    /**
     * Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function showModalNotification($params)
    {
        stripTagParam($params);
        $mSurveyTemplateNotification = app()->get(SurveyTemplateNotificationTable::class);
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->getItem($params['survey_id']);
        // Check xem có cài đặt trang hoàn thành trong db chưa
        $check = $mSurveyTemplateNotification->getDetailBySurveyId($params['survey_id']);
        // Nếu chưa có cài đặt trang hoàn thành trong db thì insert mặc định vào
        if (!$check) {
            $this->addSurveyTemplateNotification($params['survey_id']);
        }
        $data = $mSurveyTemplateNotification->getDetailBySurveyId($params['survey_id']);
        return [
            'html' => view('survey::survey.modal.config-notification', [
                'data' => $data,
                'isCountPoint' => $itemSurvey->count_point
            ])->render()
        ];
    }

    /**
     * Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param $params
     * @return array|mixed
     * @throws \Throwable
     */
    public function showModalConfigPoint($params)
    {
        stripTagParam($params);
        $mSurveyConfigPoint = app()->get(SurveyConfigPointTable::class);
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->getItem($params['survey_id']);
        // Check xem có cài đặt trang hoàn thành trong db chưa
        $itemConfigPoint = $mSurveyConfigPoint->getConfigBySurvey($params['survey_id']);
        // Nếu chưa có cài đặt trang hoàn thành trong db thì insert mặc định vào
        if (!$itemConfigPoint) {
            $itemConfigPoint =  $this->addSurveyConfigPoint($params['survey_id']);
        }
        return [
            'html' => view('survey::survey.modal.config-point', [
                'data' => $itemConfigPoint,
                'isCountPoint' => $itemSurvey->count_point,

            ])->render(),
            'time_start' => $itemConfigPoint->time_start ? Carbon::parse($itemConfigPoint->time_start)->format('H:i:s m/d/Y') : '',
            'time_end' => $itemConfigPoint->time_end ? Carbon::parse($itemConfigPoint->time_end)->format('H:i:s m/d/Y') : ''
        ];
    }

    /**
     * Update template Cài đặt trang hiển thị sau khi hoàn thành khảo sát
     * @param $params
     * @return array
     */
    public function updateTemplate($params)
    {
        stripTagParam($params);
        $mSurveyTemplateNotification = app()->get(SurveyTemplateNotificationTable::class);
        $dataUpdate = [
            'title' => $params['title'],
            'detail_background' => $params['detail_background'],
            'message' => $params['message'],
            'show_point' => $params['show_point'] ?? "",
            'updated_at' => Carbon::now(),
        ];
        $mSurveyTemplateNotification->updateBySurveyId($dataUpdate, $params['survey_id']);
        return ['error' => false];
    }

    /**
     * Update template Cài đặt cấu hình khảo sát có tính điểm
     * @param $params
     * @return array
     */
    public function updateConfigPoint($params)
    {
        try {
            stripTagParam($params);
            // validate cấu hình thời gian
            $error = $this->validationConfigPoint($params);

            if ($error) return ['error' => true, 'message' => $error];
            if ($params['show_answer'] == 'C') {
                $params['time_start'] =  Carbon::createFromFormat('H:i:s d/m/Y', $params['time_start'])
                    ->format('Y-m-d H:i:s');
                $params['time_end'] = Carbon::createFromFormat('H:i:s d/m/Y', $params['time_end'])->format('Y-m-d H:i:s');
            } else {
                $params['time_start'] = null;
                $params['time_end'] = null;
            }
            $mConfigPoint = app()->get(SurveyConfigPointTable::class);
            $itemConfigPoint = $mConfigPoint->find($params['id']);

            $itemConfigPoint->update($params);
            return ['error' => false, 'message' => __('Cập nhật cài đặt khảo sát tính điểm thành công')];
        } catch (\exception $e) {
            Log::info('update_config_point : ' . $e->getMessage());
            return ['error' => true, 'message' => __('Cập nhật cài đặt khảo sát tính điểm thất bại')];
        }
    }

    /**
     * Option outlet
     * @param $params
     * @return \Illuminate\Http\JsonResponse
     */
    public function optionLoadMore($params)
    {
        $filter = [
            'keyword_survey$survey_name' => $params['search'] ?? null,
            'in_status' => ['N', 'R'],
        ];
        $mSurvey = new SurveyTable();
        $data = $mSurvey->getListNew($filter);
        $items = [];
        $pagination = false;
        try {
            $items = $data->items();
            $pagination = range($data->currentPage(), $data->lastPage()) ? true : false;
        } catch (\Exception $e) {
        }
        return response()->json([
            'total' => $data->total(),
            'items' => $items,
            'pagination' => $pagination
        ]);
    }

    /**
     * Gán branch trong db vào session
     * @param $id
     * @param $unique
     * @return mixed
     */
    public function setSessionOutletDefault($id, $unique)
    {
        $mSurveyBranch = new SurveyBranchTable();
        $branch = $mSurveyBranch->getAll($id)->pluck('branch_id')->toArray();
        session()->put($unique . '.item_selected', $branch);
    }

    /**
     * Xóa khảo sát
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $mSurvey = new SurveyTable();
        $detail = $mSurvey->getItem($id);
        // Chỉ cho phép xóa các khảo sát có trạng thái là bản nháp
        if ($detail['status'] == 'N') {
            $data = [
                'is_delete' => 1,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ];
            $mSurvey->edit($id, $data);
        }
        return ['error' => false];
    }

    /**
     * Thay đổi trạng thái khảo sát
     * @param $param
     * @return mixed
     */
    public function changeStatus($param)
    {
        try {
            $id = $param['id'];
            $mSurvey = new SurveyTable();
            $detail = $mSurvey->getItem($id);
            if ($param['status'] == 'R') {
                $errors = $this->validateDataSurvey($id);
                if ($errors) {
                    return [
                        'error' => true,
                        'array_error' => $errors,
                    ];
                }

                $this->handleStatusConfirm($detail);
            }
            $data = [
                'status' => $param['status'],
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ];
            if ($param['status'] == 'C') {
                $data['close_date'] = Carbon::now();
            }
            if ($param['status'] == 'D' || $param['status'] == 'R' || $param['status'] == 'continue') {
                // Nếu thay đổi trạng thái sang từ chối || Duyệt thì chỉ được thay đổi khi khảo sát có trạng thái là bản nháp
                if ($detail['status'] == 'N') {
                    $mSurvey->edit($id, $data);
                } else if ($param['status'] == 'continue') {
                    $data['status'] = 'R';
                    $mSurvey->edit($id, $data);
                }
            } else {
                $mSurvey->edit($id, $data);
            }
            return ['error' => false];
        } catch (\Exception $e) {
            Log::info("error : " . $e->getMessage());
        }
    }



    /**
     * xử lý trạng thái duyệt khảo sát
     * @param $itemSurvey
     * @return void
     */

    public function handleStatusConfirm($itemSurvey)
    {
        try {
            $typeUser = $itemSurvey->type_user;
            $typeApply = $itemSurvey->type_apply;
            $listUser = [];
            if ($typeUser == 'staff') {
                if ($typeApply == 'all_staff') {
                    $listUser = $this->insertStaffAllApply($itemSurvey);
                } else {
                    $listUser = $this->insertStaffApply($itemSurvey);
                    if (isset($listUser['error'])) {
                        return $listUser;
                    }
                }
            } else {
                if ($typeApply == 'all_customer') {
                    $listUser = $this->insertCustomerAllApply($itemSurvey);
                } else {
                    $listUser = $this->insertCustomerApply($itemSurvey);
                    if (isset($listUser['error'])) {
                        return $listUser;
                    }
                }
            }
        } catch (\Exception $e) {
            Log::info('message : ' . $e->getMessage());
        }
    }





    /**
     * xử lý insert áp dụng cho tất cả nhân viên khảo sát
     * @param $itemSurvey
     * @return void
     */

    public function insertStaffAllApply($itemSurvey)
    {
        $mStaff = new StaffsTable();
        $listStaff = $mStaff->getAll()->pluck('staff_id')->toArray();
        if (count($listStaff) > 0) {
            $itemSurvey->staffs()->sync($listStaff);
            return $listStaff;
        }
        return [
            'error' => true,
            'array_error' => [
                'tab_required' => __('survey::validation.tab_required')
            ]
        ];
    }

    /**
     * xử lý insert áp dụng cho tất cả khách hàng khảo sát
     * @param $itemSurvey
     * @return void
     */

    public function insertCustomerAllApply($itemSurvey)
    {
        $mCustomer = new CustomerTable();
        $listCustomer = $mCustomer->getAll()->pluck('customer_id')->toArray();
        if (count($listCustomer) > 0) {
            $itemSurvey->customers()->sync($listCustomer);
            return $listCustomer;
        }
        return [
            'error' => true,
            'array_error' => [
                'tab_required' => __('survey::validation.tab_required')
            ]
        ];
    }

    /**
     * xử lý insert áp dụng cho nhân viên khảo sát
     * @param $itemSurvey
     */

    public function insertStaffApply($itemSurvey)
    {
        $listStaffDefine = $itemSurvey->staffs()->pluck('staff_id')->toArray();
        $listStaffAuto = $this->getAllStaffAutoApply($itemSurvey);
        $listStaff  = array_unique(array_merge($listStaffAuto, $listStaffDefine));
        $itemSurvey->staffs()->sync($listStaff);
        return $listStaff;
    }

    /**
     * xử lý insert áp dụng khảo sát cho khách hàng
     * @param $itemSurvey
     */

    public function insertCustomerApply($itemSurvey)
    {
        $listCustomerDefine =  $itemSurvey->customers()->pluck('customer_id')->toArray();
        $listCustomerAuto = $this->getAllCustomerAutoApply($itemSurvey);
        $listCustomer  = array_unique(array_merge($listCustomerAuto, $listCustomerDefine));
        $itemSurvey->customers()->sync($listCustomer);
        return $listCustomer;
    }

    /**
     * xử lý lấy tất cả đối tượng nhân viên động 
     * @param $itemSurvey
     * @return array
     */

    public function getAllStaffAutoApply($itemSurvey)
    {
        $conditionApply = $itemSurvey->staffConditionApply;
        $listStaff = [];
        if ($conditionApply) {
            $mStaff = new StaffsTable();
            $filters['condition_branch'] =  $conditionApply->condition_branch ? json_decode($conditionApply->condition_branch) : null;
            $filters['condition_department'] = $conditionApply->condition_department ? json_decode($conditionApply->condition_department) : null;
            $filters['condition_titile'] = $conditionApply->condition_titile ? json_decode($conditionApply->condition_titile) : null;
            $type = $conditionApply->condition_type;
            $listStaff = $mStaff->getAllByConditionAuto($filters, $type);

            if ($listStaff->count() > 0) {
                $listStaff = $listStaff->pluck('staff_id')->toArray();
            }
        }

        return $listStaff;
    }

    /**
     * xử lý tất cả đối tượng khách hàng động
     * @param $itemSurvey
     * @return array
     */

    public function getAllCustomerAutoApply($itemSurvey)
    {

        $conditionApply = $itemSurvey->conditionApply;
        $listCustomer = [];
        if ($conditionApply) {
            $mCustomerGroupFilter = new CustomerGroupFilterTable();
            $idGroup = $conditionApply->group_id;
            $itemCustomerGroup = $mCustomerGroupFilter->getItem($idGroup);
            $typeCustomerGroup = $itemCustomerGroup->filter_group_type;
            if ($typeCustomerGroup == 'user_define') {
                $mCustomerGroupDefineDetail = new CustomerGroupDefineDetailTable();
                $listCustomerGroup = $mCustomerGroupDefineDetail->getIdInGroup($idGroup);
                if ($listCustomerGroup->count() > 0) {
                    $listCustomer = $listCustomerGroup->pluck('customer_id')->toArray();
                }
            } else {
                $rCustomerGroupFilter = app()->make(CustomerGroupFilterRepository::class);
                $listCustomer = $rCustomerGroupFilter->getCustomerInGroupAuto($idGroup);
            }
        }

        return $listCustomer;
    }

    /**
     * Option question
     * @param $params
     * @return mixed
     */
    public function optionQuestion($params)
    {
        $filter = [
            'keyword_survey_question$survey_question_description' => $params['search'] ?? null,
            'survey_question$survey_id' => $params['id'] ?? 0,
        ];
        $mSurveyQuestion = new SurveyQuestionTable();
        $data = $mSurveyQuestion->getListNew($filter);
        $items = [];
        $pagination = false;
        try {
            $items = $data->toArray()['data'];
            array_unshift($items, ['survey_question_id' => 0, 'survey_question_description' => __('Câu hỏi')]);
            $pagination = range($data->currentPage(), $data->lastPage()) ? true : false;
        } catch (\Exception $e) {
        }
        return response()->json([
            'total' => $data->total(),
            'items' => $items,
            'pagination' => $pagination
        ]);
    }

    /**
     * Data report survey
     * @param array $params
     * @return array|mixed
     */
    public function getListReport($params = [])
    {
        $perPages = $params['perpage'] ?? PAGING_ITEM_PER_PAGE;
        $page = $params['page'] ?? 1;
        $questionId = isset($params['survey_answer_question$survey_question_id'])
            ? $params['survey_answer_question$survey_question_id'] : null;
        unset($params['survey_answer_question$survey_question_id'], $params['perpage'], $params['page']);
        $mSurveyAnswerQuestion = new SurveyAnswerQuestionTable();
        if (!empty($params['created_at'])) {
            $explodeCreatedAt = explode(" - ", $params["created_at"]);
            $params['finished_at_start'] = Carbon::createFromFormat('d/m/Y', $explodeCreatedAt[0])
                ->format('Y-m-d');
            $params['finished_at_end'] = Carbon::createFromFormat('d/m/Y', $explodeCreatedAt[1])
                ->format('Y-m-d');
            unset($params['created_at']);
        }
        // Danh sách report
        //$params['survey_answer$outlet_id'] = 221;
        // Danh sách branch trả lời câu hỏi khảo sát
        $params['perpage'] = LIST_FULL;
        $params['survey_answer$survey_answer_status'] = 'done';
        //$params['outlet_master$outlet_id'] = 221;
        $mSurveyAnswer = new SurveyAnswerTable();
        $listOutlet = $mSurveyAnswer->getListNew($params);
        // Danh sách các câu hỏi
        $mSurveyQuestion = new SurveyQuestionTable();
        $filter = [
            'survey_question$survey_id' => $params['survey_answer$survey_id'],
            'survey_question$survey_question_id' => $questionId,
            'perpage' => LIST_FULL,
            'where_not_in_survey_question_type' => [self::PAGE_TEXT, self::PAGE_PICTURE],
        ];
        $question = $mSurveyQuestion->getListNew($filter);
        // Data
        $data = [];
        if (count($question) > 0 && count($listOutlet) > 0) {
            foreach ($listOutlet as $outlets) {
                foreach ($question as $questions) {
                    $temp = array_merge($questions->toArray(), $outlets->toArray());
                    $temp['customer_ship_code_sai_sqi'] = $temp['customer_ship_code_sai'] . '||' . $temp['survey_question_id'];
                    $data[] = $temp;
                }
            }
        }
        // Phân trang
        $data = collect($data);
        $total = count($data);
        if ($total <= $perPages) {
            $page = 1;
        }
        $list = new LengthAwarePaginator(
            $data->forPage($page, $perPages),
            $total,
            $perPages,
            $page,
            ['path' => url()->current()]
        );

        // Câu trả lời.
        $condition = [
            'survey_question$survey_id' => $params['survey_answer$survey_id'],
            'where_in_branch_id' => $list->pluck('branch_id', 'branch_id')->toArray(),
            'where_in_survey_question_id' => $list->pluck('survey_question_id', 'survey_question_id')->toArray(),
        ];
        $answer = $mSurveyAnswerQuestion->geByCondition($condition)
            ->groupBy('customer_ship_code_sai_sqi')->toArray();
        // Các chi nhánh NPP của outlet
        $outletCode = $list->pluck('branch_code', 'branch_code')->toArray();
        $rStore = app(StoreRepositoryInterface::class);
        $outletMasterCompanyBranch = $rStore->getOutletCompanyBranch($outletCode);
        // NPP option
        $rCompanyBranch = app(CompanyBranchRepositoryInterface::class);
        $companyBranch = $rCompanyBranch->getList(['perpage' => LIST_FULL]);
        return [
            'list' => $list,
            'answer' => $answer,
            'outletMasterCompanyBranch' => $outletMasterCompanyBranch,
            'companyBranch' => $companyBranch,
            'question' => $question,
        ];
    }

    /**
     * Export danh sách ở màn hình báo cáo khảo sát
     * @param $params
     * @return array
     */
    public function exportReport($idSurvey)
    {
        $mSurveyAnswer = new SurveyAnswerTable();
        $mSurveyAnswerQuestion = new SurveyAnswerQuestionTable();
        $mSurvey = new SurveyTable();
        $mSurveyQuestion = new SurveyQuestionTable();
        $itemSurvey = $mSurvey->getItem($idSurvey);
        $params['typeUser'] =  $itemSurvey->type_user ?? null;
        $params['survey_id'] = $idSurvey;
        $infoAnswerSurveys = $mSurveyAnswer->getAnswerQuestionExportExcel($params);
        $listColumExport = $this->columDefaultExport($idSurvey);
        $listDataExport = [];
        $postionQuestion = $mSurveyQuestion->getBySurveyId($idSurvey)
            ->pluck("survey_question_description")
            ->toArray();
        foreach ($infoAnswerSurveys as $infoAnswerSurvey) {
            $idAnswer = $infoAnswerSurvey->survey_answer_id;
            // lấy danh sách thứ tự câu hỏi //
            if (count($postionQuestion) > 0) {
                foreach ($postionQuestion as $item) {
                    $infoAnswerSurvey["{$item}"] = '';
                }
            }
            // danh sách câu hỏi và câu trả lời của user //
            $listAnswerQuestion = collect($mSurveyAnswerQuestion
                ->getAllByIdAnswer($idAnswer))
                ->groupBy('survey_question_id')
                ->values();
            $dataConverAnswerQuestion = $this->hanldeQuestionAnswer($listAnswerQuestion);

            $dataExportExcel = $this->hanldeQuestionAnswerExcel($dataConverAnswerQuestion, $infoAnswerSurvey);
            $listDataExport[] = $dataExportExcel;
        }
        $dataExportReportSurvey = [
            "columns" => $listColumExport,
            "listDataExport" => $listDataExport
        ];
        $exportReportSurvey = new ReportSurveyExport($dataExportReportSurvey);
        ob_end_clean();
        return Excel::download($exportReportSurvey, 'report_survey.xlsx');
    }

    /**
     * lấy tất cả danh sách báo cáo của khảo sát 
     * @param $params
     * @return mixed
     */

    public function getListReportAll($params)
    {
        $mSurveyAnswerQuestion = new SurveyAnswerTable();
        $mSurvey = new SurveyTable();
        $idSurvey = $params['idSurvey'];
        $itemSurvey = $mSurvey->getItem($idSurvey);
        $params['typeUser'] =  $itemSurvey->type_user;
        // danh sách báo cáo của khảo sát //    
        $list = $mSurveyAnswerQuestion->getListCoreNews($params);
        $view = view('survey::survey.report.list', [
            'list' => $list,
            'idSurvey' =>  $idSurvey,
            'dataChartSingleChoice' => [],
            'dataChartMutipleChoice' => []
        ])->render();
        $result = [
            'view' => $view,
        ];
        return $result;
    }

    /**
     * lây tất cả danh sách câu trả lời của từng user khảo sát
     * @param $params
     * @return mixed
     */

    public function getListAllReportUser($params)
    {

        $mSurveyAnswer = new SurveyAnswerTable();
        $mSurveyAnswerQuestion = new SurveyAnswerQuestionTable();
        $mSurveyQuestionChoice = new SurveyQuestionChoiceTable();
        $mSurvey = new SurveyTable();
        $idSurvey = $params['survey_id'];
        $itemSurvey = $mSurvey->getItem($idSurvey);
        $params['typeUser'] =  $itemSurvey->type_user ?? null;
        $totalPage = $mSurveyAnswer->getListAnswerBySurvey($params)->total();

        // info khách hàng và thông tin cơ bản  //
        $infoAnswerSurvey = $mSurveyAnswer->getListAnswerBySurvey($params)->first();
        $data = [
            "infoAnswerSurvey" => '',
            "listAnswerQuestion" => '',
            "page" => $params['page'],
            'totalPage' => $totalPage,
        ];
        if ($infoAnswerSurvey) {
            // id phiên trả lời của khách hàng //
            $idAnswer = $infoAnswerSurvey->survey_answer_id;
            // danh sách câu hỏi và câu trả lời của user //
            $listAnswerQuestionBlock = collect($mSurveyAnswerQuestion
                ->getAllByIdAnswer($idAnswer))
                ->groupBy('survey_block_id')
                ->values();
            $dataListAnswerQuestionBlock = [];
            if ($listAnswerQuestionBlock->count() > 0) {
                foreach ($listAnswerQuestionBlock as $answerQuestionBlock) {
                    $nameBlock = $answerQuestionBlock[0]['survey_block_name'];
                    $answerQuestionBlockNew =  $answerQuestionBlock->groupBy('survey_question_id')->values();
                    $dataListAnswerQuestionBlock[$nameBlock] = $this->hanldeQuestionAnswer($answerQuestionBlockNew);
                }
            }

            $data = [
                "infoAnswerSurvey" => $infoAnswerSurvey,
                "dataListAnswerQuestionBlock" => $dataListAnswerQuestionBlock,
                "page" => $params['page'],
                'totalPage' => $totalPage
            ];
        }
        $view = view('survey::survey.report.show-ajax', $data)->render();
        $result =  [
            'view' => $view
        ];
        return $result;
    }

    /**
     * load item đầu tiên câu trả lời khảo sát user 
     * @param $params
     * @return mixed
     */

    public function getItemFirstReportUser($params)
    {
        $mSurveyAnswer = new SurveyAnswerTable();
        $mSurveyAnswerQuestion = new SurveyAnswerQuestionTable();
        $mSurveyQuestionChoice = new SurveyQuestionChoiceTable();
        $mSurvey = new SurveyTable();
        $idSurvey = $params['survey_id'];
        $itemSurvey = $mSurvey->getItem($idSurvey);
        $params['typeUser'] =  $itemSurvey->type_user ?? null;
        // info khách hàng và thông tin cơ bản  //
        $infoAnswerSurvey = $mSurveyAnswer->getListAnswerBySurvey($params)->first();
        $totalPage = $mSurveyAnswer->getListAnswerBySurvey($params)->total();
        $data = [
            "infoAnswerSurvey" => '',
            "listAnswerQuestion" => '',
            "page" => 1,
            'survey_id' => $idSurvey
        ];
        if ($infoAnswerSurvey) {
            // id phiên trả lời của khách hàng //
            $idAnswer = $infoAnswerSurvey->survey_answer_id;
            // danh sách câu hỏi và câu trả lời của user theo từng block //
            $listAnswerQuestionBlock = collect($mSurveyAnswerQuestion
                ->getAllByIdAnswer($idAnswer))
                ->groupBy('survey_block_id')
                ->values();
            $dataListAnswerQuestionBlock = [];
            if ($listAnswerQuestionBlock->count() > 0) {
                foreach ($listAnswerQuestionBlock as $answerQuestionBlock) {
                    $nameBlock = $answerQuestionBlock[0]['survey_block_name'];
                    $answerQuestionBlockNew =  $answerQuestionBlock->groupBy('survey_question_id')->values();
                    $dataListAnswerQuestionBlock[$nameBlock] = $this->hanldeQuestionAnswer($answerQuestionBlockNew);
                }
            }

            $data = [
                "infoAnswerSurvey" => $infoAnswerSurvey,
                "dataListAnswerQuestionBlock" => $dataListAnswerQuestionBlock,
                "page" => 1,
                'totalPage' => $totalPage,
                'survey_id' => $idSurvey
            ];
        }
        return view('survey::survey.report.show', $data);
    }

    /**
     * xử lí dữ liệu câu trả lời và câu hỏi
     * @param [object] $listAnswerQuestion
     * @return mixed
     */
    public function hanldeQuestionAnswer($listAnswerQuestion)
    {
        $mSurveyQuestionChoice = new SurveyQuestionChoiceTable();
        $mSurvey = app()->get(SurveyTable::class);
        $listAnswerQuestionConvert = [];
        foreach ($listAnswerQuestion as  $answerQuestion) {
            // câu hỏi dạng muitple choice //
            if (count($answerQuestion) > 1) {
                $listAnswerQuestionMutipleChoice = [];
                $idQuestion = $answerQuestion[0]['survey_question_id'];
                // duyệt và lấy ra danh sách chọn câu trả lời muitiple choice của user // 
                foreach ($answerQuestion as $key =>  $item) {
                    $listAnswerQuestionMutipleChoice[] = $item['survey_question_choice_id'];
                    if ($key > 0) {
                        unset($answerQuestion[$key]);
                    }
                }
                // danh sách câu trả lời mutiple choice mặc định // 
                $listQuestionChoice = $mSurveyQuestionChoice->getBySurveyQuestionId($idQuestion)->toArray();
                $answerQuestion[0]['listQuestionChoice'] = $listQuestionChoice;
                // danh sách id câu trả lời mutiple choice của user //
                $answerQuestion[0]['survey_question_choice_id'] = $listAnswerQuestionMutipleChoice;
                if ($answerQuestion[0]['count_point']) {
                    // lấy ra câu hỏi có đáp án đúng //
                    $listAnswerOfQuestionCountPoint = $mSurveyQuestionChoice->getByAnswerOfQuestionCountPoint($answerQuestion[0]['survey_question_id']);

                    // kiểm tra câu trả lời của user đúng hay sai //
                    $checkAnswerOfUser = $this->checkAnswerOfUser($listAnswerOfQuestionCountPoint, $answerQuestion[0]['survey_question_choice_id']);
                    $answerQuestion[0]['resultAnswerQuestion'] = $checkAnswerOfUser;
                    $answerQuestion[0]['listAnswerSuccess'] = $listAnswerOfQuestionCountPoint;
                }
            } else if ($answerQuestion[0]['survey_question_type'] == "single_choice" || $answerQuestion[0]['survey_question_type'] == 'multi_choice') {

                $listQuestionChoice = $mSurveyQuestionChoice->getBySurveyQuestionId($answerQuestion[0]['survey_question_id'])->toArray();
                $answerQuestion[0]['listQuestionChoice'] = $listQuestionChoice;
                // câu trả lời của user đúng hay sai //
                $answerQuestion[0]['resultAnswerQuestion'] = "";
                // danh sách id câu trả lời đúng của câu hỏi //

                if ($answerQuestion[0]['count_point']) {
                    // lấy ra câu hỏi có đáp án đúng //
                    $listAnswerOfQuestionCountPoint = $mSurveyQuestionChoice->getByAnswerOfQuestionCountPoint($answerQuestion[0]['survey_question_id']);

                    // kiểm tra câu trả lời của user đúng hay sai //
                    $checkAnswerOfUser = $this->checkAnswerOfUser($listAnswerOfQuestionCountPoint, $answerQuestion[0]['survey_question_choice_id']);
                    $answerQuestion[0]['resultAnswerQuestion'] = $checkAnswerOfUser;
                    $answerQuestion[0]['listAnswerSuccess'] = $listAnswerOfQuestionCountPoint;
                }
            } else if ($answerQuestion[0]['survey_question_type'] == 'text') {
                // Kiểm tra cấu hình khảo sát có tính điểm cho câu hỏi dạng văn bản //
                $answerQuestion[0]['text_value_point'] = false;
                $answerQuestion[0]['resultAnswerQuestion'] = false;
                if ($answerQuestion[0]['count_point']) {
                    $itemSurvey = $mSurvey->getConfigPoint($answerQuestion[0]['survey_id']);
                    if ($itemSurvey && $itemSurvey->count_point_text == 1) {
                        $answerQuestion[0]['text_value_point'] = true;
                    }
                }
            }
            $listAnswerQuestionConvert[] = $answerQuestion[0];
        }
        return $listAnswerQuestionConvert;
    }


    /**
     * Kiểm tra danh sách câu trả lời của user
     * @param $listAnswerQuestionChoice danh sách các trả lời của câu hỏi
     * @param $listAnswerUser danh sách các câu trả lời của user
     * @return mixed
     */

    public function checkAnswerOfUser($listAnswerQuestionChoice, $listAnswerUser)
    {
        $listAnswerQuestionChoice = $listAnswerQuestionChoice->pluck('survey_question_choice_id')
            ->toArray();
        $is_success = 'success';
        // check câu trả lời dạng single choice hay mutiple choice //
        if (is_array($listAnswerUser)) {
            $checkAnswerSuccess = array_diff($listAnswerUser, $listAnswerQuestionChoice);
            if (count($checkAnswerSuccess) > 0  || count($listAnswerUser) != count($listAnswerQuestionChoice)) {
                $is_success = 'wrong';
            }
        } else {
            if (!in_array($listAnswerUser, $listAnswerQuestionChoice) || count($listAnswerQuestionChoice) > 1) {
                $is_success = 'wrong';
            }
        }
        return $is_success;
    }



    /**
     * xử lí dữ liệu follow file excel
     * @param $listAnswerQuestion
     * @param $infoAnswerSurvey
     * @return mixed
     */
    public function hanldeQuestionAnswerExcel($listAnswerQuestion, $infoAnswerSurvey)
    {

        $infoAnswerSurvey = $infoAnswerSurvey->toArray();
        unset($infoAnswerSurvey['survey_answer_id']);
        foreach ($listAnswerQuestion as $key => $item) {

            $postionQuestion = $item->survey_question_description;
            if (!empty($item->listQuestionChoice)) {
                if ($item->survey_question_type == 'multi_choice') {
                    $listTitleChoice = [];
                    foreach ($item->listQuestionChoice as  $value) {
                        if (!is_array($item->survey_question_choice_id)) {
                            if ($item->survey_question_choice_id == $value['survey_question_choice_id']) {
                                $listTitleChoice[] = $value['survey_question_choice_title'];
                            }
                        } elseif (in_array($value['survey_question_choice_id'], $item->survey_question_choice_id)) {
                            $listTitleChoice[] = $value['survey_question_choice_title'];
                        }
                    }
                    $infoAnswerSurvey["{$postionQuestion}"] = implode(",", $listTitleChoice);
                } else {
                    foreach ($item->listQuestionChoice as  $value) {
                        if ($value['survey_question_choice_id'] == $item->survey_question_choice_id) {
                            $infoAnswerSurvey["{$postionQuestion}"] = $value['survey_question_choice_title'];
                        }
                    }
                }
            } else {
                $infoAnswerSurvey["{$postionQuestion}"] = $item->answer_value ?? $item->survey_question_choice_id;
            }
        }
        return  array_values($infoAnswerSurvey);
    }

    /**
     * Hàm sét các giá trị cột mặt định export excel của khảo sát
     * @param $idSurvey
     * @return array
     */
    public function columDefaultExport($idSurvey)
    {
        $mSurveyAnswerQuestion = new SurveyQuestionTable();
        $listQuestion = $mSurveyAnswerQuestion->getBySurveyIdAndSortby($idSurvey);
        $columnsExportDefault = [
            __("Mã số"),
            __("Họ và tên"),
            __("Số điện thoại"),
            __("Thời gian thực hiện"),
            __("Địa chỉ")
        ];
        if ($listQuestion->count() > 0) {

            foreach ($listQuestion as $question) {
                $nameColumnQuestion = "{$question->survey_question_description}";
                array_push($columnsExportDefault, $nameColumnQuestion);
            }
        }
        return $columnsExportDefault;
    }

    /**
     * Lấy tất cả danh sách câu hỏi báo cáo  của survey
     * @param $id_survey
     * @return mixed
     */
    public function getAllQuestionReport($id_survey)
    {
        $mSurvey = new SurveyTable();
        $mAnswer = new SurveyAnswerTable();
        // thông tin cơ bản báo cáo tổng quát //
        $itemSurvey = $mSurvey->find($id_survey);
        $infoReportOverview = $itemSurvey

            ->with(['questions' => function ($query) {
                $query->join("survey_block", "survey_block.survey_block_id", "survey_question.survey_block_id");
                $query->orderBy("survey_block.survey_block_position");
                $query->orderBy("survey_question.survey_question_position");
            }])
            ->with(['answerQuestion' => function ($query) {
                $query->groupBy("survey_question_id");
            }])
            ->find($id_survey);
        $listAnswer = $mAnswer->getAnswerNewUser($id_survey);
        if ($listAnswer->count() > 0) {
            $listAnswer = $listAnswer->pluck('survey_answer_id')->toArray();
        } else {
            $listAnswer = [];
        }

        $totalAnswerQuestionSurvey = count($listAnswer);
        // tổng user tham gia khảo sát //
        $totalUserApply = count($infoReportOverview->staffs);
        if ($infoReportOverview->type_user == 'customer') {
            $totalUserApply = $infoReportOverview->customers;
        }
        $listInfoQuestion = [];
        $dataChartSigleChoice = [];
        $dataChartMutipleChoice = [];
        $dataChartSigleChoiceBlock = [];
        $dataChartMutipleChoiceBlock = [];
        $listInfoQuestionBlock = [];
        $dataQuestionAnswerReport = $infoReportOverview->questions->groupBy('survey_block_name');
        foreach ($dataQuestionAnswerReport as $key => $questions) {
            foreach ($questions as $k => $question) {
                if ($question->survey_question_type == 'single_choice') {
                    $listInfoQuestion[$key][$k] = ['single_choice' => $this->itemQuestionSingleChoice($question, $totalUserApply, $listAnswer)];
                    $dataChartSigleChoice[$key][$k] = $this->itemQuestionSingleChoice($question, $totalUserApply, $listAnswer)['dataQuestionSingleChoice'];
                } else if ($question->survey_question_type == 'multi_choice') {
                    $listInfoQuestion[$key][$k] = ['multi_choice' => $this->itemQuestionMutipleChoice($question, $totalUserApply, $listAnswer)];
                    $dataChartMutipleChoice[$key][$k] = $this->itemQuestionMutipleChoice($question, $totalUserApply, $listAnswer)['dataQuestionSingleChoice'];
                } else if ($question->survey_question_type == 'page_text') {
                    $listInfoQuestion[$key][$k] = ['page_text' => $this->itemQuestionText($question, $totalUserApply, $listAnswer)];
                } else if ($question->survey_question_type == 'text') {
                    $listInfoQuestion[$key][$k] = ['text' => $this->itemQuestionText($question, $totalUserApply, $listAnswer)];
                } else if ($question->survey_question_type == 'page_picture') {
                    $listInfoQuestion[$key][$k] = ['page_picture' => $question];
                }
            }
            if (isset($listInfoQuestion[$key])) {
                $listInfoQuestionBlock[$key][] = $listInfoQuestion[$key];
            }
            if (isset($dataChartSigleChoice[$key])) {
                $dataChartSigleChoiceBlock[$key][] = $dataChartSigleChoice[$key];
            }
            if (isset($dataChartMutipleChoice[$key])) {
                $dataChartMutipleChoiceBlock[$key][] =  $dataChartMutipleChoice[$key];
            }
        }

        $result = [
            "infoOverview" => $infoReportOverview,
            'dataQuestionAnswer' => $listInfoQuestionBlock,
            'dataChartSingleChoice' => $dataChartSigleChoiceBlock,
            'dataChartMutipleChoice' => $dataChartMutipleChoiceBlock,
            'totalAnswerQuestion' => $totalAnswerQuestionSurvey
        ];

        return $result;
    }

    /**
     * lấy thông tin câu hỏi single  choice (báo cáo tổng quát)
     * @param $itemQuestion 
     * @param $totalUserSurvey
     * @param $listAnswer
     * @return mixed
     */
    public function itemQuestionSingleChoice(
        $itemQuestion,
        $totalUserSurvey,
        $listAnswer
    ) {
        // lấy các giá trị của câu hỏi trắc nghiệm // 
        $listValueQustionSignleChoice = $itemQuestion->singleChoice;
        // lấy các câu trả lời của câu hỏi //
        $listAnswerQuestion = $itemQuestion
            ->answerQuestion()
            ->whereIn('survey_answer_id', $listAnswer);
        // tổng danh sách câu trả lời của khảo sát //
        $totalAnswer = count($listAnswer);
        // tổng câu trả lời của câu hỏi
        $totalListAnswer = $listAnswerQuestion->count();
        // tỷ lệ trả lời câu hỏi //
        if ($totalListAnswer == 0 || $totalUserSurvey == 0) {
            $percentageAnswerQuestion = 0;
        } else {
            $percentageAnswerQuestion = round(($totalListAnswer / $totalAnswer) * 100);
        }
        $textPercentageAnswerQuestion = __("Tỷ lệ trả lời") . ': "' . $percentageAnswerQuestion . '%' . ' ' . '(' . $totalListAnswer . '/' . $totalAnswer . ')';
        $dataQuestionSingleChoice = [];
        foreach ($listValueQustionSignleChoice as $item) {
            // lấy id của giá trị chọn câu hỏi trắc nghiệm 
            $idSingleChoice = $item->survey_question_choice_id;
            // tỷ lệ phần trăm của giá trị câu hỏi 
            $percentageAnswerSingleChoice = $itemQuestion->answerQuestion()
                ->whereIn('survey_answer_id', $listAnswer)
                ->where('survey_question_choice_id', $idSingleChoice)
                ->count() == 0 && $totalListAnswer == 0 ? 0 : ($itemQuestion->answerQuestion()
                    ->whereIn('survey_answer_id', $listAnswer)
                    ->where('survey_question_choice_id', $idSingleChoice)
                    ->count() / $totalListAnswer) * 100;
            // tổng số câu trả lời của câu hỏi //
            $totalAnswerByQuestion = $itemQuestion->answerQuestion()
                ->whereIn('survey_answer_id', $listAnswer)
                ->where('survey_question_choice_id', $idSingleChoice)
                ->count();
            // dữ liệu để hiển thị thông tin trên chart
            $nameItemChart = $item->survey_question_choice_title . ' : ' . $totalAnswerByQuestion;
            $dataQuestionSingleChoice[] = [
                'name' => $nameItemChart,
                'y' => round($percentageAnswerSingleChoice, 1)
            ];
        }

        $result = [
            'infoQuestion'      => $itemQuestion,
            'percentageAnswer'  => $textPercentageAnswerQuestion,
            'dataQuestionSingleChoice' => $dataQuestionSingleChoice
        ];
        return $result;
    }

    /**
     * lấy thông tin câu hỏi single  choice (báo cáo tổng quát)
     * @param $itemQuestion 
     * @param $totalUserSurvey
     * @param $listAnswer
     * @return mixed
     */
    public function itemQuestionMutipleChoice(
        $itemQuestion,
        $totalUserSurvey,
        $listAnswer
    ) {
        // lấy các giá trị của câu hỏi trắc nghiệm // 
        $listValueQustionSignleChoice = $itemQuestion->singleChoice;
        // lấy các câu trả lời của câu hỏi //
        $listAnswerQuestion = $itemQuestion->answerQuestion()
            ->whereIn('survey_answer_id', $listAnswer);
        // tổng danh sách câu trả lời của khảo sát //
        $totalAnswer = count($listAnswer);
        // tổng câu hỏi trắc nghiệm được chọn //
        $totalListAnswerChecked = $listAnswerQuestion->count();
        // tổng câu trả lời của câu hỏi
        $totalListAnswer = $listAnswerQuestion->groupBy('survey_question_id')->groupBy('survey_answer_id')->get()->count();
        // tỷ lệ trả lời câu hỏi //
        if ($totalListAnswer == 0 || $totalUserSurvey == 0) {
            $percentageAnswerQuestion = 0;
        } else {
            $percentageAnswerQuestion = round(($totalListAnswer / $totalAnswer) * 100);
        }
        $textPercentageAnswerQuestion = __("Tỷ lệ trả lời : ") . $percentageAnswerQuestion . '%' . ' ' . '(' . $totalListAnswer . '/' . $totalAnswer . ')';
        $dataQuestionSingleChoice = [];
        foreach ($listValueQustionSignleChoice as $item) {
            // lấy id của giá trị chọn câu hỏi trắc nghiệm 
            $idSingleChoice = $item->survey_question_choice_id;
            // tỷ lệ phần trăm của giá trị câu hỏi 
            $percentageAnswerSingleChoice = $itemQuestion->answerQuestion()
                ->whereIn('survey_answer_id', $listAnswer)
                ->where('survey_question_choice_id', $idSingleChoice)
                ->count() == 0 && $totalListAnswerChecked == 0 ? 0 : ($itemQuestion->answerQuestion()
                    ->whereIn('survey_answer_id', $listAnswer)
                    ->where('survey_question_choice_id', $idSingleChoice)
                    ->count() / $totalListAnswerChecked) * 100;
            // dữ liệu để hiển thị thông tin trên chart
            // tổng số câu trả lời của câu hỏi //
            $totalAnswerByQuestion = $itemQuestion->answerQuestion()
                ->whereIn('survey_answer_id', $listAnswer)
                ->where('survey_question_choice_id', $idSingleChoice)
                ->count();
            $nameItemChart = $item->survey_question_choice_title . ' : ' . $totalAnswerByQuestion;
            $dataQuestionSingleChoice[] = [
                'name' => $nameItemChart,
                'y' => round($percentageAnswerSingleChoice, 1)
            ];
        }

        $result = [
            'infoQuestion'      => $itemQuestion,
            'percentageAnswer'  => $textPercentageAnswerQuestion,
            'dataQuestionSingleChoice' => $dataQuestionSingleChoice
        ];
        return $result;
    }

    /**
     * lấy thông tin câu hỏi text (báo cáo tổng quát)
     * @param $itemQuestion 
     * @param $totalUserSurvey
     * @param $listAnswer
     * @return mixed
     */

    public function itemQuestionText($itemQuestion, $totalUserSurvey, $listAnswer)
    {
        $listAnswerQuestion = $itemQuestion->answerQuestion()
            ->whereIn('survey_answer_id', $listAnswer)
            ->where("answer_value", "<>", null)->get();
        $totalListAnswer = $listAnswerQuestion->count();
        // tổng danh sách câu trả lời của khảo sát //
        $totalAnswer = count($listAnswer);
        if ($totalListAnswer == 0  || $totalUserSurvey == 0) {
            $percentageAnswerQuestion = 0;
        } else {
            $percentageAnswerQuestion = round(($totalListAnswer / $totalAnswer) * 100);
        }
        $textPercentageAnswerQuestion = __("Tỷ lệ trả lời : ") . $percentageAnswerQuestion . '%' . ' ' . '(' . $totalListAnswer . '/' . $totalAnswer . ')';

        $result = [
            'infoQuestion'      => $itemQuestion,
            'percentageAnswer'  => $textPercentageAnswerQuestion,
            'dataQuestionText' => $listAnswerQuestion
        ];
        return $result;
    }

    /**
     * Lấy tất cả thông tin khách hàng (loại khách hàng, nhóm khách hàng ...)
     * @return mixed
     */
    public function getAllInfoCustomer()
    {


        $optionCustomerGroup = $this->customer_group->getOption();
        $optionCustomerSource = $this->customer_source->getOption();
        $result = [
            "customerGroup" => $optionCustomerGroup,
            "customerSource" => $optionCustomerSource,
        ];
        return $result;
    }

    /**
     * chi tiết khảo sát bao gồm các thông tin liên quan 
     * @param $id
     * @return mixed
     */

    public function getItemNews($id)
    {
        $mSurvey = new SurveyTable();
        $itemSurvey = $mSurvey->find($id);
        $result  = [
            'survey' => $itemSurvey
        ];
        if ($itemSurvey->type_user == 'staff') {
            $result = $this->getListStaffApplySurvey($itemSurvey);
        } else if ($itemSurvey->type_user == 'customer') {
            $result = $this->getListCustomerApplySurvey($itemSurvey);
        }
        return $result;
    }
    /**
     * lấy thông tin nhân viên áp dụng khảo sát
     * @param object $itemSurvey
     * @return array
     */

    public function getListStaffApplySurvey(
        $itemSurvey
    ) {
        $itemSurvey->with('staffs')
            ->with('staffConditionApply');
        $listStaff = $itemSurvey->staffs->pluck('staff_id')->toArray();
        $staffCondition = $itemSurvey->staffConditionApply;
        $mdepartments = new DepartmentTable();
        $mbranchs = new BranchTable();
        $mtitles  = new  StaffTitleTable();
        $listItemDepartment = [];
        $listItemBranch = [];
        $listItemTitile = [];
        $typeCondition = null;
        if ($staffCondition) {
            $listItemDepartment = $staffCondition->condition_department ? json_decode($staffCondition->condition_department) : [];
            $listItemBranch = $staffCondition->condition_branch ? json_decode($staffCondition->condition_branch) : [];
            $listItemTitile = $staffCondition->condition_titile ? json_decode($staffCondition->condition_titile) : [];
            $typeCondition = $staffCondition->condition_type;
        }
        $listItemDepartment = $mdepartments->getListCondition($listItemDepartment);
        $listItemBranch = $mbranchs->getListCondition($listItemBranch);
        $listItemTitile = $mtitles->getListCondition($listItemTitile);
        $listItemDepartmentPopup = $listItemDepartment->pluck('department_id')->toArray();
        $listItemBranchPopup = $listItemBranch->pluck('branch_id')->toArray();
        $listItemTitilePopup = $listItemTitile->pluck('staff_title_id')->toArray();

        $result  = [
            'listStaff' => $listStaff,
            'listItemDepartment' => $listItemDepartment,
            'listItemBranch' => $listItemBranch,
            'listItemTitile' => $listItemTitile,
            'listItemDepartmentPopup' => $listItemDepartmentPopup,
            'listItemBranchPopup' => $listItemBranchPopup,
            'listItemTitilePopup' => $listItemTitilePopup,
            'typeCondition' => $typeCondition,
            'survey' => $itemSurvey,
            'typeApply' => 'staff'
        ];

        return $result;
    }

    /**
     * lấy thông tin khách hàng áp dụng khảo sát
     * @param object $itemSurvey
     * @return array
     */

    public function getListCustomerApplySurvey(
        $itemSurvey
    ) {
        $mCustomerFilter = new CustomerGroupFilterTable();
        $itemSurvey->with('customer')
            ->with('conditionApply');
        $listCustomer = $itemSurvey->customers->pluck('customer_id')->toArray();
        $conditionApply = $itemSurvey->conditionApply;
        if ($conditionApply) {
            $itemCustomerFilter = $mCustomerFilter->find($conditionApply->group_id);
        }
        $result  = [
            'listCustomer' => $listCustomer,
            'itemCustomerFilter' => $itemCustomerFilter ?? '',
            'survey' => $itemSurvey,
            'typeApply' => 'customer'
        ];

        return $result;
    }

    /**
     * lấy thông tin câu trả lời của user
     * @param $idAnswer
     * @return mixed
     */

    public function showAnswerByUser($idAnswer)
    {
        $mSurveyAnswer = new SurveyAnswerTable();
        $mSurveyAnswerQuestion = new SurveyAnswerQuestionTable();
        // info khách hàng và thông tin cơ bản  //
        $infoAnswerSurvey = $mSurveyAnswer->listAnswerByUser($idAnswer);
        $data = [
            "infoAnswerSurvey" => '',
            "listAnswerQuestion" => '',
            "page" => 1,
            "typeShowPage" => 'hiden'
        ];
        if ($infoAnswerSurvey) {
            // id phiên trả lời của khách hàng //
            $idAnswer = $infoAnswerSurvey->survey_answer_id;
            // danh sách câu hỏi và câu trả lời của user //
            $listAnswerQuestionBlock = collect($mSurveyAnswerQuestion
                ->getAllByIdAnswer($idAnswer))
                ->groupBy('survey_block_id')
                ->values();
            $dataListAnswerQuestionBlock = [];
            if ($listAnswerQuestionBlock->count() > 0) {
                foreach ($listAnswerQuestionBlock as $answerQuestionBlock) {
                    $nameBlock = $answerQuestionBlock[0]['survey_block_name'];
                    $answerQuestionBlockNew =  $answerQuestionBlock->groupBy('survey_question_id')->values();
                    $dataListAnswerQuestionBlock[$nameBlock] = $this->hanldeQuestionAnswer($answerQuestionBlockNew);
                }
            }
            $data = [
                "infoAnswerSurvey" => $infoAnswerSurvey,
                "dataListAnswerQuestionBlock" => $dataListAnswerQuestionBlock,
                "page" => 1,
                "survey_id" => '',
                "typeShowPage" => 'hiden'
            ];
        }
        return view('survey::survey.report.show', $data);
    }

    /**
     * Coppy survey
     * @param $idSurvey
     * @return mixed
     */

    public function coppySurvey($idSurvey)
    {
        DB::beginTransaction();
        try {
            $mSurvey = app()->get(SurveyTable::class);
            $mSurveyBlock = app()->get(SurveyBlockTable::class);
            $mSurveyQuestion = app()->get(SurveyQuestionTable::class);
            $mSurveyQuestionChoice = app()->get(SurveyQuestionChoiceTable::class);
            $mBranchSurvey = app()->get(SurveyBranchTable::class);

            $itemSurvey = $mSurvey->find($idSurvey);
            if ($itemSurvey) {
                // sao chép ở trạng thái không phải là bản nháp
                if ($itemSurvey->status != 'N') {
                    // sao chép khảo sát //
                    $stringCode = str_replace('-', '', str_replace(' ', '', Carbon::now()->format('H-i-s d-m-Y')));
                    $codeSurvey = 'KS' . $stringCode;
                    $itemSurveyOld = $itemSurvey->toArray();
                    $itemSurveyOld['survey_name'] = $itemSurveyOld['survey_name'] . ' ' . __('(Sao chép)');
                    $itemSurveyOld['survey_code'] = $codeSurvey;
                    $itemSurveyOld['created_by'] = Auth::id();
                    $itemSurveyOld['updated_by'] = Auth::id();
                    $itemSurveyOld['status'] = self::NEW;
                    $itemSurveyOld['status_notifi'] = null;
                    $itemSurveyOld['job_notifi'] = null;
                    if ($itemSurvey->status == 'C') {
                        $itemSurveyOld['start_date'] = null;
                        $itemSurveyOld['end_date'] = null;
                        $itemSurveyOld['is_exec_time'] = 0;
                    }
                    $itemSurveyOld['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    unset($itemSurveyOld['survey_id']);
                    // sao chép câu hỏi khảo sát //
                    $itemSurveyNew = $mSurvey->add($itemSurveyOld);
                    $surveyCurrent = $mSurvey->find($itemSurveyNew);
                    // thêm khảo sát thương hiệu 
                    $listBrandSurvey = $mBranchSurvey->getAll($itemSurvey->survey_id);
                    $listBrandSurveyNew = [];
                    foreach ($listBrandSurvey as $item) {
                        $listBrandSurveyNew[] = [
                            'survey_id' => $surveyCurrent->survey_id,
                            'branch_id' => $item->branch_id
                        ];
                    }
                    $mBranchSurvey->addInsert($listBrandSurveyNew);
                    // sao chép câu hỏi khảo sát //
                    $listBlock = $itemSurvey->blocks;
                    foreach ($listBlock as $item) {
                        // block củ //
                        $itemOld = $item->toArray();
                        unset($itemOld['survey_block_id']);
                        $itemOld['survey_id'] = $itemSurveyNew;
                        // thêm block mới //
                        $idBlockNew = $mSurveyBlock->add($itemOld);
                        // câu hỏi của block //
                        $listQuestion = $item->questions;

                        foreach ($listQuestion as $itemQuestion) {
                            // câu hỏi củ //
                            $itemQuestionOld = $itemQuestion->toArray();
                            unset($itemQuestionOld['survey_question_id']);
                            $itemQuestionOld['survey_id'] = $itemSurveyNew;
                            $itemQuestionOld['survey_block_id'] = $idBlockNew;

                            $idQuestionNew = $mSurveyQuestion->add($itemQuestionOld);
                            if ($itemQuestion->singleChoice->count() > 0) {
                                // câu hỏi dạng trắc nghiệm //
                                foreach ($itemQuestion->singleChoice as $questionChoice) {
                                    $questionChoiceOld = $questionChoice->toArray();
                                    unset($questionChoiceOld['survey_question_choice_id']);
                                    $questionChoiceOld['survey_question_id'] = $idQuestionNew;
                                    $questionChoiceOld['survey_id'] = $itemSurveyNew;
                                    $mSurveyQuestionChoice->add($questionChoiceOld);
                                }
                            }
                        }
                    }

                    // sao chép đối tượng áp dụng //
                    $listUserApplySurveyOld = [];
                    // đối tượng áp dụng cụ thể //
                    if ($itemSurvey->type_apply == 'staffs') {
                        $listUserApplySurveyOld =  $itemSurvey->staffs->count() > 0 ? $itemSurvey->staffs->pluck('staff_id') : [];
                        $surveyCurrent->staffs()->attach($listUserApplySurveyOld);
                        // đối tượng động nhân viên //
                        if ($itemSurvey->conditionApply && $itemSurvey->conditionApply->type_group == 'staff') {
                            $surveyApplyConditionStaffOld = $itemSurvey->staffConditionApply;
                            if ($surveyApplyConditionStaffOld) {
                                $surveyApplyConditionStaffNew = $surveyApplyConditionStaffOld->toArray();
                                unset($surveyApplyConditionStaffNew['survey_group_staff_id']);
                                unset($surveyApplyConditionStaffNew['survey_id']);
                                $surveyCurrent->staffConditionApply()->create($surveyApplyConditionStaffNew);
                            }
                        }
                    } elseif ($itemSurvey->type_apply == 'customers') {
                        $listUserApplySurveyOld =  $itemSurvey->customers->count() > 0 ? $itemSurvey->customers->pluck('customer_id') : [];
                        $surveyCurrent->customers()->attach($listUserApplySurveyOld);
                    }
                    // đối tượng động //
                    if ($itemSurvey->conditionApply) {
                        $itemSurveyConditonOld = $itemSurvey->conditionApply->toArray();
                        unset($itemSurveyConditonOld['id_survey_condition']);
                        unset($itemSurveyConditonOld['survey_id']);
                        $surveyCurrent->conditionApply()->create($itemSurveyConditonOld);
                    }
                    // cấu hình tính điểm //
                    if ($itemSurvey->configPoint) {
                        $configPointOld = $itemSurvey->configPoint->toArray();
                        unset($configPointOld['id_config_point']);
                        unset($configPointOld['survey_id']);
                        $surveyCurrent->configPoint()->create($configPointOld);
                    }
                } else {
                    // sao chép khảo sát //
                    $stringCode = str_replace('-', '', str_replace(' ', '', Carbon::now()->format('H-i-s d-m-Y')));
                    $codeSurvey = 'KS' . $stringCode;
                    $itemSurveyOld = $itemSurvey->toArray();
                    $itemSurveyOld['survey_name'] = $itemSurveyOld['survey_name'] . ' ' . __('(Sao chép)');
                    $itemSurveyOld['survey_code'] = $codeSurvey;
                    $itemSurveyOld['created_by'] = Auth::id();
                    $itemSurveyOld['updated_by'] = Auth::id();
                    $itemSurveyOld['status'] = 'N';
                    $itemSurveyOld['status_notifi'] = null;
                    $itemSurveyOld['job_notifi'] = null;
                    $itemSurveyOld['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    unset($itemSurveyOld['survey_id']);
                    // sao chép câu hỏi khảo sát //
                    $itemSurveyNew = $mSurvey->add($itemSurveyOld);
                    $surveyCurrent = $mSurvey->find($itemSurveyNew);
                    // cấu hình tính điểm //
                    if ($itemSurvey->configPoint) {
                        $configPointOld = $itemSurvey->configPoint->toArray();
                        unset($configPointOld['id_config_point']);
                        unset($configPointOld['survey_id']);
                        $surveyCurrent->configPoint()->create($configPointOld);
                    }
                    if ($itemSurveyOld['is_short_link']) {
                        $this->templateQuestionSurveyPublicLink($itemSurveyNew);
                    }
                    // thêm khảo sát thương hiệu 
                    $listBrandSurvey = $mBranchSurvey->getAll($itemSurvey->survey_id);
                    $listBrandSurveyNew = [];
                    foreach ($listBrandSurvey as $item) {
                        $listBrandSurveyNew[] = [
                            'survey_id' => $surveyCurrent->survey_id,
                            'branch_id' => $item->branch_id
                        ];
                    }
                    $mBranchSurvey->addInsert($listBrandSurveyNew);
                }
                DB::commit();
                return [
                    'error' => false,
                    'message' => __('Sao chép khảo sát thành công')
                ];
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info('error :' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('Sao chép khảo sát thất bại')
            ];
        }
    }
}
