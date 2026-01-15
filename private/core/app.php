<?php

class App
{
    protected $controller = 'Auth';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $URL = $this->getURL();

        /**
         * CONTROLLER
         */
        if (isset($URL[0]) && file_exists("../private/controllers/" . ucfirst($URL[0]) . ".php")) {
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        }

        require "../private/controllers/{$this->controller}.php";
        $this->controller = new $this->controller();

        /**
         * METHOD
         */
        if (isset($URL[1]) && method_exists($this->controller, $URL[1])) {
            $this->method = $URL[1];
            unset($URL[1]);
        }

        /**
         * PARAMETERS
         */
        $this->params = array_values($URL);

        /**
         * EXECUTE
         */
        call_user_func_array(
            [$this->controller, $this->method],
            $this->params
        );
    }

    private function getURL()
    {
        $url = $_GET['url'] ?? 'Auth/index';

        return explode(
            '/',
            filter_var(trim($url, '/'), FILTER_SANITIZE_URL)
        );
    }
}
