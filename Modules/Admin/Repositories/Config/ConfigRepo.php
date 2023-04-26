<?php


namespace Modules\Admin\Repositories\Config;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Admin\Models\ConfigDetailTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CountryIsoTable;
use Modules\Admin\Models\ZoneTable;
use mysql_xdevapi\Exception;

class ConfigRepo implements ConfigRepoInterface
{
    protected $config;
    protected $configDetail;
    protected $zone;
    protected $countryIso;

    public function __construct(
        ConfigTable $config,
        ConfigDetailTable $configDetail,
        ZoneTable $zone,
        CountryIsoTable $countryIso
    )
    {
        $this->config = $config;
        $this->configDetail = $configDetail;
        $this->zone = $zone;
        $this->countryIso = $countryIso;
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->config->getAll();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->config->getInfoByKey($key);
    }

    public function getInfoById($id)
    {
        return $this->config->getInfoById($id);
    }

    public function updatekey($data)
    {
        try {
            if ($data['config_id'] == 3) {
                $arr = [];
                $tmp = '';
                if (isset($data['key'])) {
                    foreach ($data['key'] as $key => $item) {
                        if ($item != '') {
                            $arr [$key] = strip_tags($item);
                        }
                    }
                }
                $checkCountArray = array_count_values($arr);
                foreach ($checkCountArray as $key => $item) {
                    if ($item > 1) {
                        $tmp = $tmp . ' Từ khóa ' . $key . ' bị trùng <br>';
                    }
                }
                if ($tmp != '') {
                    return response()->json(["error" => true, 'message' => $tmp]);
                }
                $value['value'] = implode(';', $arr);
                $id = $data['config_id'];
                $this->config->edit($value, $id);

            } else if ($data['config_id'] == 4) {
                $id = $data['config_id'];
                unset($data['config_id']);
                $config['value'] = 1;
                if (!isset($data['auto_apply_branch'])) {
                    $config['value'] = 0;
                    unset($data['auto_apply_branch']);
                    $this->config->edit($config, $id);
                    return response()->json(["error" => false, 'message' => 'Cập nhật thành công']);
                }
                $message = '';
                if (!in_array($data['product_inventory'], [1, 2])) {
                    $message = $message . 'Giá trị tồn kho phải là 1 hoặc 2 </br>';
                }
                if (!in_array($data['range'], [1, 2])) {
                    $message = $message . 'Giá trị khoảng cách phải là 1 hoặc 2';
                }
                if ($message != '') {
                    return response()->json(["error" => true, 'message' => $message]);
                }
                if ($data['product_inventory'] == $data['range']) {
                    return response()->json(["error" => true, 'message' => 'Giá trị Tồn kho và Khoảng cách phải khác nhau']);
                }

                $this->config->edit($config, $id);

                $this->configDetail->edit('product_inventory', $data['product_inventory']);
                $this->configDetail->edit('range', $data['range']);
            } else if (in_array($data['config_id'], [7,8])) {
                if ($data['logo'] == null) {
                    $data['logo'] = $data['logo_old'];
                }

                //Cập nhật logo
                $this->config->edit([
                    'value' => $data['logo']
                ], $data['config_id']);

            } else {
                $validator = Validator::make($data, [
                    'value' => 'max:255'],
                    [
                        'value.max' => 'Giá trị vượt quá 255 ký tự',
                    ]);
                if ($validator->fails()) {
                    $html = '';
                    foreach ($validator->errors()->all() as $item) {
                        $html = $item . '<br/>';
                    }
                    return response()->json([
                        'error' => true,
                        'message' => $html
                    ]);
                }

                if (in_array($data['config_id'], [13,22])){
//                    if ($data['value'])
                        $id = $data['config_id'];
                    unset($data['config_id']);
                    $config['value'] = strip_tags($data['value']);
                    $this->config->edit($config, $id);
                }else {
                    if ($data['value'])
                        $id = $data['config_id'];
                    unset($data['config_id']);
                    $config['value'] = strip_tags($data['value']);
                    $this->config->edit($config, $id);
                }

            }
            return response()->json(["error" => false, 'message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Cập nhật thất bại'
            ];
        }
    }

    public function getConfigDetail($id)
    {
        return $this->configDetail->getAllById($id);
    }

    public function getZone()
    {
        return $this->zone->getAll();
    }

    public function getCountryIso()
    {
        return $this->countryIso->getAll();
    }

    public function getNameCountryIso($country_iso)
    {
        return $this->countryIso->getDetailByIso($country_iso);
    }

    /**
     * Move ảnh từ folder temp sang folder chính
     *
     * @param $filename
     * @param $PATH
     * @return mixed|string
     */
    public function moveImage($filename, $PATH)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = $PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory($PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    /**
     * Data view chỉnh sửa cấu hình chung
     *
     * @param $id
     * @return array|mixed
     */
    public function dataViewEdit($id)
    {
        $detail = $this->config->getInfoById($id);
        $configDetail = $this->configDetail->getAllById($id);
        $option = [];
        if ($detail != null) {
            switch ($detail['key']) {
                case 'timezone':
                    $zone = $this->zone->getAll();
                    foreach ($zone as $item) {
                        $option[$item['zone_name']] = $item['zone_name'];
                    }
                    break;
                case 'area_code':
                    $countryIso = $this->countryIso->getAll();
                    foreach ($countryIso as $item) {
                        $option[$item['country_iso']] = $item['country_name'];
                    }
                    break;
            }
        } else {
            return redirect()->route('admin.config.config-general');
        }
        return [
            'detail' => $detail,
            'configDetail' => $configDetail,
            'option' => $option
        ];
    }

    /**
     * Cập nhật cấu hình chung (update 22/04/2021)
     *
     * @param $input
     * @return array|\Illuminate\Http\JsonResponse|mixed
     */
    public function updateConfigGeneral($input)
    {
        try {
            $configId = $input['config_id'];
            if (isset($configId) && $configId != null) {
                // update config
                $this->config->edit([
                    'value' => $input['value']
                ], $configId);
                // update config detail
                if (isset($input['arrConfigDetail']) && $input['arrConfigDetail'] != null) {
                    foreach ($input['arrConfigDetail'] as $key => $value) {
                        $this->configDetail->edit($key, $value);
                    }
                }
                return response()->json(["error" => false, 'message' => 'Cập nhật thành công']);

            } else {
                return [
                    'error' => true,
                    'message' => 'Cập nhật thất bại'
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Cập nhật thất bại'
            ];
        }
    }
}