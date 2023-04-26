<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/4/2020
 * Time: 2:59 PM
 */

namespace Modules\Admin\Repositories\Rating;


use Modules\Admin\Models\RatingLogTable;

class RatingRepo implements RatingRepoInterface
{
    protected $rating;

    public function __construct(
        RatingLogTable $rating
    ) {
        $this->rating = $rating;
    }

    /**
     * Danh sách đánh giá
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->rating->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Thay đổi hiển thị đánh giá KH
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function changeShow($input)
    {
        try {
            //Cập nhật hiển thị đánh giá KH
            $this->rating->edit($input, $input['id']);

            return response()->json([
                'error' => false,
                'message' => __('Thay đổi hiển thị thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thay đổi hiển thị thất bại')
            ]);
        }
    }
}