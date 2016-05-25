<?php
include 'page_header.php';
?>
<script>
    function deactivate(trans_num)
    {
     document.getElementById('status').value="deactivate"
    	document.getElementById('user_id').value=trans_num
         document.getElementById('form1').action = 'user_list.php'
            document.form1.submit();
    }
    
    function edit_list(trans_num)
    {
         document.getElementById('form1').action = 'user_file.php?trans_num='+trans_num
            document.form1.submit();
    }
    function check_list(trans_num)
    {
    document.getElementById('status').value="activate"
    	document.getElementById('user_id').value=trans_num
         document.getElementById('form1').action = 'user_list.php'
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
    $update="update user_file set mas_status=$status where user_id='$user_id' limit 1";
    $conn->query($update);
}

?>
<form method='post' name='form1' id='form1' >

<?php
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
	echo "<tr>
            	<td colspan=2>
                	<ul class='nav nav-tabs'>
                    	<li $user_active role='presentation'><a href='".$page_name."?mas_status=1'>Active</a></li>
		                <li $user_inactive role='presentation'><a href='".$page_name."?mas_status=2'>Inactive</a></li>
					    <li $user_all role='presentation'><a href='".$page_name."?mas_status=-1'>All</a></li>
		             </ul>
		          </td>
		    </tr>";
        echo "<td style='text-align:left'><a href='user_file.php'>Add New</a>";
        echo "</td>";
    echo "</tr>";
    echo "<tr>";
	
	listMaker('user_file','created_date',array('user_name','first_name','last_name','user_type','department','finance_head','mas_status'),'User List',$where);
    echo "</tr>";
echo "</table>";
?>
</form>