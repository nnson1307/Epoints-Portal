<?php


namespace Modules\Survey\Repositories\Survey;


use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Admin\Models\StaffsTable;
use Modules\Survey\Models\SurveyTable;
use Modules\Survey\Models\SurveyBlockTable;
use Modules\Survey\Models\SurveyBranchTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Models\SurveyConfigPointTable;
use Modules\Admin\Models\CustomerGroupFilterTable;
use Modules\Survey\Models\SurveyReportExportTable;
use Modules\Survey\Models\SurveyQuestionChoiceTable;
use Modules\Admin\Models\CustomerGroupDefineDetailTable;
use Modules\Survey\Models\SurveyTemplateNotificationTable;
use Modules\Admin\Repositories\CustomerGroupFilter\CustomerGroupFilterRepository;

trait SurveyTrait
{
    private $_TITLE = 'Đã hoàn thành nhiệm vụ';
    private $_SHOW_POINT = 1;
    private $_MESSAGE = 'Cám ơn bạn đã hoàn thành nhiệm vụ. Câu trả lời của bạn đã được ghi nhận!';
    private $_DETAIL_BACKGROUND = 'https://epoint-bucket.s3.ap-southeast-1.amazonaws.com/2d31780a0108715b3fa530aaaaa99bda/2022/08/23/NJYBU5166121736723082022_survey.png';
    private $_SHOW_ANSWER = 'N';
    private $_SHOW_ANSWER_WRONG = 1;
    private $_SHOW_ANSWER_SUCCESS = 1;
    private $_SHOW_POINT_TEXT = 1;
    private $_SHOW_POINT_DEFAUTL = 10;


    /**
     * Sắp xếp thứ tự của block theo vị trí
     * @param $data
     * @param $number
     */
    public function sortBlock(&$data, $number)
    {
        if (count($data) > 1) {
            foreach ($data as $key => $item) {
                if ($item['position'] > $number) {
                    $item['position'] += 1;
                }
                $data[$key] = $item;
            }
        }
    }

    /**
     * Lấy dữ liệu của block được lưu ở session ra
     * @param $unique
     * @return mixed
     */
    public function getDataSessionBlock($unique)
    {
        $data = session()->get($unique . '.block', []);
        return $data;
    }

    /**
     * Gán dữ liệu của block vào session
     * @param $unique
     * @param $data
     * @return mixed
     */
    public function putDataSessionBlock($unique, $data)
    {
        session()->put($unique . '.block', $data);
    }
    /**
     * Gán dữ liệu tổng điểm vào session
     *
     * @param  $data
     * @return void
     */

    public function putDataSessionTotalPoint($unique, $data)
    {
        session()->put($unique . '.total_point', $data);
    }

    /**
     * Lấy dữ liệu của block được lưu ở session ra
     * @param $unique
     * @return mixed
     */
    public function getDataSessionTotalPoint($unique)
    {
        $data = session()->get($unique . '.total_point', []);
        return $data;
    }

    /**
     * Sắp xếp lại các câu hỏi theo vị trí (đổi luôn element position theo giá trị tăng dần)
     * @param $data
     */
    public function sortQuestion(&$data)
    {
        if (count($data) > 0) {
            foreach ($data as $key => $item) {
                if (count($item['question']) > 0) {
                    $item['question'] = collect($item['question'])->sortByDesc(['position'])->toArray();
                    $item['question'] = array_reverse($item['question']);
                    foreach ($item['question'] as $q => $question) {
                        $item['question'][$q]['position'] = (int) ($q + 1);
                    }
                    $data[$key] = $item;
                }
            }
        }
    }

