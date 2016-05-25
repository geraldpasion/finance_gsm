 <?php
 include 'connect.php';
function getPost($field,$not='')
    {
        $value="";
        if(!empty($_POST[$field])&&$_POST[$field]!=$not)
        $value=$_POST[$field];
        return $value;
    }
function getRequest($field,$not='')
    {
        $value="";
        if(!empty($_REQUEST[$field])&&$_REQUEST[$field]!=$not)
        $value=$_REQUEST[$field];
        return $value;
    }
function textMaker($label,$id,$value='',$func='')
{
    return "<tr><th style='text-align:left'>$label</th><td><input type='text' id='$id' name='$id' $func value=\"".trim($value)."\"></td></tr>";
}
function updateMaker($table,$columns,$val,$trans_num)
{
    global $conn;
    $in="";
    for($a=0;$a<count($val);$a++)
    {
        if($in!='')
        $in.=",";
        $in.=$columns[$a]."='".$val[$a]."'";
    }
    $update="update $table set ".$in." ".$trans_num." ";
    $conn->query($update);
    //echo $update;
}
function getId($table,$var)
{
    global $conn;
    $select="select ".$var." from ".$table." order by ".$var." desc limit 1";
    $id=1;
    $result = $conn->query($select);
    if ($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        $id=$row[$var]+1;
    }
    return $id;
}
function get_title($type)
{
    switch ($type) {
        case 'account_executive': return 'Account Executive'; break;
        case 'engineer': return 'Engineer';break;
        case 'department': return 'Department'; break;
        case 'secretary': return 'Secretary'; break;
    }
        
}
function get_letter_code($number_code)
{
 $letter_code="";
 if($number_code>0)
 {
    $alpha = " ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $count=26;
    
    $num2=$number_code;
     $letter=array();
    while($num2>26)
    {
        $a=$num2/26;
        $b=$num2-((int)$a*$count);
        $letter[]=$alpha[$b];
        $num2=(int)$a;
    }
    if($num2>0)
    $letter[]=$alpha[$num2];
         for($a=count($letter)-1;$a>=0;$a--)
      $letter_code.=$letter[$a];
 }
      return $letter_code;
}
function toStringList($array)
{
    $list="";
    for($a=0;$a<count($array);$a++)
    {
        if($list!='')
        $list.=",";
        $list.="`".$array[$a]."`";
    }
    return $list;
}
function convert_to_dateTime($date)
{
    return date("F d, Y h:m:i a",strtotime($date));
}
function listMaker($table_name,$order,$select_list,$title,$where='')
{
    global $conn;
    global $access;
	global $list_header;
    $sel=" ";
     if($order=='secretary'|| $order=='engineer')
     $sel=" ,account_executive_id";
    //   echo "<br>".$table_name."<br>";
     if($table_name=='master_address_file')
     {
        $this_id="account_id";
        $sel.=",account_id";
     }
     else if($table_name=='user_file')
     {
        
        $this_id="user_id";
         $sel.=",user_id";
     }
     else if($table_name=='sms_files as a left join po_file as k on a.trans_no=k.trans_no')
     {
        
        $this_id="sms_id";
         $sel.=",sms_id";
     }
     else
     {
        $sel.=",id";
        $this_id="id";
     }
     
    $select="select ".toStringList($select_list).$sel."  from $table_name $where order by $order";
//    echo $select;
    ?>
    <h2><?php echo $title;?></h2>
    <form name='form1' id='form1' method=post>
    <table align=center class='table_data'>
    <?php
   //echo $select; 
    $result = $conn->query($select);
    echo "<tr>";
        for($a=0;$a<count($select_list);$a++)
        {
            if($select_list[$a]=='department_id')
            echo "<th>Department</th>";
            else if($select_list[$a]=='account_executive_id')
            echo "<th>Account Executive</th>";
			else if(!empty($list_header[$a])) 
			echo "<th>".ucwords(str_replace("_"," ",$list_header[$a]))."</th>";
            else
            echo "<th>".ucwords(str_replace("_"," ",$select_list[$a]))."</th>";
        }
        if($order=='secretary'|| $order=='engineer')
            echo "<th>Account Executive</tk>";
            
        echo "<th colspan=2 style='text-align:left'>Action</th>";
    echo "</tr>";
    $executive=array();
    $department_list=array();
    while($row=$result->fetch_assoc())
    {
        echo "<tr>";
            for($a=0;$a<count($select_list);$a++)
            {
                if($select_list[$a]=='account_type')
                    echo "<td>".ucwords(str_replace("_"," ",$row['account_type']))."</td>";
                else if($select_list[$a]=='mas_status')
                {
                    if($row['mas_status']==1)
                    echo "<td>Active</td>";
                    else
                    echo "<td>Inactive</td>";
                }
                else if($select_list[$a]=='department_id')
                {
                    if(empty($department_list[$row[$select_list[$a]]]))
                    {
                        $select="select department from master_department_file where department_id='".$row[$select_list[$a]]."' limit 1";
                        $result2= $conn->query($select);
                        $row2=$result2->fetch_assoc();
                        $department_list[$row[$select_list[$a]]]=$row2['department'];
                    }
                    echo "<td>".$department_list[$row[$select_list[$a]]]."</td>";
                }
                else if($select_list[$a]=='account_executive_id')
                {
                    if(empty($accout_exe[$row[$select_list[$a]]]))
                    {
                        $select="select concat(first_name,' ',last_name) as name from master_address_file where account_type='account executive' and account_id='".$row[$select_list[$a]]."' limit 1";
                        $result2= $conn->query($select);
//echo $select;
                        $row2=$result2->fetch_assoc();
                        $accout_exe[$row[$select_list[$a]]]=$row2['name'];
                    }
                    echo "<td>".$accout_exe[$row[$select_list[$a]]]."</td>";
                        
                }
                else if($select_list[$a]=='date_created')
                    echo "<td>".date("F j, Y")."</td>";
                else if ($select_list[$a]=='sms_slot' && $table_name!='sms_slot_file')
                {
                	$select="select sms_slot from  sms_slot_file where slot_id='".$row[$select_list[$a]]."' limit 1";
                	$result2= $conn->query($select);
                        $row2=$result2->fetch_assoc();
                        $sms_slot[$row[$select_list[$a]]]=$row2['sms_slot'];
                        echo "<td>".$sms_slot[$row[$select_list[$a]]]."</td>";
                }
                else if($select_list[$a]=='user_name'&&$table_name=='master_address_file')
                {
                	$user="";
                	if($row[$select_list[$a]]!='')
                	{
	                	$select="select user_name from user_file where user_id='".$row[$select_list[$a]]."'";
	                	$result2= $conn->query($select);
                        $row2=$result2->fetch_assoc();
                        $user=$row2['user_name'];
	                }
	                echo "<td>".$user."</td>";
                }
                else
                echo "<td>".$row[$select_list[$a]]."</td>";
            }
            if($order=='secretary'|| $order=='engineer')
            echo "<td>".$executive[$row['account_executive_id']]."</td>";
            echo "<td>";
            //if(!empty($access['Edit']))
            //{
                if($table_name!='sms_files as a left join po_file as k on a.trans_no=k.trans_no')
              echo "<img  src='assets/Edit.png' name='image' width='20' height='20'  style='margin-right:10px' onclick='edit_list(".$row[$this_id].")'>";
          //  }
            //if(!empty($access['Deactivate']))
            //{
                if($row['mas_status']==0)
                echo "<img  src='assets/check.png' name='image' width='20' height='20' style='margin-left:10px' onclick='check_list(".$row[$this_id].")'>";
                else 
                echo "<img  src='assets/cross.jpg' name='image' width='21' height='20' style='margin-left:10px' onclick='deactivate(".$row[$this_id].")'>";
            //}
            
        echo "</tr>";
    }
    ?>  
    </table>
    </form>
    <?php
}

