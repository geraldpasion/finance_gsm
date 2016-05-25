<?php
include 'page_header.php';
?>
<style>
    #user_table td
    {text-align:left;}
    #user_table th
    {text-align:left;width:80px}
    .table_list
    {
        border:1px solid black;
        border-collapse:collapse;
        width:800px;
    }
    
    .table_list th
    {
        border:1px solid black;
        padding:5px;
        text-align:left;
    }
    .table_list td
    {
        border:1px solid black;
        padding:10px;
        text-align: left;
    }
    .inside_table
    {
        border:none;
    border-collapse:collapse;
    }
        .inside_table td
    {
        border:none;
        padding-top:0px;
        padding-bottom:0px;
        padding-right:0px;
    }
        .inside_table th
    {
        border:none;padding:0px;
    }
    
</style>
<script>
     function releadPage(result)
     {
     //   alert(result)
        data=result.split("!")
        
        x= document.getElementsByClassName('allCheck')
        for (i = 0; i < x.length; i++) 
            x[i].checked=false
        for (a=1;a<data.length;a++)
        {
            
            data1=data[a].split("~")
            document.getElementById('menu'+data1[0]).checked=true
            for (k=1;k<data1.length;k++) {
                document.getElementById('menuType'+data1[0]+data1[k]).checked=true
            }
        }
    }
    
    function change_info() {
     type=document.getElementById('user_type').value
            url="xstatus=getMenuType&type="+type
            loadXMLDoc('menu_ajax.php?'+url,releadPage)
    }
    function get_class(menu_head,checked) {
        x= document.getElementsByClassName(menu_head)
        for (i = 0; i < x.length; i++) {
            x[i].checked=checked
        }
    }
    function check_menu(menu_id,checked) {
        if (checked) {
            document.getElementById('menu'+menu_id).checked=true
        }
    }
    
</script>
<form method=post name='form1' id='form1'>
<?php

$user_type=getPost('user_type');
if($user_type=='Choose')
$user_type="";
if(!empty($_REQUEST['submit_btn']))
{
    
    $menu=$_POST['menu'];
    $delete="delete from user_access_file where user_type='$user_type'";
    $conn->query($delete);
    $delete="delete from user_access_type_file where user_type='$user_type'";
    $conn->query($delete);
    for($a=0;$a<count($menu);$a++)
    {
        if(!empty($_POST['menuType'.$menu[$a]]) && count($_POST['menuType'.$menu[$a]])>0)
        {
            $men_type=$_POST['menuType'.$menu[$a]];
            for($k=0;$k<count($men_type);$k++)
            $result=insertMaker('user_access_type_file',array('user_type','menu_id','type'),array($user_type,$menu[$a],$men_type[$k]));
            $result=insertMaker('user_access_file',array('user_type','menu_id'),array($user_type,$menu[$a]));
        }
    }
    
}

$select="select * from menu_file where mas_status=1 and menu_head!=16 order by menu_head,menu_type,  menu_order";
$result = $conn->query($select);
echo "<h2>User Access </h2>";
echo "<table id='user_table'>";
    
        echo selectMaker('User Type','user_type',array('Finance Head','Secretary','QA','Admin','Cash Release'),'change_info()',$user_type);

echo "<tr><td colspan=2>";
echo "<table class='table_list'>";
$temp_head=-1;
while($row=$result->fetch_assoc())
{
    echo "<tr>";
        if($row['menu_type']==1)
        {
            if($temp_head!=$row['menu_head'] )
            {
                echo "<th style='padding:15px;'><input type='checkbox' class='allCheck'  onclick='get_class(\"menu_head".$row['menu_head']."\",this.checked)' ></th>";
                echo "<th colspan=2>".$row['menu_name']."</th>";
            }
            $temp_head=$row['menu_head'];
        }
        else
        {
            echo "<td style='text-align:right'><input type='checkbox' onclick='get_class(\"menu_id".$row['menu_id']."\",this.checked)'  class='allCheck menu_head".$row['menu_head']."' id='menu".$row['menu_id']."' name='menu[]' value='".$row['menu_id']."'></td>";
            echo "<td>".$row['menu_name']."</td>";
            echo "<td >";
            $select2="select a.menu_name,a.type,k.type as checked from access_file as a left join user_access_type_file as k
            on a.menu_id=k.menu_id and a.type=k.type and k.user_type='$user_type'
            where a.menu_id='".$row['menu_id']."' group by a.type order by a.id";
            $result2 = $conn->query($select2);
           // echo "<br>".$select2;
                echo "<table class='inside_table' STYLE='WIDTH:800px'	><tr>";
            while($row2=$result2->fetch_assoc())
            {
                $checked="";
                if($row2['checked']!='')
                $checked="checked";
$type=$row2['type'];
				if($row2['menu_name']!='')
				$type=$row2['menu_name'];
                echo "<td><input type='checkbox' $checked onclick='check_menu(".$row['menu_id'].",this.checked)' name='menuType".$row['menu_id']."[]' id='menuType".$row['menu_id'].$row2['type']."' class='allCheck menu_head".$row['menu_head']." menu_id".$row['menu_id']."' value='".$row2['type']."'>".$type	."</td>";
            }
            echo "</tr></table>";
            echo "</td>";
        }
    echo "</tr>";
}
echo "</table>";
echo "</td>";
echo "<tr><td colspan=2 style='padding:10px;text-align:center'><input type='submit' name='submit_btn' value='Submit'>
<input type='submit' name='cancel_btn' value='Cancel'></tr>";

echo "</table>";
?>
<script>change_info()</script>
</form>