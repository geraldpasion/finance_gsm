<?php
include 'page_header.php';
?>
<script>
    function deactivate(trans_num)
    {
         document.getElementById('form1').action = 'list_view.php?status=deactivate&user_id='+trans_num;
            document.form1.submit();
    }
     function check_list(trans_num)
    {
         document.getElementById('form1').action = 'list_view.php?status=activate&user_id='+trans_num
            document.form1.submit();
    }
    function edit_list(trans_num)
    {
        document.getElementById('form1').action = "masters.php?trans_num="+trans_num
        document.form1.submit();
    }
</script>
<?php
if(!empty($_REQUEST['status']) )
{
    $status=1;
    if($_REQUEST['status']=="deactivate")
    $status=0;
    $user_id=$_REQUEST['user_id'];
    $update="update master_address_file set mas_status=$status where account_id='$user_id' limit 1";
    $conn->query($update);
}
$mas_status=getRequest('mas_status','');
$user_active="class='active selected_tab '";
$user_inactive="class='tabby'";
$user_all="class='tabby'";
$where="";
if($mas_status=='')
	$mas_status=1;
else if($mas_status==-1)
{
	$user_active="class='tabby'";
	$user_all="class='active selected_tab '";
}
else if($mas_status==2)
{
	$user_active="class='tabby'";
	$user_inactive="class='active selected_tab '";
	$mas_status=0;	
	$where=" where mas_status=0 ";
}

$where=whereMaker($where,'mas_status',$mas_status,-1);
$data=explode("?",$page_name);
$page_name=$data[0];
echo "<table align=center  style='width:100%'>";
	echo "<tr>
            	<td colspan=2>
                	<ul class='nav nav-tabs'>
                    	<li $user_active role='presentation'><a href='".$page_name."?mas_status=1'>Active</a></li>
		                <li $user_inactive role='presentation'><a href='".$page_name."?mas_status=2'>Inactive</a></li>
					    <li $user_all role='presentation'><a href='".$page_name."?mas_status=-1'>All</a></li>
		             </ul>
		          </td>
		    </tr>";
    echo "<tr>";
        echo "<td style='text-align:left'><a href='masters.php?'>Add New</a>";
        echo "</td>";
    echo "</tr>";
    echo "<tr>";
	echo "<td colspan=15 style='text-align:center;'>";
    $val_array=array('account_type','first_name','last_name','department_id','account_executive_id','phone_number','sms_slot','user_name','date_created','mas_status');
    $list_header=array('Position','First Name','last_name','department_id','account_executive_id','phone_number','sms_slot','user_name','date_created','Active');
    listMaker('master_address_file','first_name',$val_array,'Contact List',$where);
    echo "</td>";
	echo "</tr>";
echo "</table>";
?>