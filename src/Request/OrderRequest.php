<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class OrderRequest extends BaseRequest
{

    protected function autoValidateRequest(): bool
    {
        return true;
    }

    /**
     * @Type("array")
     */
    protected $address;

    /**
     * @Type("string")
     */
    protected $code;

    /**
     * @NotBlank()
     */
    protected $shipping_date;

    /**
     * @Type("boolean")
     */
    protected $status;

    /**
     * @Type("array")
     */
    protected $product;
}
