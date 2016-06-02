<?php


    $range = $_GET['range'];
    switch($range)
    {
      case '0': 
        {
          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,qualified,weeks,sale,workshop from services_leads WHERE DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) and status='qualified'  group by id,email order by registered_date desc";
          break;
        }
      case '1':
        {
          $lastWeek = date('Y-m-d H:i:s',time()-(7*86400)); // 7 days ago

          // $sql = "SELECT * FROM table WHERE date <='$date' ";
          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,qualified,weeks,sale,workshop from services_leads where (DATE(registered_date) between date_sub(now(),INTERVAL 1 WEEK) and now()) and status='qualified' order by registered_date desc";
          break;
        }
      case '2':
        {


          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,qualified,weeks,sale,workshop from services_leads WHERE (DATE(registered_date) BETWEEN (CURDATE() - INTERVAL 30 DAY) AND CURDATE()) and status='qualified' group by id,email order by registered_date desc";
          break;

        }
      case '4':
        {
                $getMail = "select id,email,name,phone,ref,registered_date,comment,status,qualified,weeks,sale,workshop from services_leads WHERE (DATE(registered_date) = CURDATE()) and status='qualified' group by id,email order by registered_date desc";
          break;
        }
      default: 
        {
          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,qualified,weeks,sale,workshop from services_leads where status='qualified' group by id,email order by registered_date desc";
          break;
        }
    }

?>