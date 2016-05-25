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

function button_press(label,trans_num,type)
    {
		//if (type=='Receive Cash Request')
		
		if (type=='9')
        {
             $('#trans_num2').val(trans_num)
             $('#type2').val(type)
             
               html=""
               html+="<input type='hidden' id='type2' name='type' value='"+type+"'>"	
               html+="<table align=center style='vertical-align:middle'>"
                
                html+="<tr><td colspan=2 style='padding:25px;text-align:center'>"
                html+="<input type='hidden' id='trans_num' name='trans_num' value='"+trans_num+"'>";
                html+="Are you sure you want to Receive this transaction? Do you want to upload an image?</td></tr>";  
            html+="<tr>"
                html+="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload' id='fileToUpload2' accept='image/*''></td>";
            html+="</tr>"
            html+="<tr><td style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' value='Cancel' onclick='close_this()'> </td>"    
            html+="<td  style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' onclick='fileUpload()' value='Submit'></td></tr>";
            html+="</table>"
         //  $('#div_here').dialog('open')
           document.getElementById('div_here').style.display="block"
         // document.getElementById('div_here').innerHTML=html
           document.getElementById('whiteDiv').style.display="block"
            
        }
        else if(type=='7')
        {
           document.getElementById('confirm_div').innerHTML="<input type='button' id='confirm' style='margin-right:5px' value='Confirm' onclick='confirm_pick_btn("+trans_num+")'><input style='margin-left:5px' type='button' id='Cancel' value='Cancel' onclick='cancel()'>"
          	  document.getElementById('confirm_div2').innerHTML="<input type='button' id='confirm' style='margin-right:5px' value='Confirm' onclick='confirm_pick_btn2("+trans_num+")'><input style='margin-left:5px' type='button' id='Cancel' value='Cancel' onclick='cancel2()'>"
         
          if(document.getElementById('payment_type'+trans_num).value!="Check")
           $('#getCashDetails').dialog('open')
          else
           $('#getCheckDetails').dialog('open')
          
           // document.getElementById('getCheckDetails').style.display="block"
           // document.getElementById('whiteDiv').style.display="block"   
           
        }
        else
        {
            if(confirm("Are you sure you want to "+label+"  this transaction?"))
            {
                url="xstatus=change_status&status="+type+"&trans_num="+trans_num
               // alert(url)
                loadXMLDoc('get_type.php?'+url,releadPage)
              //  document.getElementById('form1').action = 'change status.php?status='+type;
              // document.form1.submit();
            }
        }
        
    }
    function getPage(page) {
         document.getElementById('page').value=page
       document.form1.submit();
    }
    function get_data(page_type,type,trans_num)
    {   
        notSubmit=true
        if(type=="View")         
        document.getElementById('form1').action = 'view_datas.php?trans_num='+trans_num+"&page_type="+page_type;
        else if (type=='Reject') {
            notSubmit=false
            test="confirm_btn(\""+page_type+"\","+trans_num+")"
            document.getElementById('reject_btn').innerHTML="<input type='button' value='Confirm' onclick='"+test+"'> <input type='button' value='Cancel'>";
            document.getElementById('reject_div').style.display="block"
        }
        else if (type=='Request Release') {
            notSubmit=false
            if (confirm("Are you sure you want to Approve this transaction?"))
            {
                // document.getElementById('form1').action = 'view_for_approve.php'
                url="xstatus=change_status&status=Request Release&trans_num="+trans_num
                loadXMLDoc('get_type.php?'+url,reloadPage)
                return false;
            }
        }
        else
        {
            if(page_type=="Without PO")
            page_type="withoutpo"
		else
page_type="withpo"
		   
            document.getElementById('form1').action = 'wo_po_form.php?type='+page_type+"&trans_num="+trans_num;
        }
        if (notSubmit) 
            document.form1.submit();
        return false;
    }
    function confirm_check_btn(trans_num)
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
    function reloadPage(result) 
    {
       document.form1.submit();
    }
    function reject_this(page_type,type,trans_num)
    {
        test="confirm_btn(\""+page_type+"\","+trans_num+",\""+type+"\")"
        document.getElementById('reject_btn').innerHTML="<input type='button' value='Confirm' onclick='"+test+"'> <input type='button' value='Cancel'>";
        $( "#reject_div" ).dialog( "open" );
        //document.getElementById('reject_div').style.display="block"
    }
    function confirm_btn(page_type,trans_num,status)
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
    
     function confirm_pick_btn(trans_num)
    {
        if(document.getElementById('name').value==''||
        document.getElementById('cv').value==''||
        document.getElementById('title').value=='')
            alert("Please Enter complete Details")
        else
        {
        alert("kai")
            name=document.getElementById('name').value
            cv=document.getElementById('cv').value
            title=document.getElementById('title').value
            url="xstatus=readyForPickUp&status=6&trans_num="+trans_num+"&name="+name+"&cv="+cv+"&title="+title
            alert(url)
            loadXMLDoc('get_type.php?'+url,releadPage)
        }
        
    } 
     function close_this() {
        $('#div_here').hide()
        $('#whiteDiv').hide()
    }
   
      function confirm_pick_btn2(trans_num)
    {
    
        if(document.getElementById('name2').value==''||
        document.getElementById('title2').value=='')
            alert("Please Enter complete Details")
        else
        {
            name=document.getElementById('name2').value
            cv=""
            title=document.getElementById('title2').value
            url="xstatus=readyForPickUp&status=7&trans_num="+trans_num+"&name="+name+"&cv="+cv+"&title="+title
            loadXMLDoc('get_type.php?'+url,releadPage)
        }
        
    }
    function releadPage(result) {
		//alert(result)
        type=document.getElementById('type').value
        document.getElementById('form1').action = 'view_data_combine.php?type='+type;
        document.form1.submit();
    }
    function fileUpload()
    {
    	//inputElement=document.getElementById('fileToUpload')
    	//test1=document.getElementById('test1')
    	//fileList = inputElement.files
    	//document.getElementById('test1')=fileList
    	
    	//$('#test2').val($('#fileToUpload').val())
    	//alert($('#test4').html())
    	
    	//alert($('#fileToUpload').html())
    	//alert($('#fileToUpload').val())
    	
    	//document.getElementById('test1').innerHTML=document.getElementById('fileToUpload').innerHTML
        if(document.getElementById('fileToUpload2').value=='')
        {
            alert("Please Choose a File")
        }
        else
        {
          // document.getElementById("form2").target = "_blank";
            document.getElementById('form2').action = 'file_upload.php';
           document.form2.submit();
        }
    }
    
    function cancel()
    {
    	$("#getCheckDetails").dialog( "close" );	
    }
    function cancel2()
    {
    	$("#getCashDetails").dialog( "close" );	
    }
    function reject_cancel()
    {
    	$("#reject_div").dialog( "close" );	
    }
	
    $(function() {
    
    
      /*$( "#div_here" ).dialog(
	    {
	    height:300,
	    modal: true,
	    width:450
	    
	    }
	    );
	    $("#div_here").dialog( "close" );
	    */
	    $( "#reject_div" ).dialog(
	    {
	    height:275,
	    modal: true,
	    width:380
	    
	    }
	    );
	    $("#reject_div").dialog( "close" );
	    
	    
	    $( "#getCheckDetails" ).dialog(
	    {
	    height:275,
	    modal: true,
	    width:380
	    
	    }
	    );
	    
	    $("#getCheckDetails").dialog( "close" );
	     $( "#getCashDetails" ).dialog(
	    {
	    height:275,
	    modal: true,
	    width:380
	    
	    }
	    );
	    
	    $("#getCashDetails").dialog( "close" );
	    
	 //    document.getElementById('confirm_div').innerHTML="<input type='button' id='confirm' style='margin-right:5px' value='Confirm' onclick='confirm_check_btn("+trans_num+")'><input style='margin-left:5px' type='button' id='Cancel' value='Cancel' onclick='cancel()'>"
      //display:none;position:fixed;top:22%;left:32%;width:400px;height:200px;     
  });