function selectMakerValue($label,$id,$array,$function,$value,$val='' )
{
    
    $func="";
    if($function!='')
    $func="onchange='".$function."'";
    $text= "<tr><th style='text-align:left'>".$label."</th>";
    $text.="<td><select id='".$id."' name='".$id."' ".$func.">";
    $text.= "<option>Choose</option>";
    for($a=0;$a<count($array);$a++)
    {
        if($val==$value[$a])
        $text.= "<option selected value='".$value[$a]."'>".$array[$a]."</option>";
        else
        $text.= "<option value='".$value[$a]."'>".$array[$a]."</option>";
    }
     $text.="</select>";
     $text.="</td></tr>";
     return $text;
}
function sendText($text,$phone_number,$smsc_id)
{
	$text=urlencode($text);
	
	try {
	$response = file_get_contents("http://127.0.0.1:13013/cgi-bin/sendsms?user=sms-app&pass=app125&text=".$text."&to=".$phone_number."&smsc_id="+$smsc_id );
	}
	catch(Exception $e)
	{
		echo "<script>alert('Failed to send message')</script>";
	}
     catch (ErrorException $e) {
   }
  // global $conn;
  /*$select="select sms_id from sms_files order by sms_id desc limit 1";
  // echo $select;
   $result2= $conn->query($select);
	$row2=$result2->fetch_assoc();
    $sms_id=$row2['sms_id'];
   $insert="insert into received_file(sms_id,sms,date_sent,received) values('$sms_id','".addslashes($text)."',now(),$phone_number)";
   $conn->query($insert);*/
   // $insert="insert into received_file(phone_number,message,received_date) values($phone_number,'".addslashes($text)."',now())";
  // $conn->query($insert);
}
function selectMakerEach($label,$id,$array,$function , $default='')
{
//echo "<br>".$function;
    $func="";
    if($function!='')
    $func="onchange='".$function."'";
    $text= "<tr><th style='text-align:left;width:129px'>".$label."</th>";
    $text.="<td><select id='".$id."' name='".$id."' ".$func.">";
    $text.= "<option>Choose</option>";
    foreach($array as $key=>$value)
    {
        if($default==$key)
        $text.= "<option selected value='".$key."'>".$value."</option>";
        else
        $text.= "<option value='".$key."'>".$value."</option>";
    }
     $text.="</select>";
     $text.="</td></tr>";
     return $text;
}
function selectMaker($label,$id,$array,$function , $default='')
{
    $func="";
    if($function!='')
    $func="onchange='".$function."'";
    $text= "<tr><th style='text-align:left'>".$label."</th>";
    $text.="<td><select id='".$id."' name='".$id."' ".$func.">";
    $text.= "<option>Choose</option>";
    for($a=0;$a<count($array);$a++)
    {
        if($default==$array[$a])
        $text.= "<option selected>".$array[$a]."</option>";
        else
        $text.= "<option>".$array[$a]."</option>";
    }
     $text.="</select>";
     $text.="</td></tr>";
     return $text;
}
function insertMaker($table_name,$columns,$val)
{
    global $conn;
    $insert="insert into ".$table_name."(";
    for($a=0;$a<count($columns);$a++)
    {
        if($a!=0)
        $insert.=",";
        $insert.="`".$columns[$a]."`";
    }
    $insert.=")values(";
    for($a=0;$a<count($val);$a++)
    {
        if($a!=0)
        $insert.=",";
        if($val[$a]!='now()')
        $insert.="'".addslashes($val[$a])."'";
        else
        $insert.="now()";
    }
    $insert.=")";
  // echo "<br>".$insert;
    $result = $conn->query($insert);
    return $result;
    
}
function selectMakerArray($title,$id,$departments,$default='',$Choose='Choose') 
{
    $text="<tr><th style='text-align:left'>".$title."</th>";
    $text.= "<td><select id='".$id."' name='".$id."'>";
    $text.="<option>$Choose</option>";
    for($a=0;$a<count($departments);$a++)
    {
        if($default==$departments[$a][1])
        $text.= "<option selected value=\"".$departments[$a][1]."\">".$departments[$a][0]."</option>";
        else
        $text.= "<option value=\"".$departments[$a][1]."\">".$departments[$a][0]."</option>";
    }
    $text.= "</select></td>";
    return $text;
}
function whereMaker($filter,$field,$value,$not='')
{
    if($value!='' &&$value!=$not)
    {
        if($filter=='')
        $filter="where ";
        else
        $filter.=" and ";
        $filter.=" ".$field."='".addslashes($value)."'";
    }
    return $filter;
}
?>

