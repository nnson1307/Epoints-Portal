<?php

namespace Modules\Loyalty\Repositories\AccumulatePointsProgram;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Survey\Models\SurveyTable;
use Modules\Admin\Models\MemberLevelTable;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Modules\Loyalty\Models\LoyaltyConfigNotificationTable;
use Modules\Loyalty\Models\LoyaltyAccumulationProgramTable;
use Modules\Loyalty\Models\LoyaltyAccumulationProgramRankTable;

class AccumulatePointsProgramRepository implements AccumulatePointsProgramRepositoryInterface
{
    protected $accumulation;

    const GOODS_RECEIPT = 'goods_receipt';
    const SURVEY = 'survey';

    public function __construct(

        LoyaltyAccumulationProgramTable $accumulation
    ) {

        $this->accumulation = $accumulation;
    }

    /**
     * get list visibility rule
     * @param array $filters
     * @return mixed
     */
    public function getList(array $filters = [])
    {
        if (!isset($filters['page'])) {
            $filters['page'] = 1;
        }
        $result = [
            'data' => $this->accumulation->getListNew($filters),
            'filter' => $filters
        ];
        return $result;
    }

    /**
     * get list survey
     * @return mixed
     */
    public function getListSurvey()
    {
        $survey = app()->get(SurveyTable::class);
        $listSurvey = $survey->getListSurveyCondition();
        return $listSurvey;
    }

    /**
     * get all list rank
     * @return mixed
     */

    public function getAllRank()
    {
        $rank = app()->get(MemberLevelTable::class);
        $listRank = $rank->getAll();
        return $listRank;
    }

    /**
     * store loyalty 
     * @param $params
     * @return mixed
     */

    public function store($params)
    {
        DB::beginTransaction();
        try {
            $itemProgram = $this->accumulation->create([
                'accumulation_program_name' => $params['accumulation_program_name'],
                'survey_id' => $params['survey_id'],
                'validity_period_type' => $params['validity_period_type'],
                'date_start' => isset($params['date_start']) ? Carbon::createFromFormat('d/m/Y H:i', $params['date_start'])->format('Y-m-d H:i') : null,
                'date_end' => isset($params['date_end']) ? Carbon::createFromFormat('d/m/Y H:i', $params['date_end'])->format('Y-m-d H:i') : null,
                'apply_type' => $params['apply_type'],
                'source_point_key' => self::SURVEY,
                'accumulation_point' => $params['accumulation_point'] ?? null,
                'description' => $params['description'],
                'is_active' => $params['is_active'],
                'created_by' => Auth::id()
            ]);
            // kiểm tra chương tích điểm loại công điểm theo rank //
            if ($params['apply_type'] == 'rank') {
                // thêm chương trình tích điểm
                $this->storeRankProgramLoyalty($itemProgram, $params['valuePoint']);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('loyalty::validation.accumulate_point.create_success'),
                'id' => $itemProgram->accumulation_program_id
            ];
        } catch (\exception $e) {
            DB::rollBack();
            Log::info('error_loyalty_create : ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('loyalty::validation.accumulate_point.create_fail')
            ];
        }
    }

    /**
     * store rank program loyalty
     * @param $itemProgram
     * @param $listValueRank
     */
    public function storeRankProgramLoyalty($itemProgram, $listValueRank)
    {
        $listValueRankNew = [];
        // kiểm tra list value rank và chuyển đổi để insert //
        if (count($listValueRank) > 0) {
            foreach ($listValueRank as $item) {
                $listValueRankNew[$item['rankId']] = [
                    'accumulation_point' => $item['accumulatePoint'],
                    'created_by' => Auth::id()
                ];
            }
            $itemProgram->ranks()->attach($listValueRankNew);
        }
    }

    /**
     * update rank program loyalty
     * @param [oject] $itemProgram 
     * @param [array] $listValueRank
     */

    public function updateRankProgramLoyalty($itemProgram, $listValueRank)
    {
        $listValueRankNew = [];
        if (count($listValueRank) > 0) {
            foreach ($listValueRank as $item) {
                $listValueRankNew[$item['rankId']] = [
                    'accumulation_point' => $item['accumulatePoint'],
                    'created_by' => Auth::id()
                ];
            }
        }
        $itemProgram->ranks()->sync($listValueRankNew);
    }

    /**
     * get item program
     * @param $idProgram
     * @return mixed
     */

