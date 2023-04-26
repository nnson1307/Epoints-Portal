<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/4/2020
 * Time: 2:55 PM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\Rating\RatingRepoInterface;

class RatingController extends Controller
{
    protected $rating;

    public function __construct(
        RatingRepoInterface $rating
    ) {
        $this->rating = $rating;
    }

    /**
     * View danh sách đánh giá
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function index()
    {
        $data = $this->rating->list();

        return view('admin::rating.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'rating_log$is_show' => [
                'data' => [
                    '' => 'Chọn hiển thi',
                    1 => 'Hiển thị',
                    0 => 'Không hiển thị'
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách đánh giá
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search', 'created_at', 'rating_log$is_show']);
        $data = $this->rating->list($filters);

        return view('admin::rating.list', [
            'LIST' => $data['list'],
            'page' => $filters['page']
        ]);
    }

    /**
     * Thay đổi hiển thị đánh giá KH
     *
     * @param Request $request
     * @return mixed
     */
    public function changeShowAction(Request $request)
    {
        return $this->rating->changeShow($request->all());
    }
}