</script>
<form action="file_upload.php"   name='form2' id='form2'  method="post" enctype="multipart/form-data">
  <div id='div_here' style='display:none;z-index:11;position:fixed;top:20%;left:32%;vertical-align:middle;border:1px solid black;background-color:white'>
    <?PHP 
               $html="<table align=center style='vertical-align:middle'>";
                
               $html.="<tr><td colspan=2 style='padding:25px;text-align:center'>";
             //   $html.="<input type='hidden' id='trans_num' name='trans_num' value='"+trans_num+"'>";
                $html.="Are you sure you want to Receive this transaction? Do you want to upload an image?</td></tr>";  
            $html.="<tr>";	
                $html.="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload' id='fileToUpload2' accept='image/*''></td>";
//	$html.="<td colspan=2 style='padding:15px;text-align:center'><input type='file' name='fileToUpload2' id='fileToUpload' accept='image/*''></td>";
            $html.="</tr>";
            $html.="<tr><td style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' value='Cancel' onclick='close_this()'> </td>";    
            $html.="<td  style='text-align:center;padding-top:15px;padding-bottom:25px'><input type='button' onclick='fileUpload()' value='Submit'></td></tr>";
            $html.="</table>";
//</form>" ;
echo $html;
?>
<input type='hidden' name='trans_num' id='trans_num2'>
<input type='hidden' name='type' id='type2' >
   </div>
