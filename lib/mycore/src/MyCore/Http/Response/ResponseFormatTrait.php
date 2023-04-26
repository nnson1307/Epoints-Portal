<?php
namespace MyCore\Http\Response;

/**
 * Created by PhpStorm.
 * User: phuoc
 * Date: 11/15/2018
 * Time: 9:14 PM
 */

trait ResponseFormatTrait
{
    private $responseData = array(
        'Result' => array(
            'ErrorCode' => 1,
            'ErrorDescription' => 'Chưa thiết lập response.',
            'Data' => array()
        )
    );

    /**
     * Set response data
     *
     * @param int $errorCode
     * @param string $message
     * @param array $data
     */
    protected function responseJson($errorCode, $message = null, $data = null)
    {
        $this->responseData['Result']['Data']             = $errorCode == CODE_SUCCESS ? $data : null;
        $this->responseData['Result']['ErrorCode']        = $errorCode;
        $this->responseData['Result']['ErrorDescription'] = $message ?: 'Xử lý thành công.';

        if ($errorCode != CODE_SUCCESS && $data != null) {
            $this->responseData['Result']['ErrorData'] = $data;
        }

        return response()->json($this->responseData);
    }
}