    public function findItem($idProgram)
    {

        $itemProgram = $this->accumulation
            ->with(['survey' => function ($query) {
                $query->select('survey.survey_id');
            }])
            ->with('ranks')
            ->find($idProgram);
        return $itemProgram;
    }

    /**
     * update item program 
     * @param $idProgram
     * @return mixed
     */

    public function update($params)
    {

        DB::beginTransaction();
        try {
            $itemProgram = $this->accumulation->find($params['id']);
            $itemProgram->update([
                'accumulation_program_name' => $params['accumulation_program_name'],
                'survey_id' => $params['survey_id'],
                'validity_period_type' => $params['validity_period_type'],
                'date_start' => isset($params['date_start']) ? Carbon::createFromFormat('d/m/Y H:i', $params['date_start'])->format('Y-m-d H:i') : null,
                'date_end' =>  isset($params['date_end']) ? Carbon::createFromFormat('d/m/Y H:i', $params['date_end'])->format('Y-m-d H:i') : null,
                'apply_type' => $params['apply_type'],
                'source_point_key' => self::SURVEY,
                'accumulation_point' => $params['accumulation_point'] ?? null,
                'description' => $params['description'],
                'is_active' => $params['is_active'],
                'created_by' => Auth::id()
            ]);
            // kiểm tra chương tích điểm loại công điểm theo rank //
            if ($params['apply_type'] == 'rank') {
                // thêm chương trình tích điểm
                $this->updateRankProgramLoyalty($itemProgram, $params['valuePoint']);
            } else {
                $this->updateRankProgramLoyalty($itemProgram, []);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => __('loyalty::validation.accumulate_point.update_success'),
                'id' => $itemProgram->accumulation_program_id
            ];
        } catch (\exception $e) {
            DB::rollBack();
            Log::info('error_loyalty_create : ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('loyalty::validation.accumulate_point.update_fail')
            ];
        }
    }

    /**
     * show config notification 
     * @return mixed
     */

    public function showConfigNotification()
    {
        $configNotification = app()->get(LoyaltyConfigNotificationTable::class);
        $itemConfigNotification = $configNotification->getDetailByKey('loyalty_accum_photo_tracking');
        return $itemConfigNotification;
    }

    /**
     * Cập nhật cấu hình thông báo 
     * @param $params
     * @return mixed
     */

    public function updateSettingNotification($params)
    {
        try {
            $configNotification = app()->get(LoyaltyConfigNotificationTable::class);
            $itemConfigNotification = $configNotification->getDetailByKey('loyalty_accum_photo_tracking');
            $itemConfigNotification->update([
                'title' => $params['title_template'],
                'message' => $params['message_template'],
                'avatar' => $params['avatar'],
                'detail_content' => $params['des_detail_template'],
                'params_show' => json_encode($params['arrPramShow']),
            ]);
            return [
                'error' => false,
                'message' => __('loyalty::accumulate-points-program.template_config_notifi.update_success')
            ];
        } catch (\Exception $e) {
            Log::info('Update Config notification : ' . $e->getMessage());
            return [
                'error' => false,
                'message' => __('loyalty::accumulate-points-program.template_config_notifi.update_fail')
            ];
        }
    }

    /**
     * Show item xoá tích điểm
     * @param int $idLoyalty
     * @return mixed
     */

    public function showItemDestroy($idLoyalty)
    {
        try {
            $itemLoyalty = $this->accumulation->find($idLoyalty);
            $view = view('loyalty::accumulate-points-program.modal.remove_loyalty', [
                'itemLoyalty' => $itemLoyalty
            ])->render();
            return [
                'view' => $view,
                'error' => false,
            ];
        } catch (\Exception $e) {
            Log::info('Show modal destroy : ' . $e->getMessage());
            return [
                'error' => true
            ];
        }
    }

    /**
     * destroy loyalty 
     * @param $request
     * @return mixed
     */

    public function destroy($idLoyalty)
    {
        try {
            $itemLoyalty = $this->accumulation->find($idLoyalty);
            $itemLoyalty->update([
                'is_deleted' => 1
            ]);
            return [
                'error' => false,
                'message' => __('loyalty::accumulate-points-program.delete.delete_success')
            ];
        } catch (\Exception $e) {
            Log::info('Destroy : ' . $e->getMessage());
            return [
                'error' => true,
                'message' => __('loyalty::accumulate-points-program.delete.delete_fail')
            ];
        }
    }
}
