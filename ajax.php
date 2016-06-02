<?php

$conn = mysql_connect("motherbeelanding.db.8914663.hostedresource.com","motherbeelanding","iBohna7n!",true);
mysql_select_db("motherbeelanding");
$table = "services_leads";
    mysql_query("SET timezone = '+5:30'");
// workshop=yes&attended=yes
  if(isset($_POST['workshop']) && isset($_POST['attended']) && isset($_POST['id']))
  {
    $id = $_POST['id'];
    $q = "update `$table` set workshop='yes' where id=".$id;
    if(mysql_query($q))
    {
      echo '1';
    }
    else echo mysql_error();
  }


if(isset($_GET['approve']) && isset($_GET['id']))
{
  $id = $_GET['id'];
  if($_GET['approve'] == 'true')
  {
    // change status to qualified
    $q = mysql_query("update services_leads set status='qualified' where id=".$id);
    if($q)
    {
      echo '1';
    }
    else
    {
      echo mysql_error();
    }
  }


  if($_GET['approve'] == 'false')
  {
    // change status to Not Qualified

    $q = mysql_query("update services_leads set status='not qualified' where id=".$id);
    if($q)
    {
      echo '1';
    }
    else
    {
      echo mysql_error();
    }
  }
if($_GET['approve'] == 'nc')
{
   $q = mysql_query("update services_leads set status='not contactable' where id=".$id);
    if($q)
    {
      echo '1';
    }
    else
    {
      echo mysql_error();
    }
}
}

// data:'comment=true&id='+id+'&cmt='+cmnt,

// add comment for a lead
if(isset($_POST['comment']) && isset($_POST['id']) && isset($_POST['cmt']))
{
  $cmnt = $_POST['cmt'];

  if($cmnt == '')
  {
    die('Enter comment'); 
  }

  $id = $_POST['id'];
  $insCOmmQ = mysql_query("update ".$table." set comment='".$cmnt."' where id=$id");
  if($insCOmmQ)
  {
    echo '1';
  }
  else
  {
    echo mysql_error();
  }
}

// leads with range
if(isset($_GET['leads']) && isset($_GET['range']))
{
  $leadsType = $_GET['leads'];
  $range = $_GET['range'];
  
  // sql for range
  switch($leadsType)
  {
    case 'qualified':
      {
        if($range == '0')
        {
         $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) and status='qualified'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select count(id) as count from ".$table." where registered_date between date_sub(now(),INTERVAL 1 WEEK) and now() and status='qualified'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE registered_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() and status='qualified'");
    
        }
        else if($range == '4')
        {
          $getQfleads = mysql_query("select count(id) as count from ".$table." where (DATE(registered_date) = CURDATE()) and status='qualified'");
        }
        else
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='qualified'");

        }
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break;
      }
    case 'unqualified':
      {
        if($range == '0')
        {
         $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) and status='not qualified'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select count(id) as count from ".$table." where registered_date between date_sub(now(),INTERVAL 1 WEEK) and now() and status='not qualified'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE registered_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() and status='not qualified'");
    
        }
        else if($range == '4')
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." where DATE(registered_date) = CURDATE() and status='not qualified'");
        }
        else
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='not qualified'");

        }
//         $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not qualified'");
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break;
      }
          case 'nc':
      {
        if($range == '0')
        {
         $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) and status='not contactable'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select count(id) as count from ".$table." where registered_date between date_sub(now(),INTERVAL 1 WEEK) and now() and status='not contactable'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE registered_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() and status='not contactable'");
    
        }
        else if($range == '4')
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." where DATE(registered_date) = CURDATE() and status='not contactable'");
        }
        else
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='not contactable'");

        }
//         $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not qualified'");
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break;
      }
       case 'unchecked':
      {
              if($range == '0')
        {
         $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE (DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))) and status='unchecked'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select count(id) as count from ".$table." where registered_date between (date_sub(now(),INTERVAL 1 WEEK) and now()) and status='unchecked'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE registered_date BETWEEN (CURDATE() - INTERVAL 30 DAY AND CURDATE()) and status='unchecked'");
    
        }
      else if($range == '4')
      {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." where (DATE(registered_date) = CURDATE()) and status='unchecked'");
      }
        else
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='unchecked'");

        }
