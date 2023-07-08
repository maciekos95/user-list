<?php

namespace Framework\Classes;

use Framework\Enums\HttpStatusCode;

class Response
{
    /**
     * @var mixed The response content.
     */
    protected $content;

    /**
     * @var HttpStatusCode The HTTP status code.
     */
    protected $status;

    /**
     * @var array The response headers.
     */
    protected $headers;
    
    /**
     * The Response constructor.
     *
     * @param mixed $content The response content.
     * @param HttpStatusCode $status The HTTP status code.
     * @param array $headers The response headers.
     */
    protected function __construct($content, HttpStatusCode $status, array $headers)
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    /**
     * Create a new Response instance.
     *
     * @param mixed $content The response content.
     * @param HttpStatusCode $status The HTTP status code.
     * @param array $headers The response headers.
     * @return static The created instance.
     */
    public static function make($content = '', HttpStatusCode $status = HttpStatusCode::OK, array $headers = []): static
    {
        return new static($content, $status, $headers);
    }

    /**
     * Set the response content.
     *
     * @param mixed $content The response content.
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * Set the HTTP status code of the response.
     *
     * @param int $status The HTTP status code.
     */
    public function setStatusCode(HttpStatusCode $status): void
    {
        $this->status = $status;
    }

    /**
     * Set a header in the response.
     *
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     */
    public function setHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Redirect the response to the specified URL.
     *
     * @param string $url The URL to redirect to.
     */
    public function redirect(string $url): void
    {
        $this->setHeader('Location', $url);
        $this->setStatusCode(HttpStatusCode::FOUND);
    }

    /**
     * Redirect the response to the home page.
     */
    public function redirectToHomePage(): void
    {
        $this->redirect('/');
    }

    /**
     * Redirect the response to the previous page.
     */
    public function redirectToPreviousPage(): void
    {
        $previousPage = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH) ?? null;

        if (!$previousPage) {
            $this->setStatusCode(HttpStatusCode::BAD_REQUEST);
            $this->setContent(Lang::get('framework::response.previous_page_not_found'));
            return;
        }

        $this->redirect($previousPage);
    }

    /**
     * Set the response status code to 404 Not Found and set the content respectively if empty.
     */
    public function notFound(): void
    {
        $this->setStatusCode(HttpStatusCode::NOT_FOUND);

        if (empty($this->content)) {
            $this->setContent(Lang::get('framework::response.page_not_found'));
        }
    }

    /**
     * Send the response.
     */
    public function send(): void
    {
        http_response_code($this->status->value);
        
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        echo $this->content;
    }
}