</form>
<form name='form1' id='form1' method=post  enctype="multipart/form-data">

    <div id='reject_div' style='display:none;z-index:11;width:300px;border:1px solid black;background-color:white'>
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
$type="With PO";
if(!empty($_REQUEST['type']))
$type=$_REQUEST['type'];
if($type==1)
$type='Without Po'; 

$active_po="class='active selected_tab '";
$active_wpo="class='tabby'";
if($type=='Without Po')
{
    $active_wpo="class='active selected_tab '";
    $active_po="class='tabby'";
}
?>
<div id='getCheckDetails' style='display:none;z-index:11;border:1px solid black;background-color:white;padding:10px'>
    <table>
        <?php
        echo "<tr><th colspan=3 style='text-align:center;padding:10px' >Enter Check Details</th></tr>";
        echo textMaker('Title','title','');
        echo textMaker('Name of Check','name','');
        echo textMaker('CV#','cv','');
        echo "<tr><td colspan=2 id='confirm_div' STYLE='text-align:center;padding:10px'></td></tr>";
        ?>
    </table>
</div>

<div id='getCashDetails' style='display:none;z-index:11;border:1px solid black;background-color:white;padding:10px'>
    <table>
        <?php
        echo "<tr><th colspan=3 style='text-align:center;padding:10px' id='check_header'>Enter Cash Details</th></tr>";
        echo textMaker('Title','title2','');
        echo textMaker('Name ','name2','');
         echo "<tr><td colspan=2 id='confirm_div2' STYLE='text-align:center;padding:10px'></td></tr>";
        ?>
    </table>
</div>

<div>
    <table class='filter' align=left>
        <tr>
            <td colspan=2>
                <ul class='nav nav-tabs'>
                    <li <?php echo $active_po;?> role='presentation'><a href='view_data_combine.php?type=With Po'>With PO</a></li>
                    <li <?php echo $active_wpo;?> role='presentation'><a href='view_data_combine.php?type=Without Po'>Without PO</a></li>
                </ul>
            </td>
    </tr>
<?php echo "<input type='hidden' id='type' name='type' value='".$type."'>"; ?>
    <?php
    
    $status_type=getPost('status','Choose');
    $requestor_id=getPost('requestor_id','Choose');
    
    
    $company_name=getPost('company_name','Choose');
    
    $supplier_id=getPost('supplier','Choose');
    
    
    $date_from=getPost('date_from','') ;
    $date_to=getPost('date_to','') ;
    $company_name=getPost('company_name','Choose');
    $supplier_id=getPost('supplier','Choose');
    $secretary_id=getPost('secretary_id','Choose');
    //$status_array=array('Request Release','Ready for pick up','Receive Request','Receive Cash Request','Rejected','Receive Cash Request' ,'Received');