    /**
     * Validate dữ liệu đầu vào tab thông tin chung
     * @param $params
     * @return array
     */
    public function validateDataInfo(&$params)
    {
        $errors = [];
        if ($params['is_exec_time'] == 1) {
            // Format lại thời gian bắt đầu Y-m-d H:i:s
            $params['start_date'] = Carbon::createFromFormat('H:i:s d/m/Y', $params['start_date'])
                ->format('Y-m-d H:i:s');
            // Format lại thời gian kết thúc Y-m-d H:i:s
            $params['end_date'] = Carbon::createFromFormat('H:i:s d/m/Y', $params['end_date'])
                ->format('Y-m-d H:i:s');
            // Thời gian bắt đầu phải lớn hơn hoặc bằng thời gian hiện tại
            if (strtotime($params['start_date']) < strtotime(Carbon::now())) {
                $errors[] = __('survey::validation.start_date_now_fail');
            }
            // Thời kết thúc phải lớn hơn thời gian bắt đầu
            if (strtotime($params['start_date']) > strtotime($params['end_date'])) {
                $errors[] = __('survey::validation.start_date_end_date_fail');
            }
        }
        if ($params['is_limit_exec_time'] == 1) {
            if (strtotime($params['exec_time_from']) >= strtotime($params['exec_time_to'])) {
                $errors[] = __('survey::validation.start_time_end_time_fail');
            }
        }
        return $errors;
    }

    /**
     * validate dữ liệu cấu hình khảo sát có tính điểm khi chọn cấu hình thời gian
     * @param [array] $param
     * @return array
     */
    public function validationConfigPoint($params)
    {
        $error = [];
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->getItem($params['idSurvey']);
        if ((int)$params['point_default'] <= 0) {
            $error["error_point"] = __('Vui lòng nhập số điểm lớn 0');
        }
        if ($params['show_answer'] == 'C') {
            $timeStartConfigPoint =  Carbon::createFromFormat('H:i:s d/m/Y', $params['time_start'])
                ->format('Y-m-d H:i:s');
            $timeEndConfigPoint = Carbon::createFromFormat('H:i:s d/m/Y', $params['time_end'])->format('Y-m-d H:i:s');
            if ($timeStartConfigPoint > $timeEndConfigPoint) {
                $error["error_date"] = __('Thời gian kết thúc phải lớn hơn thời gian bắt đầu');
            }
            if ($itemSurvey->is_exec_time) {
                // kiểm tra thời gian cấu hình với thời gian bắt đầu khảo sát //
                if ($timeStartConfigPoint < $itemSurvey->start_date) {
                    $error["error_time_survey"] = __('Thời gian bắt đầu hiển hiển thị không đúng');
                }
            } else {
                if ($timeStartConfigPoint < $itemSurvey->created_at) {
                    $error["error_time_survey"] = __('Thời gian bắt đầu hiển hiển thị không đúng');
                }
            }
        }
        return $error;
    }

    /**
     * Validate dữ liệu đầu vào tab câu hỏi khảo sát
     * @param $data
     * @return array
     */
    public function validateDataSurveyQuestion($data, $countPoint = null)
    {

        $errors = [];
        if ($data != []) {
            foreach ($data as $key => $block) {
                if ($block['block_name'] != '') {
                    // RET-1761 Không bắt buộc - Cho phép nhập tối đa 100 ký tự
                    $validator = \Validator::make(
                        ['block_name' => $block['block_name']],
                        [
                            'block_name' => 'max:100',
                        ],
                        [
                            'block_name.max' => __('survey::validation.block_name_max'),
                        ]
                    );
                    if ($validator->fails()) {
                        $errors['block_name_max'] = $validator->errors()->first();
                    }
                }
                if (count($block['question']) > 0) {

                    foreach ($block['question'] as $k => $question) {
                        if (
                            $question['survey_question_type'] == self::SINGLE_CHOICE
                            || $question['survey_question_type'] == self::MULTI_CHOICE
                        ) {
                            // Trắc nghiệm
                            $this->validateSurveyQuestionTypeSingleChoice($errors, $question);
                            // trắc nghiệm tính điểm 
                            if ($countPoint) {
                                $errorPoint =  $this->validationSurveyQuestionChoicePoint($question);
                                if ($errorPoint) {
                                    $errors['select_answer_success'] = $errorPoint;
                                }
                            }
                        } elseif ($question['survey_question_type'] == self::TEXT) {

                            // Tự luận
                            $this->validateSurveyQuestionTypeText($errors, $question);
                        } elseif ($question['survey_question_type'] == self::PAGE_PICTURE) {
                            // Hình ảnh minh họa
                            $this->validateSurveyQuestionTypePagePicture($errors, $question);
                        }
                    }
                }
            }
        }
        return $errors;
    }

