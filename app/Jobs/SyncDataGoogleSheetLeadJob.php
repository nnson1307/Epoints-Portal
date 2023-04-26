<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\SyncDataGoogleSheet\Models\DataGoogleSheetTable;
use Modules\SyncDataGoogleSheet\Models\RowLastGoogleSheetTable;

class SyncDataGoogleSheetLeadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            // dữ liệu insert googleSheet //
            $dataLeadOnlineInsert = $this->data['dataOnlineLead'];
            // số dòng cuối cùng insert //
            $numberRow = $this->data['rowLast'];
            // dữ liệu của đơn vị nào //
            $idGoogleSheet = $this->data['idGoogleSheet'];
            // băm nhỏ data để insert //
            $chunksData = collect($dataLeadOnlineInsert)->chunk(50)->toArray();
            foreach ($chunksData as $item) {
                // kiểm tra số điện thoại có tồn tại và convert lại dữ liệu //
                $filterItem = $this->checkUniquePhone($item);
                // insert dữ liệu googleSheet //
                $this->insertDataGoogleSheet($filterItem);
            }
            // cập nhật hàng cuối cùng googleSheet //
            $this->updateRowLastGoogleSheet($numberRow, $idGoogleSheet);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::info("error : " . $e->getMessage());
        }
    }

    public function insertDataGoogleSheet(array $data = [])
    {
        // lấy instant model dataGoogleSheet //
        $dataVsetGoogleSheet = app()->get(DataGoogleSheetTable::class);
        // insert data vset //
        $dataVsetGoogleSheet->insertMutipleDataGoogleSheet($data);
    }

    /**
     * cập nhật hàng cuối cùng insert googleSheet
     * @param [int] $numberRow
     * @param [string] $idGoogleSheet
     * @return void
     */

    public function updateRowLastGoogleSheet($numberRow, $idGoogleSheet)
    {
        // data cập nhật số hàng cuối cùng //
        $data = [
            'number_row_last' => $numberRow,
            'id_google_sheet' => $idGoogleSheet
        ];
        // lấy instant model rowLastGoogleSheet //
        $numberRowLastGoogleSheet = app()->get(RowLastGoogleSheetTable::class);
        // cập nhật lại số hàng cuối cùng insert //
        $numberRowLastGoogleSheet->updateRowlast($data);
    }

    /**
     * kiểm tra trường số điện thoại
     * @param [array] $data
     * @return array
     */
    public function checkUniquePhone($data)
    {
        // lấy instant model CustomerLeadTable //
        $customerLead = app()->get(CustomerLeadTable::class);
        $dataNew = [];
        foreach ($data as  $value) {
            if (isset($value['phone'])) {
                $filters = [
                    'condition_phone' => [
                        'id_google_sheet' =>  $value['id_google_sheet'] ?? "",
                        'number_row'      => $value['number_row'] ?? "",
                    ]
                ];
                $checkPhoneUnique = $customerLead->checkPhoneUnique($value['phone'], $filters);
                if ($checkPhoneUnique) {
                    $value['is_success'] = 1;
                    $value['is_error'] = 2;
                }
            }
            $dataNew[] = $value;
        }
        return $dataNew;
    }
}
