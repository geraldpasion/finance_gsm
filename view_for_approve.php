<?php
include 'page_header.php';

?>

<div id='whiteDiv' style='display:none;z-index:10;position:fixed;top:0;left:0;background-color:white;opacity: 0.4;filter: alpha(opacity=40);width:100%;height:100%' >
</div>

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
    .container
    {
        width: 970px;
    }
   
    .action_btn
    {
        background-color:blue;
        color:white;
        padding:25px;
        
    }
       .nav-tabs {
    border-bottom: 1px solid #ddd;
    }
    .nav {
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
 
    .nav-tabs>li {
        float: left;
        margin-bottom: -1px;
    }
    .nav>li {
        position: relative;
        display: block;
    }
    .nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus .selected_tab {
        color: #555555;
        background-color: #fff;
        border: 1px solid #ddd;
        border-bottom-color: transparent;
        cursor: default;
        padding: 10px;
    }
    .tabby
    {
        padding:10px;
    }
    .nav-tabs>li>a {
        
        margin-right: 2px;
        line-height: 1.428571429;
        text-decoration:none;
        border: 1px solid transparent;
        border-radius: 4px 4px 0 0;
    }
</style>
<script>
    function get_data(page_type,type,trans_num) {
        alert("kai hiwatari")
        notSubmit=true
        if(type=="View")
        document.getElementById('form1').action = 'view_datas.php?trans_num='+trans_num+"&page_type="+page_type;
        else if (type=='Reject') {
            if(confirm("Are you sure you want to Reject this transaction?"))
                document.getElementById('form1').action = 'delete_transaction.php?trans_num='+trans_num+"&page_type="+page_type
            else
            notSubmit=false
        }
        else
        document.getElementById('form1').action = 'wo_po_form.php?type'+type+"&trans_num="+trans_num;
        if (notSubmit) 
            document.form1.submit();
    }
    function close_this() {
        document.getElementById('div_here').style.display="none"
        document.getElementById('whiteDiv').style.display="none" 
    }
    function button_press(type,trans_num)
    {
        if (type=='Receive Cash Request')
        {
            html="<table align=center style='vertical-align:middle'>"
                
                html+="<tr><td colspan=2 style='padding:25px;text-align:center'>"
                html+="<input type='hidden' id='trans_num' name='trans_num' value='"+trans_num+"'>";
                html+="Are you sure you want to Receive this transaction? Do you want to upload an image?</td></tr>";  
            html+="<tr>"
                html+="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload' id='fileToUpload'></td>";
            html+="</tr>"
            html+="<tr><td style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' value='Cancel' onclick='close_this()'> </td>"    
            html+="<td  style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' onclick='fileUpload()' value='Submit'></td></tr>";
            html+="</table>"
            document.getElementById('div_here').style.display="block"
            document.getElementById('div_here').innerHTML=html
            document.getElementById('whiteDiv').style.display="block"
            
        }
        else if(type=='Ready for pick up' && document.getElementById('payment_type'+trans_num).value=="Check")
        {
            document.getElementById('confirm_div').innerHTML="<input type='button' id='confirm' style='margin-right:5px' value='Confirm' onclick='confirm_btn("+trans_num+")'><input style='margin-left:5px' type='button' id='Cancel' value='Cancel' onclick='cancel()'>"
            document.getElementById('getCheckDetails').style.display="block"
            document.getElementById('whiteDiv').style.display="block"   
            
        }
        else
        {
            if(confirm("Are you sure you want to "+type+"  this transaction?"))
            {
                url="xstatus=change_status&status="+type+"&trans_num="+trans_num
                loadXMLDoc('get_type.php?'+url,releadPage)
                //document.getElementById('form1').action = 'change status.php?status='+type;
               // document.form1.submit();
            }
        }
        
    }
    function cancel() {
        document.getElementById('getCheckDetails').style.display="none"
            document.getElementById('whiteDiv').style.display="none" 
    }
    function confirm_btn(trans_num)
    {
        if(document.getElementById('name').value==''||
        document.getElementById('cv').value==''||
        document.getElementById('title').value=='')
            alert("Please Enter complete Details")
        else
        {
            name=document.getElementById('name').value
            cv=document.getElementById('cv').value
            title=document.getElementById('title').value
            url="xstatus=readyForPickUp&status=Ready for pick up&trans_num="+trans_num+"&name="+name+"&cv="+cv+"&title="+title
            loadXMLDoc('get_type.php?'+url,releadPage)
        }
        
    }
    function fileUpload()
    {
        if(document.getElementById('fileToUpload').value=='')
        {
            alert("Please Choose a File")
        }
        else
        {
            document.getElementById('form1').action = 'file_upload.php';
            document.form1.submit();
        }
    }
    function releadPage(result) {
        type=document.getElementById('type').value
        //alert(type)
        document.getElementById('form1').action = 'view_for_approve.php?type='+type;
      //  alert(document.getElementById('form1').action)
        document.form1.submit();
    }
    function getPage(page) {
         document.getElementById('page').value=page
       document.form1.submit();
    }
    function reject_this(page_type,type,trans_num)
    {
        test="confirm_btn_reject(\""+page_type+"\","+trans_num+",\""+type+"\")"
        document.getElementById('reject_btn').innerHTML="<input type='button' value='Confirm' onclick='"+test+"'> <input type='button' value='Cancel'>";
        //document.getElementById('reject_div').style.display="block"
		alert("kai")         
         $( "#reject_div" ).dialog( "open" );
    }
    function confirm_btn_reject(page_type,trans_num,status)
    {
        if (document.getElementById('reasons').value=='') {
            alert("Please enter a reason")
        }
        else
        {
            document.getElementById('form1').action = 'delete_transaction.php?trans_num='+trans_num+"&page_type="+page_type+"&status="+status
            document.form1.submit();
        }
    }
    function po_go(type)
    {
          document.getElementById('type').value=type
            document.form1.submit();
    }
    function reject_cancel()
    {
    	$('#reject_div').hide()
    }
    $(function() {
    $( "#reject_div" ).dialog();
  });
</script>
<div id='getCheckDetails' style='z-index:11;width:270px;display:none;position: fixed;top:15%;left:40%;border:1px solid black;background-color:white;padding:10px'>
    <table>
        <?php
        echo "<tr><th colspan=3 style='text-align:center;padding:10px '>Enter Check Details</th></tr>";
        echo textMaker('Title','title','');
        echo textMaker('Name of Check','name','');
        echo textMaker('CV#','cv','');
        echo "<tr><td colspan=2 id='confirm_div' STYLE='text-align:center;padding:10px'></td></tr>";
        ?>
    </table>
</div>
<?php
$type="";
if(!empty($_REQUEST['type']))
$type=$_REQUEST['type'];
?>
<form name='form1' id='form1' method="post" enctype="multipart/form-data">
   <?php echo "<input type='hidden' id='type' name='type' value='".$type."'>"; ?>
    <div id='div_here' style='z-index:11;vertical-align:middle;display:none;position:fixed;top:22%;left:32%;width:400px;height:200px;border:1px solid black;background-color:white'>
        
    </div>
    <div id='reject_div' style='z-index:11;width:300px;display:none;position: fixed;top:15%;left:40%;border:1px solid black;background-color:white'>
    <table>
        <tr>
            <th style='padding:10px'>Are you sure you want to reject this item? Please Enter reason for rejection</th>
        </tr>
        <tr>
            <td style='padding:10px;'>
                <textarea style='width:280px;height:60px' id='reasons' name='reason'></textarea>
            </td>
        </tr>
        <tr>
            <td style='padding:10px;text-align:center' id='reject_btn' onclick='reject_cancel()'>
            </td>
        </tr>
    </table>
</div>
<?php
$status_type=getPost('status','Choose');
    $requestor_id=getPost('requestor_id','Choose');
    $date_from=getPost('date_from','') ;
    $date_to=getPost('date_to','') ;
$type="With PO";
if(!empty($_REQUEST['type']))
$type=$_REQUEST['type'];
//echo $type;


$limit=10;
$start=0;
$page=1;
if(!empty($_POST['page']))
{
    $page=$_POST['page'];
    $start=(($_POST['page']-1)*$limit);
}
$filter="where status  in ('Request Release','Ready for pick up','Receive Cash Request' ,'Received')";
$filter=whereMaker($filter,'status',$status_type);
$filter=whereMaker($filter,'requestor',$requestor_id);
if($date_from!='' ||$date_to!='')
{
         
    $filter.=" and ";
     if($date_from!='' && $date_to!='')
            $filter.=" date_created >= '".date("Y-m-d",strtotime($date_from))." 00:00:00' and date_created <='".date("Y-m-d",strtotime($date_to))." 23:59:59'";
     else if($date_from!='')
           $filter.=" date_created like '".date("Y-m-d",strtotime($date_from))."%' ";
     else
           $filter.=" date_created like '".date("Y-m-d",strtotime($date_to))."%' ";
}
$size=" style='border:none;background-color:transparent;font-size:18px;font-weight:normal; color: #555555;
        background-color: #fff;
        border: 1px solid #ddd;
        border-bottom-color: transparent;
        cursor: default;
        padding: 10px;'";
$size1=" style='border:none;background-color:transparent;font-size:14px'";
$active_po="class='active selected_tab' $size";
$active_wpo="class='tabby' $size1";
if(strtolower($type)=="with po")
{
    $select="select * from po_file $filter  and po!='---'     order by id desc";
    $column=array('Letter Code','Date','Po#','Requestor','Company Name','Supplier','Engineer','Secretary','Payment Type','Total Amount','Status');
    $val=array('letter_code','date_created','po','requestor','company_name','supplier','engineer','secretary','payment_type','total_amount','status','trans_no');
}
else
{
    $type="Without PO";
    $active_po="class='tabby' $size1";
    $active_wpo="class='active selected_tab' $size";
    $select="select * from po_file $filter and  po='---'   order by id desc";
    $column=array('Letter Code','Date','Requestor','Company Name','Supplier','Secretary','Payment Type','Total Amount');
    $val=array('letter_code','date_created','requestor','company_name','supplier','secretary','payment_type','total_amount','status','trans_no');
}
echo $select;
$select2=str_replace("*","id",$select);
$result = $conn->query($select2);
$rowcount=mysqli_num_rows($result);
$data=array();
$result = $conn->query($select." limit $start,$limit ");
$pages=$rowcount/$limit;
if((int)$pages<$pages)
    $pages++;
$pages=(int)$pages;

while($row=$result->fetch_assoc())
{
    $items=array('');
    $trans_num=$row['trans_no'];
    echo "<input type='hidden' id='payment_type".$trans_num."' value='".$row['payment_type']."'>";   
    for($a=0;$a<count($val);$a++)
    {
        
        if($a==0 &&$val[$a]!='' )
        {$items[$a]=$row[$val[$a]];    }
        else if($a==1)
        $items[$a]=date("F d,Y",strtotime($row[$val[$a]]));
        else
        {
            if(empty($row[$val[$a]]))
            $row[$val[$a]]="";
            $items[$a]=$row[$val[$a]];
        }
    }
    $status[]=$row['status'];
    $data[]=$items;
}
echo "<table>";
//<a href='view_for_approve.php?type=With Po'>
//<a href='view_for_approve.php?type=Without Po'>
echo "<tr>
<td colspan=10  >
<div>
    <table  class='filter' align=left>
          <tr>
               <td role='presentation'> 
                    <input  type='button' $active_po onclick='po_go(this.value)' value='With PO'>
               </td>
               <td role='presentation'>
                    <input   type='button' $active_wpo onclick='po_go(this.value)' value='Without PO'>
               </td>
          </tr>
     </table>
    
</div>
</td></tr>
";
    $status_array=array('pending','For Approval','Rejected');
    echo selectMakerValue('Status','status',$status_array,'',$status_array,$status_type);
    
    echo "<tr><th style='border:none;text-align:left'>Date Sent</th>
        <th colspan=10 style='text-align:left; border:none'>
     <table align=left><tr> <td style='border:none'>
     <input title='parseMe' style='width:120px' id='date_from' name='date_from' value='$date_from'>
     <span id='date_from_cal' class='calendar-icon'></span>
     
     </td><td style='padding:1px;border:none'>-</td><td style='border:none'>
     <input title='parseMe' style='width:120px' id='date_to' name='date_to' value='$date_to'>
     <span id='date_to_cal' class='calendar-icon'></span>
     
     </td></table></td></tr>";
    
    $select="select concat(first_name,' ',last_name) as name,account_id from master_address_file where mas_status=1 and account_type='Account Executive' order by name";
    $result1 = $conn->query($select);
    while($row1=$result1->fetch_assoc())
    $requestor[$row1['account_id']]=$row1['name'];
    
   echo  selectMakerEach('Requestor','requestor_id',$requestor,'' , $requestor_id);
   echo "<tr ><td colspan=2 style='padding:10px;text-align:center'><input type='submit' value='Search'></td></tr>";
   
    echo "<TR><TD colspan=10><h2 style='text-align:left' >RM $type</h2></td></tr>   ";

?>

<table class='table_data'>
    <?php
        echo "<tr>";
    for($a=0;$a<count($column);$a++)
    {
            echo "<th>".$column[$a]."</th>";
    }
    echo "<th colspan=2>Action</th>";
        echo "</tr>";
        for($a=0;$a<count($data);$a++)
        {
            echo "<tr>";
            for($k=0;$k<count($data[$a])-1;$k++)
                echo "<td>".$data[$a][$k]."</td>";
          if(!empty($access[$data[$a][$k-1]]))
          {
            if($data[$a][$k-1]!='Received')
            else "<td></td>";
          }
          if(!empty($access['View']))
          echo "<td><input type='image' src='assets/view_details.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','View','".$data[$a][$k]."')\"></td>";
            
            //echo "<td><input type='image' src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"get_data('".$type."','Reject','".$data[$a][$k]
            //."')\"></td>";
          if(!empty($access[$data[$a][$k-1]]))
          echo "<td><img  src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"reject_this('".$type."','".$data[$a][$k-1]."','".$data[$a][$k]."');\"></td>";
         
          echo "</tr>";
        }
    ?>
    <tr>
        <td colspan=20 style='text-align:center'>
            <table align=center >
                <?php
                    if($page!=1)
                    echo "<td style='border:none;padding:0px'><input style='padding:10px' type='button' value='First' onclick='getPage(1)'></td>";
                    if($page>1)
                    echo "<td style='border:none;padding:0px'><input type='button' value='Prev' onclick='getPage(".($page-1).")'></td>";
                    if($page+1<=$pages)
                    echo "<td style='border:none;padding:0px'><input type='button' value='Next' onclick='getPage(".($page+1).")'></td>";
                    if($page!=$pages)
                    echo "<td style='border:none;padding:0px'><input type='button' value='Last' onclick='getPage(".($pages).")'></td>";
                ?>
                <td style='border:none;padding:0px;padding-left: 10px' >Page
                    <select  style='font-size:24px' id='page' name='page' onchange='getPage(this.value)' >
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
<script>
       datepickr('#date_from_cal', { altInput: document.getElementById('date_from') });
     datepickr('#date_to_cal', { altInput: document.getElementById('date_to') });

</script>