<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/28/2020
 * Time: 4:42 PM
 */

namespace Modules\Admin\Repositories\News;


use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\NewTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ServiceTable;

class NewRepo implements NewRepoInterface
{
    protected $new;

    public function __construct(
        NewTable $new
    ) {
        $this->new = $new;
    }

    /**
     * Danh sách bài viết
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->new->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Data view thêm bài viết
     *
     * @return array|mixed
     */
    public function dateViewCreate()
    {
        $mService = new ServiceTable();
        $mProductChild = new ProductChildTable();

        $optionProduct = $mProductChild->getProductChildOption();
        $optionService = $mService->getServiceOption();

        return [
            'optionProduct' => $optionProduct,
            'optionService' => $optionService
        ];
    }

    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function uploadAction($input)
    {
        $file = $this->uploadImageTemp($input['file']);
        return response()->json(["file" => $file, "success" => "1"]);
    }

    /**
     * Lưu image vào folder temp
     *
     * @param $file
     * @return string
     */
    private function uploadImageTemp($file)
    {
        $time = Carbon::now();
        $file_name = rand(0, 9) . time() . date_format($time, 'd') . date_format($time, 'm') . date_format($time, 'Y') . "_new." . $file->getClientOriginalExtension();
        Storage::disk('public')->put(TEMP_PATH. "/" .$file_name, file_get_contents($file));
        return $file_name;

    }

    /**
     * Move image vào folder voucher
     *
     * @param $filename
     * @return string
     */
    private function transferTempfileToAdminfile($filename)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = NEW_PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory(NEW_PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    /**
     * Thêm bài viết
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        try {
            if (isset($input['product'])) {
                $value = implode(",", $input['product']);
                $input["product"] = $value;
            }

            if (isset($input['service'])) {
                $value = implode(",", $input['service']);
                $input["service"] = $value;
            }

            $input['created_by'] = Auth()->id();
            $input['updated_by'] = Auth()->id();

            $this->new->add($input);

            return response()->json([
                'error' => false,
                'message' => __('Thêm bài viết thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm bài viết thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }

    }

    /**
     * Dữ liệu view chỉnh sửa bài viết
     *
     * @param $newId
     * @return array|mixed
     */
    public function dataViewEdit($newId)
    {
        $mService = new ServiceTable();
        $mProductChild = new ProductChildTable();

        $optionProduct = $mProductChild->getProductChildOption();
        $optionService = $mService->getServiceOption();

        //Thông tin bài viết
        $info = $this->new->getItem($newId);

        return [
            'optionProduct' => $optionProduct,
            'optionService' => $optionService,
            'item' => $info
        ];
    }

    /**
     * Chỉnh sửa bài viết
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            if (isset($input['product'])) {
                $value = implode(",", $input['product']);
                $input["product"] = $value;
            }

            if (isset($input['service'])) {
                $value = implode(",", $input['service']);
                $input["service"] = $value;
            }

            if ($input['image'] == null) {
                $input['image'] = $input['image_old'];
            }

            unset($input['image_old']);

            $input['updated_by'] = Auth()->id();

            $this->new->edit($input, $input['new_id']);

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa bài viết thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa bài viết thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Thay đổi trạng thái
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function changeStatus($input)
    {
        try {
            $this->new->edit($input, $input['new_id']);

            return response()->json([
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ]);
        }
    }

    /**
     * Xóa bài viết
     *
     * @param $newId
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function remove($newId)
    {
        try {
            $this->new->edit([
                'is_deleted' => 1
            ], $newId);

            return response()->json([
                'error' => false,
                'message' => __('Xóa thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Xóa thất bại')
            ]);
        }
    }
}