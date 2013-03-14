<?php
class Response
{
    public function renderView($viewPath, $data, $contentType='text/html')
    {
        header('Content-Type: ' . $contentType);
        import('views.' . $viewPath);
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
}
