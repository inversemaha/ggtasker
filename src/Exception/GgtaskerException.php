<?php
namespace Gglink\Ggtasker\Exception;

/**
 * The base exception for Ggtasker SDK errors
 *
 * @package Gglink\Ggtasker\Exception
 */
class GgtaskerException extends \Exception {

    /**
     * Creates a instance of the GgtaskerException
     *
     * @param string $message The error message
     * @param int|null $code The error code
     * @param \Exception|null $previous The original exception
     */
    public function __construct($message, $code = null, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}

