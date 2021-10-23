<?php
namespace Gglink\Ggtasker\Response;

/**
 * Represents a API error response
 *
 * @package Gglink\Ggtasker\Response
 */
class ErrorResponse {

    /**
     * The http status code
     *
     * @var int
     */
    private $statusCode;

    /**
     * The list of errors
     *
     * @var array[]
     */
    private $errors;

    /**
     * Creates a ErrorResponse instance
     *
     * @param int $statusCode The http status code
     * @param array $errors the errors response body
     */
    public function __construct($statusCode, array $errors) {
        $this->statusCode = $statusCode;
        $this->errors = $errors['errors'];
    }

    /**
     * Get the http status code
     *
     * @return int
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * Get the list of errors
     *
     * @return array[]
     */
    public function getErrors() {
        return $this->errors;
    }

}
