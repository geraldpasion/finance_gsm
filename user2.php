<?php include 'page_header.php';
?>
<style>
.user_set td
{border:1px solid black;}
.user_set th
{border:1px solid black;}
</style>
<?php
echo "<form name='form1' id='form1' method=post>";
error_reporting(E_ALL);
ini_set('display_errors', '1');

//print_r($_POST);



$select="select * from master_position_file where mas_status=1 and system_access=1";
$result = $conn->query($select);
        
while($row=$result->fetch_assoc())
{
	$pos[]=array($row['account_type'],$row['id']);
	$a_type[$row['id']]=$row['account_type'];
}
if(!empty($_POST['access']))
{
	$access=$_POST['access'];
	//print_r($access);
	//echo "<br>";
	//print_r($_POST);
	$delete="TRUNCATE user_access_file_new";
	$result = $conn->query($delete);
	for($a=0;$a<count($access);$a++)
	{
		$data=explode("~",$access[$a]);
		//echo "<br>=".$data[0]."="	;
	//print_r($data);
	//print_r($a_type);
	
		$insert="insert into user_access_file_new (menu_id,user_type,type,added_by,added_date) values('".$data[1]."','".$a_type[$data[0]]."','".$data[2]."','".addslashes($_SESSION['uname'])."',now())";
		$result = $conn->query($insert);
		//echo "<br>".$insert;
	}
	$select="SELECT k.head_name,k.type,k.menu_name from user_access_file_new as a inner join access_file_20161 as k on a.menu_id=k.menu_id WHERE user_type='".$_SESSION['user_type']."'";
    $result = $conn->query($select);
    $access_type=array();
    $access_type_type=array();
    $access_menu_type=array();
    while($row=$result->fetch_assoc())
    {
        $access_type[$row['head_name']][$row['type']]="yes";
        $access_type_type[$row['type']][$row['type']]="yes";
        $access_menu_type[$row['menu_name']][$row['type']]="yes";
        
    }
    $_SESSION['access_type']=$access_type;
    $_SESSION['access_type_type']=$access_type_type;
    $_SESSION['access_menu_type']=$access_menu_type;
}
$select="select * from user_access_file_new";
$result = $conn->query($select);
while($row=$result->fetch_assoc())
{
	$old_access[$row['menu_id']][$row['user_type']][$row['type']]="Yes";
}
$select="select * from access_file_20161 order by access_order";
$result = $conn->query($select);
$temp="";
$data=array();
while($row=$result->fetch_assoc())
{
	$data[$row['head_name']][$row['menu_name']][]=array($row['type'],$row['sub_status'],$row['menu_id']);
	//$data1[$row['head_name']][$row['menu_name']][]=array($row['menu_id']);
	//$menu_name[$row['menu_id']]=$row['menu_name'];
	
	
	if(empty($head_count[$row['head_name']]))
	$head_count[$row['head_name']]=0;
	$head_count[$row['head_name']]++;
	if($row['type']!=''&&$row['menu_name']!='' &&$temp!=$row['menu_name'])
		$head_count[$row['head_name']]++;
	$temp=$row['menu_name'];
	
// 	/echo "<br>".$head_count[$row['head_name']];
}
$edit=array('Add','Edit','View','Delete');
echo "<table border=2 class='user_set' style='border-collapse:collapse;border:none'>";
echo "<tr><td colspan='100' style='border:none;text-align:center;padding:20px'><input type='submit' style='font-size:18px' value='User Activity Set'></td></tr>";

	echo "<tr>";
		echo "<th colspan=2 style='text-align:center'>Software Access</th>";
		for($a=0;$a<count($pos);$a++)
			echo "<th style='text-align:center;padding:5px'  colspan=4 >".$pos[$a][0]."</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan=2></td>";
		for($a=0;$a<count($pos);$a++)
		{
			for($k=0;$k<count($edit);$k++)
			echo "<th style='text-align:center;padding:15px'  >".$edit[$k]."</td>";
		}
