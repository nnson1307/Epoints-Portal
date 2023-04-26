<?php
namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Admin\Libs\Calendar\CalendarPromoFactory;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\PromotionDateTimeTable;
use Modules\Admin\Models\PromotionDetailTable;
use Modules\Admin\Models\ServiceBranchPriceTable;
use Modules\Admin\Repositories\Calendar\CalendarRepoInterface;

class CalendarController extends Controller
{
    protected $calendar;

    public function __construct(
        CalendarRepoInterface $calendar
    ) {
        $this->calendar = $calendar;
    }

    /**
     * Đặt lịch dịch vụ
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function bookingServiceAction(Request $request, ServiceBranchPriceTable $mServicePrice, PromotionDetailTable $mPromoDetail)
    {

        // Tạo list ngày
        $searchKeyword = trim(strip_tags($request->get('s')));
        $statusBooking = trim(strip_tags($request->get('status')));
        $dateBooking = trim(strip_tags($request->get('date_filter')));
        //Filter tên dv thì ko filter ngày
        if (isset($searchKeyword) && $searchKeyword != null) {
            $statusBooking = null;
            $dateBooking = null;
        }

        $fromDate = $request->get('from_date', Carbon::now()->format('Y-m-d')); // nhận từ param url
        $fromDate = Carbon::createFromFormat('Y-m-d', $fromDate);
        $fromDate = $fromDate->startOfWeek(); // Lấy ngày thứ 2 của tuần chọn

        $arrDates = [];
        if ($dateBooking != null && $dateBooking != "") {
            $arrDates[] = Carbon::createFromFormat('d/m/Y', $dateBooking)->format('Y-m-d');
        } else {
            // Tạo mãng list 14 ngày
            for ($i = 0; $i < 14; $i++) {
                $arrDates[] = $fromDate->format('Y-m-d');
                $fromDate->addDay();
            }
        }

        // Lấy danh sách dịch vụ ra
        $idBranch = $this->getUserBranch();
        $services = $mServicePrice->getServicesPrice($idBranch, $searchKeyword);

        $arrSrvId = [];

        // Tạo ma trận giá
        foreach ($services as $item) {
            $servicePrices = [];
            $arrSrvId[] = $item->service_id;

            foreach ($arrDates as $date) {
                $servicePrices[$date] = [
                    'price' => $item->new_price
                ];
            }

            $item['price_list'] = $servicePrices;
        }

        // Lấy ra danh sách các chương trình khuyến mãi
        $viewFrom = $arrDates[0];
        $viewTo = end($arrDates);
        $arrPromoPrice = $this->getPromoPrice($arrSrvId, $viewFrom, $viewTo);

        // Danh sach book dich vu
        $arrBookingService = $this->getBookingService($arrSrvId, $arrDates, $searchKeyword);
        // filter dịch vụ đã đặt hay chưa được đặt
        $arrSrvIdBooked = array_keys($arrBookingService); // mảng những dịch vụ đã được đặt
        if (isset($statusBooking) && $statusBooking == 'empty') {
            // unset những dịch vụ đã được book
            foreach ($services as $key => $item) {
                if (in_array($item['service_id'], $arrSrvIdBooked)) {
                    unset($services[$key]);
                }
            }
        } else if ($statusBooking == 'booked') {
            // unset những dịch vụ chưa được book
            foreach ($services as $key => $item) {
                if (!in_array($item['service_id'], $arrSrvIdBooked)) {
                    unset($services[$key]);
                }
            }
        }
        // Tính giá của khuyến mãi cho từng chương trình
        foreach ($services as &$service)
        {
            // Điền booking date vô cho service
            $service['booking_date'] = $arrBookingService[$service->service_id] ?? [];

            // Không có chương trình khuyến mãi thì bỏ qua
            if (! isset($arrPromoPrice[$service->service_id])) {
                continue;
            }

            $priceList = $service['price_list'];

            // Tính giá khuyến mãi cho từng ngày
            foreach ($priceList as $date => $priceItem) {
                $this->calcSalePrice($priceItem, $arrPromoPrice[$service->service_id], $date);

                $priceList[$date] = $priceItem;
            }

            $service['price_list'] = $priceList;
        }

        $prev = Carbon::createFromFormat('Y-m-d', $viewFrom)->addDays(-8)->format('Y-m-d');
        $next = Carbon::createFromFormat('Y-m-d', $viewTo)->addDay()->format('Y-m-d');

        //Lấy cấu hình đặt lịch từ ngày đến ngày
        $mConfig = app()->get(ConfigTable::class);
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy số tuần trong năm
        $numberWeek = 52;

//        dd($services);
        return view('admin::calendar.booking-service', [
            'SEARCH' => $searchKeyword,
            'HEADER' => $arrDates,
            'SERVICES' => $services,
            'DATE_BOOKING' => $dateBooking,
            'STATUS_BOOKING' => $statusBooking,
            'NEXT_LINK' => http_build_query(['from_date' => $next, 's' => $searchKeyword]),
            'PREV_LINK' => http_build_query(['from_date' => $prev, 's' => $searchKeyword]),
            'configToDate' => $configToDate,
            'numberWeek' => $numberWeek
        ]);
    }


    /**
     * Tính toán giá của chương trình khuyến mãi
     *
     * @param $priceItem
     * @param $promoPrice
     * @param $curDate
     */
    protected function calcSalePrice(&$priceItem, $promoPrice, $curDate)
    {
        $idPromo   = null;
        $priceBase = $priceItem['price'];
        $curDate   = Carbon::createFromFormat('Y-m-d', $curDate);
        $curDate->setTime(0,0,0);

        foreach ($promoPrice as $item)
        {
            $start = $item->start_date;
            $end = $item->end_date;

            // Kiểm tra ngày hiện tại có nằm trong vùng của chương trình km không
            if ($start->diffInDays($curDate, false) < 0 || $curDate->diffInDays($end, false) < 0) {
                continue;
            }

            // Kiểm tra xem cấu hình loại thời gian có khớp với ngày hiện tại không
            $processor = CalendarPromoFactory::getProcessor($item->time_type);
            if (! $processor->inPromotionDate($curDate, $item)) {
                continue;
            }

            if ($item->promotion_price < $priceBase) {
                $priceBase = $item->promotion_price;
                $idPromo = $item->promotion_id;
            }
        }

        $priceItem['price'] = $priceBase;
        $priceItem['promotion_id'] = $idPromo;
    }

