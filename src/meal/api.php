<?php
    // done!
    // For gyoha high school application project
    // Juneyoung Kang 20181017
    // how to use? http://juneyoung.kr/api/gyoha-api/src/api/meal/api.php?resultType=date&mealDate=2018-10-19
    
    error_reporting(0);                                             
    header("Content-type: application/json; charset=UTF-8"); 

    require "../simple_html_dom.php"; 

    $resultType = $_GET['resultType'];
    $mealDate = $_GET['mealDate'];

    if($resultType == "today"){                   
        $date = date('Y.m.d');  
        $day = date('w');                       
    } elseif($resultType == "tomorrow"){
        $date = date('Y.m.d', strtotime('24 hours', time()));       
        $day = date('w', strtotime('24 hours', time()));    
    } elseif($resultType == "week"){
        $date = NULL;  
        $day = NULL;                                              
    } elseif($resultType == "date"){
        if(isset($mealDate)){
            $date = str_replace('-', '.', $mealDate);
            $day = date('w', strtotime($mealDate));
        } else {
            die('mealDate field is empty');
        }
    } elseif(!isset($resultType)){
        die('resultType field is empty');             
    } else {
        die('resultType field is wrong');          
    }

    $dom = new DOMDocument;

    function mealResult($dom, $date, $day){
        $url = "http://stu.goe.go.kr/sts_sci_md01_001.do?schulCode=J100004922&insttNm=교하고등학교&schulCrseScCode=4&schMmealScCode=2&schYmd=".$date;
        $html = $dom->loadHTMLFile($url);
        $dom->preserveWhiteSpace = false;
        $table = $dom->getElementsByTagName('table');
        $tbody = $table->item(0)->getElementsByTagName('tbody');
        $rows = $tbody->item(0)->getElementsByTagName('tr');
        $cols = $rows->item(1)->getElementsByTagName('td');
        if($cols->item($day)->nodeValue == null){
            return NULL;
        } elseif($cols->item($day)->nodeValue == " "){
            return '급식이 없습니다.';
        } else{
            $final = $cols->item($day)->nodeValue;
            $final = preg_replace("/[0-9]/", "", $final);
            $final = str_replace(".", "", $final);
            $menu = explode('(교하)', $final);
            $last_array = end($menu);
            if(empty($last_array)){
                array_pop($menu);
            }
            return $menu;
        }
    }             
    
    function dayKorean($day){                                    
        switch($day){
            case '0': $day_kr = "일요일"; break;
            case '1': $day_kr = "월요일"; break;
            case '2': $day_kr = "화요일"; break;
            case '3': $day_kr = "수요일"; break;
            case '4': $day_kr = "목요일"; break;
            case '5': $day_kr = "금요일"; break;
            case '6': $day_kr = "토요일"; break;
        }
        return $day_kr;
    }

    switch($resultType){
        case "date":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'date' => $date,
                        'day' => dayKorean($day),
                        'meal' => mealResult($dom, $date, $day),
                    )
                )
            );
            break;
        case "today":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'date' => date('Y.m.d'),
                        'day' => dayKorean(date('w')),
                        'meal' => mealResult($dom, $date, $day),
                    )
                )
            );
            break;
        case "tomorrow":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'date' => date('Y.m.d', strtotime('24 hours', time())),
                        'day' => dayKorean(date('w', strtotime('24 hours', time()))),
                        'meal' => mealResult($dom, $date, $day),
                    )
                )
            );
            break;
        case "week":
            $day = date('w');
            if($day==0){$time_init='+24 hours';$time_2='+48 hours';$time_3='+72 hours';$time_4='+96 hours';$time_5='+120 hours';}
            elseif($day==1){$time_init='+0 hours';$time_2='+24 hours';$time_3='+48 hours';$time_4='+72 hours';$time_5='+96 hours';}
            elseif($day==2){$time_init='-24 hours';$time_2='+0 hours';$time_3='+24 hours';$time_4='+48 hours';$time_5='+72 hours';}
            elseif($day==3){$time_init='-48 hours';$time_2='-24 hours';$time_3='+0 hours';$time_4='+24 hours';$time_5='+48 hours';}
            elseif($day==4){$time_init='-72 hours';$time_2='-48 hours';$time_3='-24 hours';$time_4='+0 hours';$time_5='+24 hours';}
            elseif($day==5){$time_init='-96 hours';$time_2='-72 hours';$time_3='-48 hours';$time_4='-24 hours';$time_5='+0 hours';}
            elseif($day==6){$time_init='-120 hours';$time_2='-96 hours';$time_3='-72 hours';$time_4='-48 hours';$time_5='-24 hours';}
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        0 => array(
                            'date' => date('Y.m.d', strtotime($time_init, time())),
                            'day' => dayKorean(date('w', strtotime($time_init, time()))),
                            'meal' => mealResult($dom, date('Y.m.d', strtotime($time_init, time())), date('w', strtotime($time_init, time()))),
                        ),
                        1 => array(
                            'date' => date('Y.m.d', strtotime($time_2, time())),
                            'day' => dayKorean(date('w', strtotime($time_2, time()))),
                            'meal' => mealResult($dom, date('Y.m.d', strtotime($time_2, time())), date('w', strtotime($time_2, time()))),
                        ),
                        2 => array(
                            'date' => date('Y.m.d', strtotime($time_3, time())),
                            'day' => dayKorean(date('w', strtotime($time_3, time()))),
                            'meal' => mealResult($dom, date('Y.m.d', strtotime($time_3, time())), date('w', strtotime($time_3, time()))),
                        ),
                        3 => array(
                            'date' => date('Y.m.d', strtotime($time_4, time())),
                            'day' => dayKorean(date('w', strtotime($time_4, time()))),
                            'meal' => mealResult($dom, date('Y.m.d', strtotime($time_4, time())), date('w', strtotime($time_4, time()))),
                        ),
                        4 => array(
                            'date' => date('Y.m.d', strtotime($time_5, time())),
                            'day' => dayKorean(date('w', strtotime($time_5, time()))),
                            'meal' => mealResult($dom, date('Y.m.d', strtotime($time_5, time())), date('w', strtotime($time_5, time()))),
                        )
                    )
                )
            );
            break;
    }

    $json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    echo $json;
?>