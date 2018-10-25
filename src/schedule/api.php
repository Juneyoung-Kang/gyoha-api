<?php
    error_reporting(0);                                             
    header("Content-type: application/json; charset=UTF-8"); 

    require "../simple_html_dom.php"; 

    $resultType = $_GET['resultType']; 
    $getDate = $_GET['getDate'];

    if($resultType == "this"){                   
        $year = date('Y');
        $month = date('m');                       
    } elseif($resultType == "next"){
        $year = date('Y', strtotime('+1 month', time()));       
        $month = date('m', strtotime('+1 month', time()));    
    } elseif($resultType == "year"){
        $date = NULL;  
        $day = NULL;                                         
    } elseif($resultType == "date"){
        if(!isset($getDate)){
            die('getDate field is undefined');
        } else {
            $year = date('Y', strtotime($getDate));
            $month = date('m', strtotime($getDate));
        }
    } elseif(!isset($resultType)){
        die('resultType field is empty');             
    } else {
        die('resultType field is wrong');          
    }

    $dom = new DOMDocument;

    function scheduleResult($dom, $year, $month){
        $url = "https://stu.goe.go.kr/sts_sci_sf01_001.do?schulCode=J100004922&schulCrseScCode=4&schulKndScCode=04&ay=".$year."&mm=".$month;
        $html = $dom->loadHTMLFile($url);
        $dom->preserveWhiteSpace = false;
        $table = $dom->getElementsByTagName('table');
        $tbody = $table->item(0)->getElementsByTagName('tbody');
        $rows = $tbody->item(0)->getElementsByTagName('tr');
        $g=0;
        for($i=0; $i<5;){
            $row = $rows->item($i)->getElementsByTagName('td');
            for($j=0; $j<7;){
                $xy = $row->item($j)->getElementsByTagName('div');

                $dateNumber = $xy->item(0)->getElementsByTagName('em');

                $eventName = $xy->item(0)->getElementsByTagName('a');
                $finalDateNumber = $dateNumber->item(0)->nodeValue;

                if($finalDateNumber == null){
                } else if($finalDateNumber == " "){
                } else if($finalDateNumber){
                    $finalEventName = $eventName->item(0)->nodeValue;
                    $regex = '/(\s|\\\\[rntv]{1})/';
                    $finalEventName = preg_replace($regex, '', $finalEventName);
                    if(empty($finalEventName)){
                        $finalEventName = NULL;
                    }
                    $finalArray[$g] = array($finalDateNumber, $finalEventName);
                    $g++;
                }
                $j++;
            }
            $i++;
        }
        return $finalArray;
    }

    switch($resultType){
        case "date":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'schedule' => scheduleResult($dom, $year, $month),
                    )
                )
            );
            break;
        case "this":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'schedule' => scheduleResult($dom, $year, $month),
                    )
                )
            );
            break;
        case "next":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'schedule' => scheduleResult($dom, $year, $month),
                    )
                )
            );
            break;
        case "year":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'schedule01' => scheduleResult($dom, $year, '01'),
                        'schedule02' => scheduleResult($dom, $year, '02'),
                        'schedule03' => scheduleResult($dom, $year, '03'),
                        'schedule04' => scheduleResult($dom, $year, '04'),
                        'schedule05' => scheduleResult($dom, $year, '05'),
                        'schedule06' => scheduleResult($dom, $year, '06'),
                        'schedule07' => scheduleResult($dom, $year, '07'),
                        'schedule08' => scheduleResult($dom, $year, '08'),
                        'schedule09' => scheduleResult($dom, $year, '09'),
                        'schedule10' => scheduleResult($dom, $year, '10'),
                        'schedule11' => scheduleResult($dom, $year, '11'),
                        'schedule12' => scheduleResult($dom, $year, '12'),
                    )
                )
            );
            break;
    }

    $json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    echo $json;
?>