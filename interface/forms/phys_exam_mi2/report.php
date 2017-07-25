<?php
// Copyright (C) 2013 Medical Information Integration, LLC
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or any later version.

include_once("../../globals.php");
include_once($GLOBALS["srcdir"] . "/api.inc");
include_once("lines.php");

function phys_exam_mi2_report($pid, $encounter, $cols, $id) {
 global $pelines;

 $rows = array();
 $res = sqlStatement("SELECT * FROM form_phys_exam_mi2 WHERE forms_id = '$id'");
 while ($row = sqlFetchArray($res)) {
  $rows[$row['line_id']] = $row;
 }

 echo "<table cellpadding='0' cellspacing='0'>\n";

 foreach ($pelines as $sysname => $sysarray) {
  $sysnamedisp = xl($sysname);
  foreach ($sysarray as $line_id => $description) {
   $linedbrow = $rows[$line_id];
   if (!($linedbrow['yes'] || $linedbrow['no'] || $linedbrow['comments'])) 
       continue;

    echo " <tr>\n";
    echo "  <td class='text' nowrap>$sysnamedisp&nbsp;&nbsp;</td>\n";
    echo "  <td class='text' nowrap>$description&nbsp;&nbsp;</td>\n";
    echo "  <td class='text' align='center'>" . ($linedbrow['yes'] ? "Yes" : "") . "&nbsp;&nbsp;</td>\n";
    echo "  <td class='text' align='center'>" . ($linedbrow['no'] ? "No" : "") . "&nbsp;&nbsp;</td>\n";
    echo "  <td class='text'>" . htmlentities($linedbrow['comments']) . "</td>\n";
    echo " </tr>\n";

   $sysnamedisp = '';
  } // end of line
 } // end of system name

 echo "</table>\n";
}
?> 
