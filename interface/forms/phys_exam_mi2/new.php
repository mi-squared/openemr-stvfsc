<?php
// Copyright (C) 2013 Medical Information Integration, LLC
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

global $srcdir, $rootdir, $encounter, $css_header;

include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
include_once("lines.php");

if (! $encounter) { // comes from globals.php
 die("Internal error: we do not seem to be in an encounter!");
}

$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

function showExamLine($line_id, $description, &$linedbrow, $sysnamedisp) {
 
 if ($sysnamedisp !== '') {
    echo "<tr>\n";
    echo "  <td align='left'   width='1%' nowrap><b>$sysnamedisp</b></td>\n";
    echo "  <td align='left'   width='1%' nowrap><b>" . xl('Specific') . "</b></td>\n";
    echo "  <td align='center' width='1%' nowrap><b>" . xl('Yes') . "</b></td>\n";
    echo "  <td align='center' width='1%' nowrap><b>" . xl('No') . "</b></td>\n";
    echo "  <td class='add_comment' align='left'  width='95%' nowrap><b>" . xl('Comments') . "</b></td>\n";
    echo "</tr>\n";
 }
 echo "<tr>\n";
 echo "  <td align='left'   width='1%' nowrap></td>\n";
 echo "  <td nowrap>$description</td>\n";
 echo "  <td align='center'><input class='checkbox' type='checkbox' name='form_obs[$line_id][yes]' " .
  "value='1'" . ($linedbrow['yes'] ? " checked" : "") . " /></td>\n";
 echo "  <td align='center'><input class='checkbox' type='checkbox' name='form_obs[$line_id][no]' " .
  "value='1'" . ($linedbrow['no'] ? " checked" : "") . " /></td>\n";
 echo "  <td><input class='add_comment' style='display: none;' type='textarea' name='form_obs[$line_id][comments]' " .
  "size='50' maxlength='250' style='width:100%' " .
  "value='" . htmlentities($linedbrow['comments']) . "' /></td>\n";
 echo "</tr>\n";
}

$formid = $_GET['id'];

// If Save was clicked, save the info.
//
if ($_POST['bn_save']) {

 // We are to update/insert multiple table rows for the form.
 // Each has 2 checkboxes, a dropdown and a text input field.
 // Skip rows that have no entries.
 
 if ($formid) {
  $query = "DELETE FROM form_phys_exam_mi2 WHERE forms_id = '$formid'";
  sqlStatement($query);
 }
 else {
  $formid = addForm($encounter, "Physical Exam", 0, "phys_exam_mi2", $pid, $userauthorized);
  $query = "UPDATE forms SET form_id = id WHERE id = '$formid' AND form_id = 0";
  sqlStatement($query);
 }

 $form_obs = $_POST['form_obs'];
 foreach ($form_obs as $line_id => $line_array) {
  $yes = $line_array['yes'] ? '1' : '0';
  $no = $line_array['no'] ? '1' : '0';
  $comments  = $line_array['comments']  ? $line_array['comments'] : '';
  if ($yes || $no || $comments) {
   $query = "INSERT INTO form_phys_exam_mi2 ( " .
    "forms_id, line_id, yes, no, comments " .
    ") VALUES ( " .
    "'$formid', '$line_id', '$yes', '$no', '$comments' " .
    ")";
   sqlInsert($query);
  }
 }

 if (! $_POST['form_refresh']) {
  formHeader("Redirecting....");
  formJump();
  formFooter();
  exit;
 }
}

// Load all existing rows for this form as a hash keyed on line_id.
//
$rows = array();
if ($formid) {
 $res = sqlStatement("SELECT * FROM form_phys_exam_mi2 WHERE forms_id = '$formid'");
 while ($row = sqlFetchArray($res)) {
  $rows[$row['line_id']] = $row;
 }
}
?>

<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/js/jquery-1.7.2.min.js"></script>
<script language="JavaScript">

 function refreshme() {
  top.restoreSession();
  var f = document.forms[0];
  f.form_refresh.value = '1';
  f.submit();
 }
 
</script>
   
</head>

<body class="body_top">
<form method="post" action="<?php echo $rootdir ?>/forms/phys_exam_mi2/new.php?id=<?php echo $formid ?>"
 onsubmit="return top.restoreSession();">

<center>

<p>
<table border='0' width='98%'>

 <tr>
  <td align='left'   width='1%' nowrap><b><?php xl('System(s) Examined','e'); ?></b></td>
 </tr>

<?php
 foreach ($pelines as $sysname => $sysarray) {
  $sysnamedisp = xl($sysname);
  
  foreach ($sysarray as $line_id => $description) {
   showExamLine($line_id, $description, $rows[$line_id], $sysnamedisp);
   $sysnamedisp = '';
  } // end of line
 } // end of system name
?>

</table>

<p>
<input type='hidden' name='form_refresh' value='' />
<input type='submit' name='bn_save' value='<?php xl('Save','e'); ?>' />
&nbsp;
<input type='button' value='<?php xl('Cancel','e'); ?>'
 onclick="top.restoreSession();location='<?php echo "$rootdir/patient_file/encounter/$returnurl"; ?>';" />
</p>

</center>

</form>
<?php
// TBD: If $alertmsg, display it with a JavaScript alert().
?>
<script>
    $(document).ready(
        function() {
         
        $(".add_comment").each(function() {
            if ($(this).val().trim().length !== 0 ) {
                $(this).show();
            }
        });
  
  
        $(".checkbox").click( function() {            
            var checkbox = $(this);
            var checked = checkbox.is(":checked");
            var comment = checkbox.closest( 'tr' ).find(".add_comment");
            if ( checked ) {
                comment.show();
            } else {
                comment.hide();
            }
        });
        
   });
</script> 
</body>
</html>
