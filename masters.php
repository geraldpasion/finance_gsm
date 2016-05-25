<?php
include 'page_header.php';
?>

  <script type="text/javascript" src="jquery/combo.js"></script>
  <style>
.custom-combobox {
position: relative;
display: inline-block;
}

.custom-combobox-toggle {
position: absolute;
top: 0;
bottom: 0;
margin-left: -1px;
padding: 0;
/* support: IE7 */
*height: 0.1em;
*top: 0.1em;
width:25px;
}
.custom-combobox-input {
margin: 0;
padding: 0.1em;
width:105px;
height:20px;
font-size:12px;

}

</style>
<script>
$(function() {
//$( "#combobox" ).combobox();
  	  $( "#department" ).combobox({ 
        select: function (event, ui) { 
            if($( "#department" ).val()=="Others")
            $('.others_div').show()
            else
            {
            	$('#others').val("")
            	$('.others_div').hide()
            }
        } 
    }
);
  });
  
function change_info(type)
{
     if (type=='Secretary' ||type=='Engineer') 
            $('.account_exe').show();
        else
            $('.account_exe').hide()
     //if (type=='Account Executive' ) 
     //       $('.dept_div').show()
    //    else
    //        $('.dept_div').hide()
    if (type=='Others' ) 
            $('.other_pos_div').show()
        else
        {
        	$('#other_pos').val("")
            $('.other_pos_div').hide()
        }
}
function submit_button()
{
	type=$('#account_type').val()
	first_name=$('#first_name').val()
    last_name=$('#last_name').val()
    department=$('#department').val()
    account_executive=$('#account_executive').val()
    phone_number=$('#phone_number').val()
    others=$('#others').val()
    if ((type!='Account Executive'&&type!='Admin' && type!='Cash Release') &&(account_executive==''||account_executive=='Select'))
           alert("Please enter Account Executive")
     else if (type=='Account Executive' &&(department==''||department=='Select')) 
           alert("Please enter department")
     else if (type=='Account Executive' &&(others==''&&department=='Others')) 
     alert("Please enter new department")
      else if(first_name=='')
       alert("Please enter First Name")
    else if(last_name=='')
     alert("Please enter Last Name")
    else if(phone_number=='')
       alert("Please enter Phone Number")
    else if (type=='Select'||type=='')
    alert("Please enter account type")
    else
	{
	$('#status').val("Submit")
	 document.form1.submit();
	}

}

function show_user_name(checked)
{
	if(checked)
	{
		$('.user_div').show()
		user_name=$('#first_name').val().substring(0, 1)+$('#last_name').val();
		$('#user_name').val(user_name)
	}
	else
	{
		$('#user_name').val("")
		$('.user_div').hide()
	}
}
</script>
<?php
//$addType=$_REQUEST['add_type'];
//$title=get_title($addType);

echo "<form name=form1 id=form1 method=post>";
echo "<input type='hidden' id='status' name='status'>";
$trans_no="";
if(!empty($_POST['update_btn']))
{
     $account_id=$_REQUEST['trans_num'];
    $type=$_POST['account_type'];
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $department=$_POST['department'];
    $account_executive=$_POST['account_executive'];
    $phone_number=$_POST['phone_number'];
    
    $sms_id=$_POST['sms_id'];
    $user_name=$_POST['user_name'];
    
   $select="select user_name,sms_slot from master_address_file where account_id='$account_id' limit 1";
     $result = $conn->query($select);
     $row=$result->fetch_assoc();
    $user_id=$row['user_name'];
    $sms_slot=$row['sms_slot'];
    
    if($user_id==0 &&$user_name!='')
    {
    	$select="select user_id from user_file order by user_id desc limit 1";
        $result = $conn->query($select);
    	$row=$result->fetch_assoc();
   		$user_id=(int)$row['user_id']+1 ;      
   		$result=insertMaker('user_file',array('user_id','user_name','password','first_name','last_name','user_type','department','finance_head','phone_number'),array($user_id,$user_name,'password123',$first_name,$last_name,$type,$department,$account_executive,$phone_number));	
    }
    if($user_id!=0 &&$user_name=='')
    	updateMaker('user_file',array('mas_status'),array('0'),"where user_id='$user_id'");
    else if($user_id!=0 &&$user_name!='' )
    {
    	$select="select user_name from user_file where user_id='$user_id' limit 1";
    	$result = $conn->query($select);
    	$row=$result->fetch_assoc();
    	if($user_name!=$row['user_name'])
    		updateMaker('user_file',array('user_name'),array($user_name),"where user_id='$user_id'");
    }
    
    
    
	$add_array=array('account_type','first_name','last_name','department_id','account_executive_id','phone_number','date_created','sms_slot','user_name');
    $value_array=array($type,$first_name,$last_name,$department,$account_executive,$phone_number,'now()',$sms_slot,$user_id);
 //   $result= updateMaker('po_item_file',array('item','description','quantity','unit_price'),array($item,$description,$quantity,$unit_price), "where id='$id' and trans_no='$trans_num' ");
               
    $result=updateMaker('master_address_file',$add_array,$value_array,"where account_id='$account_id'");
    echo "<script>alert('Successfully Update $type');
    document.getElementById('form1').action = 'list_view.php'
            document.form1.submit();
                                             
    </script>";
}
$type="";
    $first_name="";
    $last_name="";
    $department="";
    $account_executive="";
    $phone_number="";
    $account_id="";

