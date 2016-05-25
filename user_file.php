<?php
include 'page_header.php';
?>
<form name='form1' id='form1' method='post'>
<script>
    function get_type(type) {
        if (type=='Secretary' ) 
            document.getElementById('finance_div').style.display='block';
        else
            document.getElementById('finance_div').style.display='none';
        if (type=='Finance Head') 
            document.getElementById('department_div').style.display='block';
        else
            document.getElementById('department_div').style.display='none';
        if (type=='Account Executive') 
            document.getElementById('department_div').style.display='block';
        else
            document.getElementById('department_div').style.display='none';
       
         if (type=='Secretary' ||type=='QA')
            document.getElementById('phone_id').style.display='block';
        else
            document.getElementById('phone_id').style.display='none';
            
    }
</script>

<?php
$username="";
        $user_type="";
        $department="";
        $finance_head="";
        $phone_number="";
        $first_name="";
        $last_name="";
    if(!empty($_POST['submit_btn']))
    {
        $username=getPost('user_name','Choose');
        $user_type=getPost('user_type','Choose');
        $department=getPost('department','Choose');
        $finance_head=getPost('finance_head','Choose');
        $phone_number=getPost('phone_number','Choose');
        $first_name=getPost('first_name','Choose');
        $last_name=getPost('last_name','Choose');
        $account_executive_id=" ";
        if($user_type=='Secretary' || $user_type=='Finance Head'||$user_type=='Account Executive')
        {
            $account_executive_id=$_POST['finance_head'];
            if( $user_type=='Finance Head' ||$user_type=='Account Executive')
            $user_type='account executive';
            else
            {
                $select="select department from master_address_file as a inner join master_department_file  as k
                on a.department_id=k.department_id
                where account_id='".$account_executive_id."' and account_type='account executive' limit 1";
                $result = $conn->query($select);
                $row=$result->fetch_assoc();
                $department=$row['department'];
            }
            $id=getId('master_address_file','account_id');
            
            $add_array=array('account_type',"account_id",'first_name','last_name','department_id','account_executive_id','phone_number','date_created');
            $value_array=array($user_type,$id,$first_name,$last_name,$department,$account_executive_id,$phone_number,'now()');
            
            
            $result=insertMaker('master_address_file',$add_array,$value_array);
            //$result=insertMaker('master_address_file',array('secretary','secretary_id','account_executive_id'),array($name,$id,$account_executive_id));
        }
        if($department=='Choose')
        $department="";
        $select="select user_id from user_file order by user_id desc limit 1";
        $result = $conn->query($select);
        $row=$result->fetch_assoc();
        $user_id=(int)$row['user_id']+1 ;      
        
        $result=insertMaker('user_file',array('user_id','user_name','password','first_name','last_name','user_type','department','finance_head','phone_number'),array($user_id,$username,'password123',$first_name,$last_name,$user_type,$department,$finance_head,$phone_number));
        
        
        echo "<script>alert('Successfully added New User');";
        
         echo "document.getElementById('form1').action='user_list.php';";
                 
         echo "document.form1.submit();</script>";
    }
    
?>

<table style='width:300px' class='form_css'>
    <tr>
        <th style='text-align:left'><h2>User File</h2></th>
    </tr>
    <?php
    $select="select department from master_department_file where mas_status=1 order by department";
    $result = $conn->query($select);
    $departments=array();
    while($row=$result->fetch_assoc())
        $departments[]=$row['department'];
    $finance_head=array();
    $finance_head=array();
     $select="select concat(first_name,' ',last_name) as account_executive,account_id from master_address_file
     where mas_status=1 and account_type='account executive' order by account_executive";
    $result = $conn->query($select);
    while($row=$result->fetch_assoc())
    {
        $finance_value[]=$row['account_id'];
        $finance_head[]=$row['account_executive'];
    }
    /*
    $select="select user_name from user_file where mas_status=1 order by user_name";
    $result = $conn->query($select);
    $username=array();
    while($row=$result->fetch_assoc())
        $username[]=$row['user_name'];*/
    if(!empty($_REQUEST['trans_num']))
    {
        $user_id=$_REQUEST['trans_num'];
        
        $select="select * from user_file where user_id='$user_id' limit 1";
        $result = $conn->query($select);
        $row=$result->fetch_assoc();
        
        
        $username=$row['user_name'];
        $user_type=$row['user_type'];
        $department=$row['department'];
        $finance_head=$row['finance_head'];
        $phone_number=$row['phone_number'];
        $first_name=$row['first_name'];
        $last_name=$row['last_name'];
        if( $user_type=='account executive')
            $user_type='Finance Head';
    }
    echo textMaker('Username','user_name',$username);
    echo textMaker('First Name','first_name',$first_name);
    echo textMaker('Last Name','last_name',$last_name);
    
	$select="select account_type from master_position_file where mas_status=1 order by account_type";
    $result = $conn->query($select);
    $account_array=array();
    while($row=$result->fetch_assoc())
        $account_array[]=$row['account_type'];
    //        $account_array[]="Others";
    /*echo selectMaker('Position','account_type',$account_array,"change_info(this.value)",$type);
	$others_select=textMaker('New Position','other_pos');
         $others_select=str_replace("<td>","<td class='other_pos_div' style='display:none'>",$others_select);
		
		 $others_select=str_replace("<th style='text-align:left'>","<th class='other_pos_div' style='display:none;text-align:left'>",$others_select);
		echo $others_select; 	   	
    */
	echo selectMaker('User Type','user_type',$account_array,'get_type(this.value)',$user_type);
    
	
	//$label,$id,$array,$function,$value,$val=''
    echo "<tr><td colspan=2><div style='display:none' id='phone_id'>";
    echo "<table>";
    
    echo "<tr >";
    echo textMaker('Phone Number','phone_number',$phone_number);
    echo "</tr>";
    echo "</table></td></tr>";
    echo "<tr>";
    echo "<tr><td colspan=2><div style='display:none' id='department_div'>";
    echo "<table>";
    echo selectMaker('Department','department',$departments,'',$department);
    echo "</table>";
    echo "</div></td></tr>";
    echo "<tr><td colspan=2><div style='display:none' id='finance_div'>";
    echo "<table>";
    echo selectMakerValue('Finance Head','finance_head',$finance_head,'',$finance_head);
    echo "</table>";
    echo "</div></td></tr>";
    echo "<tr>";
        echo "<td colspan=2 style='text-align:center'>";
            echo "<input type='submit' name='submit_btn' value='Submit' style='margin:15px'>";
            echo "<input type='button' value='Cancel' style='margin:15px'>";
        echo "</td>";
    echo "</tr>";
    echo "<script>get_type(\"$user_type\")</script>";
    ?>
</table>
<script>

	if($('#user_name').val()!='')
	{
		$('#user_name').attr('readonly','readonly')
	}		
</script>
</form>