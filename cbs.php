<?php
echo 'CBSK CallBackUrl';
function request()
{
    return  json_decode(file_get_contents('php://input'));
    
    
}
    $name = 'cbs_data';
    $response = request();
        $fp = fopen( $name . '.json', 'w');
		fwrite($fp, json_encode($response));
		fclose($fp);
		
		
		
		