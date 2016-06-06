<?php
error_reporting(E_ALL);
ini_set('max_execution_time', 10000000000);
function sendRequest( $url, $timeout = 10000000 ){
    $url = str_replace( "&amp;", "&", urldecode(trim($url)) );
    $cookie = tempnam ("/tmp", "CURLCOOKIE");
    echo "<br/><br/>".$cookie."<br/><br/>";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: lucky9=5481577; __sonar=13855659210299537985; _we_wk_ss_lsf_=true; JSESSIONID=BB90D6B25D323BCE33D8B0FBCF0E6DD0; ns1=BAQAAAU++2fF7AAaAANgATFfcvcpjNzJ8NjAxXjE0NDI1MTM2NDA1NDZeXjFeM3wyfDV8NHw3fDExXjFeMl40XjNeMTJeMTJeMl4xXjFeMF4xXjBeMV42NDQyNDU5MDc1t+W1ahpFyFArMWrE6WogWrtq9TU*; s=CgAD4ACBV/NvKZGU5MjJlZGExNGYwYTVlNWU4ZjEyOGM4ZmZmZWZmMTYA7gBmVfzbyjMGaHR0cDovL3d3dy5lYmF5LmluL3NjaC9Nb2JpbGUtUGhvbmVzLS8xNTAzMi9pLmh0bWw/X2Zyb209UjQwJl9ua3c9bW9iaWxlK3Bob25lcyZfcGduPTImX3NrYz01MCZydD1uY6qYIJY*; nonsession=CgAAIAB5WIxdKMTQ0MjQzMDIxM3gxOTE2ODIwOTYwMzd4MjAzeDJOAMsAAVX7kVI0AMoAIF9hi8o5Yjk5ZjBhOTE0ZjBhMmFmZTJlNDMxMzRmZmY3YWQxYcZMLxw*; npii=btguid/9b99f0a914f0a2afe2e43134fff7ad1a57dcbdd1^cguid/7a6a7c1314f0a62a02c270c2fe8a6d3c57dcbdd1^; nDvsts=3%7C1442634590842; _we_wk_ss_lsf_=true; ds2=ssts/1442548553899^; dp1=btzo/-14a59bdf249^idm/155fcdb51^u1p/QEBfX0BAX19AQA**57dcbdca^bl/IN59bdf14a^pbf/#2800008000e0006081000200000059bdf23c^; ebay=%5Esbf%3D%2340400000000120000100100%5Ejs%3D1%5Epsi%3DAkyp%2FiN0*%5E"));
    curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_ENCODING, "" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
    curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
    $content = curl_exec($ch);
    //echo "--".$content;
    //echo 'Curl error: ' . curl_error($ch);
    $response = curl_getinfo($ch);
    //print_r($response);
    curl_close ($ch);
    if ($response['http_code'] == 301 || $response['http_code'] == 302){
        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
        $headers = get_headers($response['url']);
        $location = "";
        foreach( $headers as $value ){
            if ( substr( strtolower($value), 0, 9 ) == "location:" )
                return sendRequest( trim( substr( $value, 9, strlen($value) ) ) );
        }
    }
    if (preg_match("/window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/window\.location\=\"(.*)\"/i", $content, $value)){
        return sendRequest($value[1]);
    }else{
        return $content;
    }
}
function getResponseEbay($url){
	$ch = curl_init();
	
	$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER         => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,
	);
	curl_setopt_array( $ch, $options );
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	
	curl_close($ch);
	return $response;
}
?>
