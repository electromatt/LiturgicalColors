<?php
   /*
   Plugin Name: Liturgical Color
   Plugin URI: http://mattdruckhammer.com
   Description: a plugin to get the current liturgical color for the day
   Version: 1.1
   Author: Matthew Druckhammer
   Author URI: http://mattdruckhammer.com
   License: GPL2
   */
   

date_default_timezone_set('America/Los_Angeles'); 

function getColor(){
	// Date Variables
	$year = date('Y');
	$today = date("m/d/Y");
	
	// Colors
	$gold = "#cf9f52";
	$blue = "#5179ba";
	$green = "#477258";
	$red = "#af231c";
	$purple = "#3e3254 ";
	$black = "#333333";
	
	// Days
	$easter = get_easter_datetime($year);
	$formatEaster = $easter->format('m/d/Y');
	$christmas = date("m/d/Y",strtotime("$year-12-25"));
	$end_year = addDaysWithDate($christmas,6);
	$new_year = date("m/d/Y",strtotime("+1 year", strtotime("$year-01-01")));
	$fourth_advent_sunday = getPreviousSunday($christmas);
	$first_advent_sunday = addDaysWithDate($fourth_advent_sunday, -21);
	$epiphany = date("m/d/Y",strtotime("$year-01-06"));
	$first_sunday_after_epiphany = getNextSunday($epiphany);
	$ash_wednesday = addDaysWithDate($formatEaster, -46);
	$first_lent = addDaysWithDate($ash_wednesday, 4);
	$transfiguration_sunday = addDaysWithDate(getPreviousSunday($first_lent), -21);
	$palm_sunday = addDaysWithDate($formatEaster, -7);
	$maundy_thursday = addDaysWithDate($formatEaster, -3);
	$good_friday = addDaysWithDate($formatEaster, -2);
	$pentecost = addDaysWithDate($formatEaster, 49);
	$trinity_sunday = getNextSunday($pentecost);
	$first_sunday_after_trinity = getNextSunday($trinity_sunday);
	$reformation_day = date("m/d/Y",strtotime("$year-10-31"));
	$all_saints_day = getNextSunday($reformation_day);
	$last_sunday = getPreviousSunday($first_advent_sunday);
	$thanksgiving = date('m/d/Y', strtotime("november $year fourth thursday"));
	
	if($today >= $first_advent_sunday && $today < $christmas){
		return $blue;
	}
	if($today >= $christmas && $today <= $end_year || $today < getNextSunday($first_sunday_after_epiphany)){
		return $gold;
	}
	if(($today >= getNextSunday($first_sunday_after_epiphany) && $today < $ash_wednesday) && $today != $transfiguration_sunday){
		return $green;
	}
	if($today == $transfiguration_sunday){ return $gold; }
	if($today == $ash_wednesday){ return $black; }
	if($today > $ash_wednesday && $today < $palm_sunday){ return $purple; }
	if($today >= $palm_sunday && $today < $maundy_thursday){ return $red; }
	if($today >= $maundy_thursday && $today < $good_friday){ return $gold; }
	if($today == $good_friday){ return $black; }
	if(($today >= $formatEaster && $today < $first_sunday_after_trinity ) && $today != $pentecost){ return $gold; }
	if($today == $pentecost){ return  $red; }
	if($today >= $first_sunday_after_trinity && $today < getPreviousSunday($reformation_day)){ return $green; }
	if($today >= getPreviousSunday($reformation_day) && $today < $all_saints_day){ return $red; }
	if($today >= $all_saints_day && $today < getNextSunday($all_saints_day)){ return $gold; }
	if($today >= getNextSunday($all_saints_day) && $today < $first_advent_sunday){ return  $green; }
}

function getNextSunday($date){
	$time = strtotime($date);
	$sunday = strtotime('next sunday, 12:00am', $time);
	$format = 'm/d/Y';
	$day = date($format, $sunday);
	return $day;
}
function getPreviousSunday($date){
	$time = strtotime($date);
	$sunday = strtotime('last sunday, 12:00am', $time);
	$format = 'm/d/Y';
	$day = date($format, $sunday);
	return $day;	
}
function get_easter_datetime($year) {
    $base = new DateTime("$year-03-21");
    $days = easter_days($year);
    return $base->add(new DateInterval("P{$days}D"));
}
function addDaysWithDate($date,$days){
    return date("m/d/Y", strtotime("+".$days." days", strtotime($date)));
}


$data = array('color' => getColor());
