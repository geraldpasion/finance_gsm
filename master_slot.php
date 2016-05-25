<?php
include 'page_header.php';
?>

<?php
echo "<form name=form1 id=form1 method=post>";
echo "<input type='hidden' id='status' name='status' value=''>";
if(!empty($_POST['status']))
{
    $sms_slot=$_POST['sms_slot'];
    $phone_number=$_POST['phone_number'];
    $select="select slot_id from sms_slot_file order by slot_id desc limit 1";
     $result = $conn->query($select);
   	$row = $result->fetch_assoc();
   	$slot_id=$row['slot_id']+1;
    $result=insertMaker('sms_slot_file',array('slot_id','sms_slot','phone_number'),array($slot_id,$sms_slot,$phone_number));
       echo "<script>alert('Successfully Added New SMS Slot');
       
       document.getElementById('form1').action = 'sms_slot_list.php';
            document.form1.submit();
    </script>";
       
}
//print_r($_POST);
if(!empty($_POST['update_btn']))
{
    $sms_slot=$_POST['sms_slot'];
    $phone_number=$_POST['phone_number'];
    $slot_id=$_POST['slot_id'];
    $result=updateMaker('sms_slot_file',array('sms_slot','phone_number'),array($sms_slot,$phone_number)," where slot_id='$slot_id'");
    //echo $result;
   echo "<script>alert('Successfully Updated New SMS Slot');
        document.getElementById('form1').action = 'sms_slot_list.php';
         //   document.form1.submit();
    </script>";
}

$trans_num="";
$sms_slot="smsc";
$phone_number="";
if(!empty($_REQUEST['trans_num']))
{
    $slot_id=$_REQUEST['trans_num'];
    $select="select sms_slot,phone_number from sms_slot_file where slot_id='$slot_id' limit 1";
    $result = $conn->query($select);
    $row = $result->fetch_assoc();
    $sms_slot=$row['sms_slot'];
    $phone_number=$row['phone_number'];
    echo "<input type='hidden' id='slot_id' name='slot_id' value='$slot_id'>";
}
?>

<table style='width:400px' class='form_css'>
    <tr>
        <th style='text-align:left'><h2>SMS Slot</h2></th>
    </tr>
    <?php
    echo textMaker('SMS Slot','sms_slot',$sms_slot);
    echo textMaker('Phone Number','phone_number',$phone_number);
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
            

		if(empty($_REQUEST['trans_num']))
            echo "<input type='button'  onclick='submit_btn_slot()'  name='submit_btn' value='Submit' style='margin:15px'>";
            else
            echo "<input type='submit' name='update_btn' value='Update' style='margin:15px'>";
            
            echo "<input type='button' onclick='cancel_btn()' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
	//echo "<input type='hidden' name='status'>";
    ?>
</table>

</form>
<script>
function submit_btn_slot()
{
	if($('#sms_slot').val()=='' ||$('#sms_slot').val()=='smsc')
	alert("Please Enter SMS Slot")
	else if($('#phone_number').val()=='')
	alert("Please enter a phone number")
	else
	{
			$('#status').val("Submit")
	 document.form1.submit();
	}
}
function cancel_btn()
{
 	document.getElementById('form1').action = 'sms_slot_list.php'
    document.form1.submit();
}

</script>