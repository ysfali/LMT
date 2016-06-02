<?php
session_start();

if(!isset($_SESSION['login'])) header('Location: /leads/login.php');

if($_SESSION['login'] === 'admin') header('Location: /leads/admin/');
if($_SESSION['login'] == 'motherbee') header('Location: /leads/qualified/?range=4');

if(!isset($_GET['range']))
{
  header('Location: /leads/?range=4');
}
// $clientId = 8;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Client Dashboard</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/foundation/6.2.0/foundation.min.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<!--     <script type="text/javascript" src="tableExport.js"></script>
    <script type="text/javascript" src="jquery.base64.js"></script>
    <script type="text/javascript" src="html2canvas.js"></script> -->
  </head>
  <body>


    <?php
    $link = mysqli_connect("localhost","root","","motherbeelanding");//mysql_connect("localhost","motherbeelanding","iBohna7n!",true);
    //mysql_select_db("motherbeelanding");
    //mysql_query("SET timezone = '+5:30'");
    $query="SHOW COLUMNS FROM `services_leads` LIKE 'comment'";
    $result=mysqli_query($link, $query);
    $row=mysqli_fetch_array($result);
    if(!isset($row)) {
      // do your stuff
      $insCol = "ALTER TABLE services_leads ADD COLUMN `comment` VARCHAR(1000) DEFAULT 'No Comments',ADD COLUMN `status` VARCHAR(100) DEFAULT 'unchecked'";
      $q = mysqli_query($link,$insCol);

    }
    $range = $_GET['range'];
    switch($range)
    {
      case '0': 
        {
          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,weeks from services_leads WHERE DATE(registered_date) = DATE(DATE_SUB(NOW(), INTERVAL 1 DAY)) group by id,email order by registered_date desc";
          break;
        }
      case '1':
        {
          $lastWeek = date('Y-m-d H:i:s',time()-(7*86400)); // 7 days ago

          // $sql = "SELECT * FROM table WHERE date <='$date' ";
          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,weeks,utm_mediumfrom services_leads where registered_date between date_sub(now(),INTERVAL 1 WEEK) and now() order by registered_date desc";
          break;
        }
      case '2':
        {


          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,weeks,utm_medium from services_leads WHERE registered_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() group by id,email order by registered_date desc";
          break;

        }
      case '4':
        {
                $getMail = "select id,email,name,phone,ref,registered_date,comment,status,weeks,utm_medium from services_leads WHERE DATE(registered_date) = CURDATE() group by id,email order by registered_date desc";
          break;
        }
      default: 
        {
          $getMail = "select id,email,name,phone,ref,registered_date,comment,status,weeks,utm_medium from services_leads group by id,email order by registered_date desc";
          break;
        }
    }

    $gtm = mysqli_query($link,$getMail);

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
              <th>Due Date</th>
              <th width="30%">Status</th>
              <th width="40%">Comments</th>
            </tr>
          </thead>
          <tbody>

            <?php
      while($row = mysql_fetch_assoc($gtm))
      {
        $id = $row['id'];
        $current_param = $row['ref'];
        parse_str($current_param, $result);
        
        $parts = parse_url($current_param);
parse_str($parts['query'], $query);
// echo $query['email'];
        
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
              <?php if($query['utm_medium'] == '') 
        {
          if($row['utm_medium'] == NULL || $row['utm_medium'] == '')
          {
          echo 'Direct';
            
          }
          else echo $row['utm_medium'];
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
            <td><?= $row['weeks']; ?></td>

            <td width="30%" id="status-<?= $id; ?>">

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
            <td width="40%"><?php if($row['comment'] == 'No Comments')  {
              ?>
              <button class="small primary button" onclick="sendComment('<?= $id; ?>','<?= $row['phone']; ?>');">
                COMMENT
              </button>
              <button class="small primary button" onclick="editData('<?= $id; ?>')">
                EDIT
              </button>
              
                            
              <?php
              } 
        else
              { 
                echo '<div class="cmnt" id="cm-'.$id.'">'.$row['comment'].'</div> <br><button class="small primary button" onclick="editData(\''.$id.'\')">EDIT</button>';  
        } ?>
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
<style>
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
</style>
<!-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.11/css/dataTables.foundation.min.css">
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.11/js/dataTables.foundation.min.js"></script> -->
<script>
  function changecmnt(id)
  {
    alert(id);
    $('#ta-'+id).attr('disabled','true');
  }
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
  $(function() {
    
    $.when( fetchStats()).then(getUnresolved());
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
//     $('.cmnt').dblclick(function(){
//      var text =  $(this).html();
//       var str = $(this).attr('id');
//       var idarr = str.split("-");
//       var id = idarr[1];
//       $(this).html('<textarea id="ta-'+id+'" onblur="changecmnt('+id+')" style="box-shadow: 0 0 30px #2d2d2d;border-radius: 5px;height: 90px;">'+text+'</textarea>');
//     })
    
           $('#edLdFrmbtn').on('click', function(){
      var edt = $('#edLdFrm').serialize();
             
      $.ajax({
        url:'ajax.php',
        method:'post',
        data: edt,
        success: function(ret)
        {
          if(ret == '1') window.location.reload();
          else console.log(ret);
        }
      })
    })
     
    
    
    $('#rangeSelector').change(function(){
      var optionSelected = $(this).find("option:selected");
      var valueSelected  = optionSelected.val();
      //       alert(valueSelected);
      window.location.href="http://test.mixorg.com/leads/?range="+valueSelected;
    })

    

 
    
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

    $(this).bind("contextmenu cut copy", function(e) { 
      e.preventDefault();
      alert('Copying is not allowed');
    });
  }); 

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
        url:'ajax.php',
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
    if(confirm('Are you sure this person has been approved ?'))
    {
      $.ajax({
        url:'ajax.php',
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
    if(confirm('Are you sure this person has been disapproved ?'))
    {
      $.ajax({
        url:'ajax.php',
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
  
    function notConnectableBtn(id)
  {
    if(confirm('Are you sure this person is not contactable?'))
    {
      $.ajax({
        url:'ajax.php',
        method:'get',
        data:'approve=nc&id='+id,
        success: function(ret)
        {
          if(ret == '1')
          {
            $('#status-'+id).html('Not Contactable');
            $('#tr-'+id).removeClass().addClass('ncColor');
          }
        }
      })
    }

  }  
    function getUnresolved()
  {
     var range = '<? echo $_GET['range']; ?>';
$.ajax({
      url:'ajax.php',
      method:'get',
      data:'leads=unchecked&range='+range,
      success: function(total)
      {
        //arr.push(parseInt(ret));
           $('unr').html(total);
      }
    })

  }
   var arr = [];  
   function fetchStats()
    {
          var range = '<? echo $_GET['range']; ?>';
  
    $.when($.ajax({
      url:'ajax.php',
      method:'get',
      data:'leads=qualified&range='+range,
      success: function(ret)
      {
        //arr.push(parseInt(ret));
        $('qld').html(ret);
      }
    })).then($.ajax({
      url:'ajax.php',
      method:'get',
      data:'leads=unqualified&range='+range,
      success: function(ret)
      {
         //arr.push(parseInt(ret));
        $('uqld').html(ret);
      }
    })).then($.ajax({
      url:'ajax.php',
      method:'get',
      data:'leads=nc&range='+range,
      success:function(retnc)
      {
        console.log(retnc);
        $('nc').html(retnc);
      }
    }))
      

  
    }
  function toExcel()
  {
    $('#emailList').tableExport({type:'excel',escape:'false'});
  } 
</script>
</html>