<?php
session_start();

if(!isset($_SESSION['login'])) header('Location: /leads/login.php');  

if($_SESSION['login'] !=='admin')   header('Location: /leads/login.php');

// if($_SESSION['login'] == 'motherbee') header('Location: /leads/qualified/?range=4');

function validateDate($date)
{
  $d = DateTime::createFromFormat('Y/m/d', $date);
  return $d && $d->format('Y/m/d') === $date;
}  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Dashboard - Lead Management System - MixORG : People and Ideas</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundation/6.2.0/foundation.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/clarketm/TableExport/master/dist/css/tableexport.min.css">
    <script src="https://cdn.rawgit.com/SheetJS/js-xlsx/master/dist/xlsx.core.min.js"></script>
    <script src="https://cdn.rawgit.com/eligrey/Blob.js/master/Blob.js"></script>
    <script src="https://cdn.rawgit.com/eligrey/FileSaver.js/master/FileSaver.min.js"></script>
    <script src="https://cdn.rawgit.com/clarketm/TableExport/master/dist/js/tableexport.min.js"></script>
    <style>
      .toRight
      {
        position: absolute;
        right: 90px;
        top: 40px;
      }
      .xlsx:before 
      {
        display: none;
      }
      #addDialog, #addDialog > div
      {
        overflow: hidden !important;
      }

      .fab {
        font-family: RobotoDraft, 'Helvetica Neue', Helvetica, Arial;
        position: fixed;
        right: 0;
        bottom: 0;
        border:none;
        font-size:3.5em;
        color:white;
        background-color: #da3116;;
        border-radius: 50%;
        width: 80px;
        height: 80px;
        margin: auto;
        -webkit-box-shadow: inset 12px 12px 12px 0px rgba(41, 41, 41, .3);
        -moz-box-shadow: inset 12px 12px 12px 0px rgba(41, 41, 41, .3);
        box-shadow: inset 12px 12px 12px 0px rgba(41, 41, 41, .3);
        outline: none;
      }

      .fab:hover {
        background-color:#ec5840;
      }
      .fab:active,.fab:focus
      {
        outline: none;
      }
      .small.success.button
      {
        padding-right: 30px;
      }
      .defaultColor
      {
        background-color: #F5D062 !important;
        border-bottom: 1px solid #eee;
      }

      .approvedColor
      {
        /*     background-color: #B4EFB4 !important; */
        background-color: #5AB953 !important;
        border-bottom: 1px solid #eee;
      }
      .disapprovedColor
      {
        /*     background-color: #FF5858 !important; */
        background-color: #E57260 !important;
        border-bottom: 1px solid #eee;
      }
        .ncColor
  {
    background-color: #F7C225 !important;
    border-bottom: 1px solid #eee;
  }
      thead tr > th
      {
        /*       text-align: center; */
      }
      table tbody td
      {
        padding: 0px;
      }
      .button
      {
        border-top-left-radius: 6px;
        border-bottom-right-radius: 6px;
      }
    </style>
<!--     <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '178421315887279',
      xfbml      : true,
      version    : 'v2.6'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
  function subscribeApp(page_id, page_access_token)
      {
        console.log('Subscribing to app '+page_id);
        
        FB.api(
          '/'+page_id+'/subscribed_apps',
          'post',
          {access_token: page_access_token},
          function(resp){
          console.log('Successfully Subscribed app ',resp);
        })
      }
  function loginFB()
  {
    FB.login(function(resp){
      console.log('Successfully logged in ', resp);
      FB.api('/me/accounts/', function(resp){
        console.log('Pages retreived ',resp);
        var pages = resp.data;
        var ul = document.getElementById('fblist');
        for(var i = 0, len = pages.length; i < len ; i++)
          {
            var page = pages[i];
            var li = document.createElement("li");
            var a = document.createElement("a");
            a.href = "#";
            a.onclick= subscribeApp.bind(this, page.id, page.access_token);
            a.innerHTML = page.name;
            li.appendChild(a);
            ul.appendChild(li);
          }
      })
    },{scope: 'manage_pages'});
  }
  
</script> -->
  </head>
  <body onload="">


<!--     <button onclick="loginFB();">Login to Facebook</button>
    <div id="pageSelector">
