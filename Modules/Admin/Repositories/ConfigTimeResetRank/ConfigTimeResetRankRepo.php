<?php


namespace Modules\Admin\Repositories\ConfigTimeResetRank;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\ConfigTimeResetRankTable;

class ConfigTimeResetRankRepo implements ConfigTimeResetRankRepoInterface
{
    protected $configTimeResetRank;

    public function __construct(
        ConfigTimeResetRankTable $configTimeRestRank
    ) {
        $this->configTimeResetRank = $configTimeRestRank;
    }

    /**
     * Lấy danh sách
     */
    public function list(array $filters = [])
    {
        return $this->configTimeResetRank->getList($filters);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->configTimeResetRank->getItem($id);
    }

    public function edit(array $data)
    {
        $validator = \Validator::make($data, [
            'value' => 'required',
        ], [
            'value.required' => 'Hãy nhập tháng thiết lập',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                '_error' => $validator->errors()->all(),
                'message' => 'Chỉnh sửa thất bại'
            ]);
        } else {
            $getItem = $this->configTimeResetRank->getItem($data['id']);
            $insert = [
                'name' => $getItem['name'],
                'value' => $data['value'],
                'type' => $getItem['type'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            //Remove All Config Time Rank
            $this->configTimeResetRank->removeAll();
            $this->configTimeResetRank->add($insert);
            return response()->json([
                'error' => false,
                'message' => 'Chỉnh sửa thành công'
            ]);
        }
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getItemByType($type)
    {
        return $this->configTimeResetRank->getItemByType($type);
    }
}