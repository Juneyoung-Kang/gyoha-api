<?php
    // done!
    // For gyoha high school application project
    // Juneyoung Kang 20181019
    // how to use? http://juneyoung.kr/api/gyoha-api/src/api/timetable/api.php?gradeNumber=2&classNumber=6&resultType=date&ttDate=2018-10-22

    error_reporting(0);                                             
    header("Content-type: application/json; charset=UTF-8"); 
    
    $schoolName = '교하고';                
    $gradeNumber = $_GET['gradeNumber'];                   
    $classNumber = $_GET['classNumber'];                   
    $resultType = $_GET['resultType'];    
    $ttDate = $_GET['ttDate'];  

    if(!isset($gradeNumber)){
        die('gradeNumber field is empty');
    } elseif(!isset($classNumber)){
        die('classNumber field is empty');
    }

    if(strlen($gradeNumber)>2){
        die('gradeNumber is too long');
    } elseif(strlen($classNumber)>3){
        die('classNumber is too long');
    }

    if($resultType == "today"){                   
        $day = date('w');                             
    } elseif($resultType == "tomorrow"){
        $day = date('w', strtotime('24 hours', time()));          
    } elseif($resultType == "week"){
        $day = NULL;                                                
    } elseif($resultType == "date"){
        if(isset($ttDate)){
            $day = date('w', strtotime($ttDate));
        } else {
            die('ttDate field is empty');
        }
    } elseif(!isset($resultType)){
        die('resultType field is empty');             
    } else {
        die('resultType field is wrong');          
    }  

    $gradeNumber = (int)$gradeNumber;                              
    $classNumber = (int)$classNumber;           

    if(strtotime($ttDate) > strtotime('this sunday', time())){
        $url_tt = "http://comci.kr:4081/98372?MzQ3MzlfOTEwMDJfMF8y";
    } else {
        $url_tt = "http://comci.kr:4081/98372?MzQ3MzlfOTEwMDJfMF8x"; 
    }    
    
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url_tt);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
        $content = curl_exec($ch);
        curl_close($ch); 
        $json_tt = $content;
    }
    
    $json_tt = stripslashes(html_entity_decode($json_tt));          
    $array_tt = json_decode(trim($json_tt), true);                
    $result_tt = $array_tt['자료14'][$gradeNumber][$classNumber];

    $result_teacher = $array_tt['자료46'];                          
    $result_subject = $array_tt['긴자료92'];                      

    function result($class, $result_tt, $result_teacher, $result_subject, $day){     
        $class_info = $result_tt[$day][$class];               
        $subject = substr($class_info, -2, 2);    
        $subject = (int)$subject;                                 
        $subject_final = $result_subject[$subject];             
        $teacher = substr($class_info, -4, -2);                
        $teacher = (int)$teacher;                                
        $teacher_final = $result_teacher[$teacher];          
        $result = $subject_final."(".$teacher_final.")";    
        if($result=='(  *)'){                                      
            return NULL;                                        
        } else if($result=='()'){
            usleep(100000);
            header("Refresh:0");
        }
        return $result;                                         
    }

    switch($resultType){
        case "date":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'class01' => result(1, $result_tt, $result_teacher, $result_subject, $day),
                        'class02' => result(2, $result_tt, $result_teacher, $result_subject, $day),
                        'class03' => result(3, $result_tt, $result_teacher, $result_subject, $day),
                        'class04' => result(4, $result_tt, $result_teacher, $result_subject, $day),
                        'class05' => result(5, $result_tt, $result_teacher, $result_subject, $day),
                        'class06' => result(6, $result_tt, $result_teacher, $result_subject, $day),
                        'class07' => result(7, $result_tt, $result_teacher, $result_subject, $day),
                        'class08' => result(8, $result_tt, $result_teacher, $result_subject, $day),
                        'class09' => result(9, $result_tt, $result_teacher, $result_subject, $day),
                        'class10' => result(10, $result_tt, $result_teacher, $result_subject, $day)
                    )
                )
            );
            break;
        case "today":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'class01' => result(1, $result_tt, $result_teacher, $result_subject, $day),
                        'class02' => result(2, $result_tt, $result_teacher, $result_subject, $day),
                        'class03' => result(3, $result_tt, $result_teacher, $result_subject, $day),
                        'class04' => result(4, $result_tt, $result_teacher, $result_subject, $day),
                        'class05' => result(5, $result_tt, $result_teacher, $result_subject, $day),
                        'class06' => result(6, $result_tt, $result_teacher, $result_subject, $day),
                        'class07' => result(7, $result_tt, $result_teacher, $result_subject, $day),
                        'class08' => result(8, $result_tt, $result_teacher, $result_subject, $day),
                        'class09' => result(9, $result_tt, $result_teacher, $result_subject, $day),
                        'class10' => result(10, $result_tt, $result_teacher, $result_subject, $day)
                    )
                )
            );
            break;
        case "tomorrow":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        'class01' => result(1, $result_tt, $result_teacher, $result_subject, $day),
                        'class02' => result(2, $result_tt, $result_teacher, $result_subject, $day),
                        'class03' => result(3, $result_tt, $result_teacher, $result_subject, $day),
                        'class04' => result(4, $result_tt, $result_teacher, $result_subject, $day),
                        'class05' => result(5, $result_tt, $result_teacher, $result_subject, $day),
                        'class06' => result(6, $result_tt, $result_teacher, $result_subject, $day),
                        'class07' => result(7, $result_tt, $result_teacher, $result_subject, $day),
                        'class08' => result(8, $result_tt, $result_teacher, $result_subject, $day),
                        'class09' => result(9, $result_tt, $result_teacher, $result_subject, $day),
                        'class10' => result(10, $result_tt, $result_teacher, $result_subject, $day)
                    )
                )
            );
            break;
        case "week":
            $array = array(
                'apiName' => 'gyoha-api',
                'data' => array(
                    'result' => array(
                        0 => array(
                            'class01' => result(1, $result_tt, $result_teacher, $result_subject, 1),
                            'class02' => result(2, $result_tt, $result_teacher, $result_subject, 1),
                            'class03' => result(3, $result_tt, $result_teacher, $result_subject, 1),
                            'class04' => result(4, $result_tt, $result_teacher, $result_subject, 1),
                            'class05' => result(5, $result_tt, $result_teacher, $result_subject, 1),
                            'class06' => result(6, $result_tt, $result_teacher, $result_subject, 1),
                            'class07' => result(7, $result_tt, $result_teacher, $result_subject, 1),
                            'class08' => result(8, $result_tt, $result_teacher, $result_subject, 1),
                            'class09' => result(9, $result_tt, $result_teacher, $result_subject, 1),
                            'class10' => result(10, $result_tt, $result_teacher, $result_subject, 1)
                        ),
                        1 => array(
                            'class01' => result(1, $result_tt, $result_teacher, $result_subject, 2),
                            'class02' => result(2, $result_tt, $result_teacher, $result_subject, 2),
                            'class03' => result(3, $result_tt, $result_teacher, $result_subject, 2),
                            'class04' => result(4, $result_tt, $result_teacher, $result_subject, 2),
                            'class05' => result(5, $result_tt, $result_teacher, $result_subject, 2),
                            'class06' => result(6, $result_tt, $result_teacher, $result_subject, 2),
                            'class07' => result(7, $result_tt, $result_teacher, $result_subject, 2),
                            'class08' => result(8, $result_tt, $result_teacher, $result_subject, 2),
                            'class09' => result(9, $result_tt, $result_teacher, $result_subject, 2),
                            'class10' => result(10, $result_tt, $result_teacher, $result_subject, 2)
                        ),
                        2 => array(
                            'class01' => result(1, $result_tt, $result_teacher, $result_subject, 3),
                            'class02' => result(2, $result_tt, $result_teacher, $result_subject, 3),
                            'class03' => result(3, $result_tt, $result_teacher, $result_subject, 3),
                            'class04' => result(4, $result_tt, $result_teacher, $result_subject, 3),
                            'class05' => result(5, $result_tt, $result_teacher, $result_subject, 3),
                            'class06' => result(6, $result_tt, $result_teacher, $result_subject, 3),
                            'class07' => result(7, $result_tt, $result_teacher, $result_subject, 3),
                            'class08' => result(8, $result_tt, $result_teacher, $result_subject, 3),
                            'class09' => result(9, $result_tt, $result_teacher, $result_subject, 3),
                            'class10' => result(10, $result_tt, $result_teacher, $result_subject, 3)
                        ),
                        3 => array(
                            'class01' => result(1, $result_tt, $result_teacher, $result_subject, 4),
                            'class02' => result(2, $result_tt, $result_teacher, $result_subject, 4),
                            'class03' => result(3, $result_tt, $result_teacher, $result_subject, 4),
                            'class04' => result(4, $result_tt, $result_teacher, $result_subject, 4),
                            'class05' => result(5, $result_tt, $result_teacher, $result_subject, 4),
                            'class06' => result(6, $result_tt, $result_teacher, $result_subject, 4),
                            'class07' => result(7, $result_tt, $result_teacher, $result_subject, 4),
                            'class08' => result(8, $result_tt, $result_teacher, $result_subject, 4),
                            'class09' => result(9, $result_tt, $result_teacher, $result_subject, 4),
                            'class10' => result(10, $result_tt, $result_teacher, $result_subject, 4)
                        ),
                        4 => array(
                            'class01' => result(1, $result_tt, $result_teacher, $result_subject, 5),
                            'class02' => result(2, $result_tt, $result_teacher, $result_subject, 5),
                            'class03' => result(3, $result_tt, $result_teacher, $result_subject, 5),
                            'class04' => result(4, $result_tt, $result_teacher, $result_subject, 5),
                            'class05' => result(5, $result_tt, $result_teacher, $result_subject, 5),
                            'class06' => result(6, $result_tt, $result_teacher, $result_subject, 5),
                            'class07' => result(7, $result_tt, $result_teacher, $result_subject, 5),
                            'class08' => result(8, $result_tt, $result_teacher, $result_subject, 5),
                            'class09' => result(9, $result_tt, $result_teacher, $result_subject, 5),
                            'class10' => result(10, $result_tt, $result_teacher, $result_subject, 5)
                        )
                    )
                )
            );
            break;
    }

    $json = json_encode($array, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    echo $json;
?>