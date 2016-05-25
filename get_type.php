<?php
session_start();
include 'string.php';
include 'connect.php';
include 'functions.php';
if($_REQUEST['xstatus']=='getRequestor')
{
    $requestor=$_REQUEST['requestor'];
    $engineer=array();
   $select="select account_id , concat(first_name,' ',last_name) as engineer from master_address_file
    where mas_status=1 and account_type='engineer' and account_executive_id='$requestor' order by engineer";
    $result = $conn->query($select);
    echo "<option>Choose</option>";
    while($row=$result->fetch_assoc())
        echo "<option value='".$row['account_id']."'>".$row['engineer']."</option>";
    echo "~";
    $secretary=array();
    $select="select account_id , concat(first_name,' ',last_name) as secretary from master_address_file
    where mas_status=1 and account_type='secretary' and account_executive_id='$requestor' order by secretary";
    $result = $conn->query($select);
    echo "<option>Choose</option>";
    while($row=$result->fetch_assoc())
        echo "<option value='".$row['account_id']."'>".$row['secretary']."</option>";
}
else if($_REQUEST['xstatus']=='readyForPickUp')
{
    $trans_num=$_REQUEST['trans_num'];
    $status=$_REQUEST['status'];
    $trans_num=$_REQUEST['trans_num'];
    $name=$_REQUEST['name'];
    $cv=$_REQUEST['cv'];
    $title=$_REQUEST['title'];
    $date_set="";
    if($status==6)
    {
    	$date_set=",release_date=now()";
    	$new_status="Receive Cash Request";
    }
    $select="select ae_status,status from po_file where trans_no='$trans_num' limit 1";
  	$result1 = $conn->query($select);
   	$row1=$result1->fetch_assoc();
   	$ae_status=$row1['ae_status'];
    if($ae_status==1)
    $status=7;
    
    $insert="insert into po_check_file (`trans_no`,`name`,`title`,`cv`,`date_created`,`created_by`) value
    ('$trans_num','".addslashes($name)."','".addslashes($title)."','".addslashes($cv)."',now(),'".addslashes($_SESSION['uname'])."')";
    $conn->query($insert);
    $update="update po_file set status='".($status+1)."' $date_set where trans_no='$trans_num'";
    $conn->query($update);
    echo $update." ".$for_qa_approval." ".$status;
}
else if($_REQUEST['xstatus']=='checkPO')
{
	$po=$_REQUEST['PO'];
	$select="select id from po_file where po='$po' limit 1";
	$result1 = $conn->query($select);
	$a="";
   	while($row=$result1->fetch_assoc())
   	$a="PO# already entered";
   	echo trim($a);
}
else if ($_REQUEST['xstatus']=='resend')
{
	$trans_num=$_REQUEST['trans_num'];
	formulateText($trans_num);
}
else if($_REQUEST['xstatus']=='change_status')
{
    $trans_num=$_REQUEST['trans_num'];
    $status=$_REQUEST['status'];

    //'Request for Cash Release','Ready for pick up','Receive Cash Request'
    $new_status="";
    if($status==$for_qa_approval)
    $new_status=$for_ae_approval;
    else if($status==$for_ae_approval)
    $new_status="Request Release";
    else if($status==$request_release_btn)
    $new_status="Ready for pick up";
    else if($status=='Ready for pick up')
    $new_status="Receive Cash Request";
    else if($status=='Receive Cash Request')
    $new_status="Received";
    else
    $new_status=$status;
    
    
    $date_set="";
	//$select="select * from system_steps_file where step_count='".($status+1)."' limit 1";
	$filter="";
	if($status==3)
	$filter=",ae_status='2'";
	if($status==4)
	{
		$filter=",ae_status='1'";
		$select="select ae_status,status from po_file where trans_no='$trans_num' limit 1";
  		$result1 = $conn->query($select);
   		$row1=$result1->fetch_assoc();
   		
   		$ae_status=$row1['ae_status'];
   		if($ae_status==2)
   		{
   			$filter=",ae_status='1'";
   			$status=$row1['status']-1;
   			//if($status==$for_qa_approval)
    			$date_set=",approved_date=now()";
   		}
   		
	}
    $update="update po_file set status='".($status+1)."' ".$date_set." $filter where trans_no='$trans_num'";
echo $update;
    $conn->query($update);
    
    $insert="insert into history_file(remarks ) values('".addslashes($update)."')";
    $conn->query($insert);
		if($status==3)
    	formulateText($trans_num);
}
else if($_REQUEST['xstatus']=='send_message')
{
	$phone_number=$_REQUEST['phone_number'];
	$text=$_REQUEST['message'];
	$trans_no=$_REQUEST['trans_no'];
	$sms_id=$_REQUEST['smsc_id'];
 	$insert="insert into sms_files(sms_id,received,sms,date_sent,trans_no) values('$sms_id',$phone_number,'".addslashes($text)."',now(),'$trans_no')";
    $conn->query($insert);
    echo $insert;
    
}
?>