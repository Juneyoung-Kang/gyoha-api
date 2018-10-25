<?php
    error_reporting(0);
    header("Content-type: application/json; charset=UTF-8"); 

    include "../simple_html_dom.php"; 
    
    $url = "http://www.gyoha.hs.kr/wah/main/bbs/board/list.htm?menuCode=26&scale=5";
    $dom = new DOMDocument;

    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
        $content = curl_exec($ch);
        curl_close($ch); 
    }
    
    $html = $dom->loadHTML($content);
    $dom->preserveWhiteSpace = false;

    function getNoticeTitle($num, $dom){
        $table = $dom->getElementsByTagName('table');
        $tbody = $table->item(1)->getElementsByTagName('tr');
        $tr = $tbody->item($num)->getElementsByTagName('td');
        $td = $tr->item(1)->getElementsByTagName('a');
        if($td->item(0)->nodeValue==NULL){
            return NULL;
        }else{
            return $td->item(0)->nodeValue;
        }
    }
    function getNoticeNumber($num, $dom){
        $table = $dom->getElementsByTagName('table');
        $tbody = $table->item(1)->getElementsByTagName('tr');
        $tr = $tbody->item($num)->getElementsByTagName('td');
        if($tr->item(0)->nodeValue==NULL){
            return '공지';
        }else{
            $final = $tr->item(0)->nodeValue;
            $final = strtr($final,array("\r\n\t"=>'',"\r"=>'',"\n"=>'',"\t"=>''));
            return $final;
        }
    }
    function getNoticeDate($num, $dom){
        $table = $dom->getElementsByTagName('table');
        $tbody = $table->item(1)->getElementsByTagName('tr');
        $tr = $tbody->item($num)->getElementsByTagName('td');
        if($tr->item(3)->nodeValue==NULL){
            return NULL;
        }else{
            return $tr->item(3)->nodeValue;
        }
    }
    function getNoticeLink($num, $dom){
        $table = $dom->getElementsByTagName('table');
        $tbody = $table->item(1)->getElementsByTagName('tr');
        $tr = $tbody->item($num)->getElementsByTagName('td');
        $td = $tr->item(1)->getElementsByTagName('a');
        if($td->item(0)==NULL){
            return NULL;
        }else{
            $final = $td->item(0)->attributes;
            $_GLOBALS['href'] = $final->item(0)->nodeValue;
            return $final->item(0)->nodeValue;  
        }
    }
    $array = array(
        'apiName' => 'gyoha-api',
        'data' => array(
            'result' => array(
                array(
                    'num' => getNoticeNumber(1, $dom),
                    'title' => getNoticeTitle(1, $dom),
                    'date' => getNoticeDate(1, $dom),
                    'link' => getNoticeLink(1, $dom),
                ),
                array(
                   'num' => getNoticeNumber(2, $dom),
                   'title' => getNoticeTitle(2, $dom),
                   'date' => getNoticeDate(2, $dom),
                   'link' => getNoticeLink(2, $dom),
                ),
                array(
                   'num' => getNoticeNumber(3, $dom),
                   'title' => getNoticeTitle(3, $dom),
                   'date' => getNoticeDate(3, $dom),
                   'link' => getNoticeLink(3, $dom),
                ),
                array(
                   'num' => getNoticeNumber(4, $dom),
                   'title' => getNoticeTitle(4, $dom),
                   'date' => getNoticeDate(4, $dom),
                   'link' => getNoticeLink(4, $dom),
                ),
                array(
                   'num' => getNoticeNumber(5, $dom),
                   'title' => getNoticeTitle(5, $dom),
                   'date' => getNoticeDate(5, $dom),
                   'link' => getNoticeLink(5, $dom),
                ),
            )
        )
    );
    $json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES);
    echo $json;
?>