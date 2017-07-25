<?php
// Copyright (C) 2013 Medical Information Integration <info@mi-squared.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
include_once("../../globals.php");
include_once("$srcdir/api.inc");

require ("C_FormStdAssessmentPlan.class.php");

$c = new C_FormStdAssessmentPlan();
echo $c->default_action();
?>