foreach($data as $head=>$d1)
{
$kai=0;
	echo "<tr>";
	echo "<th rowspan='".($head_count[$head]+1)."' style='padding:10px'>".$head."</td>";
	foreach($d1 as $head1=>$d2)
	{
		if($head1!='')
		{
			echo "<tr>";
				echo "<th	 style='text-align:left;padding:10px' >".$head1."</td>";
				$kai++;
		}
		if(count($d2)==1 && $d2[0][0]=="" &&$head1!='')
		{	
			for($a=0;$a<count($pos);$a++)
			{
				//$old_access[$row['menu_id']]['user_tye']['type']="Yes";
				$checked="";
				if(!empty($old_access[$d2[0][2]][$pos[$a][0]]['']))
				$checked="checked";
				
				//echo "<br><b>".$d2[0][2]." =".$pos[$a][0]."=".$checked."</b>";
				echo "<td style='text-align:center' colspan=4 ><input type='checkbox' $checked name='access[]' value='".$pos[$a][1]."~".$d2[0][2]."~'></td>";
			}
			echo "</tr>";	
		}
		else
		{
			if($head1!='')
			{
			
				if($d2[0][0]=='')
				{
					echo "<td colspan='".(count($pos)*4)."' style='text-align:center' >";
					//print_r($d2[0][0]);
					
					echo "</td>";
				}
				else
				{
					if($d2[0][1]=='1')
					{
						for($a=0;$a<count($pos);$a++)
						{
							$checked="";
							if(!empty($old_access[$d2[0][2]][$pos[$a][0]][$edit[0]]))
							$checked="checked";
							$checked1="";
							if(!empty($old_access[$d2[0][2]][$pos[$a][0]][$edit[3]]))
							$checked1="checked";
							//echo "<br>".$checked."=".$checked1;
							//echo "<br><b>".$d2[0][2]." =".$pos[$a][1]."=".$checked."=".$checked1."</b>";
							echo "<td style='text-align:center'><input type='checkbox' name='access[]' $checked value='".$pos[$a][1]."~".$d2[0][2]."~".$edit[0]."'></td>";
							echo "<td style='text-align:center'></td>";
							echo "<td style='text-align:center'></td>";
							echo "<td style='text-align:center'><input type='checkbox' name='access[]' $checked1 value='".$pos[$a][1]."~".$d2[0][2]."~".$edit[3]."'></td>";
						}	
					}
					else
					{
						echo "<td colspan='".(count($pos)*4)."' style='text-align:center' >";
					//print_r($d2[0][0]);
					
						echo "</td>";
					
					}
				
				}
				
				echo "</tr>";
			
			}	
			if($d2[0]!='')
			{
				foreach($d2 as $head2=>$d3)
				{	
				
					echo "<tr>";
						echo "<td style='padding:20px'>".$d3[0]."</td>";
						for($a=0;$a<count($pos);$a++)
						{
							if($d3[1]==1)
							{
								$checked="";
								if(!empty($old_access[$d3[2]][$pos[$a][0]][$edit[1]]))
								$checked="checked";
								$checked1="";
								if(!empty($old_access[$d3[2]][$pos[$a][0]][$edit[2]]))
								$checked1="checked";
							//echo "<br>".$checked."=".$checked1;
								echo "<td style='text-align:center'></td>";
								echo "<td style='text-align:center'><input type='checkbox' $checked name='access[]' value='".$pos[$a][1]."~".$d3[2]."~".$edit[1]."'></td>";
								echo "<td style='text-align:center'><input type='checkbox' $checked1 name='access[]' value='".$pos[$a][1]."~".$d3[2]."~".$edit[2]."'></td>";
								echo "<td style='text-align:center'></td>";
							}
							else
							{
								$checked="";
								if(!empty($old_access[$d3[2]][$pos[$a][0]]['']))
								$checked="checked";
								//echo "<br>".$checked;
								echo "<td style='text-align:center' colspan=4 ><input type='checkbox' $checked name='access[]' value='".$pos[$a][1]."~".$d3[2]."~'></td>";
							}
						}
					echo "</tr>";
					$kai++;
				}
			}
		}
	}
	echo "</tr>";
}

echo "</form>";

?>