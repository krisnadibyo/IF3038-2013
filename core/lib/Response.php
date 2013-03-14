<?php
class Response
{
    public function renderView($viewPath, $data=array(), $contentType='text/html')
    {
        if (!$data) {
            $data = array();
        }

        foreach($data as $key => $val) {
            ${$key} = $val;
        }

        header('Content-Type: ' . $contentType);
        require_once dirname(__FILE__) . '/../../views/' . preg_replace('/\./', '/', $viewPath) . '.php';
    }

    public function setHeader($header)
    {
        header($header);
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
    }

    public function renderJson($data, $arrayOfModelObject=false)
    {
        if ($arrayOfModelObject && $data != null) {
            $array = array();
            foreach($data as $obj) {
                $array[] = $obj->toArray();
            }

            $data = $array;
        }

        if ($data instanceof Model) {
            $data = $data->toArray();
        }        

        $json = json_encode($data);
        header('Content-Type: application/json');
        echo $json;
    }

    public function write($str)
    {
        echo $str;
    }    
}
