<?php

namespace Core\Http;

class Response extends Message
{
    /**
     * Response constructor.
     */
    public function __construct()
    {
        $this->setHttpVersion($_SERVER['SERVER_PROTOCOL']);
    }

    /**
     * @param $contextType
     * @return $this
     */
    public function setContextType($contextType)
    {
        $this->setHeader("Content-Type", $contextType);
        return $this;
    }

    /**
     * Send response headers
     */
    protected function sendHeaders(): void
    {
        http_response_code($this->getCode());
        foreach ($this->getHeaders() as $header_name => $header_value) {
            header("$header_name: $header_value");
        }
    }

    /**
     * Send html response
     * @param string $viewTemplate
     * @param array $data
     */
    public function sendHtml(string $viewTemplate, array $data = [])
    {
        $this->setContextType("text/html; charset=UTF-8");
        $this->sendHeaders();
        (new View($viewTemplate, $data))->display();
    }

    /**
     * Send json response
     * @param array $data
     */
    public function sendJSON(array $data = [])
    {
        $this->setContextType("application/json; charset=UTF-8");
        $this->sendHeaders();
        echo json_encode($data);
    }
}
