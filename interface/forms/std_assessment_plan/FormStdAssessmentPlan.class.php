<?php
// Copyright (C) 2013 Medical Information Integration <info@mi-squared.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2

require_once(dirname(__FILE__) . "/../../../library/classes/ORDataObject.class.php");


/**
 * class FormStdAssessmentPlan
 *
 */
class FormStdAssessmentPlan extends ORDataObject {

	/**
	 *
	 * @access public
	 */


	/**
	 *
	 * static
	 */
	var $id;
	var $date;
	var $pid;
	var $user;
	var $groupname;
	var $activity;
	var $assessment;
	var $plan;
        var $facullty_notes;
	 
	/**
	 * Constructor sets all Form attributes to their default value
	 */

	function FormStdAssessmentPlan($id= "", $_prefix = "")	{
		if (is_numeric($id)) {
			$this->id = $id;
		}
		else {
			$id = "";
			$this->date = date("Y-m-d H:i:s");	
		}
		
		$this->_table = "form_std_assessment_plan";
		$this->activity = 1;
		$this->pid = $GLOBALS['pid'];
		if ($id != "") {
			$this->populate();
			//$this->date = $this->get_date();
		}
	}
	function populate() {
		parent::populate();
		//$this->temp_methods = parent::_load_enum("temp_locations",false);		
	}

	function toString($html = false) {
		$string .= "\n"
			."ID: " . $this->id . "\n";

		if ($html) {
			return nl2br($string);
		}
		else {
			return $string;
		}
	}
	function set_id($id) {
		if (!empty($id) && is_numeric($id)) {
			$this->id = $id;
		}
	}
	function get_id() {
		return $this->id;
	}
	function set_pid($pid) {
		if (!empty($pid) && is_numeric($pid)) {
			$this->pid = $pid;
		}
	}
	function get_pid() {
		return $this->pid;
	}
	
	function get_date() {
		return $this->date;
	}
	function set_date($dt) {
		if (!empty($dt)) {
			$this->date = $dt;
		}
	}
	function get_user() {
		return $this->user;
	}
	function set_user($u) {
		if(!empty($u)){
			$this->user = $u;
		}
	}

	function set_activity($tf) {
		if (!empty($tf) && is_numeric($tf)) {
			$this->activity = $tf;
		}
	}
	function get_activity() {
		return $this->activity;
	}

	function get_assessment() {
		return $this->assessment;
	}
	function set_assessment($data) {
		if(!empty($data)){
			$this->assessment = $data;
		}
	}

	function get_plan() {
		return $this->plan;
	}
	function set_plan($data) {
		if(!empty($data)){
			$this->plan = $data;
		}
	}
        
        function get_faculty_notes() {
		return $this->faculty_notes;
	}
	function set_faculty_notes($data) {
		if(!empty($data)){
			$this->faculty_notes = $data;
		}
	}
	
	function persist() {
		parent::persist();
	}
}	// end of Form

?>
