<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 4:43 PM
 */

namespace Modules\Booking\Repositories\Loyalty;


interface LoyaltyRepositoryInterface
{
    public function scoreCalculation(array $data = []);

    public function plusPointEvent(array $data = []);

    /**
     * Cộng điểm khi thanh toán chưa đủ tiền
     *
     * @param $input
     * @return mixed
     */
    public function plusPointReceiptAction($input);

    /**
     * Cộng điểm khi thanh toán đủ tiền
     *
     * @param $input
     * @return mixed
     */
    public function plusPointReceiptFullAction($input);
}