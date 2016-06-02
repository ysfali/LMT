<?php
session_start();

if(!isset($_SESSION['login'])) header('Location: /leads/login.php');

if($_SESSION['login'] === 'admin') header('Location: /leads/admin/');
if($_SESSION['login'] == 'user') header('Location: /leads/?range=4');

if(!isset($_GET['range']))
{
  header('Location: /leads/qualified/?range=4');
}

$username = $_SESSION['username'];
// if($_SESSION['username'] === '') $_SESSION['user']
// $clientId = 8;

        function validateDate($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Qualified Leads :: Client Dashboard</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundation/6.2.0/foundation.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!--     <link rel="stylesheet" href="//cdn.rawgit.com/abpetkov/switchery/master/dist/switchery.min.css">
    <script src="//cdn.rawgit.com/abpetkov/switchery/master/dist/switchery.min.js"></script> -->
   <link rel="stylesheet" href="https://cdn.rawgit.com/clarketm/TableExport/master/dist/css/tableexport.min.css">
    <script src="https://cdn.rawgit.com/SheetJS/js-xlsx/master/dist/xlsx.core.min.js"></script>
    <script src="https://cdn.rawgit.com/eligrey/Blob.js/master/Blob.js"></script>
    <script src="https://cdn.rawgit.com/eligrey/FileSaver.js/master/FileSaver.min.js"></script>
    <script src="https://cdn.rawgit.com/clarketm/TableExport/master/dist/js/tableexport.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>

    </style>

  </head>
  <body>
<?php require_once('dbcommon.php'); ?>
<?php require_once('fetch.php'); ?>
<?php $gtm = mysql_query($getMail);
    if($gtm)
    {
    ?>
    <div class="row">
      <div class="large-8 columns">
                <div class="large-12 columns">
                <p>
          <strong>Quick Stats</strong>
        </p>          
        </div>
        
        <div class="large-6 columns">
     <p>
          Total Leads In View : <?= mysql_num_rows($gtm); ?>
        </p>   
        <p>
          Unresolved Leads : <unr/>
        </p>         

          <p>
          Qualified Leads : <qld/>
        </p>   
        <p>
          Disqualified Leads : <uqld/>
        </p> 
          <p>
            Not Contactable : <nc/>
          </p>
        </div>
        
        <div class="large-6 columns">
          <p>
            Total Converted Leads : <conv />
          </p>
          <p>
            Total Sales :  &#8377; <tSale/>
          </p>
        </div>

    
  
      </div>
      <div class="large-2 columns">
        <p>
          Duration of Leads <select id="rangeSelector" name="range">
                    <option value="4"  <?php if(isset($_GET['range'])){ if($_GET['range'] == '4') echo 'selected'; } ?>>Today</option>
          <option value="0"  <?php if(isset($_GET['range'])){ if($_GET['range'] == '0') echo 'selected'; } ?>>Yesterday</option>
          <option value="1" <?php if(isset($_GET['range'])){ if($_GET['range'] == '1') echo 'selected'; } ?>>Last Week</option>
          <option value="2" <?php if(isset($_GET['range'])){ if($_GET['range'] == '2') echo 'selected'; } ?>>Last Month</option>     
                     <option value="3" <?php  if(isset($_GET['range'])){ if($_GET['range'] == '3' ) echo 'selected'; } ?>>All</option>  
          </select> 
        </p>
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
              <th>Date</th>
              <th>Mobile No</th>
              <th>Name</th>
              <th>Lead Source</th>
              
              <th width="10%">Status</th>
              <th>Due Date</th>
              <th width="20%">Comments</th>
              <th width="25%">Converted</th>
              <th width="20%">Sale Amount</th>
              <th width="10%">Workshop</th>
            </tr>
          </thead>
          <tbody>

            <?php
      while($row = mysql_fetch_assoc($gtm))
      {
        $i++;
        ?>

            <?php
        $id = $row['id'];
        $current_param = $row['ref'];
        parse_str($current_param, $result);
        
        $parts = parse_url($current_param);
parse_str($parts['query'], $query);
// echo $query['email'];
        
        if($row['qualified'] == '')
        {
                  if($row['status'] == 'unchecked')
         {
          echo '<tr class="defaultColor"  id="tr-'.$id.'">';
        }
        else if($row['status'] =='qualified')
        {
          echo '<tr class="approvedColor" id="tr-'.$id.'">';
        }
        else
        {
          echo '<tr class="disapprovedColor" id="tr-'.$id.'">';
        }
        }
        else
        {
           echo '<tr class="approvedAndConverted" id="tr-'.$id.'">';
        }
        
        


            ?>

            <td><?php

        
        $phpdate = strtotime( $row['registered_date'] );
        echo $mysqldate = date( 'd-m-Y', $phpdate );
        //$row['registered_date'] ?></td>
            <td><?= $row['phone']; ?></td>
            <td><?= $row['name']; ?></td>
   
            <td style="padding:0px;">
              <?php if($query['utm_medium'] == '') 
        {
          echo 'FB';
        }
        else echo $query['utm_medium']; ?>
              
                           <input type="hidden" id="eml-<?= $id; ?>" value="<?= $row['email']; ?>">
      <input type="hidden" id="reg-<?= $id; ?>" value="<?= $newmysqldate = date( 'Y/m/d H:s', $phpdate ); ?>">
      <input type="hidden" id="ph-<?= $id; ?>" value="<?= $row['phone']; ?>">
        <input type="hidden" id="cmt-<?= $id; ?>" value="<?= $row['comment']; ?>">
        <?php
        if($row['status'] == 'unchecked')
        {
         echo '<input type="hidden" id="aprv-'.$id.'" value="unchecked">'; 
        }
        else if($row['status'] == 'qualified')
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

            <td width="10%" id="status-<?= $id; ?>">

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
            <td id="due-<?= $id; ?>"><?php
        $time = strtotime($row['weeks']);
        if(validateDate($time))
        {
          $newformat = date('d-m-Y',$time);

echo $newformat;
        }
        else
        {
          echo $row['weeks'];
        }
        ?></td>
            <td width="20%"><?php if($row['comment'] == 'No Comments')  {
              ?>
              <button class="small primary button" onclick="sendComment('<?= $id; ?>','<?= $row['phone']; ?>');">
                COMMENT
              </button>
              <button class="small primary button" onclick="editData('<?= $id; ?>')">
                EDIT
              </button>
              
                            
              <?php
              } else { echo '<div class="cmnt" id="cm-'.$id.'">'.$row['comment'].'</div> <br><button class="small primary button" onclick="editData(\''.$id.'\')">EDIT</button>';  } ?>
            </td>
            <td>
                       <?php if($row['qualified'] =='')
              {
              ?>
              <input type="radio" onclick="approveReq('<?= $id; ?>')" id="apprv-<?= $id;?>">

            <?php
              }
        else
        {
          ?>
              Converted <img src="/leads/converted.png" width="25" height="25">
              
         
              <?php
     
        }
            ?>
            </td>
            <td>
                 <?php 
     if($row['qualified'] =='')
     {
       echo 'Not Converted';
     }
        else
        {
          
      
              if($row['sale'] == 0 || $row['sale'] == '0')
              {
                ?>
            
              <p>
                Sale Value (&#8377;)
              </p>
<div class="input-group">

  <input id="amt-<?= $row['id']; ?>" class="input-group-field" type="number" style="width: 75px;float: left;" min="0">

    <input onclick="saleBtn('<?= $row['id']; ?>')" type="button" class="button" value="Submit" style="float:left;" >
 
</div> 
              <?php
              }
          else 
          { 
            ?>
              &#8377; <?= $row['sale']; ?>/- <button class="tiny info button" onclick="editSale(<?= $row['id'] ?>);">EDIT</button>
       <div class="input-group hide" id="postSaleDiv-<?= $row['id']; ?>">

  <input id="amt-<?= $row['id']; ?>" class="input-group-field" type="number" style="width: 75px;float: left;" min="0">

    <input onclick="saleBtn('<?= $row['id']; ?>')" type="button" class="button" value="Submit" style="float:left;" >
 
</div>        
              <?php
              }
           }
              ?>
            </td>
            <td>
              <?php
        if($row['workshop'] == 'no data' || $row['workshop'] == '')
        {
          

        ?>
              <p>
                Interested in Workshop ?
              </p>
              <input name="wsp-<?= $id; ?>" type="radio" onclick="workshopAttend(<?= $id; ?>)"> Yes
<!--             <p>This person attended workshop ?</p>
<div class="switch round">
  <input  class="switch-input" id="yes-no" type="checkbox" name="exampleSwitch">
  <label class="switch-paddle" for="yes-no">
    <span class="show-for-sr">This person attended workshop ?</span>
    <span class="switch-active" aria-hidden="true">Yes</span>
    <span class="switch-inactive" aria-hidden="true">No</span>
  </label>
</div> -->
              
              <?php
                  }
        else
        {
          echo $row['workshop'];
        }
          ?>
            </td>
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
      Due Date (YYYY-MM-DD)
        <input type="text" name="dueDate" id="edueDate">
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
</body>

<script src="script.php?range=<?= $_GET['range']; ?>"></script>

</html>