//$filter="where status  in ('Request Release','Ready for pick up','Receive Cash Request' ,'Received')";
    $status_array=array('Pending','Request Release','Ready for pick up','Receive Request','Rejected','Receive Cash Request' ,'Received');
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
   
    $select="select concat(first_name,' ',last_name) as name,account_id from master_address_file where mas_status=1 and account_type='secretary' order by name";
    $result1 = $conn->query($select);
    while($row1=$result1->fetch_assoc())
    $secretary[$row1['account_id']]=$row1['name'];
    
    $select="select concat(first_name,' ',last_name) as name,account_id from master_address_file where mas_status=1 and account_type='engineer' order by name";
    $result1 = $conn->query($select);
    while($row1=$result1->fetch_assoc())
    $engineer[$row1['account_id']]=$row1['name'];
    
   echo  selectMakerEach('Secretary','secretary_id',$secretary,'' , $secretary_id);
   
    $select="select company_name from po_file where mas_status=1 and company_name!='' group by company_name order by company_name";
    $result1 = $conn->query($select);
    while($row1=$result1->fetch_assoc())
    $company[$row1['company_name']]=$row1['company_name'];
    
   echo  selectMakerEach('Company Name','company_name',$company,'' , $company_name);
   
   
   
    $select="select supplier from po_file where mas_status=1 and supplier!='' group by supplier order by supplier";
    $result1 = $conn->query($select);
    while($row1=$result1->fetch_assoc())
    $supplier[$row1['supplier']]=$row1['supplier'];
    
   echo  selectMakerEach('Supplier','supplier',$supplier,'' , $supplier_id);
   
   echo "<tr ><td colspan=2 style='padding:10px;text-align:center'><input type='submit' value='Search'></td></tr>";
    ?>
    <tr><td><?php echo "<h2 style='text-align:left;padding:0px' >RM $type</h2>";?></td></tr>
</table>
    <br>
</div>
<?php


$limit=10;
$start=0;
$page=1;

			//print_r($_SESSION);
			//echo "<br>(".$access[$data[$a][$k-1]]."=".$data[$a][$k-1].")";
			


if(!empty($_POST['page']))
{
    $page=$_POST['page'];
    $start=(($_POST['page']-1)*$limit);
}
$filter=" where ( mas_status!=0)  ";
$filter=whereMaker($filter,'status',$status_type);
$filter=whereMaker($filter,'requestor',$requestor_id);
if($date_from!='' ||$date_to!='')
{
    
    //  $filter.=" date_created between '".date("Y-m",strtotime($date_from))."' and '".date("Y-m-d",strtotime($date_to))."'";
         
    $filter.=" and ";
     if($date_from!='' && $date_to!='')
            $filter.=" date_created >= '".date("Y-m-d",strtotime($date_from))." 00:00:00' and date_created <='".date("Y-m-d",strtotime($date_to))." 23:59:59'";
     else if($date_from!='')
           $filter.=" date_created like '".date("Y-m-d",strtotime($date_from))."%' ";
     else
           $filter.=" date_created like '".date("Y-m-d",strtotime($date_to))."%' ";
}
if(strtolower($type)=="with po")
{
    $select="select * from po_file $filter and  po!='---' ";
    $column=array('Letter Code','Date','Po#','Requestor','Company Name','Supplier','Engineer','Secretary','Payment Type','Total Amount','Created By','AE Status','Status');
    $val=array('letter_code','date_created','po','requestor','company_name','supplier','engineer','secretary','payment_type','total_amount','created_by','status','trans_no','mas_status','ae_status');
}
else
{
    $type="Without PO";
    $active_po="class='tabby'";
    $active_wpo="class='active selected_tab'";
    $select="select * from po_file $filter and  po='---' ";
    $column=array('Letter Code','Date','TXT&ID_NO','Requestor','Company Name','Supplier','Secretary','Payment Type','Total Amount','Created By','AE Status','Status');
    $val=array('letter_code','date_created','TXT&ID_NO','requestor','company_name','supplier','secretary','payment_type','total_amount','created_by','status','trans_no','mas_status','ae_status');
}
//echo $select;
$select2=str_replace("*","id",$select);
$result = $conn->query($select2);
$rowcount=mysqli_num_rows($result);
$data=array();
$status_name=array();
$result = $conn->query("select * from system_steps_file");
while($row=$result->fetch_assoc())
{
	$status_name[$row['step_count']]=$row['status_label'];
	
	$men="menu_id";
	if($type==1||$type=='Without PO')
	$men="menu_id_wo_po";
	if(!empty($access_menu[$row[$men]]))
	$button_name[$row['step_count']]=$row['button_name'];
	else
	$button_name[$row['step_count']]="";
	$menu_id[$row['step_count']]=$row[$men];
	
}
$result = $conn->query($select." order by id desc limit $start,$limit ");
//echo "<br>".$select." order by id desc limit ".$start.",".$limit."<br>";
//print_r($result);
$pages=$rowcount/$limit;
if((int)$pages<$pages)
    $pages++;
