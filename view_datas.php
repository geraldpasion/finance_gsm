<?php
include 'page_header.php';
$type=$_REQUEST['page_type'];
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<form action="file_upload.php"   name='form2' id='form2'  method="post" enctype="multipart/form-data">

 <div id='div_here' style='display:none;z-index:11;position:fixed;top:20%;left:32%;vertical-align:middle;border:1px solid black;background-color:white'>
    <?PHP 
               $html="<table align=center style='vertical-align:middle'>";
                
               $html.="<tr><td colspan=2 style='padding:25px;text-align:center'>";
             //   $html.="<input type='hidden' id='trans_num' name='trans_num' value='"+trans_num+"'>";
                $html.="Are you sure you want to Receive this transaction? Do you want to upload an image?</td></tr>";  
            $html.="<tr>";	
                $html.="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload' id='fileToUpload2' accept='image/*''></td>";
//	$html.="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload2' id='fileToUpload' accept='image/*''></td>";
            $html.="</tr>";
            $html.="<tr><td style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' value='Cancel' onclick='close_this()'> </td>";    
            $html.="<td  style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' onclick='fileUpload()' value='Submit'></td></tr>";
            $html.="</table>";
//
echo $html;

?>
</form>
<input type='hidden' name='trans_num' id='trans_num2'>
<input type='hidden' name='type' id='type2' >
   </div>
<style>
.approve
{
padding:10px;
margin:10px;
font-size:19px;
}
</style>
<?php
$result = $conn->query("select * from system_steps_file");

while($row=$result->fetch_assoc())
{
	$status_name[$row['step_count']]=$row['status_label'];
	$men="menu_id";
	if($type==1||$type=='Without PO')
	$men="menu_id_wo_po";
	if(!empty($access_menu[$row[$men]]))
	$button_name[$row['step_count']]=$row['button_name'];
	else
	$button_name[$row['step_count']]="";
	$menu_id[$row['step_count']]=$row[$men];
}
$menu_id['Rejected']="Rejected";
?>
<script>

function button_press(label,trans_num,type)
{
		if (type=='9')
        {
             $('#trans_num2').val(trans_num)
             $('#type2').val(type)
             
               html=""
               html+="<input type='hidden' id='type2' name='type' value='"+type+"'>"	
               html+="<table align=center style='vertical-align:middle'>"
                
                html+="<tr><td colspan=2 style='padding:25px;text-align:center'>"
                html+="<input type='hidden' id='trans_num' name='trans_num' value='"+trans_num+"'>";
                html+="Are you sure you want to Receive this transaction? Do you want to upload an image?</td></tr>";  
            html+="<tr>"
                html+="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload' id='fileToUpload2' accept='image/*''></td>";
            html+="</tr>"
            html+="<tr><td style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' value='Cancel' onclick='close_this()'> </td>"    
            html+="<td  style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' onclick='fileUpload()' value='Submit'></td></tr>";
            html+="</table>"
           //document.getElementById('div_here').style.display="block"
           //document.getElementById('whiteDiv').style.display="block"
           $('#div_here').show()
           $('#whiteDiv').show()
            
        }
        else if(type=='7')
        {
           document.getElementById('confirm_div').innerHTML="<input type='button' id='confirm' style='margin-right:5px' value='Confirm' onclick='confirm_pick_btn("+trans_num+")'><input style='margin-left:5px' type='button' id='Cancel' value='Cancel' onclick='cancel()'>"
          	  document.getElementById('confirm_div2').innerHTML="<input type='button' id='confirm' style='margin-right:5px' value='Confirm' onclick='confirm_pick_btn2("+trans_num+")'><input style='margin-left:5px' type='button' id='Cancel' value='Cancel' onclick='cancel2()'>"
          
          if(document.getElementById('payment_type').value!="Check")
           $('#getCashDetails').dialog('open')
          else
           $('#getCheckDetails').dialog('open')
        }
        else
        {
            if(confirm("Are you sure you want to "+label+"  this transaction?"))
            {
                url="xstatus=change_status&status="+type+"&trans_num="+trans_num
                loadXMLDoc('get_type.php?'+url,releadPage)
            }
        }
        
}
function releadPage(result) {
		//alert(result)
        type=document.getElementById('page_type').value
        document.getElementById('form1').action = 'view_data_combine.php?type='+type;
        document.form1.submit();
    }
 function close_this() {
        $('#div_here').hide()
        $('#whiteDiv').hide()
    }
