<?php

namespace Parhomenko\Olx\Exceptions;


use Throwable;

/**
 * Class ValidationException
 *
 * @package Parhomenko\Olx\Exceptions
 */
class ValidationException extends BaseOlxException
{
    /**
     * @var array
     */
    protected $validation;

    /**
     * ValidationException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string|null $title
     * @param string|null $detail
     * @param array $validation
     */
    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null,
        string $title = null,
        string $detail = null,
        array $validation = []
    ) {
        parent::__construct($message, $code, $previous);
        $this->validation = $validation;
        $this->title = $title;
        $this->detail = $detail;
    }

    /**
     * @return array
     */
    public function getValidation(): array
    {
        return $this->validation;
    }

}