    /**
     * Loại câu hỏi trắc nghiệm có tính điểm
     * @param $errors
     * @param $question
     */

    private function validationSurveyQuestionChoicePoint($question)
    {
        $error = '';
        // kiểm tra câu hỏi public link mặc định
        if (isset($question['question_pl_defaul']))
        return $error;
        
        if ($question['answer_success'] == '' ||  $question['answer_success'] == []) {
            $error = __('Bạn chưa chọn đáp án chính xác cho các câu hỏi. Bạn có muốn tiếp tục cập nhật.');
        }
        return $error;
    }

    /**
     * RET-1746 Loại câu hỏi "Trắc nghiệm"
     * @param $errors
     * @param $question
     */
    private function validateSurveyQuestionTypeSingleChoice(&$errors, $question)
    {
        // "Nhập câu hỏi" - bắt buộc - tối đa 400 kí tự
        $validator = \Validator::make(
            ['question' => $question['question']],
            [
                'question' => 'required|max:400',
            ],
            [
                'question.required' => __('survey::validation.question_required'),
                'question.max' => __('survey::validation.question_max'),
            ]
        );
        if ($validator->fails()) {
            $errors['question_error'] = $validator->errors()->first();
        }

        // "Nhập đáp án" - bắt buộc - tối đa 255 kí tự
        foreach ($question['answer'] as $ka => $vAnswer) {
            $validator = \Validator::make(
                ['answer' => $vAnswer],
                [
                    'answer' => 'required|max:255',
                ],
                [
                    'answer.required' => __('survey::validation.answer_required'),
                    'answer.max' => __('survey::validation.answer_max'),
                ]
            );
            if ($validator->fails()) {
                $errors['answer_error'] = $validator->errors()->first();
            }
        }
    }