<ul id="fblist"></ul>      
    </div> -->


    <?php
    $conn = mysql_connect("motherbeelanding.db.8914663.hostedresource.com","motherbeelanding","iBohna7n!",true);
    mysql_select_db("motherbeelanding");
    mysql_query("SET timezone = '+5:30'");
    ?>
    <?php
    $result = mysql_query("SHOW COLUMNS FROM `services_leads` LIKE 'comment'");
    $exists = (mysql_num_rows($result))?TRUE:FALSE;
    $campQ = mysql_query("SHOW COLUMNS FROM `services_leads` LIKE 'utm_source'");
    $campaignExists = (mysql_num_rows($campQ))?TRUE:FALSE;
    if(!$campaignExists)
    {
      $insCol = "ALTER TABLE services_leads ADD COLUMN `utm_source` VARCHAR(10), ADD COLUMN `utm_medium` VARCHAR(10),ADD COLUMN `utm_campaign` VARCHAR(10)";
     $q = mysql_query($insCol);
    }
    if(!$exists) {
      // do your stuff
      $insCol = "ALTER TABLE services_leads ADD COLUMN `comment` VARCHAR(1000) DEFAULT 'No Comments',ADD COLUMN `status` VARCHAR(100) DEFAULT 'unchecked'";
      $q = mysql_query($insCol);

    }
    if(isset($_GET['sd']) && isset($_GET['ed']))
    {
      $sdt = mysql_real_escape_string($_GET['sd']);
      $edt = mysql_real_escape_string($_GET['ed']);
      if(validateDate($edt) && validateDate($sdt))
      {
        $getMail = mysql_query("select id,name,email,phone,ref,registered_date,comment,status,qualified,utm_source,utm_medium,utm_campaign from services_leads where registered_date between '".$sdt."' and '".$edt."' group by id,email order by registered_date desc");

      }
      else
      {
        $getMail = mysql_query("select id,name,email,phone,ref,registered_date,comment,status,qualified,utm_source,utm_medium,utm_campaign from services_leads group by id,email order by registered_date desc");

      }


    }
    else
    {
      $getMail = mysql_query("select id,name,email,phone,ref,registered_date,comment,status,qualified,utm_source,utm_medium,utm_campaign from services_leads group by id,email order by registered_date desc");

    }
    if($getMail)
    {
    ?>
    <div class="row">
      <div class="large-6 columns">
        <div class="large-12 columns">
          <p>
            <strong>Quick Stats</strong>
          </p>

        </div>
        <div class="large-6 columns">
          <p>
            Total Leads : <?= mysql_num_rows($getMail); ?>
          </p>   
          <p>
            Qualified Leads : <qld/>
          </p>  
        </div>
        <div class="large-6 columns">
          <p>
            Disqualified Leads : <uqld/>
          </p>    
          <p>
            Unresolved Leads : <unr/>
          </p> 
         <p>
            Not Contactable : <nc/>
          </p>
        </div>


      </div>
      <div class="large-4 columns">
        <div class="large-12 columns"a align="center">
          <strong>Duration of Leads</strong>
        </div>
        <!--         <div class="large-4 columns" style="position: relative;top: 24px;"> -->
        <!--           <select id="rangeSelector" name="range">
<option value="0"  <?php //if(isset($_GET['range'])){ if($_GET['range'] == '0') echo 'selected'; } ?>>Yesterday</option>
<option value="1" <?php //if(isset($_GET['range'])){ if($_GET['range'] == '1') echo 'selected'; } ?>>One Week</option>
<option value="2" <?php //if(isset($_GET['range'])){ if($_GET['range'] == '2') echo 'selected'; } ?>>One Month</option>     
<option value="3" <?php //if(isset($_GET['range'])){ if($_GET['range'] == '3') echo 'selected'; } ?>>All</option>  
</select>  -->
        <!--         </div> -->
        <div class="large-6 columns">
          Start <input id="date_timepicker_start" type="text" value="">
        </div>
        <div class="large-6 columns">
          End <input id="date_timepicker_end" type="text" value="">
        </div>   

        <div class="large-12 columns">
          <button id="selRng" class="small expanded block primary button">
            FILTER
          </button>
        </div>
      </div>
      <div class="large-2 columns">
        <a href="/leads/logout.php" class="small alert button expanded">LOGOUT</a>
        <!--         <a href="#" onclick="toExcel()" class="small primary button expanded">DOWNLOAD</a>  -->
      </div>

    </div>

    <div class="container">
      <?php //print_r($data); ?>


      <div class="large-12 columns">

        <table id="emailList">
          <thead>
            <tr>
              <th width="10%">Date</th>
              <th width="5%">Mobile</th>
              <th width="5%">Name</th>
              <th width="2%">Source</th>
              <th width="3%">Medium</th>
              <th width="3%">Campaign</th>
              <th width="35%">Status</th>
              <th width="35%">Comments</th>
              <th width="2%">Converted?</th>
            </tr>
          </thead>
          <tbody>

            <?php
      while($row = mysql_fetch_assoc($getMail))
      {
        $id = $row['id'];
        $current_param = $row['ref'];
        parse_str($current_param, $result);
        
   
              
        $parts = parse_url($current_param);
parse_str($parts['query'], $query);  
          
        
        if($row['status'] == 'unchecked')
        {
          echo '<tr class="defaultColor"  id="tr-'.$id.'">';
        }
        else if($row['status'] =='qualified')
        {
          echo '<tr class="approvedColor" id="tr-'.$id.'">';
        }
        else if($row['status'] == 'not contactable')
        {
          echo '<tr class="ncColor" id="tr-'.$id.'">';
        }
        else
        {
          echo '<tr class="disapprovedColor" id="tr-'.$id.'">';
        }

            ?>

            <td><?php
        $phpdate = strtotime( $row['registered_date'] );
        echo $mysqldate = date( 'd-m-Y', $phpdate );
        //$row['registered_date'] ?></td>
            <td><?= $row['phone']; ?></td>
            <td><?= $row['name']; ?></td>
            <td style="padding:0px;">
              <?php
        if($row['utm_source'] !== NULL) echo $row['utm_source'];
        else echo $query['utm_source'];
        ?>
            
            
            </td>
            <td style="padding:0px;">
              <?php
        if($row['utm_medium'] !== NULL) echo $row['utm_medium'];
        else echo $query['utm_medium'];
        ?>
            
            
            </td>
            <td style="padding:0px;">
              <?php
        if($row['utm_campaign'] !== NULL) echo $row['utm_campaign'];
        else echo $query['utm_campaign'];
        ?>
              
              
                           <input type="hidden" id="eml-<?= $id; ?>" value="<?= $row['email']; ?>">
      <input type="hidden" id="reg-<?= $id; ?>" value="<?= $newmysqldate = date( 'Y/m/d H:s', $phpdate ); ?>">
      <input type="hidden" id="ph-<?= $id; ?>" value="<?= $row['phone']; ?>">
        <input type="hidden" id="cmt-<?= $id; ?>" value="<?= $row['comment']; ?>">
        <?php
        if($row['status'] == 'unchecked')
        {
         echo '<input type="hidden" id="aprv-'.$id.'" value="unchecked">'; 
        }
        else if($row['statuhbgmedicalassistance.coms'] == 'qualified')
        {
            echo '<input type="hidden" id="aprv-'.$id.'" value="qualified">'; 
        }
        else
        {
          echo '<input type="hidden" id="aprv-'.$id.'" value="unqualified">';
        }
        ?>
<?php
        if($row['qualified'] == '')
        {
           echo '<input type="hidden" id="qual-'.$id.'" value="nostatus">';
        }
        else if($row['qualified'] == 'yes')
        {
           echo '<input type="hidden" id="qual-'.$id.'" value="yes">';
          
        } else {}
        
        ?>
            </td>
            <td id="status-<?= $id; ?>">

              <?php
        if($row['status'] == 'unchecked')
        {
              ?>
              <input type="radio" name="approval-<?= $id; ?>" value="qualified" onclick="approveBtn('<?= $id; ?>')" > Qualified <br>
              <input type="radio" name="approval-<?= $id; ?>" value="disqualified" onclick="disapproveBtn('<?= $id; ?>')">Disqualified<br>
              <input type="radio" name="approval-<?= $id; ?>" value="not contactable" onclick="notConnectableBtn('<?= $id; ?>')">Not Contactable<br>              
              <?php
        }
        else
        {
          ?>
              <input type="radio" name="approval-<?= $id; ?>" value="qualified" onclick="approveBtn('<?= $id; ?>')" <?php if($row['status'] == 'qualified')  echo 'checked="checked"'; ?> > Qualified <br>
              <input type="radio" name="approval-<?= $id; ?>" value="disqualified" onclick="disapproveBtn('<?= $id; ?>')" <?php if($row['status'] == 'not qualified')  echo 'checked="checked"'; ?>>Disqualified<br>
              <input type="radio" name="approval-<?= $id; ?>" value="not contactable" onclick="notConnectableBtn('<?= $id; ?>')" <?php if($row['status'] == 'not contactable')  echo 'checked="checked"'; ?>>Not Contactable<br>   
     <?php     
//           echo ucwords($row['status']);
        }
              ?>
            </td>
            <td><?php if($row['comment'] == 'No Comments') { 
              ?>
              <div class="large-6 columns">
                <button class="small primary button expanded" onclick="sendComment('<?= $id; ?>','<?= $row['phone']; ?>');">
                  COMMENT
                </button>
              </div>
              <div class="large-6 columns">
                <button onclick="delComment('<?= $id; ?>');" class="small alert button expanded">
                  DELETE
                </button>
              </div>
              <div class="large-6 columns">
                <button onclick="editData('<?= $id; ?>');" class="small button expanded">EDIT</button>
              </div>
              <div class="large-6 columns" id="refurl-<?= $id; ?>">

                <button id="showURL-<?= $id; ?>" data-url="<?= $row['ref']; ?>" onclick="showURL('<?= $id; ?>')" class="small primary button expanded">URL</button>
              </div>


              <?php
              } else { echo ' <div class="large-12 columns">'.$row['comment'].'</div>'; ?>
              <div class="large-6 columns">
                <button class="small primary button expanded" onclick="sendComment('<?= $id; ?>','<?= $row['phone']; ?>');">
                  COMMENT
                </button>
              </div>
              <div class="large-6 columns">
                <button onclick="delComment('<?= $id; ?>');" class="small alert button expanded"> DELETE</button>
              </div>
              <div class="large-6 columns">
                <button onclick="editData('<?= $id; ?>')" class="small primary button expanded"> EDIT </button>
              </div>
              <div class="large-6 columns" id="refurl-<?= $id; ?>">

                <button id="showURL-<?= $id; ?>" data-url="<?= $row['ref']; ?>" onclick="showURL('<?= $id; ?>')" class="small primary button expanded">URL</button>
              </div>
              <?php } ?></td>
            <td>
              <?php if($row['qualified'] =='')
              {
              ?>
              <input type="radio" onclick="approveReq('<?= $id; ?>')" id="apprv-<?= $id;?>"></td>

            <?php
              }
        else
        {
          echo 'Converted </td>';
        }
            ?>

        
          </tr>
        <?php

        // print_r($result);
        // echo $result['utm_source'];
      }
    }
    else
    {
      echo mysql_error();
    }

        ?>
        </tbody>
      </table>
    </div>


  </div>
