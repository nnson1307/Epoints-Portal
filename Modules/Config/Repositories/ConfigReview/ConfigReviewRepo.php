<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 14:20
 */

namespace Modules\Config\Repositories\ConfigReview;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Config\Models\ConfigReviewContentHintTable;
use Modules\Config\Models\ConfigReviewContentSuggestTable;
use Modules\Config\Models\ConfigReviewTable;
use Modules\Config\Models\ContentSuggestTable;

class ConfigReviewRepo implements ConfigReviewRepoInterface
{
    protected $configReview;

    public function __construct(
        ConfigReviewTable $configReview
    )
    {
        $this->configReview = $configReview;
    }

    const ORDER = "order";

    /**
     * Lấy dữ liệu cấu hình đánh giá đơn hàng
     *
     * @return mixed|void
     */
    public function getDataConfigOrder()
    {
        $mContentSuggest = app()->get(ConfigReviewContentSuggestTable::class);
        $mContentHint = app()->get(ConfigReviewContentHintTable::class);
        $suggest = app()->get(ContentSuggestTable::class);

        //Lấy cấu hình đánh giá
        $getConfig = $this->configReview->getConfigReview(self::ORDER);
        //Lấy cú pháp đánh giá
        $getSuggest = $mContentSuggest->getContentSuggest($getConfig['config_review_id']);
        //Lấy nội dung gợi ý đánh giá
        $getHint = $mContentHint->getContentHint($getConfig['config_review_id']);

        $arrRatingValue = [];

        if ($getConfig['rating_value_google'] != null) {
            $arrRatingValue = explode(",", $getConfig['rating_value_google']);
        }

        //Lấy option cú pháp gợi ý 5 sao
        $suggest5 = $suggest->getOption(5);
        //Lấy option cú pháp gợi ý 4 sao
        $suggest4 = $suggest->getOption(4);
        //Lấy option cú pháp gợi ý 3 sao
        $suggest3 = $suggest->getOption(3);
        //Lấy option cú pháp gợi ý 2 sao
        $suggest2 = $suggest->getOption(2);
        //Lấy option cú pháp gợi ý 1 sao
        $suggest1 = $suggest->getOption(1);

        $arrSuggest1 = [];
        $arrSuggest2 = [];
        $arrSuggest3 = [];
        $arrSuggest4 = [];
        $arrSuggest5 = [];

        if (count($getSuggest) > 0) {
            foreach ($getSuggest as $v) {
                switch ($v['rating_value']) {
                    case 5:
                        $arrSuggest5 [] = $v['content_suggest_id'];
                        break;
                    case 4:
                        $arrSuggest4 [] = $v['content_suggest_id'];
                        break;
                    case 3:
                        $arrSuggest3 [] = $v['content_suggest_id'];
                        break;
                    case 2:
                        $arrSuggest2 [] = $v['content_suggest_id'];
                        break;
                    case 1:
                        $arrSuggest1 [] = $v['content_suggest_id'];
                        break;
                }
            }
        }

        $contentHint1 = null;
        $contentHint2 = null;
        $contentHint3 = null;
        $contentHint4 = null;
        $contentHint5 = null;

        if (count($getHint) > 0) {
            foreach ($getHint as $v) {
                switch ($v['rating_value']) {
                    case 5:
                        $contentHint5 = $v['content_hint'];
                        break;
                    case 4:
                        $contentHint4 = $v['content_hint'];
                        break;
                    case 3:
                        $contentHint3 = $v['content_hint'];
                        break;
                    case 2:
                        $contentHint2 = $v['content_hint'];
                        break;
                    case 1:
                        $contentHint1 = $v['content_hint'];
                        break;
                }
            }
        }


        return [
            'item' => $getConfig,
            'suggest5' => $suggest5,
            'suggest4' => $suggest4,
            'suggest3' => $suggest3,
            'suggest2' => $suggest2,
            'suggest1' => $suggest1,
            'contentHint5' => $contentHint5,
            'contentHint4' => $contentHint4,
            'contentHint3' => $contentHint3,
            'contentHint2' => $contentHint2,
            'contentHint1' => $contentHint1,
            'arrSuggest5' => $arrSuggest5,
            'arrSuggest4' => $arrSuggest4,
            'arrSuggest3' => $arrSuggest3,
            'arrSuggest2' => $arrSuggest2,
            'arrSuggest1' => $arrSuggest1,
            'arrRatingValue' => $arrRatingValue
         ];
    }

    /**
     * Thêm mới cú pháp đánh giá
     *
     * @param $input
     * @return array|mixed
     */
    public function insertContentSuggest($input)
    {
        try {
            $mContentSuggest = app()->get(ContentSuggestTable::class);

            //Insert cú pháp đánh giá
            $idContent = $mContentSuggest->add([
                'content_suggest' => $input['content_suggest'],
                'rating_value' => $input['rating_value']
            ]);

            return response()->json([
                'error' => false,
                'id_content' => $idContent,
                'message' => __('Thêm mới thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm mới thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Cập nhật cấu hình đánh giá đơn hàng
     *
     * @param $input
     * @return mixed|void
     */
    public function updateConfigOrder($input)
    {
        DB::beginTransaction();
        try {
            $mContentSuggest = app()->get(ConfigReviewContentSuggestTable::class);
            $mContentHint = app()->get(ConfigReviewContentHintTable::class);

            if (isset($input['max_length_content']) && intval($input['max_length_content']) < 250) {
                return response()->json([
                    'error' => true,
                    'message' => __('Giới hạn ký tự tối thiểu 250 ký tự'),
                ]);
            }

            $ratingValueGoogle = null;

            if (count($input['rating_value_google']) > 0) {
                $ratingValueGoogle = implode(",", $input['rating_value_google']);
            }

            //Update cấu hình đánh giá đơn hàng
            $this->configReview->edit([
                'expired_review' => $input['expired_review'] * 30,
                'max_length_content' => $input['max_length_content'],
                'is_review_image' => $input['is_review_image'],
                'limit_number_image' => $input['limit_number_image'],
                'limit_capacity_image' => $input['limit_capacity_image'],
                'is_review_video' => $input['is_review_video'],
                'limit_number_video' => $input['limit_number_video'],
                'limit_capacity_video' => $input['limit_capacity_video'],
                'is_suggest' => $input['is_suggest'],
                'is_review_google' => $input['is_review_google'],
                'rating_value_google' => $ratingValueGoogle
            ], $input['config_review_id']);

            $arrSuggest = [];
            $arrHint = [];

            for ($i = 1; $i <= 5; $i++) {
                if (isset($input["content_suggest_$i"]) && count($input["content_suggest_$i"]) > 0) {
                    foreach ($input["content_suggest_$i"] as $v) {
                        $arrSuggest [] = [
                            'config_review_id' => $input['config_review_id'],
                            'content_suggest_id' => $v,
                            'rating_value' => $i,
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }

                if (isset($input["content_hint_$i"]) && $input["content_hint_$i"] != null) {
                    $arrHint [] = [
                        'config_review_id' => $input['config_review_id'],
                        'rating_value' => $i,
                        'content_hint' => $input["content_hint_$i"],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Xoá cú pháp gợi ý
            $mContentSuggest->removeSuggestByReviewId($input['config_review_id']);
            //Insert cú pháp gợi ý
            $mContentSuggest->insert($arrSuggest);
            //Xoá nội dung gợi ý đánh giá
            $mContentHint->removeHintByReviewId($input['config_review_id']);
            //Insert nội dung gợi ý đánh giá
            $mContentHint->insert($arrHint);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Cập nhật thành công'),
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => true,
                'message' => __('Cập nhật thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }
}