    /**
     * Lấy giá khuyến mãi của dịch vụ. Kết quả group theo dang Service_ID => [list price]
     *
     * @param $arrSrvId
     * @param $minDate
     * @param $maxDate
     * @return array
     */
    protected function getPromoPrice($arrSrvId, $minDate, $maxDate)
    {
        // Lấy danh sách khuyến mãi
        $mPromoDetail = app()->get(PromotionDetailTable::class);
        $oPromoPrice = $mPromoDetail->getServicesPromotionPrice($arrSrvId, $minDate, $maxDate);

        // Lấy ra cấu hình khuyến mãi loại date_time
        $promoDateTimeCf = $this->getPromoDateTimeConfig($oPromoPrice);

        $arrPromoPrice = [];
        foreach ($oPromoPrice as $item)
        {
            $item->start_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->start_date);
            $item->start_date->setTime(0,0,0);
            $item->end_date = Carbon::createFromFormat('Y-m-d H:i:s', $item->end_date);
            $item->end_date->setTime(0,0,0);
            $item->cf_date_time = $promoDateTimeCf[$item->promotion_id] ?? [];

            $arrPromoPrice[ $item->service_id ][] = $item;
        }

        return $arrPromoPrice;
    }


    /**
     * Lấy thông tin chi nhánh của user đang login
     *
     * @return mixed
     */
    protected function getUserBranch()
    {
        return auth()->user()->branch_id;
    }

    /**
     * Lấy lịch booking cho dịch vụ theo từng ngày
     *
     * @param $arrSrvId
     * @param $arrDates
     * @param $searchKeyword
     * @return array
     */
    protected function getBookingService($arrSrvId, $arrDates, $searchKeyword = null)
    {
        $viewFrom = $arrDates[0];
        $viewTo = end($arrDates);

        // Lấy lịch booking từ ngày đến ngày
        $mAppoment = app()->get(CustomerAppointmentTable::class);
        $listBooked = $mAppoment->bookingCalendar($arrSrvId, $viewFrom, $viewTo, $searchKeyword);

        $data = [];

        // Tính toán thời gian booking của service theo từng ngày
        foreach ($arrDates as $date) {
            $curPointDate = Carbon::createFromFormat('Y-m-d', $date);

            foreach ($listBooked as $item) {
                if (empty($item->end_date) || empty($item->date) ) {
                    continue;
                }

                $start = Carbon::createFromFormat('Y-m-d', $item->date);
                $end   = Carbon::createFromFormat('Y-m-d', $item->end_date);

                // Kiểm tra ngày vét có trùng ngày đầu tiên đặt không
                if (! $start->equalTo($curPointDate) && !$end->equalTo($curPointDate))
                {
                    if ( !($start->diffInDays($curPointDate, false) >= 0 && $curPointDate->diffInDays($end, false) >=0) ) {
                        continue;
                    }

                    // đã booking full ngày
                    $data[$date][$item->service_id]['parts'] = [
                        [
                            'customer_appointment_id' => $item->customer_appointment_id,
                            'start_time' => '00:00:00',
                            'end_time' => '23:59:59',
                            'status' => $item->status
                        ]
                    ];
                    $data[$date][$item->service_id]['is_full'] = 1;
                }
                else
                {
                    // Booking chưa full ngày
                    if (isset($data[$date][$item->service_id]['is_full']) && $data[$date][$item->service_id]['is_full']) {
                        continue;
                    }

                    $data[$date][$item->service_id]['parts'][] = [
                        'customer_appointment_id' => $item->customer_appointment_id,
                        'start_time' => $start->equalTo($curPointDate) ? $item->time : '00:00:00',
                        'end_time' => $end->equalTo($curPointDate) ? $item->end_time : '23:59:59',
                        'status' => $item->status
                    ];
                }
            }
        }

        // Chuyển cấu trúc booking sang dạng service => ngày
        $bookingServiceRs = [];

        foreach ($data as $date => $serviceChilds)
        {
            foreach ($serviceChilds as $serviceId => $item)
            {
                $bookingServiceRs[ $serviceId ][$date] = $item;
            }
        }

        return $bookingServiceRs;
    }

    /**
     * Show popup thêm lịch hẹn
     *
     * @param Request $request
     * @return mixed
     */
    public function showModalAddAction(Request $request)
    {
        return $this->calendar->showModalAdd($request->all());
    }


    /**
     * Lấy cấu hình của promotion loại R
     *
     * @param $oPromoPrice Kết quả của PromotionDetailTable@getServicesPromotionPrice
     * @return array
     */
    protected function getPromoDateTimeConfig($oPromoPrice)
    {
        // Lấy ra danh sách khuyến mãi loại date_time
        $dateTimePromoId = [];
        foreach ($oPromoPrice as $item) {
            if ($item->time_type == PromotionDetailTable::TIME_TYPE_DATE_TIME) {
                $dateTimePromoId[] = $item->promotion_id;
            }
        }

        if (empty($dateTimePromoId)) {
            return [];
        }

        // Loại bỏ trùng
        $dateTimePromoId = array_unique($dateTimePromoId);

        // Lấy thông tin cấu hình
        $mDateTimeCf = app()->get(PromotionDateTimeTable::class);
        $configs = $mDateTimeCf->getPromotionsConfig($dateTimePromoId);

        // Gôm nhóm
        $dateTimePromoId = [];
        foreach ($configs as $item) {
            $dateTimePromoId[ $item->promotion_id ][] = $item->toArray();
        }

        return $dateTimePromoId;
    }

    /**
     * Show popup chi tiết dịch vụ
     *
     * @param Request $request
     * @return mixed
     */
    public function showModalDetailAction(Request $request)
    {
        return $this->calendar->showModalDetail($request->all());
    }
}