    /**
     * RET-1753 Loại câu hỏi "Tự luận"
     * @param $errors
     * @param $question
     */
    private function validateSurveyQuestionTypeText(&$errors, $question)
    {
        // "Nhập câu hỏi" - bắt buộc - tối đa 400 kí tự
        $validator = \Validator::make(
            ['question' => $question['question']],
            [
                'question' => 'required|max:400',
            ],
            [
                'question.required' => __('survey::validation.question_required'),
                'question.max' => __('survey::validation.question_max'),
            ]
        );
        if ($validator->fails()) {
            $errors['question_error'] = $validator->errors()->first();
        }
        // Loại xác nhận - Số kí tự tối thiểu: Bắt buộc và > 0
        if ($question['confirm_type'] == 'min') {
            $validator = \Validator::make(
                ['min_value' => $question['min_value']],
                [
                    'min_value' => 'required|numeric|min:1',
                ],
                [
                    'min_value.required' => __('survey::validation.min_value_required'),
                    'min_value.numeric' => __('survey::validation.min_value_min'),
                    'min_value.min' => __('survey::validation.min_value_min'),
                ]
            );
            if ($validator->fails()) {
                $errors['min_value_error'] = $validator->errors()->first();
            }
        } elseif ($question['confirm_type'] == 'max') {
            // Loại xác nhận - Số kí tự tối đa: Bắt buộc và > 0
            $validator = \Validator::make(
                ['max_value' => $question['max_value']],
                [
                    'max_value' => 'required|numeric|min:1',
                ],
                [
                    'max_value.required' => __('survey::validation.max_value_required'),
                    'max_value.numeric' => __('survey::validation.max_value_min'),
                    'max_value.min' => __('survey::validation.max_value_min'),
                ]
            );
            if ($validator->fails()) {
                $errors['max_value_error'] = $validator->errors()->first();
            }
        } elseif ($question['confirm_type'] == 'digits_between') {
            // Loại xác nhận - Chọn số kí tự
            // Loại xác nhận - Số kí tự tối thiểu: Bắt buộc và > 0
            $validator = \Validator::make(
                ['min_value' => $question['min_value']],
                [
                    'min_value' => 'required|numeric|min:1',
                ],
                [
                    'min_value.required' => __('survey::validation.min_value_required'),
                    'min_value.numeric' => __('survey::validation.min_value_min'),
                    'min_value.min' => __('survey::validation.min_value_min')
                ]
            );
            if ($validator->fails()) {
                $errors['min_value_error'] = $validator->errors()->first();
            }
            // Loại xác nhận - Số kí tự tối đa: Bắt buộc và > 0
            $validator = \Validator::make(
                ['max_value' => $question['max_value']],
                [
                    'max_value' => 'required|numeric|min:1',
                ],
                [
                    'max_value.required' => __('survey::validation.max_value_required'),
                    'max_value.numeric' => __('survey::validation.max_value_min'),
                    'max_value.min' => __('survey::validation.max_value_min'),
                ]
            );
            if ($validator->fails()) {
                $errors['max_value_error'] = $validator->errors()->first();
            }
            // Giá trị trong ô kí tự tối đa phải > Giá trị trong ô kí tự tối thiểu
            if (
                $question['min_value'] != '' && $question['max_value'] != ''
                && $question['max_value'] <= $question['min_value']
            ) {
                $errors['min_max_value_is_char_custom_false'] = __('survey::validation.min_max_value_is_char_custom_false');
            }
        } elseif ($question['confirm_type'] == 'numeric') {
            // Loại xác nhận - Xác nhận nội dung - Number
            // Giá trị lớn nhất phải lớn hơn giá trị nhỏ nhất
            if (
                $question['min_value'] != '' && $question['max_value'] != ''
                && $question['max_value'] <= $question['min_value']
            ) {
                $errors['min_max_value_is_number_false'] = __('survey::validation.min_max_value_is_number_false');
            }
        }
    }

    /**
     * RET-1746 Loại câu hỏi "THình ảnh minh họa"
     * @param $errors
     * @param $question
     */
    private function validateSurveyQuestionTypePagePicture(&$errors, $question)
    {
        // Phải có ảnh
        foreach ($question['image'] as $ki => $vImage) {
            $validator = \Validator::make(
                ['image' => $vImage],
                [
                    'image' => 'required',
                ],
                [
                    'image.required' => __('survey::validation.image_required'),
                ]
            );
            if ($validator->fails()) {
                $errors['image_required'] = $validator->errors()->first();
            }
        }
    }

    /**
     * Xóa dữ liệu của tab câu hỏi khảo sát
     * Xóa các block/ câu hỏi
     * @param $id
     */
    public function removeSurveyBlockQuestion($id)
    {
        $mSurveyBlock = new SurveyBlockTable();
        $mSurveyQuestion = new SurveyQuestionTable();
        $mSurveyQuestionChoice = new SurveyQuestionChoiceTable();
        // Xóa block câu hỏi
        $mSurveyBlock->removeBySurveyId($id);
        // Xóa danh sách câu hỏi của bảng khảo sát
        $mSurveyQuestion->removeBySurveyId($id);
        // Xóa danh sách câu trả lời của câu hỏi
        $mSurveyQuestionChoice->removeBySurveyId($id);
    }