if(!empty($_REQUEST['trans_num']))
{
     $account_id=$_REQUEST['trans_num'];
     
    $select="select * from master_address_file where account_id='$account_id' limit 1";
     $result = $conn->query($select);
     $row=$result->fetch_assoc();
    $type=$row['account_type'];
    $first_name=$row['first_name'];
    $last_name=$row['last_name'];
    $department=$row['department_id'];
    $account_executive=$row['account_executive_id'];
    $phone_number=$row['phone_number'];
    $sms_id=$row['sms_slot'];
    $user_name=$row['user_name'];
    
    if($user_name!='')
    {
	    $select="select user_name from user_file where user_id='$user_name' limit 1";
	     $result2 = $conn->query($select);
     	$row2=$result2->fetch_assoc();
     	$user_name=$row2['user_name'];
    }
    /*if($sms_id!='')
    {
    	$select="select sms_slot from sms_slot_file where slot_id='$sms_id' limit 1";
    	  $result2 = $conn->query($select);
     	$row2=$result2->fetch_assoc();
     	$sms_slot=$row2['sms_slot'];
    }*/
    
    echo "<input type='hidden' name='trans_num' value='$account_id'>";
}

if(!empty($_POST['status']))
{
    $type=$_POST['account_type'];
    $id=getId('master_address_file','account_id');
    $first_name=$_POST['first_name'];
    $last_name=$_POST['last_name'];
    $department=$_POST['department'];
    $account_executive=$_POST['account_executive'];
    $phone_number=$_POST['phone_number'];
    $others=$_POST['others'];
    
    $sms_slot=$_POST['sms_slot'];
    $user_name=$_POST['user_name'];
	$other_pos=$_POST['other_pos'];
	if($account_executive=="Others")
		$account_executive="";
    if($type=='Others')
	{
		$result=insertMaker('master_position_file',array('account_type','added_by',date_added),array($other_pos,$_SESSION['uname'],'now()'));
		$type=$other_pos;
	}
    if($sms_slot!='' &&$sms_slot!='N/A')
    {
    	$update="update sms_slot_file set account_id='$id' where slot_id='$sms_slot' and account_id=0";
    	$conn->query($update);
    }
    
    $user_id=0;
    if($user_name!='')
    {
    	$select="select user_id from user_file order by user_id desc limit 1";
        $result = $conn->query($select);
        $row=$result->fetch_assoc();
        $user_id=(int)$row['user_id']+1 ;      
        $result=insertMaker('user_file',array('user_id','user_name','password','first_name','last_name','user_type','department','finance_head','phone_number'),array($user_id,$user_name,'password123',$first_name,$last_name,$type,$department,$account_executive,$phone_number));
    }
    
    $add_array=array('account_type',"account_id",'first_name','last_name','department_id','account_executive_id','phone_number','date_created','sms_slot','user_name','created_by');
    $value_array=array($type,$id,$first_name,$last_name,$department,$account_executive,$phone_number,'now()',$sms_slot,$user_id,$_SESSION['uname']);
    
	if($department=='Others')
	{
   	   $select="select department_id,mas_status from master_department_file where department='".addslashes($others)."' limit 1";
       $result = $conn->query($select);
       $row=$result->fetch_assoc();
		if(mysql_affected_rows()>0)
		{
			if($row['mas_status']==0)
			{
				$select="update master_department_file set mas_status=1 where department_id='".$row['department_id']."' limit 1";
       			$conn->query($select);
			}
			$department=$row['department_id'];
		}
		else
		{
			$department=getId('master_department_file','department_id','added_by');
			$result=insertMaker('master_department_file',array('department_id','department'),array($department,$others,$_SESSION['uname']));
		}
	}
	
        

    $result=insertMaker('master_address_file',$add_array,$value_array);
   echo "<script>alert('Successfully Added New $type');
    document.getElementById('form1').action = 'list_view.php'
           document.form1.submit();
    
    </script>";

}
?>
<table style='width:500px' class='form_css'>
    <tr>
        <th colspan=2 style='text-align:left'><h2>Contact List</h2></th>
    </tr>
    <?php
	$select="select account_type from master_position_file where mas_status=1 order by account_type";
    $result = $conn->query($select);
    $account_array=array();
    while($row=$result->fetch_assoc())
        $account_array[]=$row['account_type'];
            $account_array[]="Others";
    echo selectMaker('Position','account_type',$account_array,"change_info(this.value)",$type);
	$others_select=textMaker('New Position','other_pos');
         $others_select=str_replace("<td>","<td class='other_pos_div' style='display:none'>",$others_select);
		
		 $others_select=str_replace("<th style='text-align:left'>","<th class='other_pos_div' style='display:none;text-align:left'>",$others_select);
		echo $others_select; 	   	
    echo textMaker('First Name','first_name',$first_name);
    echo textMaker('Last Name','last_name',$last_name);
    echo textMaker('Phone Number','phone_number',$phone_number);
    
        $finance_head=array();
        $select="select account_id , concat(first_name,' ',last_name) as account_executive from master_address_file
    where mas_status=1 and account_type='account executive' order by account_executive";
        $result = $conn->query($select);
        $finance_head=array();
        $finance_value=array();
        
        while($row=$result->fetch_assoc())
        {
            $finance_head[]=$row['account_executive'];
            $finance_value[]=$row['account_id'];
        }
        
        
        $display_account="display:none;";
        //$display_dep="display:none";
        
        if ($type=='Secretary' ||$type=='Engineer') 
            $display_account="display:blocks";
        
        if ($type=='Account Executive' ) 
             $display_dep="display:block;";
        
        /*
        echo "<tr><td colspan=2><table id='account_exe' style='width:90%;".$display_account."'>";
        echo selectMakerValue('Account Executive','account_executive',$finance_head,'',$finance_value,$account_executive);
        echo "</table></td></tr>";*/
        $account_select= selectMakerValue('Account Executive','account_executive',$finance_head,'',$finance_value,$account_executive);
         $account_select=str_replace("<td>","<td class='account_exe' style='".$display_account."'>",$account_select);
		
		$account_select=str_replace("<th style='text-align:left'>","<th class='account_exe' style='text-align:left;".$display_account."'>",$account_select);
        echo $account_select;
        
        
        $select="select department,department_id from master_department_file where mas_status=1 order by department";
        $result = $conn->query($select);
        $departments=array();
        while($row=$result->fetch_assoc())
            $departments[]=array($row['department'],$row['department_id']);
        $departments[]=array("Others","Others");
        //echo "<tr><td colspan=2 style='text-align:right'>";
		//<table id='dept_div'  style='width:98%;".$display_dep."'>";
         $dep_select=selectMakerArray('Department','department',$departments,$department);
         $dep_select=str_replace("<td>","<td class='dept_div' >",$dep_select);
		 $dep_select=str_replace("<th style='text-align:left'>","<th class='dept_div' style='text-align:left'>",$dep_select);
		
 		echo $dep_select;
 		
 		$select="SELECT sms_slot,slot_id FROM `sms_slot_file` where account_id=0 or account_id='$account_id'";
 		$result = $conn->query($select);
 		$sms_slot_array=array();
        while($row=$result->fetch_assoc())
            $sms_slot_array[]=array($row['sms_slot'],$row['slot_id']);
       	$others_select=textMaker('Others','others');
         $others_select=str_replace("<td>","<td class='others_div' style='display:none'>",$others_select);
		
		 $others_select=str_replace("<th style='text-align:left'>","<th class='others_div' style='display:none;text-align:left'>",$others_select);
		echo $others_select; 
		echo selectMakerArray('SMS Slot','sms_slot',$sms_slot_array,$sms_id,"N/A");
		$checked="";
		$div="display:none;";
		if($user_name!='')
		{
			$checked=" checked ";
			$div="";
		}
		echo "<tr>";
			echo "<th style='text-align:left	'>Software User</th>";
			echo "<td><input type='checkbox' $checked onclick='show_user_name(this.checked)'></td>";
		echo "</tr>";	   	
		
		$others_select=textMaker('User name','user_name',$user_name);
         $others_select=str_replace("<td>","<td class='user_div' style='".$div."' >",$others_select);
		
		 $others_select=str_replace("<th style='text-align:left'>","<th class='user_div' style='".$div."text-align:left'>",$others_select);
	echo $others_select; 
//  echo "</table></td></tr>";
    
    
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
          if($account_id=='')
            echo "<input type='button' onclick='submit_button()' name='submit_btn' value='Submit' style='margin:15px'>";
            else
            echo "<input type='Submit' name='update_btn' value='Update' style='margin:15px'>";
            
            echo "<input type='button' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
    ?>
</form>
</table>
<?php
?>