function reloadPage(result) {
       
       document.form1.submit();
    }
    function hide_this(a)
    {
        document.getElementById('sms_row'+a).style.display='none'
        document.getElementById('2sms_row'+a).style.display='none'
    }
    function approve_ae_btn(status,trans_num)
    {
        if (confirm("Are you sure you want to Approve this transaction by Account Execeutive ?")) {
            url="xstatus=change_status&status="+status+"&trans_num="+trans_num
            loadXMLDoc('get_type.php?'+url,reloadPage)
            
            return false;
        }
    }
     function approve_QA_btn(status,trans_num)
    {
        if (confirm("Are you sure you want to Approve  this transaction	by QA?")) {
            
            url="xstatus=change_status&status="+status+"&trans_num="+trans_num
            loadXMLDoc('get_type.php?'+url,reloadPage)
            
            return false;
        }
    }
    function reloadPage(result)
    {
        page_type=document.getElementById('page_type').value
        window.location.assign('view_data_combine.php?type='+page_type);
    }
    function edit_btn(page_type,trans_num)
    {
        if(page_type=="Without PO")
            page_type="withoutpo"
            document.getElementById('form1').action = 'wo_po_form.php?type='+page_type+"&trans_num="+trans_num;
            document.form1.submit();
    }
      function confirm_pick_btn2(trans_num)
    {
    
        if(document.getElementById('name2').value==''||
        document.getElementById('title2').value=='')
            alert("Please Enter complete Details")
        else
        {
            name=document.getElementById('name2').value
            cv=""
            title=document.getElementById('title2').value
            url="xstatus=readyForPickUp&status=7&trans_num="+trans_num+"&name="+name+"&cv="+cv+"&title="+title
            loadXMLDoc('get_type.php?'+url,releadPage)
        }
        
    }
    function resend()
    {
    	url="xstatus=resend&trans_num="+trans_num
        loadXMLDoc('get_type.php?'+url,releadPage)
    
    }
    function fileUpload()
    {
    	if(document.getElementById('fileToUpload2').value=='')
            alert("Please Choose a File")
        else
        {
            document.getElementById('form2').action = 'file_upload.php';
           document.form2.submit();
        }
    }
    
    $(function() {
    
	    $( "#reject_div" ).dialog(
	    {
	    height:275,
	    modal: true,
	    width:380
	    
	    }
	    );
	    $("#reject_div").dialog( "close" );
	    
	    
	    $( "#getCheckDetails" ).dialog(
	    {
	    height:275,
	    modal: true,
	    width:380
	    
	    }
	    );
	    
	    $("#getCheckDetails").dialog( "close" );
	     $( "#getCashDetails" ).dialog(
	    {
	    height:275,
	    modal: true,
	    width:380
	    
	    }
	    );
	    
	    $("#getCashDetails").dialog( "close" );
	    
 });
  
     function confirm_pick_btn(trans_num)
    {
        if(document.getElementById('name').value==''||
        document.getElementById('cv').value==''||
        document.getElementById('title').value=='')
            alert("Please Enter complete Details")
        else
        {
            name=document.getElementById('name').value
            cv=document.getElementById('cv').value
            title=document.getElementById('title').value
            url="xstatus=readyForPickUp&status=7&trans_num="+trans_num+"&name="+name+"&cv="+cv+"&title="+title
            loadXMLDoc('get_type.php?'+url,releadPage)
        }
        
    }  function close_this() {
        $('#div_here').hide()
        $('#whiteDiv').hide()
    }
</script>
<?php
$trans_num=$_REQUEST['trans_num'];
if(empty($_SESSION['uname']))
    echo "<script>window.location.assign('login.php')</script>";
echo "<form name='form1' id='form1' method=post>";
?>	
<div id='getCheckDetails' style='display:none;z-index:11;border:1px solid black;background-color:white;padding:10px'>
    <table>
        <?php
        echo "<tr><th colspan=3 style='text-align:center;padding:10px' >Enter Check Details</th></tr>";
        echo textMaker('Title','title','');
        echo textMaker('Name of Check','name','');
        echo textMaker('CV#','cv','');
        echo "<tr><td colspan=2 id='confirm_div' STYLE='text-align:center;padding:10px'></td></tr>";
        ?>
    </table>