$pages=(int)$pages;
// $result = $conn->query($select);
$data=array();
$ae_status=array();
while($row=$result->fetch_assoc())
{
    $items=array('');
 $trans_num=$row['trans_no'];
echo "<input type='hidden' id='payment_type".$trans_num."' value='".$row['payment_type']."'>";   
    
    for($a=0;$a<count($val)-1;$a++)
    {
        if($a==0 &&$val[$a]!='' )
        $items[$a]=$row[$val[$a]];    
        else if($a==1)
        $items[$a]=date("F d,Y",strtotime($row[$val[$a]]));
        else
        {
            if(empty($row[$val[$a]]))
            $row[$val[$a]]="";
            if($val[$a]=='requestor')
            {
                if(empty($requestor[$row[$val[$a]]]))
                {
                    $select="select concat(first_name,' ',last_name) as name from master_address_file where account_id='".$row[$val[$a]]."' limit 1";
                    $result1 = $conn->query($select);
                    $row1=$result1->fetch_assoc();
                    $requestor[$row[$val[$a]]]=$row1['name'];
                }
                $items[$a]=$requestor[$row[$val[$a]]];
            }
            else if($val[$a]=='engineer')
            {
                 $select2="select concat(first_name,' ',last_name) as name from master_address_file where
                account_type='Engineer' and mas_status=1 and account_id='".$row[$val[$a]]."' limit 1";
                 $result2 = $conn->query($select2);
                 $rows2=$result2->fetch_assoc();
                 $engineer_name=$rows2['name'];
                 
                 $items[$a]=$engineer_name;
            }
            else if($val[$a]=='secretary')
            {
                $select2="select concat(first_name,' ',last_name) as name from master_address_file where
                account_type='Secretary' and mas_status=1 and account_id='".$row[$val[$a]]."' limit 1";
                 $result2= $conn->query($select2);
                 $rows2=$result2->fetch_assoc();
                 $secretary_name=$rows2['name'];
                $items[$a]=$secretary_name;
            }
            else if($val[$a]=='total_amount')
             $items[$a]=number_format($row[$val[$a]],2);
            else
                $items[$a]=$row[$val[$a]];
            //   echo "<br>".$a."=(".$val[$a].") ".$items[$a];
        }
    }
    $ae_status[]=$row['ae_status'];
    $status[]=$row['status'];
    $data[]=$items;
}
?>

<?php

?>