    /**
     * RET-8481
     * Validate dữ liệu khi duyệt khảo sát
     * Tab Câu hỏi khảo sát: có ít nhất 1 câu hỏi được cài đặt
     * Tab Thành viên áp dụng: có ít nhất 1 thành viên/1 giá trị thuộc tính/1 khu vực địa lý được chọn
     * @param $id
     * @return array
     */
    public function validateDataSurvey($id)
    {
        $errors = [];
        $mSurvey = new SurveyTable();
        $itemSurvey = $mSurvey->with('questions')
            ->with('staffs')
            ->with('customers')
            ->find($id);
        // danh sách câu hỏi //
        $listQuestion = $itemSurvey->questions;
        if ($listQuestion->count() <= 0) {
            $errors['tab_required'] = __('survey::validation.tab_required');
            return $errors;
        }
        // banner câu hỏi khảo sát //
        $bannerSurvey = $itemSurvey->survey_banner;
        if (!$bannerSurvey) {
            $errors['tab_required'] = __('survey::validation.tab_required');
            return $errors;
        }
        // kiểu đối tương áp dụng (nhân viên, khách hàng)
        $typeApply = $itemSurvey->type_apply;
        $typeUser  = $itemSurvey->type_user;
        // kiểm tra các điều kiện để duyêt chương trình khảo sát //
        if ($typeApply == '' || $typeUser == '') {
            $errors['tab_required'] = __('survey::validation.tab_required');
            return $errors;
        } else {
            // danh sách nhân viên áp dụng //
            if ($typeUser == 'staff') {
                if ($typeApply == 'staffs') {
                    $listStaffApplyDefine = $itemSurvey->staffs;
                    $staffAuto = $this->getAllStaffAutoApply($itemSurvey);
                    if ($listStaffApplyDefine->count() <= 0 && count($staffAuto) <= 0) {
                        $errors['tab_required'] = __('survey::validation.tab_required');
                        return $errors;
                    }
                }
            } else {
                // danh sách khách hàng áp dụng
                if ($typeApply == 'customers') {
                    $listStaffApplyDefine = $itemSurvey->customers;
                    $customerAuto = $this->getAllCustomerAutoApply($itemSurvey);
                    if ($listStaffApplyDefine->count() <= 0 && count($customerAuto) <= 0) {
                        $errors['tab_required'] = __('survey::validation.tab_required');
                        return $errors;
                    }
                }
            }
        }
        return $errors;
    }


    /**
     * Lưu danh sách báo cáo khảo sát để export
     * @param $exportId
     * @param $data
     * @throws \Exception
     */
    public function insertSurveyReportExport($exportId, $data)
    {
        $list = $data['list'];
        $outletMasterCompanyBranch = $data['outletMasterCompanyBranch'];
        $dataInsert = [];
        $mSurveyReportExport = new SurveyReportExportTable();
        if (count($list) > 0) {
            foreach ($list as $item) {
                $companyBranchCode = '';
                $companyBranchName = '';
                if (
                    !isset($outletMasterCompanyBranch[$item['outlet_id']])
                    && empty($item['company_branch_id'])
                ) {
                    $companyBranchCode = __('Chưa có');
                    $companyBranchName = __('Chưa có');
                } else {
                    if (!empty($item['company_branch_code']) && !empty($item['company_branch_name'])) {
                        $companyBranchCode = $item['company_branch_code'];
                        $companyBranchName = $item['company_branch_name'];
                    }
                    if (isset($outletMasterCompanyBranch[$item['outlet_id']]) && count($outletMasterCompanyBranch[$item['outlet_id']])) {
                        foreach ($outletMasterCompanyBranch[$item['outlet_id']] as $k => $cb) {
                            if ($cb['company_branch_id'] != $item['company_branch_id']) {
                                if ($k == 0) {
                                    if (!empty($item['company_branch_code']) && !empty($item['company_branch_name'])) {
                                        $companyBranchCode .= '<br>';
                                        $companyBranchName .= '<br>';
                                    }
                                } else {
                                    $companyBranchCode .= '<br>';
                                    $companyBranchName .= '<br>';
                                }
                                $companyBranchCode .= $cb['company_branch_code'];
                                $companyBranchName .= $cb['company_branch_name'];
                            }
                        }
                    }
                }
                $finishedAt = '';
                if (!empty($item['finished_at'])) {
                    $finishedAt = (new \DateTime($item['finished_at']))->format('H:i:s d/m/Y');
                }
                $dataInsert[] = [
                    'export_id' => $exportId,
                    'company_branch_code' => $companyBranchCode,
                    'company_branch_name' => $companyBranchName,
                    'customer_code' => $item['customer_code'],
                    'ship_to_code' => $item['ship_to_code'],
                    'ship_to_name' => $item['ship_to_name'],
                    'address' => $item['address'],
                    'created_at' => $finishedAt,
                    'survey_question_type' => $this->convertSurveyQuestionType($item['survey_question_type']),
                    'survey_question' => $item['survey_question_description'],
                    'answer_value' => $this->convertAnswerValue($data, $item),
                ];
                if (count($dataInsert) == MAX_SIZE_INSERT_ARRAY) {
                    $mSurveyReportExport->addInsert($dataInsert);
                }
            }
            $mSurveyReportExport->addInsert($dataInsert);
        }
    }

