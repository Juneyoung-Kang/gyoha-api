<?php
    error_reporting(0);
    header("Content-type: application/json; charset=UTF-8"); 

    include "../simple_html_dom.php"; 

    $number = $_GET['number'];
    
    $url = "http://juneyoung.kr/api/gyoha-api/src/notice/api.php";
    
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
        $content = curl_exec($ch);
        curl_close($ch); 
    }

    $content = json_decode($content, true);
    $url4contents = $content['data']['result'][$number][3];

    function getNoticeContents($url){
        include('../Snoopy.class.php');
        $snoopy = new Snoopy;

        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
            $content = curl_exec($ch);
            curl_close($ch); 
        }

        $snoopy->fetch($url);
        preg_match('/<div class="Board_Cont_Cont">(.*?)<\/div>/is', $snoopy->results, $text);
        // return preg_replace('/\s\s/', '', html_entity_decode(strip_tags($text[1])));
        return html_entity_decode(strip_tags($text[1]));
    }

    echo getNoticeContents($url4contents);
    
?>