<?php
date_default_timezone_set('Asia/Tehran');
class Convertdate{
	function convert($time){
    $weekdays = array("شنبه" , "یکشنبه" , "دوشنبه" , "سه شنبه" , "چهارشنبه" , "پنج شنبه" , "جمعه");
    $months = array("فروردین" , "اردیبهشت" , "خرداد" , "تیر" , "مرداد" , "شهریور" ,
        "مهر" , "آبان" , "آذر" , "دی" , "بهمن" , "اسفند" );
    $dayNumber = date("d" , $time);
    $day2 = $dayNumber;
    $monthNumber = date("m" , $time);
    $month2 = $monthNumber;
    $year = date("Y",$time);
    $weekDayNumber = date("w" , $time);
    $hour = date("H" , $time);
    $minute = date("i" , $time);
    $second = date("s" , $time);
    switch ($monthNumber)
    {
        case 1:
			if($dayNumber <= 20){
				$monthNumber = 10;
				$dayNumber += 10;
			}
			else{
				$monthNumber = 11;
				if($dayNumber >= 21 and $dayNumber <= 29){
					$xx = $dayNumber-20;
					$dayNumber = "0".$xx;
				}
				else{
					$dayNumber = $dayNumber -20;
				}
			}
            break;
        case 2:
			if($dayNumber <= 19){
				$monthNumber = 11;
				$dayNumber  += 11;
			}
			else{
				$monthNumber = 12;
				$xx = $dayNumber - 19;
				$dayNumber = "0".$xx;
			}
            break;
        case 3:
			if($dayNumber <= 20){
				$monthNumber = 12;
				$dayNumber += 9;
			}
			else{
				$monthNumber = '01';
				if($dayNumber >= 21 and $dayNumber <= 29){
					$xx = $dayNumber-20;
					$dayNumber = "0".$xx;
				}
				else{
					$dayNumber -= 20;
				}
			}
            break;
        case 4:
			if($dayNumber <= 20){
				$monthNumber = "01";
				$dayNumber += 11;
			}else{
				$monthNumber = "02";
				if($dayNumber >= 21 and $dayNumber <= 29){
					$xx = $dayNumber-20;
					$dayNumber = "0".$xx;
				}
				else{
					$dayNumber -= 20;
				}
			}
            break;
        case 5:
        case 6:
			if($monthNumber == 5){
				if($dayNumber <= 21){
					$monthNumber = "02";
					$dayNumber += 10;
				}
				else{
					$monthNumber = "03";
					if($dayNumber >= 22 and $dayNumber <= 30){
						$xx = $dayNumber - 21;
						$dayNumber = "0".$xx;
					}
					else{
						$dayNumber -= 21;
					}
				}
			}
			else if($monthNumber == 6){
				if($dayNumber <= 21){
					$monthNumber = "03";
					$dayNumber += 10;
				}
				else{
					$monthNumber = "04";
					$xx = $dayNumber-21;
					$dayNumber = "0".$xx;
				}
			}
            break;
        case 7:
        case 8:
        case 9:
			if($monthNumber == 7){
				if($dayNumber <= 22){
					$monthNumber = "04";
					$dayNumber += 9;
				}
				else{
					$monthNumber = "05";
					$xx = $dayNumber - 22;
					$dayNumber = "0".$xx;
				}
			}else if($monthNumber == 8){
				if($dayNumber <= 22){
					$monthNumber = "05";
					$dayNumber += 9;
				}
				else{
					$monthNumber = "06";
					$xx = $dayNumber -22;
					$dayNumber = "0".$xx;
				}
			}else if($monthNumber == 9){
				if($dayNumber <= 22){
					$monthNumber = "06";
					$dayNumber += 9;
				}
				else{
					$monthNumber = "07";
					$xx = $dayNumber -22;
					$dayNumber = "0".$xx;
				}
			}
            break;
        case 10:
			if($dayNumber <= 22){
				$monthNumber = "07";
				if($dayNumber == 1){
					$xx = $dayNumber + 8;
					$dayNumber = "0".$xx;
				}
				else{
					$dayNumber += 8;
				}
				
			}
			else{
				$monthNumber = "08";
				$xx = $dayNumber -22;
				$dayNumber = "0".$xx;
			}
            break;
        case 11:
        case 12:
			if($dayNumber <= 21){
				if($monthNumber == 11){
					$monthNumber = "08";
				}
				else{
					$monthNumber = "09";
				}
				$dayNumber += 9;
			}
			else{
				if($monthNumber == 11){
					$monthNumber = "09";
					$xx = $dayNumber -21;
					$dayNumber = "0".$xx;
				}else{
					$monthNumber = 10;
					if($dayNumber >= 22 and $dayNumber <= 30){
						$xx = $dayNumber -21;
						$dayNumber = "0".$xx;
					}else{
						$dayNumber -= 21;
					}
				}
			}
            break;
    }
    $newDate['day'] = $dayNumber;
    $newDate['month_num'] = $monthNumber;
    $newDate['month_name'] = $months[$monthNumber - 1];
    if((date("m" , $time) < 3) or ((date("m" , $time) == 3) and (date("d" , $time) < 21)))
        $newDate['year'] = $year - 622;
    else
        $newDate['year'] = $year - 621;
    if($weekDayNumber == 6)
        $newDate['weekday_num'] = 0;
    else
    $newDate['weekday_num'] = $weekDayNumber + 1;
    $newDate['weekday_name'] = $weekdays[$newDate['weekday_num']];
    $newDate['hour'] = $hour;
    $newDate['minute'] = $minute;
	$newDate['second'] = $second;
	$newDate['dd'] = $newDate['year']."-".$newDate['month_num']."-".$newDate['day'];
	$newDate['t'] = $newDate['hour'].":".$newDate['minute'].":".$newDate['second'];
	$newDate['d'] = $newDate['year']."/".$newDate['month_num']."/".$newDate['day'];
    return $newDate;
		}
}