<div id="dialog" title="Add Comment">
  <h5>
    Enter your comment for <mob/>
  </h5>

  <input type="text" id="cmnt">
  <input type="hidden" id="mid">
  <button class="small primary button" id="cmntbtn">COMMENT</button>
</div>
<div id="addDialog" title="Add New Lead" style="">
  <div>


    <p>Please enter all values</p>
    <form action="#" id="adLdFrm">
      <label>
        Name
        <input type="text" name="name" required>
      </label>
      <label>
        Email
        <input type="email" name="email" required>
      </label>
      <label>
        URL
        <input type="url" name="url" >
      </label>
      <label>
        Registration Date
        <input type="text" name="regDate" id="regDateP">
      </label>
       <label>
        Due Date
        <input type="text" name="dueDate" id="dueDateP">
      </label>
      <label>
        Mobile Number
        <input type="text" name="mobile" >
        <input type="hidden" name="insert" value="yes">
      </label>
<div class="large-4 columns">
            <label>
      utm_source
              <input type="text" name="source" >
      </label>
      </div>
<div class="large-4 columns">
              <label>
      utm_medium
                <input type="text" name="medium" >
  </label>
      </div>
<div class="large-4 columns">
              <label>
      utm_campaign
         <input type="text" name="campaign" >
  </label>
      </div>
      <label>
        <input type="submit" value="ADD" class="small primary button">
      </label>
    </form>
  </div>