    /**
     * Loại câu hỏi
     * @param $type
     * @return array|string|null
     */
    public function convertSurveyQuestionType($type)
    {
        $result = '';
        if ($type == 'single_choice') {
            $result = __('Trắc nghiệm') . ' - 1 ' . __('đáp án');
        } elseif ($type == 'multi_choice') {
            $result = __('Trắc nghiệm') . ' - ' . __('nhiều đáp án');
        } elseif ($type == 'text') {
            $result = __('Tự luận');
        } elseif ($type == 'page_text') {
            $result = __('Văn bản mô tả');
        } elseif ($type == 'page_picture') {
            $result = __('Hình ảnh minh họa');
        }
        return $result;
    }

    /**
     * Câu hỏi
     * @param $item
     * @return mixed
     */
    public function convertSurveyQuestion($item)
    {
        $result = $item['survey_question_title'];
        if ($item['survey_question_type'] == 'page_text' || $item['survey_question_type'] == 'page_picture') {
            $result = $item['survey_question_description'];
        }
        return $result;
    }

    /**
     * Câu trả lời
     * @param $data
     * @param $item
     * @return mixed|string
     */
    public function convertAnswerValue($data, $item)
    {
        $result = '';
        if ($item['survey_question_type'] == 'single_choice' || $item['survey_question_type'] == 'multi_choice') {
            if (isset($data['answer'][$item['customer_ship_code_sai_sqi']])) {
                $br = '';
                if (count($data['answer'][$item['customer_ship_code_sai_sqi']]) > 1) {
                    $br = '<br>';
                }
                foreach ($data['answer'][$item['customer_ship_code_sai_sqi']] as $ans) {
                    $result .= $ans['survey_question_choice_title'] . $br;
                }
            }
        } else {
            $result = isset($data['answer'][$item['customer_ship_code_sai_sqi']][0]['answer_value']) ? $data['answer'][$item['customer_ship_code_sai_sqi']][0]['answer_value'] : '';
        }
        return $result;
    }

    /**
     * Cài đặt trang hoàn thành mặc định
     * @param $id
     * @param $isCountPoint
     */
    public function addSurveyTemplateNotification($id)
    {
        $mSurveyTemplateNotification = new SurveyTemplateNotificationTable();
        $data = [
            'survey_id' => $id,
            'title' => __($this->_TITLE),
            'message' => __($this->_MESSAGE),
            'detail_background' => $this->_DETAIL_BACKGROUND,
            'show_point' => $this->_SHOW_POINT,
            'created_at' => Carbon::now(),
        ];
        $mSurveyTemplateNotification->add($data);
    }

    /**
     * Cài đặt cấu hình tính điểm 
     * @param $id
     * @return void
     */

