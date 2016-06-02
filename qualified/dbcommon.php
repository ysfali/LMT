<?php
    $conn = mysql_connect("motherbeelanding.db.8914663.hostedresource.com","motherbeelanding","iBohna7n!",true);
    mysql_select_db("motherbeelanding");
    mysql_query("SET timezone = '+5:30'");
    $result = mysql_query("SHOW COLUMNS FROM `services_leads` LIKE 'comment'");
    $exists = (mysql_num_rows($result))?TRUE:FALSE;
    if(!$exists) {
      // do your stuff
      $insCol = "ALTER TABLE services_leads ADD COLUMN `comment` VARCHAR(1000) DEFAULT 'No Comments',ADD COLUMN `status` VARCHAR(100) DEFAULT 'unchecked'";
      $q = mysql_query($insCol);
    }
?>