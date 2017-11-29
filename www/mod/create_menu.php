<?php
require_once('../adm/mod/conn/db_conn.php');
$sql = "SELECT * FROM mainmenu WHERE vyvod=1 ORDER by sort";
$stmt = $db->prepare($sql);
$stmt->execute();
if ($sms = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
    //debug_to_console($sms);
    foreach ($sms as $row) {
        if ($row['kod'] == $valcur) {
            $t = 'selected';
        } else {
            $t = '';
        }
        $zapr .= '<option value="' . $row['kod'] . '"' . $t . '>' . $row['type'] . '</option>';
    }
}
?>