</div>


<div id="editDialog" title="Edit This Lead" style="">
  <div>


    <p class="small">Please enter all values</p>
    <form action="#" id="edLdFrm">
      <label>
        Email
        <input type="email" name="email" required id="edEmail">
      </label>
      <label>
        Registration Date
        <input type="text" name="regDate" id="edregDateP">
      </label>
      <label>
        Mobile Number
        <input type="text" name="mobile" id="edMob">
        <input type="hidden" name="edit" value="yes">
        <input type="hidden" name="editid" id="editid">
      </label>
      <label>
        COMMENT
        <input type="text" name="comment" required id="edcmnt">
      </label>
      <label>
        LEAD APPROVAL<br>
        <input type="radio" name="approval" value="Q">&nbsp;Qualified
        <input type="radio" name="approval" value="D">&nbsp;Disqualified
        <input type="radio" name="approval" value="U">&nbsp;Undecided
      </label>
      <label>
        LEAD CONVERSION<br>
        <input type="radio" name="conversion" value="C">&nbsp;Converted
        <input type="radio" name="conversion" value="N">&nbsp;No Change
      </label>
      <label>
        <input id="edLdFrmbtn" type="button" value="EDIT NOW" class="small primary button">
      </label>
    </form>
  </div>

</div>
<button class="fab" id="addDialogBtn">+</button>