</div>

<div id='getCashDetails' style='display:none;z-index:11;border:1px solid black;background-color:white;padding:10px'>
    <table>
        <?php
        echo "<tr><th colspan=3 style='text-align:center;padding:10px' id='check_header'>Enter Cash Details</th></tr>";
        echo textMaker('Title','title2','');
        echo textMaker('Name ','name2','');
         echo "<tr><td colspan=2 id='confirm_div2' STYLE='text-align:center;padding:10px'></td></tr>";
        ?>
    </table>
</div>
<?php


echo "<input type='hidden' id='page_type' name='page_type' value='$type'>";
echo "<input type='hidden' id='trans_num' name='trans_num'  value='$trans_num'>";



$select="select * from po_file WHERE trans_no='$trans_num' LIMIT 1";
$select2="select * from po_item_file where trans_no='$trans_num'";
$head="";
if($type=="po_type"||$type=="With Po")
{
    $column=array('Letter Code', 'Requestor', 'Title', 'Company Name', 'Secretary','Engineer', 'Supplier', 'Payment Type', 'Status', 'JO', 'PO', 'Item Description','Date Created','Created By');
    $val=array('letter_code', 'requestor', 'title', 'company_name', 'secretary', 'engineer','supplier', 'payment_type',  'status', 'jo', 'po', 'item_description','date_created','created_by','status');
    $head="<h2>With Po</h2>";
}
else
{
    $column=array('Letter Code', 'Requestor', 'Title', 'Company Name', 'Secretary', 'Supplier', 'Payment Type', 'Status','TXT&ID_NO','Date Created','Created By');
    $val=array('letter_code', 'requestor', 'title', 'company_name', 'secretary', 'supplier', 'payment_type',  'status','TXT&ID_NO','date_created','created_by','status');
    $head="<h2>Without Po</h2>";
}

