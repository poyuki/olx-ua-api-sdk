<?php

namespace Parhomenko\Olx\Exceptions;


use Exception;
use Throwable;

/**
 * Class BaseOlxException
 *
 * @package Parhomenko\Olx\Exceptions
 */
abstract class BaseOlxException extends Exception
{
    /**
     * @var string|null
     */
    protected $detail;
    /**
     * @var string|null
     */
    protected $title;

    /**
     * BaseOlxException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string|null $title
     * @param string|null $detail
     */
    public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null,
        string $title = null,
        string $detail = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->title = $title;
        $this->detail = $detail;
    }

    /**
     * @return string|null
     */
    public function getDetail(): ?string
    {
        return $this->detail;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
}