    public function addSurveyConfigPoint($id)
    {
        $mSurveyConfigPoint = app()->get(SurveyConfigPointTable::class);
        $data = [
            'survey_id' => $id,
            'show_answer' => $this->_SHOW_ANSWER,
            'show_answer_wrong' => $this->_SHOW_ANSWER_WRONG,
            'show_answer_success' => $this->_SHOW_ANSWER_SUCCESS,
            'show_point' => $this->_SHOW_POINT,
            'count_point_text' => $this->_SHOW_POINT_TEXT,
            'point_default' => $this->_SHOW_POINT_DEFAUTL,
        ];
        $result = $mSurveyConfigPoint->create($data);
        return $result;
    }

    /**
     * Tạo câu hỏi mặc định cho khảo sát public link
     * @param [int] $idSurvey
     * @return mixed
     */

    public function templateQuestionSurveyPublicLink($idSurvey)
    {
        $mSurvey = app()->get(SurveyTable::class);
        $itemSurvey = $mSurvey->find($idSurvey);
        $listQuestion = [
            [
                'survey_question_type' => "text",
                'position' => 1,
                'is_required' => 1,
                'question' => __('Họ tên của bạn là gì?'),
                'confirm_type' => ["valid_type" => "none"],
                'min_value' => '',
                'max_value' => ''
            ],
            [
                'survey_question_type' => "text",
                'position' => 2,
                'is_required' => 1,
                'question' => __('Số điện thoại của bạn là gì?'),
                'confirm_type' => ["valid_type" => "phone"],
                'min_value' => '',
                'max_value' => ''
            ],
            [
                'survey_question_type' => "text",
                'position' => 3,
                'is_required' => 0,
                'question' => __('Email của bạn là gì?'),
                'confirm_type' => ["valid_type" => "email"],
                'min_value' => '',
                'max_value' => ''
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 4,
                'is_required' => 1,
                'question' => __('Giới tính của bạn là?'),
                'answer' => [
                    __('Nam'),
                    __('Nữ'),
                    __('Khác')
                ],
            ],
            [
                'survey_question_type' => "single_choice",
                'position' => 5,
                'is_required' => 1,
                'question' => __('Độ tuổi của bạn?'),
                'answer' => [
                    __('Từ 18 - 25 tuổi'),
                    __('Từ 26 - 35 tuổi'),
                    __('Từ 36 - 45 tuổi'),
                    __('Trên 45 tuổi'),
                ],
            ]
        ];
        $blockQuestion = $itemSurvey->blocks()->create([
            'survey_block_name' => __('Thu thập thông tin khách hàng'),
            'survey_block_position' => 1
        ]);
        foreach ($listQuestion as $item) {
            $dataQuestion = [
                'survey_question_type' => $item['survey_question_type'],
                'survey_question_position' => $item['position'],
                'is_required' => $item['is_required'],
                'survey_question_description' => $item['question'],
                'survey_block_id' => $blockQuestion->survey_block_id
            ];
            if ($item['survey_question_type'] == 'text') {
                $dataQuestion['survey_question_config'] = json_encode($item['confirm_type']);
            }
            $itemQuestion = $itemSurvey->questions()->create($dataQuestion);
            if ($item['survey_question_type'] == 'single_choice') {
                $dataInsertSignChoice = [];
                foreach ($item['answer'] as $key => $v) {
                    $dataInsertSignChoice[] = [
                        'survey_question_id' => $itemQuestion->survey_question_id,
                        'survey_id' => $itemSurvey->survey_id,
                        'survey_question_choice_title' => $v,
                        'survey_question_choice_position' => $key + 1,
                        'survey_question_choice_config' => json_encode([])
                    ];
                }
                $itemQuestion->singleChoice()->createMany($dataInsertSignChoice);
            }
        }
    }

    /**
     * Khi ngày hiện tại > ngày kết thúc thì đóng khảo sát
     */
    public function closeSurvey()
    {
        $mSurvey = new SurveyTable();
        $mSurvey->closeSurvey();
    }
}
