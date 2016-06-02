<?php
$range = $_GET['range'];
header('Content-Type: application/javascript');


?>
function workshopAttend(id)
{
  if(confirm('This person attended workshop ?'))
  {
    $.ajax({
url:'../ajax.php',
method:'post',
data:'workshop=yes&attended=yes&id='+id,
success: function(ret)
{
if(ret == '1')
{
window.location.reload();
}
}
})
  }
}

function editSale(id)
{

$('#postSaleDiv-'+id).toggleClass('hide');

}
function saleBtn(id)
{

var amount = $('#amt-'+id).val();
if(amount === '' || amount === null)
{
alert('Please enter sale amount first');
}
else
{

$.ajax({
url:'../ajax.php',
method:'post',
data:'putSales=true&amount='+amount+'&id='+id,
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
})

}

}
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
var dueDate = $('#due-'+id).html();
    var cmt = $('#cmt-'+id).val();
    var aprv = $('#aprv-'+id).val();
    var qual = $('#qual-'+id).val();
    
    // set values
    $('#edEmail').val(email);
    $('#edregDateP').val(regdate);
    $('#edMob').val(phn);
    $('#edcmnt').val(cmt);
$('#edueDate').val(dueDate);
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
  
  $(function() {

$('#yes-no').on('change', function(evt){
//console.log(evt); 
var values = $(this).val();
alert(values);
})


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
     
    
    
    $('#rangeSelector').change(function(){
      var optionSelected = $(this).find("option:selected");
      var valueSelected  = optionSelected.val();
      //       alert(valueSelected);
      window.location.href="http://test.mixorg.com/leads/qualified/?range="+valueSelected;
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
    if(confirm('Are you sure this person has been approved ?'))
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
    if(confirm('Are you sure this person has been disapproved ?'))
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
  
    function notConnectableBtn(id)
  {
    if(confirm('Are you sure this person is not contactable?'))
    {
      $.ajax({
        url:'../ajax.php',
        method:'get',
        data:'approve=nc&id='+id,
        success: function(ret)
        {
          if(ret == '1')
          {
            $('#status-'+id).html('Not Contactable');
            $('#tr-'+id).removeClass().addClass('disapprovedColor');
          }
        }
      })
    }

  }  
    function getUnresolved()
  {
     var range = '<?= $range; ?>';
$.ajax({
      url:'../ajax.php',
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
          var range = '<?=  $_GET['range']; ?>';
  
    $.when($.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=qualified&range='+range,
      success: function(ret)
      {
        //arr.push(parseInt(ret));
        $('qld').text(ret);
      }
    })).then($.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=unqualified&range='+range,
      success: function(ret)
      {
         //arr.push(parseInt(ret));
        $('uqld').text(ret);
      }
    })).then($.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=nc&range='+range,
      success:function(retnc)
      {
        console.log(retnc);
        $('nc').text(retnc);
      }
    })).then($.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=conv&range='+range,
      success: function(retconv)
      {
        $('conv').text(retconv);
      }
    })).then($.ajax({
      url:'../ajax.php',
      method:'get',
      data:'leads=sales&range='+range,
      success: function(ret)
      {
        $('tSale').text(ret+' /-');
      }
    }));
      

  
    }
  function toExcel()
  {
    $('#emailList').tableExport({type:'excel',escape:'false'});
  } 

        $.fn.tableExport.defaultButton = "button primary toRight small";

    $("#emailList").tableExport({
      formats: ["xlsx"],
      headings: true,
      fileName: "Motherbee_Leads",
      bootstrap: false,
      position: "top"
    });