</body>

<!-- Import RobotoDraft -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.3/build/jquery.datetimepicker.full.min.js"></script>
<link rel="stylesheet" href="https://cdn.rawgit.com/xdan/datetimepicker/master/jquery.datetimepicker.css">
<style>
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EA3cHpeTo4zNkUa02-F9r1VE.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EA5HDipyzW3oxlM2ogtcJE3o.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EAwrQu7msDD1BXoJWeH_ykbQ.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EAz_9YuvR6BbpvcNvv-P7CJ0.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EAyGQb_MN5JCwpvZt9ko0I5U.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EA7r6l97bd_cX8oZCLqDvOn0.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 100;
  src: local('RobotoDraft Thin'), local('RobotoDraft-Thin'), url(http://fonts.gstatic.com/s/robotodraft/v4/hope9NW9iJ5hh8P5PM_EA9FPPhm6yPYYGACxOp9LMJ4.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwRgVThLs8Y7ETJzDCYFCSLE.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwZiMaisvaUVUsYyVzOmndek.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwbBAWGjcah5Ky0jbCgIwDB8.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-Vwf14vlcfyPYlAcQy2UfDRm4.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwafJul7RR1X4poJgi27uS4w.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwavyPXdneeGd26m9EmFSSWg.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 300;
  src: local('RobotoDraft Light'), local('RobotoDraft-Light'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwUo2lTMeWA_kmIyWrkNCwPc.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuonizTOQ_MqJVwkKsUn0wKzc2I.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuonizUj_cnvWIuuBMVgbX098Mw.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuoni0bcKLIaa1LC45dFaAfauRA.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuoni2o_sUJ8uO4YLWRInS22T3Y.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuoni76up8jxqWt8HVA3mDhkV_0.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuoniyYE0-AqJ3nfInTTiDXDjU4.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 400;
  src: local('RobotoDraft'), local('RobotoDraft-Regular'), url(http://fonts.gstatic.com/s/robotodraft/v4/0xES5Sl_v6oyT7dAKuoni44P5ICox8Kq3LLUNMylGO4.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VweKfXlfQjcwZMTeE7wI4WGI.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwUtJuoiaQfPpa269V2FDaLo.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-Vwb_PilIG-AM4Aw-a0tUNcnA.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwZ2u1fTOyc-e8Bt1FRZ8XII.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwXdOSIwbEkyw5bZp8Nzw7hU.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwbX8PDrklLOWyWIn-2-AqKA.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 500;
  src: local('RobotoDraft Medium'), local('RobotoDraft-Medium'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwbBojE9J5UnpMtv5N3zfxwk.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwZ6iIh_FvlUHQwED9Yt5Kbw.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwS_vZmeiCMnoWNN9rHBYaTc.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwSFaMxiho_5XQnyRZzQsrZs.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwQalQocB-__pDVGhF3uS2Ks.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwWhQUTDJGru-0vvUpABgH8I.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwejkDdvhIIFj_YMdgqpnSB0.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 700;
  src: local('RobotoDraft Bold'), local('RobotoDraft-Bold'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwYlIZu-HDpmDIZMigmsroc4.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwT3ms2ZYvNIk-NBT0TWd7d8.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwSSVcAiWRG34AZh10ouskJM.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwdMe8bR2ydf-7TTtdsqQSX8.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-Vwcds_PxIKQVWhSolulHOiVA.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwbSYYZADbk_6PT7FzZ2GScM.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwblR9l3n4ZtVZdCvvDPwi9o.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: normal;
  font-weight: 900;
  src: local('RobotoDraft Black'), local('RobotoDraft-Black'), url(http://fonts.gstatic.com/s/robotodraft/v4/u0_CMoUf3y3-4Ss4ci-VwcpPZoEQdaDZ3o3Np19rzJk.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTfhNcqx07xvyppV96iFRdwiM.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTfufhZE2STYI3KzBGzrJG_ik.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTfm6cj8HaeL2jS4NIBPr3RFo.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTftcKKn5Xt5n-nnvkqIBMZms.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTfvoTkEokFSrSpvYSpZOeZRs.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTfk_0lycXMw8PhobHtu2Qgco.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 400;
  src: local('RobotoDraft Italic'), local('RobotoDraft-Italic'), url(http://fonts.gstatic.com/s/robotodraft/v4/er-TIW55l9KWsTS1x9bTfsu2Q0OS-KeTAWjgkS85mDg.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
/* cyrillic-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt58DmOtrqdFwUcwXAuhp1QCY.woff2) format('woff2');
  unicode-range: U+0460-052F, U+20B4, U+2DE0-2DFF, U+A640-A69F;
}
/* cyrillic */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt54NNZfQjdGza5CVL5EXb104.woff2) format('woff2');
  unicode-range: U+0400-045F, U+0490-0491, U+04B0-04B1, U+2116;
}
/* greek-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt59SaERDUMBGl2bYraIkx774.woff2) format('woff2');
  unicode-range: U+1F00-1FFF;
}
/* greek */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt59djt7iHp2QfZLLOPHfnIug.woff2) format('woff2');
  unicode-range: U+0370-03FF;
}
/* vietnamese */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt5wF4hM5sQ7qK06vqS1ULhSo.woff2) format('woff2');
  unicode-range: U+0102-0103, U+1EA0-1EF9, U+20AB;
}
/* latin-ext */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt5_D-tiQYKMdw_jk-5jaY04o.woff2) format('woff2');
  unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
}
/* latin */
@font-face {
  font-family: 'RobotoDraft';
  font-style: italic;
  font-weight: 700;
  src: local('RobotoDraft Bold Italic'), local('RobotoDraft-BoldItalic'), url(http://fonts.gstatic.com/s/robotodraft/v4/5SAvdU0uYYlH8OURAykt549ObOXPY1wUIXqKtDjSdsY.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
}
</style>
<script>
 function editData(id)
  {
//     alert('Under development');
//     var ename = $('#')

    // get values
    var email = $('#eml-'+id).val();
    var regdate = $('#reg-'+id).val();
    var phn = $('#ph-'+id).val();
    var cmt = $('#cmt-'+id).val();
    var aprv = $('#aprv-'+id).val();
    var qual = $('#qual-'+id).val();
    
    // set values
    $('#edEmail').val(email);
    $('#edregDateP').val(regdate);
    $('#edMob').val(phn);
    $('#edcmnt').val(cmt);
    $('#editid').val(id);
    
    if(aprv == 'qualified')
      {
        var value = "Q";
          $("input[name=approval][value=" + value + "]").prop('checked', true);
      }
    else if(aprv == 'unchecked')
      {
        var value = "U";
          $("input[name=approval][value=" + value + "]").prop('checked', true);
      }
    else if(aprv == 'unqualified')
      {
        var value = "D";
          $("input[name=approval][value=" + value + "]").prop('checked', true);
      }
    else {}
  
    if(qual == 'nostatus')
      {
        var value = "N";
            $("input[name=conversion][value=" + value + "]").prop('checked', true);
      }
    else
      {
            $("input[name=conversion][value=C]").prop('checked', true);

      }
        
    $( "#editDialog" ).dialog("open");
  } 
  function approveReq(id)
  {
    if(confirm('Converted ?'))
    {
      $.ajax({
        url:'../ajax.php',
        method:'post',
        data:'converted=true&id='+id,
        success: function(ret)
        {
          if(ret == '1')
          {
            window.location.reload();
          }
          else
          {
            console.log(ret);
          }
        }
      });
    }
    else
    {
      $('#apprv-'+id).prop('checked', false);

      return false;
    }
  }
  function showURL(id)
  {
    var url = $('#showURL-'+id).attr("data-url");
    alert(url);
    //     $('#refurl-'+id).html(url);
  }


  $(function() {
//     $('#emailList').dataTable();
     $( "#editDialog" ).dialog({
      autoOpen: false,
      modal:true,
      width:450,
      height:580,
      show: {
        effect: "blind",
        duration: 200
      },
      hide: {
        effect: "explode",
        duration: 200
      }
    });   
    
        $('#edLdFrmbtn').on('click', function(){
      var edt = $('#edLdFrm').serialize();
      $.ajax({
        url:'../ajax.php',
        method:'post',
        data: edt,
        success: function(ret)
        {
          if(ret == '1') window.location.reload();
          else console.log(ret);
        }
      })
    })
    
    
    $.fn.tableExport.defaultButton = "button primary toRight small";

    $("#emailList").tableExport({
      formats: ["xlsx"],
      headings: true,
      fileName: "Motherbee_Leads",
      bootstrap: false,
      position: "top"
    });

    $('#selRng').click(function(){
      var std = $('#date_timepicker_start').val();
      var edt = $('#date_timepicker_end').val();

      if(std == '' || edt == '') alert('Please enter dates to filter');
      else
      {
        window.location.href = "http://test.mixorg.com/leads/admin/?sd="+std+'&ed='+edt+'';
      }
    })
    jQuery('#date_timepicker_start').datetimepicker({
      format:'Y/m/d',
      onShow:function( ct ){
        this.setOptions({
          maxDate:jQuery('#date_timepicker_end').val()?jQuery('#date_timepicker_end').val():false
        })
      },
      timepicker:false
    });
    jQuery('#date_timepicker_end').datetimepicker({
      format:'Y/m/d',
      onShow:function( ct ){
        this.setOptions({
          minDate:jQuery('#date_timepicker_start').val()?jQuery('#date_timepicker_start').val():false
        })
      },
      timepicker:false
    });


    jQuery('#regDateP').datetimepicker({
      mask:true
    });
        jQuery('#dueDateP').datetimepicker({
      mask:false,
      timepicker:false
    });
    $('#adLdFrm').submit(function(evt){
      evt.preventDefault();
      var values = $(this).serialize();
      //       alert(values);
      $.ajax({
        url:'../ajax.php',
        method:'post',
        data:values,
        success:function(e)
        {
          if(e === '1')
          {
            window.location.reload();
          }
          else
          {
            console.log(e);
          }
        }
      })
    })

    $('#addDialogBtn').click(function(){
      console.log('add');
      $( "#addDialog" ).dialog({
        autoOpen: false,
        modal:true,
        width:450,
        height:750,
        show: {
          effect: "blind",
          duration: 200
        },
        hide: {
          effect: "explode",
          duration: 200
        }
      });
      $( "#addDialog" ).dialog("open");
    })

      <?php

    if(isset($_GET['sd']) && isset($_GET['ed']))
    {
      $sd = $_GET['sd'];
      $ed = $_GET['ed'];
      if(validateDate($sd) && validateDate($ed))
      {

        //         echo 'valid dates';
        // send a filtered ajax query
      ?>
    $('#date_timepicker_start').val('<?= $sd; ?>');
    $('#date_timepicker_end').val('<?= $ed; ?>');
    var sd = '<?= $sd; ?>';
    var ed = '<?= $ed; ?>';    
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=qualified&sd='+sd+'&ed='+ed+'&req=filtered',
      success: function(ret)
      {
        $('qld').html(ret);
      }
    });
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unqualified&sd='+sd+'&ed='+ed+'&req=filtered',
      success: function(ret)
      {
        $('uqld').html(ret);
      }
    });
        $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unchecked&req=filtered&sd='+sd+'&ed='+ed,
      success:function(ret)
      {
        $('unr').html(ret);
      }
    });
           $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=nc&req=filtered&sd='+sd+'&ed='+ed,
      success:function(retnc)
      {
        console.log(retnc);
        $('nc').html(retnc);
      }
    }) 

    
      <?php

      }
      else
      {

        // echo 'invalid dates';
        // send a normal ajax query
      ?>
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=qualified&req=filtered',
      success: function(ret)
      {
        $('qld').html(ret);
      }
    })
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unqualified&req=filtered',
      success: function(ret)
      {
        $('uqld').html(ret);
      }
    });
        $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unchecked&req=unfiltered',
      success: function(ret)
      {
        $('unr').html(ret);
      }
    });
        $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=nc&req=unfiltered',
      success:function(retnc)
      {
        console.log(retnc);
        $('nc').html(retnc);
      }
    })
      <?php
      }
    }
    else
    {
      // send a normal ajax query
      ?>
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=qualified&req=filtered',
      success: function(ret)
      {
        $('qld').html(ret);
      }
    })
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unqualified&req=filtered',
      success: function(ret)
      {
        $('uqld').html(ret);
      }
    });
    $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unchecked&req=unfiltered',
      success: function(ret)
      {
        $('unr').html(ret);
      }
    });
        $.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=nc&req=unfiltered',
      success: function(ret)
      {
        $('nc').html(ret);
      }
    });
      <?php
    }

      ?>




    $( "#dialog" ).dialog({
      autoOpen: false,
      modal:true,
      width:450,
      height:300,
      show: {
        effect: "blind",
        duration: 400
      },
      hide: {
        effect: "explode",
        duration: 400
      }
    });
    //     $('#emailList').DataTable({
    //        "order": [[ 2, "desc" ]]
    //     });

    //     $(this).bind("contextmenu cut copy", function(e) { 
    //       e.preventDefault();
    //       alert('Copying is not allowed');
    //     });

  }); 

  function delComment(id)
  {
    if(confirm('Are you sure ? '))
    {
      $.ajax({
        url:'../ajax.php',
        method:'post',
        data:'del=true&id='+id,
        success: function(er)
        {
          if(er === '1')
          {
            window.location.reload();
          }
          else
          {
            console.log(er);
          }
        }
      })
    }
    else
    {
      return false;
    }
  }

  function sendComment(id,mobile)
  {
    $('mob').html(mobile);
    $('#mid').val(id);
    $( "#dialog" ).dialog("open");


  }
  $('#cmntbtn').click(function(){

    var cmnt = $('#cmnt').val();
    var id = $('#mid').val();
    if(cmnt == '' ) alert('Enter comment');
    else
    {
      $.ajax({
        url:'../ajax.php',
        method:'post',
        data:'comment=true&id='+id+'&cmt='+cmnt,
        success:function(ret)
        {
          if(ret === '1')
          {
            window.location.reload();
          }
          console.log(ret);
        }
      })
    }

  })

  function approveBtn(id)
  {
    if(confirm('Are you sure ?'))
    {
      $.ajax({
        url:'../ajax.php',
        method:'get',
        data:'approve=true&id='+id,
        success: function(ret)
        {
          if(ret == '1')
          {
            $('#status-'+id).html('Qualified');
            $('#tr-'+id).removeClass().addClass('approvedColor');
          }
          else
          {
            console.log(ret);
          }
        }
      })
    }

    else
    {
      return false;
    }
  }
  function disapproveBtn(id)
  {
    if(confirm('Are you sure ?'))
    {
      $.ajax({
        url:'../ajax.php',
        method:'get',
        data:'approve=false&id='+id,
        success: function(ret)
        {
          if(ret == '1')
          {
            $('#status-'+id).html('Not Qualified');
            $('#tr-'+id).removeClass().addClass('disapprovedColor');
          }
        }
      })
    }

  }   
</script>
</html>