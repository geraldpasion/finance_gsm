<?php
include 'page_header.php';
?>
<link rel="stylesheet" type="text/css" href="src/datepickr.min.css">
<script src="src/datepickr.min.js"></script>
<style>
     .calendar-icon {
                display: inline-block;
                vertical-align: middle;
                width: 19px;
                height: 19px;
                background: url(assets/calendar.jpg);
            }
</style>
<script>
  
function deactivate(trans_num)
    {
         document.getElementById('form1').action = 'sms_list.php?status=deactivate&sms_id='+trans_num
            document.form1.submit();
    }
    function check_list(trans_num)
    {
         document.getElementById('form1').action = 'sms_list.php?status=activate&sms_id='+trans_num
            document.form1.submit();
    }
    
</script>
<?php

if(!empty($_REQUEST['status']))
{
    $sms_id=$_REQUEST['sms_id'];
    if($_REQUEST['status']=='deactivate')
        $update="update sms_files set hide='1' where sms_id='$sms_id' ";
    if($_REQUEST['status']=='activate')
        $update="update sms_files set hide='0' where sms_id='$sms_id' ";
     $conn->query($update);
}

//function listMaker($table_name,$order,$select_list,$title)
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

$table_name='';
$order='date_sent desc';
$select_list=array('received','sms','date_sent','date_created','letter_code');
$title='SMS List';
    
    $sel=",hide ";
        $this_id="sms_id";
         $sel.=",sms_id";
         
    $limit=10;
    $start=0;
    $page=1;
    $phone_number=getPost('phone_number','Choose') ;
    $date_from=getPost('date_from','') ;
    $date_to=getPost('date_to','') ;
    if(!empty($_POST['page']))
    {
        $page=$_POST['page'];
        $start=(($_POST['page']-1)*$limit);
    }     
         
    $select="select a.id  from  sms_files as a left join po_file as k on a.trans_no=k.trans_no   ";
    $result = $conn->query($select);
    $rowcount=mysqli_num_rows($result);
     $limit=10;
    $pages=$rowcount/$limit;
     if((int)$pages<$pages)
         $pages++;
     $pages=(int)$pages;
    
    $where=whereMaker("",'received',$phone_number,'');
    
    if($date_from!='' ||$date_to!='')
    {
          if($where!='')
         $where.=" and ";
         else
         $where=" where";
         if($date_from!='' && $date_to!='')
               $where.=" date_sent >= '".date("Y-m-d",strtotime($date_from))." 00:00:00' and date_sent <='".date("Y-m-d",strtotime($date_to))." 23:59:59'";
         else if($date_from!='')
               $where.=" date_sent like '".date("Y-m-d",strtotime($date_from))."%' ";
         else
               $where.=" date_sent like '".date("Y-m-d",strtotime($date_to))."%' ";
    }
    $select="select ".toStringList($select_list).$sel." from sms_files as a left join po_file as k on a.trans_no=k.trans_no  $where ";
    //    echo $select;
    ?>
    <h2><?php echo $title;?></h2>
    <form name='form1' id='form1' method=post>
    <table class='table_data'>
    <?php
    $result = $conn->query($select." order by $order limit $start,$limit");
   // echo $select." order by $order limit $start,$limit";
    $table="";
    $table.="<tr>";
          $table.= "<th>Sender</th>";
        for($a=0;$a<count($select_list);$a++)
            $table.= "<th style='text-align:center'>".ucwords(str_replace("_"," ",$select_list[$a]))."</th>";
           
        $table.= "<th colspan=2 style='text-align:left'>Hide</th>";
    $table.= "</tr>";
    
    $received=array();
    $received_name=array();
    while($row=$result->fetch_assoc())
    {
          $received[$row['received']]=$row['received'];
          
        $table.= "<tr>";
          if(empty($executive[$row['received']]))
          {
             $select="select concat(first_name,' ',last_name) as name
             from master_address_file where phone_number='".$row['received']."' limit 1";
            $result2= $conn->query($select);
            $row2=$result2->fetch_assoc();
            $rowcount=mysqli_num_rows($result2);
            if($rowcount>0)
            {
               $executive[$row['received']]=$row2['name'];
               $received_name[$row['received']]=$executive[$row['received']];
            }
            else
            {
               $executive[$row['received']]="";
               $received_name[$row['received']]=$row['received'];
            }
            
          }  
            $table.= "<td>".$executive[$row['received']]."</td>";
            for($a=0;$a<count($select_list);$a++)
            {
                if($select_list[$a]=='account_type')
                    $table.= "<td>".ucwords(str_replace("_"," ",$row['account_type']))."</td>";
               else if($select_list[$a]=='date_sent')
                    $table.="<td>".convert_to_dateTime($row['date_sent'])."</td>";
                else if($select_list[$a]=='date_created')
                {
                    $date="";
                    if($row['date_created']!='')
                    $date=date("F j, Y",strtotime($row['date_created']));
                    $table.= "<td style='text-align:center  '>".$date."</td>";
                }
                else
                $table.= "<td style='text-align:center'>".$row[$select_list[$a]]."</td>";
            }
            $table.= "<td>";
          if(!empty($access['Hide']))
          {
                if($row['hide']!=0)
                $table.= "<td><img  src='assets/check.png' name='image' width='20' height='20' onclick='check_list(".$row[$this_id].")'></td>";
                else 
                $table.= "<td><img  src='assets/cross.jpg' name='image' width='21' height='20' onclick='deactivate(".$row[$this_id].")'></td>";
          }
        $table.= "</tr>";
    }
    echo selectMakerEach('Received','phone_number',$received_name,'' ,$phone_number);
     echo "<tr><th colspan=10 style='text-align:left; border:none'>
     <table align=left><tr><th style='border:none;text-align:left'>Date Sent</th><td style='border:none'>
     <input title='parseMe' style='width:120px' id='date_from' name='date_from' value='$date_from'>
     <span id='date_from_cal' class='calendar-icon'></span>
     
     </td><td style='padding:1px;border:none'>-</td><td style='border:none'>
     <input title='parseMe' style='width:120px' id='date_to' name='date_to' value='$date_to'>
     <span id='date_to_cal' class='calendar-icon'></span>
     
     </td></table></td></tr>";
    echo "<tr><td colspan=2 style='text-align:center   '><input type='submit' value='Submit'></td><td colspan=10></td></tr>";
    echo $table;
    ?>
    <tr>
     <td colspan=20 style='text-align:center'>
            <table align=center > 
                <?php
                    if($page!=1)
                    echo "<td><input type='button' value='First' onclick='getPage(1)'></td>";
                    if($page>1)
                    echo "<td><input type='button' value='Prev' onclick='getPage(".($page-1).")'></td>";
                    if($page+1<=$pages)
                    echo "<td><input type='button' value='Next' onclick='getPage(".($page+1).")'></td>";
                    if($page!=$pages)
                    echo "<td><input type='button' value='Last' onclick='getPage(".($pages).")'></td>";
                ?>
                <td style='width:200px;border:none;padding: 0px' colspan=16>Page
                    <select style='font-size:24px' id='page' name='page' onchange='getPage(this.value)' >
                        <?php
                        for($a=0;$a<$pages;$a++)
                        {
                            if($a+1==$page)
                            echo "<option selected>".($a+1)."</option>";
                            else
                            echo "<option >".($a+1)."</option>";
                        }
                        ?>
                    </select>
                    of <?php echo $pages;?> 
                </td>
            </table>
        </td>
    </tr>   
    </table>
    </form>
    <?php

//listMaker('sms_files as a left join po_file as k on a.trans_no=k.trans_no','date_sent desc',array('sms','date_sent','letter_code','hide'),'SMS List');
?>
<script>
       datepickr('#date_from_cal', { altInput: document.getElementById('date_from') });
     datepickr('#date_to_cal', { altInput: document.getElementById('date_to') });

     //   datepickr('#datepickr');
     //datepickr('[title="parseMe"]');
    // datepickr.prototype.l10n.months.longhand = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

</script>