<?php namespace App\Service;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Json;

class ResponseService
{
    private $success;

    private $status;

    private $message;

    private $errorType;

    private $errorCode;

    private function __construct($success, $status)
    {
        $this->success = (boolean) $success;
        $this->status  = $status;
    }

    /**
     * Success ResponseService
     * @param int $status
     * @return static
     */
    public static function success($status = 200)
    {
        return new static(true, $status);
    }

    /**
     * Fail ResponseService
     * @param int $status
     * @return static
     */
    public static function error($status = 404)
    {
        return new static(false, $status);
    }

    /**
     * Warning ResponseService
     *
     * @param int $status
     *
     * @return static
     */
    public static function warning($status = 400)
    {
        return new static(false, $status);
    }

    /**
     * @param $message
     * @return $this
     */
    public function message($message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param $errorType
     * @return $this
     */
    public function errorType($errorType)
    {
        $this->errorType = $errorType;

        return $this;
    }

    /**
     * @param $errorCode
     * @return $this
     */
    public function errorCode($errorCode = 0)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function response($data = null)
    {
        $response = [
            'success'   => $this->success,
            'status'    => $this->status,
        ];

        if (!is_null($this->message)){
            $response['message'] = $this->message;
        }
        if (!is_null($this->errorCode)){
            $response['errorCode'] = $this->errorCode;
        }
        if (!is_null($this->errorType)){
            $response['errorType'] = $this->errorType;
        }
        if (!is_null($this->errorCode)){
            $response['errorCode'] = $this->errorCode;
        }
        $response['data'] = $data;

        return new Response(json_encode($response), $this->status, array(
            'Content-Type' => 'application/json'
        ));

    }
}