$result = $conn->query($select);
$result818 = $conn->query($select2);
if ($result->num_rows > 0)
{
    
   $row = $result->fetch_assoc();
   $requestor_id=$row['requestor'];
   $secretary=$row['secretary'];
   $engineer=$row['engineer'];
   
   
   $select="select concat(first_name,' ',last_name) as name from master_address_file where
    account_type='Secretary' and mas_status=1 and account_id='$secretary' limit 1";
     $result = $conn->query($select);
     $rows=$result->fetch_assoc();
     $secretary_name=$rows['name'];
     
     IF($engineer!="N/A")
     {
     	$select="select concat(first_name,' ',last_name) as name from master_address_file where
    	account_type='Engineer' and mas_status=1 and account_id='$engineer' limit 1";
     	$result = $conn->query($select);
     	$rows=$result->fetch_assoc();
     	$engineer_name=$rows['name'];
   }
   else
   $engineer_name="N/A";
   $select2="select phone_number,concat(first_name,' ',last_name) as requestor  from master_address_file where account_type='Account Executive' and mas_status=1 and account_id='$requestor_id' limit 1";
    $result2 = $conn->query($select2);
    $row3=$result2->fetch_assoc();
    $requestor=$row3['requestor'];
    $phone_number=$row3['phone_number'];
    if(!empty($_REQUEST['chat_box']))
    {
        $text="Letter Code ".$row['letter_code']."
Message:".$_REQUEST['chat_box'];
        $insert="insert into chat_history_file (remarks,user_name,chat_date,trans_no)
        values('".addslashes($_REQUEST['chat_box'])."','".$_SESSION['uname']."',now(),'".$trans_num."')";
        $conn->query($insert);
        
        sendText($text,$row3['phone_number'],1);
		
        //$text=urlencode($text);
        //$response = file_get_contents("http://127.0.0.1:13013/cgi-bin/sendsms?user=sms-app&pass=app125&text=$text&to=".$row3['phone_number']);
    }

   $trans_no=$row['trans_no'];
   if(!empty($_REQUEST['num']))
    {
        $num=$_REQUEST['num'];
        if(!empty($_REQUEST['sms_id']))
        {
            $sms_id=$_REQUEST['sms_id'];
            for($a=0;$a<count($sms_id);$a++)
            {
                $update="update sms_files set trans_no='".$trans_no."' where sms_id='".$sms_id[$a]."'";
                $conn->query($update);
            }
        }
    }
    echo "<table style='width:100%;border-collapse:collapse' >";
    echo "<td style='vertical-align:top'>";
    echo "<table style='border-collapse:collapse'>";
    echo "<tr><th colspan=2 style='text-align:left'>".$head."</th></tr>";
    
    $status=$row['status'];
     $ae_status=$row['ae_status'];
    for($a=0;$a<count($val)-1;$a++)
    {
        echo "<tr>";
            echo "<th style='text-align:left;padding-top:7px;padding-right:15px;'>".$column[$a]."</th><td>";
            if($val[$a]=='requestor')
            echo $requestor;
            else if($val[$a]=='secretary')
            echo $secretary_name;
            else if($val[$a]=='engineer' )
            echo $engineer_name;
            else if($val[$a]=='date_created')
            echo convert_to_dateTime($row[$val[$a]]);
			else if($val[$a]=='status')
			{
				$value=$row[$val[$a]];
				if($value==$for_qa_approval)
				$value=$qa_text;
				else if($value==$for_ae_approval)
				$value=$ae_text;
				//echo $value;	
				
				//$value=$row2['status'];
				if($status>=4 && $ae_status!=1)
				$value=4;
				if($value=='Received')
				echo "Received";
				else if (!empty($status_name[$value]))
				echo $status_name[$value];
				else
				echo $value;
				
			}
            else if($val[$a]!='')
            echo $row[$val[$a]];
               
            echo "</td>";
        echo "</tr>";
    }
    echo "<input type='hidden' id='payment_type' value='".$row['payment_type']."'>";
    $status=$row['status'];
    $ae_status=$row['ae_status'];
    //echo "<br>Status".$row['status'];
    if($result818->num_rows > 0)
    {
        echo "<tr><th colspan=2 style='text-align:left;font-size:25px;padding:10px'>Items</th></tr>";
        echo "<tr><td colspan=2><table border=1 style='border:none;border-collapse:collapse'>";
        echo "<thead style='display:block'>";
        echo "<tr>";
            echo "<th style='width:100px;padding:10px;'>Item</th>";
            echo "<th style='width:205px;padding:10px;'>Description</th>";
            echo "<th style='width:90px;padding:10px;'>Quantity</th>";
            echo "<th style='width:90px;padding:10px;'>Unit Price</th>";
             echo "<th style='width:90px;padding:10px;'>Amount</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody style='max-height:100px;overflow-y:auto;display:block;min-width:690px'>";
        $quantity=0;
        $amount=0;
        while($row2=$result818->fetch_assoc())
        {
            echo "<tr>";
                echo "<th style='width:100px;padding:10px;border:1px solid black;text-align:left'>".$row2['item']."</th>";
                echo "<th style='width:205px;padding:10px;text-align:left'>".$row2['description']."</th>";
                echo "<td style='width:90px;padding:10px;text-align:right'>".$row2['quantity']."</td>";
                 echo "<td style='width:90px;padding:10px;text-align:right'>".number_format($row2['unit_price'],2)."</td>";
                 echo "<td style='width:90px;padding:10px;text-align:right'>".number_format(($row2['unit_price']*$row2['quantity']),2)."</td>";
            echo "</tr>";
            $quantity+=$row2['quantity'];
        $amount+=$row2['unit_price'];
        }
        
        echo "</tbody>";
        echo "<tfoot style='display:block'>";
        echo "<tr>";
                echo "<th style='width:326px;padding:10px;border:1px solid black;text-align:left'></th>";
                echo "<td style='width:90px;padding:10px;text-align:right'>".$quantity."</td>";
                 echo "<td style='width:90px;padding:10px;text-align:right'>".number_format($amount,2)."</td>";
                 echo "<td style='width:90px;padding:10px;text-align:right'>".number_format(($amount*$quantity),2)."</td>";
            echo "</tr>";
        echo "</tfoot>";
        echo "</table></td>";
    }
  //  echo $row['created_by']."==".$_SESSION['uname'];
    if($status=='Receive Cash Request' || $status=="Received")
    {
        $select="select name,title,cv from po_check_file where trans_no='$trans_num' limit 1";
        $result = $conn->query($select);
        //echo $select;
        while($row2=$result->fetch_assoc())
        {
			$t="Check";
			if($row2['cv']=='')
			$t="Cash";
            echo "<tr><td colspan=5><table>";
            echo "<tr><th colspan=2 style='padding-top:10px;font-size:19px'>$t Details</th></tr>";
            echo "<tr><th style='width:50px;text-align:left'>Name</th><td>name".$row2['name']."</td></tr></tr>";
            echo "<tr><th style='width:50px;text-align:left'>Title</th><td>".$row2['title']."</td></tr>";
	        if( $row2['cv']!='')    
			echo "<tr><th style='width:50px;text-align:left'>CV</th><td>".$row2['cv']."</td></tr>";
            echo "</table></td></tr>";
        }
    }
    
    $select="select remarks,date_created,rejected_by,status from po_remarks_file where trans_no='$trans_num' limit 1";
    $result2 = $conn->query($select);
    $rowcount=mysqli_num_rows($result2);
    if($rowcount>0)
    {
        echo "<tr><td colspan=5><table>";
            echo "<tr><th colspan=2 style='padding-top:10px;padding-bottom:10px;font-size:19px;text-align:left'>Rejection History</th></tr>";
        while($row2=$result2->fetch_assoc())
        {
            echo "<tr><th style='width:115px;text-align:left'>Rejected By</th><td>".$row2['rejected_by']."</td></tr>";
            echo "<tr><th style='width:115px;text-align:left'>Date Rejected</th><td>".$row2['date_created']."</td></tr>";
            echo "<tr><th style='width:115px;text-align:left'>Status</th><td>";
		
			$value=$row2['status'];
			
			echo $value."</td></tr>";
            echo "<tr><th style='width:115px;text-align:left'>Remarks</th><td>name".$row2['remarks']."</td></tr></tr>";
           
           
        }
         echo "</table></td></tr>";
    }
    
    //$access[$for_qa_page]
    //For QA Approval
    echo "<tr><td colspan=2 style='text-align:center;padding:20px'>";
    
    
    	$temp_status=$status;
		if($status>=4 &&!empty($access_menu[$menu_id[4]])&&$ae_status!=1)
		{
			echo "<input type='button' class='approve' value='".$button_name[4]."'onclick='button_press(this.value,".$trans_num.",4)'>";
			$temp_status=4;
			
		}
		if(empty($menu_id[$status]))
			$menu_id[$status]="";
		
		
		if($status==4)
		$status=$status+1;
    	if($status=='QA Approve' &&!empty($access_menu[$menu_id[2]]))
		echo "<input type='button' class='approve' onclick='button_press(this.value,".$trans_num.",2)' value='".$button_name[2]."'>";	
		else if($status=='pending')
		echo "";
		else if(!empty($access_menu[$menu_id[$status]]))
			echo "<input type='button' class='approve'  onclick='button_press(this.value,".$trans_num.",\"".$status."\")' value='".$button_name[$status]."'>";
		else
		echo "";
		/*if(!empty($access_menu[$menu_id[$status]]))
			echo "<input type='button' class='approve'  onclick='button_press(this.value,".$trans_num.",\"".$status."\")' value='".$button_name[$status]."'>";
		*/
		 if(!empty($access_menu[$menu_id[4]]) && $ae_status==2)
			echo "<input type='button' class='approve'  onclick='resend(this.value,".$trans_num.",3)' value='Resend SMS'>";
		
	echo "</td></tr>";
    /*unset($access[$for_qa_page]);
    if($type=='With Po' &&!empty($_SESSION['access_type']['RM with PO']['Approve/Reject sending to AE']))
		$access[$for_qa_page]="Yes";
	else if (!empty($_SESSION['access_type']['RM without PO']['Approve/Reject sending to AE']))
	$access[$for_qa_page]="Yes";

    if(($status=='pending'||$row['mas_status']!=1) && $row['created_by']==$_SESSION['uname'] )
    echo "<tr><td colspan=2 style='text-align:center;padding-top:10px'><input onclick='edit_btn(\"".$type."\",".$trans_num.")' style='padding:10px;height:50px' type='button' value='Edit'></td></tr>";
    else if($status==$for_qa_approval && !empty($access[$for_qa_page]))
    echo "<tr><td colspan=2 style='text-align:center;padding-top:10px'><input onclick='approve_QA_btn(\"$for_qa_approval\",".$trans_num.")' style='font-size:14px;padding:25px;height:50px' type='button' value='$qa_approve_btn'></td></tr>";
    else if($status==$for_ae_approval&& !empty($access[$for_ae_page]))
    echo "<tr><td colspan=2 style='text-align:center;padding-top:10px'><input onclick='approve_ae_btn(\"$for_ae_approval\",".$trans_num.")' style='font-size:14px;padding:25px;height:50px' type='button' value='$ae_approve_btn'></td></tr>";
    */
    
    if($status!='pending')
    {
        echo "<tr><th colspan=2><h2>Chat Box</h2> </th></tr>";
        echo "<tr><td colspan=2>
        <textarea id='chat_box' name='chat_box' style='width:100%;height:100px'></textarea>
        </td></tr>";
        echo "<tr><td colspan=2 style='text-align:right;padding:10px'><input type='submit' value='Submit'></td>";
        $select="select remarks,date,received from ((select sms as remarks,date_sent as date,received from sms_files where trans_no='".$trans_num."' ) UNION ALL
        (select remarks ,chat_date as date,user_name as received     from chat_history_file where trans_no='".$trans_num."')) as s order by date desc ";
        //echo $select;
        $result = $conn->query($select);
         while($row=$result->fetch_assoc())
        {
            echo "<tr><td colspan=3 style='padding:0px;' ><table style='border:1px solid black;width:400px;border-collapse:collapse'>";
            echo "<tr>";
                echo "<td style='width:100px;text-align:left;padding:5px'><b>Sender:</b></td><td> ".$row['received']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td style='text-align:left;;padding:5px'><b>Remarks:</b> </td><td> ".$row['remarks']."</td>";
            echo "</tr>";
            echo "<tr>";
                echo "<td colspan=2 style='padding:5px;text-align:left'>".convert_to_dateTime($row['date'])."</td>";
            echo "</tr>";
            echo "</table></td></tr>";
        }
        
        echo "</table>";
        echo "</td>";
    
        $select="select * from sms_files where hide!=1 and trans_no='0' and (received='$phone_number' ) order by date_sent desc ";
        $result = $conn->query($select);
        if($result->num_rows > 0)
        {
            echo "<td style='vertical-align:top'>";
                echo "<h2>SMS List</h2>";
                    echo "<table  style='border-collapse:collapse'>";
                    echo "<tbody style='border:1px solid black;display:block;height:400px;overflow:auto;min-width:300px'>";
                    $a=0;
                    while($row=$result->fetch_assoc())
                    {
                       // echo "<tr><th>".$row['received']."</th></tr>";
                      //  echo "<tr><th style='width:60px;padding-left:10px;padding-top:8px;padding-bottom:10px;text-align:left'>Sender</th>   <td style='padding-left:10px;padding-top:8px;padding-bottom:10px;text-align:left'>".$row['received']."</th></tr>";
                        echo "<tr  id='sms_row".$a."'  style='vertical-align: middle;display:block' name='sms_row".$a."'>";
                            echo "<td style='width:60px;padding-left:10px;padding-top:8px;padding-bottom:10px;text-align:right;vertical-align: middle;'>
                            <input type='checkbox' id='sms_id$a' name='sms_id[] ' value='".$row['sms_id']."' style='text-align:right;width:50px;height:30px' ></td>";
                            echo "<td style='padding-left:5px;border:1px solid black;min-width:250px;min-height:190px;vertical-align: middle;'>".$row['sms']."</td>";
                        echo "</tr>"; 
                        echo "<tr id='sms_row".$a."' name='sms_row".$a."'>";
                            echo "<td colspan=2 style='padding-left:10px;text-align:left'>".convert_to_dateTime($row['date_sent']);
                            echo "<input type='button' style='border:none;background-color:transparent;margin:10px;textdecoration:underliner;' onclick='hide_this($a)' value='Hide'>";
                            echo "</td>";
                        echo "</tr>";
                        $a++;
                    }
                  //  echo "<input type='hidden' id='num' name='num' value='$a'>";
                    echo "</tbody>";
                    echo "<tr>";
                        echo "<td colspan=2 style='text-align:center'><input type='hidden' id='num' name='num' value='$a'>
                        <input style='width:100px;padding:5px;height:20px' type='submit' name='match_submit' value='Match' ></td>";
                    echo "</tr>";
                    echo "</table>";
                echo "</td>";
        }
        else
            echo "<td><h2>No Data Found</h2></td>";
            echo "</tr>";
    }    
    echo "</table>";
}
?>
</form>