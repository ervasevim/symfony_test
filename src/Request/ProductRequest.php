<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

use Symfony\Component\Validator\Mapping\ClassMetadata;

class ProductRequest extends BaseRequest
{

    protected function autoValidateRequest(): bool
    {
        return true;
    }

    /**
     * @Type("string")
     */
    protected $name;

    /**
     * @Type("string")
     */
    protected $code;

    /**
     * @Type("float")
     */
    protected $price;

    /**
     * @Type("boolean")
     */
    protected $status;

    /**
     * @Type("integer")
     */
    protected $stock;
}
