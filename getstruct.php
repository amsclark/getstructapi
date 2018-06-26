<?php 
/*
   ___   ___ __  __   ___  ___   ___ _               _                    _   ___ ___ 
  / _ \ / __|  \/  | |   \| _ ) / __| |_ _ _ _  _ __| |_ _  _ _ _ ___    /_\ | _ \_ _|
 | (_) | (__| |\/| | | |) | _ \ \__ \  _| '_| || / _|  _| || | '_/ -_)  / _ \|  _/| | 
  \___/ \___|_|  |_| |___/|___/ |___/\__|_|  \_,_\__|\__|\_,_|_| \___| /_/ \_\_| |___|
                                                                                      
  _                  _     _   _    _        __   _  _ ___ 
 | |   ___ __ _ __ _| |   /_\ (_)__| |  ___ / _| | \| | __|
 | |__/ -_) _` / _` | |  / _ \| / _` | / _ \  _| | .` | _| 
 |____\___\__, \__,_|_| /_/ \_\_\__,_| \___/_|   |_|\_|___|
          |___/                                            

    _   _            ___ _          _           ___ __  _ ___ 
   /_\ | |_____ __  / __| |__ _ _ _| |__  ___  |_  )  \/ ( _ )
  / _ \| / -_) \ / | (__| / _` | '_| / / |___|  / / () | / _ \
 /_/ \_\_\___/_\_\  \___|_\__,_|_| |_\_\       /___\__/|_\___/

This code is licensed under the GNU Public License version 3.
A copy of the GPL v 3 is available at https://www.gnu.org/licenses/gpl-3.0.en.html                                                              
*/



//error_reporting(E_ALL);
//ini_set('display_errors', '1');


if($_POST['key'] != "APIKeyGoesHere") {
  die('Access Denied');
}

$link = mysql_connect('localhost', 'UsernameGoesHere', 'PassGoesHere');
if (!$link) {
  die('Could not connect to database.');
}
$db_select = mysql_select_db("DataBaseNameGoesHere", $link);



class EnumerationMenu {
  public $menuName = null;
  public $menuItems = array();
}

class FieldDescriptorList {
  public $tableName = null;
  public $codesAndTypes = array();
}

class DBSchema {
  public $caseTables = array();
  public $menus = array();
}

$myDBSchema = new DBSchema();



// build the SQL statement, based on user input
$menu_prefix = "menu_%";
$sql = "show tables like '$menu_prefix'"; 



				
// execute the SQL statement, format the results, and add to the table object
$result = mysql_query($sql) or trigger_error('Could not execute query');




while(($row =  mysql_fetch_row($result))) {
    $myEnumerated = new EnumerationMenu();
    $myEnumerated->menuName = $row[0];
    $sql_table_list = "select * from $row[0]";
    $result_table_list = mysql_query($sql_table_list) or trigger_error();
    while(($result_table_row = mysql_fetch_row($result_table_list))) {	
      $myEnumerated->menuItems[$result_table_row[0]] = $result_table_row[1];
	}
      array_push($myDBSchema->menus, $myEnumerated);
}

$sql2="show columns from contacts";
$result = mysql_query($sql2) or trigger_error();
$contactsFDL = new FieldDescriptorList();
$contactsFDL->tableName = "contacts";
while (($row = mysql_fetch_row($result))) {
  $contactsFDL->codesAndTypes[$row[0]] = $row[1];
}
$myDBSchema->caseTables["contacts"] = $contactsFDL;



$sql3="show columns from cases";
$result = mysql_query($sql3) or trigger_error();
$casesFDL = new FieldDescriptorList();
$casesFDL->tableName = "cases";
while (($row = mysql_fetch_row($result))) {
  $casesFDL->codesAndTypes[$row[0]] = $row[1];
}
$myDBSchema->caseTables["cases"] = $casesFDL;


$sql4="show columns from activities";
$result = mysql_query($sql4) or trigger_error();
$activitiesFDL = new FieldDescriptorList();
$activitiesFDL->tableName = "activities";
while (($row = mysql_fetch_row($result))) {
  $activitiesFDL->codesAndTypes[$row[0]] = $row[1];
}
$myDBSchema->caseTables["activities"] = $activitiesFDL;

echo json_encode($myDBSchema);

exit();

?>
