<?php
class Request
{
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGET()
    {
        return $this->getMethod() == 'GET';
    }

    public function isPOST()
    {
        return $this->getMethod() == 'POST';
    }

    public function getParam($key)
    {
        return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;
    }

    public function getParams()
    {
        return $_REQUEST;
    }
}