<table class='table_data' style='width:1050px'>
    <?php
        echo "<tr>";
    for($a=0;$a<count($column);$a++)
            echo "<th>".$column[$a]."</th>";
    echo "<th colspan=2>Action</th>";
        echo "</tr>";
        
        //print_r($access);
        
        
        for($a=0;$a<count($data);$a++)
        {
            echo "<tr>";
            echo "<td>".$data[$a][0]."</td>";
            echo "<td style='width:200px'>".$data[$a][1]."</td>";
            for($k=2;$k<count($data[$a])-3;$k++)
             {
                echo "<td>".$data[$a][$k]."</td>";
				}
			 $trans_num=$data[$a][$k+1];
			/*
            /if($data[$a][$k+2]!=1)
            echo "<td>Rejected</td>";  
            else
            {
				$value=$data[$a][$k];
				if($value==$for_qa_approval)
				$value=$qa_text;
				else if($value==$for_ae_approval)
				$value=$ae_text;
				else if($value==$request_release)
					$value=$request_release_text;		
				echo "<td>".$value."</td>";
			}
            $trans_num=$data[$a][$k+1];
          if(!empty($access['View']))
            echo "<td><input type='image' src='assets/view_details.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','View','".$trans_num."')\"></td>";
            
            //echo "<br>".$_SESSION['uname']."==".$data[$a][$k-1]." && (".$data[$a][$k]."==pending'|| ".$data[$a][$k+2];
            if(!empty($access['Edit']))
            {
               if($_SESSION['uname']==$data[$a][$k-1] && ($data[$a][$k]=='pending'|| $data[$a][$k+2]!=1))
               echo "<td><input type='image' src='assets/Edit.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','Edit','".$trans_num."')\"></td>";
            }  
            //echo "<td><input type='image' src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"get_data('".$type."','Reject','".$data[$a][$k]."')\"></td>";
            if(!empty($access['Reject']))
            {
               if($data[$a][$k+2]==1)
               echo "<td><img  src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"reject_this('".$type."','".$data[$a][$k]."','".$trans_num."');\"></td>";
            }
            
            $k++;

            echo "<br>";
            echo "<td>";
          
			if(!empty($access[$data[$a][$k-1]]))
          {
			$value=$data[$a][$k-1];
			if($value==$request_release)
			$value=$request_release_btn;
            if($data[$a][$k-1]!='Received')
            echo "<input type='button' onclick='button_press(this.value,".$data[$a][$k].")' value='".$value."'>";
            else "<td></td>";
          }
            echo "</td>";
        */
       // echo "<br>".$status[$a];
       
       
    	$temp_status=$status[$a];
    	
		echo "<td>";
		
		if($status[$a]>=4 &&$ae_status[$a]!=1)
		$temp_status=4;
		
		if($status[$a]>='Rejected')
			$status_name[$status[$a]]=$status[$a];
		else if($status[$a]>=4 &&!empty($access_menu[$menu_id[4]])&&$ae_status[$a]!=1)
			echo "<input type='button' onclick='button_press(this.value,".$data[$a][$k+1].",4)' value='".$button_name[4]."'>";
		else if($ae_status[$a]==1 &&$status[$a]==4)
			$temp_status=$status[$a]+1;
		else if($ae_status[$a]==1 )
		echo str_replace("al","",str_replace("For","",$status_name[4]))."ed";
		echo "</td>";	
		//echo "<br>Status".$status[$a]." ".$temp_status."=".$ae_status[$a];
			
		if($status[$a]=='QA Approve')
		echo "<td>".$status_name[2]."</td>";	
		else if($status[$a]=='pending')
		echo "<td>Pending</td>";
		else if($status[$a]=='Received')
		echo "<td>Received</td>";
		else 
			echo "<td>".$status_name[$temp_status]."</td>";
		if($status[$a]==4)
		$status[$a]=$status[$a]+1;
		
		if($status[$a]=='QA Approve' &&!empty($access_menu[$menu_id[2]]))
		echo "<td><input type='button' onclick='button_press(this.value,".$data[$a][$k+1].",2)' value='".$button_name[2]."'></td>";	
		else if($status[$a]=='pending'||($status[$a]==8 && $ae_status[$a]==2))
		echo "<td></td>";
		else if(!empty($access_menu[$menu_id[$status[$a]]]))
			echo "<td><input type='button' onclick='button_press(this.value,".$data[$a][$k+1].",\"".$status[$a]."\")' value='".$button_name[$status[$a]]."'></td>";
		else
		echo "<td></td>";
		

		
		//if(!empty($access['View']))
            echo "<td><input type='image' src='assets/view_details.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','View','".$trans_num."')\"></td>";
            
            //echo "<br>".$_SESSION['uname']."==".$data[$a][$k-1]." && (".$data[$a][$k]."==pending'|| ".$data[$a][$k+2];
          //  if(!empty($access['Edit']))
            //{
               if($_SESSION['uname']==$data[$a][$k-1] && ($data[$a][$k]=='pending'|| $data[$a][$k+2]!=1)||$status[$a]=='Rejected')
               echo "<td><input type='image' src='assets/Edit.png' name='image' width='20' height='20' onclick=\"get_data('".$type."','Edit','".$trans_num."')\"></td>";
            //}  
            //echo "<td><input type='image' src==$data[$a][$k-1]'assets/cross.jpg' name='image' width='20' height='20' onclick=\"get_data('".$type."','Reject','".$data[$a][$k]."')\"></td>";
           // if(!empty($access['Reject']))
            //{
             $reject=true;
            if( $status[$a]!='Rejected' && $status[$a]==4 && !empty($access_menu[$menu_id[$status[$a]]]))
             $reject=false;
            else if( $status[$a]!='Rejected' && $status[$a]==8 && !empty($access_menu[$menu_id[$status[$a]]]))
             $reject=false;
               if($status[$a]!='Rejected' && ( $reject ||$_SESSION['uname']==$data[$a][$k-1]))
               echo "<td><img  src='assets/cross.jpg' name='image' width='20' height='20' onclick=\"reject_this('".$type."','".$data[$a][$k]."','".$trans_num."');\"></td>";
           // }
		 echo "</tr>";
        }
        
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
<script>
       datepickr('#date_from_cal', { altInput: document.getElementById('date_from') });
     datepickr('#date_to_cal', { altInput: document.getElementById('date_to') });

</script>