//         $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not qualified'");
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break;      
      }
    case 'conv':
      {
                  if($range == '0')
        {
         $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE (DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))) and qualified='yes'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select count(id) as count from ".$table." where registered_date between (date_sub(now(),INTERVAL 1 WEEK) and now()) and qualified='yes'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE registered_date BETWEEN (CURDATE() - INTERVAL 30 DAY AND CURDATE()) and qualified='yes'");
    
        }
      else if($range == '4')
      {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." where (DATE(registered_date) = CURDATE()) and qualified='yes'");
      }
        else
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE qualified='yes'");

        }
//         $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not qualified'");
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break; 
  }
    case 'sales':
      {
                      if($range == '0')
        {
         $getQfleads = mysql_query("select sum(sale) as count from ".$table." WHERE (DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))) and qualified='yes'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select sum(sale) as count from ".$table." where registered_date between (date_sub(now(),INTERVAL 1 WEEK) and now()) and qualified='yes'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select sum(sale) as count from ".$table." WHERE registered_date BETWEEN (CURDATE() - INTERVAL 30 DAY AND CURDATE()) and qualified='yes'");
    
        }
      else if($range == '4')
      {
                  $getQfleads = mysql_query("select sum(sale) as count from ".$table." where (DATE(registered_date) = CURDATE()) and qualified='yes'");
      }
        else
        {
           $getQfleads = mysql_query("select sum(sale) as count from ".$table." WHERE qualified='yes'");

        }
//         $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not qualified'");
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break; 
  }
    default: {
              if($range == '0')
        {
         $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE (DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY))) and status='unchecked'");

        }
        else if($range == '1')
        {
          
    $getQfleads = mysql_query("select count(id) as count from ".$table." where registered_date between (date_sub(now(),INTERVAL 1 WEEK) and now()) and status='unchecked'");
  
        }
        else if($range == '2')
        {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE registered_date BETWEEN (CURDATE() - INTERVAL 30 DAY AND CURDATE()) and status='unchecked'");
    
        }
      else if($range == '4')
      {
                  $getQfleads = mysql_query("select count(id) as count from ".$table." where DATE(registered_date) = CURDATE() and status='unchecked'");
      }
        else
        {
           $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='unchecked'");

        }
//         $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not qualified'");
        if($getQfleads)
        {
          $row = mysql_fetch_assoc($getQfleads);
          echo $row['count'];
        }else echo mysql_error();
        break;
    }
  }
}

// delete lead
if(isset($_POST['del']) && isset($_POST['id']))
{
  if($_POST['del'] == 'true')
  {
    $id = $_POST['id'];
    
    if(mysql_query("delete from ".$table." where id=".$id))
    {
      echo '1';
    }
    else
    {
      echo mysql_error();
    }
  }
}

// add new lead
if(isset($_POST['insert']))
{
  if($_POST['insert'] == 'yes')
  {
    $name = $_POST['name'];
    $url = $_POST['url'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $date = $_POST['regDate'];
    $dueDate = $_POST['dueDate'];
    $source = $_POST['source'];
      $medium = $_POST['medium'];
      $campaign = $_POST['campaign']; 
 
    $date = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $date);
    $insQ = "insert into services_leads values('','$name','$email','$mobile','$date','$url','$dueDate','No Comments','unchecked','',0,'$source','$medium','$campaign','no data')";
    if(mysql_query($insQ))
    {
      echo '1';
    }
    else
    {
      echo mysql_error();
    }
  }
}

// converted checkbox
if(isset($_POST['converted']))
{
  if($_POST['converted'] == 'true' && isset($_POST['id']))
  {
    $id = $_POST['id'];
    $updQ = "update ".$table." set qualified='yes' where id=".$id;
    if(mysql_query($updQ))
    {
      echo '1';
    }else
    {
      echo mysql_error();
    }
  }
}

