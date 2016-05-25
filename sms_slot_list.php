<?php
include 'page_header.php';
?>

<script>
    function deactivate(trans_num)
    {
    	document.getElementById('status').value="deactivate"
    	document.getElementById('user_id').value=trans_num
         document.getElementById('form1').action = 'sms_slot_list.php'
            document.form1.submit();
    }
    
    function edit_list(trans_num)
    {
         document.getElementById('form1').action = 'master_slot.php?trans_num='+trans_num
            document.form1.submit();
    }
    function check_list(trans_num)
    {
    	document.getElementById('status').value="activate"
    	document.getElementById('user_id').value=trans_num
         document.getElementById('form1').action = 'sms_slot_list.php'
            document.form1.submit();
    }
</script>
<form id='form1' name='form1' method=post>
<?php
if(!empty($_REQUEST['status']) )
{
    $status=1;
    if($_REQUEST['status']=="deactivate")
    $status=0;
    $user_id=$_REQUEST['user_id'];
    $update="update sms_slot_file set mas_status=$status where slot_id='$user_id' limit 1";
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

echo "<input type='hidden' name='status' id='status' >";
echo "<input type='hidden' name='user_id' id='user_id' >";


echo "<table>";
echo "<table>";
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
        echo "<td style='text-align:left'><a href='master_slot.php'>Add New</a>";
        echo "</td>";
    echo "</tr>";
    echo "<tr>";
        listMaker('sms_slot_file','sms_slot',array('sms_slot','phone_number','mas_status'),'SMS Slot List',$where);
    echo "</tr>";
echo "</table>";

?>
</form>