// leads with/ without range

if(isset($_GET['leads']) && isset($_GET['req']))
{
    $leadsType = $_GET['leads'];
  $req = $_GET['req'];
  if($req == 'filtered' && isset($_GET['sd']) && isset($_GET['ed']))
  {
    // filtered by a date range
$sd = $_GET['sd'];
$ed = $_GET['ed'];
    
    switch($leadsType)
    {
      case 'qualified':
        {
          //where registered_date between '".$sdt."' and '".$edt."'
            $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE DATE(registered_date) between '".$sd."' and '".$ed."' and status='qualified'");
            
          if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
        }
      case 'unqualified':
        {
                      $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE DATE(registered_date) between '".$sd."' and '".$ed."' and status='not qualified'");
            
          if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
        }
              case 'unchecked':
        {
          // get unchecked leads
          $getQfleads = mysql_query("select count(id) as count from ".$table." where DATE(registered_date) between DATE('".$sd."') and DATE('".$ed."') and status='unchecked'");
                  if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
        }
                      case 'nc':
        {
          // get unchecked leads
          $getQfleads = mysql_query("select count(id) as count from ".$table." where DATE(registered_date) between DATE('".$sd."') and DATE('".$ed."') and status='not contactable'");
                  if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
        }
        
    }
    
  }
  else
  {
    // full data without any filter
   
        switch($leadsType)
    {
      case 'qualified':
        {
         
            $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='qualified'");
            
          if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
        }
      case 'unqualified':
        {
                      $getQfleads = mysql_query("select count(id) as count from ".$table." WHERE status='not qualified'");
            
          if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
        }
                   case 'unchecked':
            {
                        // get unchecked leads
          $getQfleads = mysql_query("select count(id) as count from ".$table." where status='unchecked'");
                  if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break;
            }
      case 'nc':
        {
                             // get not contactable leads
          $getQfleads = mysql_query("select count(id) as count from ".$table." where status='not contactable'");
                  if($getQfleads)
          {
            $row = mysql_fetch_assoc($getQfleads);
            echo $row['count'];
          }
          else
          {
            echo 'mysql error';
          }
          break; 
    }
        
    }
    
    
  }
  


  

}


// update lead data

if(isset($_POST['edit']))
{
  if($_POST['edit'] == 'yes')
  {
    $email = $_POST['email'];
    $regdate = $_POST['regDate'];
    $mobile = $_POST['mobile'];
    $comment = $_POST['comment'];
    if($comment == '' || $comment == null || $comment == 'undefined' || $comment == 'no comments' || $comment == 'no Comments' || $comment == 'No comments')
    {
      $comment = "No Comments";
    }
    $qual = $_POST['approval'];
    $conv = $_POST['conversion'];
    $id = $_POST['editid'];    
    if($qual == 'q' || $qual == 'Q') $q = 'qualified';
    else if($qual == 'd' || $qual == 'D') $q = 'not qualified';
    else $q = 'unchecked';
    
    if($conv != 'N') $c = 'yes';
      else $c = '';
    
    $id = $_POST['editid'];
    $updQry = "update ".$table." set email='".$email."',registered_date='".$regdate."', phone='".$mobile."', comment='".$comment."',status='".$q."',qualified='".$c."' where id=".$id;
  if(mysql_query($updQry))
  {
    echo '1';
  }else
  {
    echo mysql_error();
  }
  }
}

// putSales=true&amount='+amount+'&id='+id

if(isset($_POST['putSales']) && isset($_POST['amount']) && isset($_POST['id']))
{
  if($_POST['putSales'] == 'true' && $_POST['amount'] !== '' && $_POST['id'] !== '')
  {
    $id = $_POST['id'];
    $amount = $_POST['amount'];
    $qry = "update `$table` set sale=".$amount." where id=".$id."";
    $q = mysql_query($qry);
    if($q)
    {
      echo '1';
      
    }
    else
    {
      echo mysql_error();
    }
  }
}





