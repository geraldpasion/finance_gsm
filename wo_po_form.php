<?php
    include 'page_header.php';
            $trans_num="";
$update_type="";
    ?>
    <style>
    .cross_image
    {
    width:15;
    height:15;
    }
    </style>
    <div id='child'></div>
    <script>
    function check_po(value)
    {
    	url="xstatus=checkPO&PO="+value
        result=loadXMLDoc('get_type.php?'+url,show_result)
    }
    function show_result(value)
    {
    	if(value!='')
    	{
    		alert(value)
    		$('#po').val('')
    	}
    }
        function addCommas(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
        function clear_row(a)
        {
        	$('#item'+a).val("")
        	$('#description'+a).val("")
        	$('#quantity'+a).val("")
        	$('#unit_price'+a).val("")
        	$('#total_amount'+a).val("")
			computeAmount(a)
        }
        function addMore() {
            
            document.getElementById('total_am').style.display="block"
            item_no=document.getElementById('item_no').value
            
            item=[]
                unit_price=[]
                description=[]
                quantity=[]
            
            for(a=1,b=0;a<=item_no;a++)
            {
                item[a]=document.getElementById('item'+a).value
                unit_price[a]=document.getElementById('unit_price'+a).value
                description[a]=document.getElementById('description'+a).value
                quantity[a]=document.getElementById('quantity'+a).value
            }
            item_no=parseInt(document.getElementById('item_no').value)+1;
            html=document.getElementById('table_items').innerHTML
           // alert(html)
            add="<tr>";
            add+="<td>"+item_no+"<img  src='assets/cross.jpg' name='image' class='cross_image' onclick='clear_row("+item_no+")'></td>";
            add+="<td><input type='text' id='item"+item_no+"' name='item"+item_no+"' placeholder='item' value=''></td>";
            add+="<td><input type='text' id='description"+item_no+"' name='description"+item_no+"' placeholder='description'></td>";
            add+="<td><input type='text' id='quantity"+item_no+"' name='quantity"+item_no+"' placeholder='quantity' onchange='computeAmount("+item_no+")'></td>";
            add+="<td><input type='text' id='unit_price"+item_no+"' name='unit_price"+item_no+"' placeholder='unit_price'  onchange='computeAmount("+item_no+")' ></td>";
            add+="<td><input style='border:none;text-align:right' type='text' readonly id='total_amount"+item_no+"' name='total_amount"+item_no+"'  value='' placeholder='amount'></td>";
                                
            add+="</tr>";
           document.getElementById('item_no').value=item_no
           document.getElementById('table_items').innerHTML=html+add
           
           
           for(aw=1,b=0;aw<item_no;aw++)
            {
            
               document.getElementById('item'+aw).value=item[aw]
                document.getElementById('unit_price'+aw).value= unit_price[aw]
                document.getElementById('description'+aw).value=description[aw]
                document.getElementById('quantity'+aw).value=quantity[aw]
                computeAmount(aw)
            }
           
        }
        function computeAmount(a) {
            quantity2=parseInt(document.getElementById('quantity'+a).value)
            unit_price2=parseFloat(document.getElementById('unit_price'+a).value)
            if(unit_price2==undefined)
            unit_price2="";
            //if(description2==undefined)
            //description2="";
            if (!isNaN(quantity2) && !isNaN(unit_price2)) {
                amount=quantity2*unit_price2
                amount=addCommas(amount)
                //amount=amount.toFixed(2)
                document.getElementById('total_amount'+a).value=amount
             //   alert(document.getElementById('total_amount'+a).value)
            }
            item_no=document.getElementById('item_no').value
            total_amount=0
            for(as=1;as<=item_no;as++)
            {
                
                quantity2=parseInt(document.getElementById('quantity'+as).value)
                unit_price2=parseFloat(document.getElementById('unit_price'+as).value)
                //alert(quantity+" "+unit_price)
                if (!isNaN(quantity2) && !isNaN(unit_price2)) {
                    total_amount+=parseFloat(quantity2*unit_price2)
                }
            }
            document.getElementById('grand_amount').value=addCommas(total_amount)
        }
        function save_this(type ,status)
       {
             document.getElementById('update_type').value=type
                document.getElementById('status').value=status
               document.form1.submit();document.form1.submit();
       }
        function submit_this(type ,status)
        { 
            error=1
            if(document.getElementById('requestor').value=='Choose')
            {alert("Please enter Requestor")}
            else if(document.getElementById('company_name').value=='')
            {alert("Please enter Company Name")}
            else if(document.getElementById('secretary').value=='Choose')
            {alert("Please enter Secretary")}
            else if(document.getElementById('supplier').value=='')
            {alert("Please enter Supplier")}
            else if(document.getElementById('payment_type').value=='Choose')
            {alert("Please enter Payment Type")}
            else if(document.getElementById('title').value=='')
            {alert("Please enter Title")}
            else if(document.getElementById('jo').value=='')
            {alert("Please enter Jo")}
            else if(document.getElementById('engineer').value=='')
           {alert("Please enter Engineer")}
           else if(document.getElementById('page').value=='')
           {alert("Please enter Page")}
           else
           {
            error=0;
                item_no=document.getElementById('item_no').value
                for(a=1,b=0;a<=item_no;a++)
                {
                    item=document.getElementById('item'+a).value
                    unit_price=document.getElementById('unit_price'+a).value
                    description=document.getElementById('description'+a).value
                    quantity=document.getElementById('quantity'+a).value

                    if(item!='' && unit_price!='' && description!='' &&  quantity!='')
                    b++;
                   else if(item=='' && unit_price=='' && description=='' && quantity=='')
                    {}
                    else if(item=='' || unit_price=='' || description=='' ||  quantity=='')
                    {
                        alert("Please Enter complete Information")
                        error=1
                        break;
                    }
                }
                if (b==0) {
                    error=1
                    alert("Please enter at least 1 Item")
                }
           }
           //alert(error)
            if (error==0) 
            {
                document.getElementById('update_type').value=type
                document.getElementById('status').value=status
               document.form1.submit();
            }
            
        }
        function getRequestor(value)
        {
            url="xstatus=getRequestor&requestor="+value
            result=loadXMLDoc('get_type.php?'+url,getChildren)
        }
        function getChildren(result)
        {
            data=result.split("~")  
            document.getElementById('engineer').innerHTML=data[0]
            document.getElementById('secretary').innerHTML=data[1]
        }
        
    </script>
      <form name='form1' id='form1' method=post>
    <?php
    if(!empty($_REQUEST['trans_num']))
     $trans_num=$_REQUEST['trans_num'];
      $type="With Po";
      if(!empty($_REQUEST['type']))
    $type=$_REQUEST['type'];
      
    $type1="type=With Po";
    if($type=="withoutpo")
    $type1="type=Without Po";
    echo " <input type='hidden' id='type' name='type' value='$type'>";
//print_r($_POST);
    if(!empty($_POST['status']) && $_POST['status']!='Choose')
    {

          $select="delete from po_header_file where user_name='".$_SESSION['uname']."' ";
        $result = $conn->query($select);
        $requestor=$_POST['requestor'];
        $company_name=$_POST['company_name'];
        $secretary=getPost('secretary','Choose');
        $supplier=getPost('supplier','Choose');
        $payment_type=getPost('payment_type','Choose');
        $item_description=getPost('item_description','');
        $title=$_POST['title'];
        $jo=$_POST['jo'];
        $po="---"; 
       if(!empty($_REQUEST['po']))
           $po=$_POST['po'];
        $TXT_ID_NO="---";
        if(!empty($_REQUEST['TXT&ID_NO'])) 
        $TXT_ID_NO=$_REQUEST['TXT&ID_NO'];
       $table="po_file";
       $engineer=getPost('engineer','Choose');
       $page=$_POST['page'];
       $status=$for_qa_approval;	
       if($_POST['status']=="Save")
       $status="pending";
        $number_code=$_POST['number_code'];
    
        if($_POST['update_type']=='Edit')
        {
            $trans_num=$_POST['trans_num'];
           $letter_code=get_letter_code($number_code);
           $columns=array('requestor', 'title', 'company_name', 'secretary', 'supplier',
           'payment_type',   'status', 'jo', 'po','item_description','engineer','page');
           $val=array($requestor, $title, $company_name, $secretary, $supplier,
           $payment_type,$status, $jo, $po,$item_description,$engineer,$page);
           $result=updateMaker($table,$columns,$val,"where trans_no='$trans_num'");
           //$result=insertMaker($table,$columns,$val);
           $item_no=$_POST['item_no'];
           
           
           $select="select phone_number,concat(first_name,' ',last_name) as name from master_address_file where
            account_type='Account Executive' and mas_status=1 and account_id='$requestor' limit 1";
            $result = $conn->query($select);
            $row=$result->fetch_assoc();
            $phone_number=$row['phone_number'];
            $requestor_name=$row['name'];
           
           $select="select concat(first_name,' ',last_name) as name,smsc_id from master_address_file where
           account_type='Secretary' and mas_status=1 and account_id='$secretary' limit 1";
            $result = $conn->query($select);
            $row=$result->fetch_assoc();
            $secretary_name=$row['name'];
            $smsc_id=$row['smsc_id'];
            
            $select="select concat(first_name,' ',last_name) as name from master_address_file where
           account_type='Engineer' and mas_status=1 and account_id='$engineer' limit 1";
            $result = $conn->query($select);
            $row=$result->fetch_assoc();
            $engineer_name=$row['name'];
           $text="Letter Code:".$letter_code."
Requestor:".$requestor_name."
Title:".$title."
Company Name:".$company_name."
Secretary:".$secretary_name."
Engineer:".$engineer_name."
Supplier:".$supplier."
Payment Type:".$payment_type."
Jo:".$jo;

if(!empty($_REQUEST['po']))
{$text.="
Po#".$po;
}
$text.="
Page:".$page;
           $total_qty=0;
           $total_amount=0;
           for($a=1;$a<=$item_no;$a++)
           {
                $item=$_POST['item'.$a];
                $unit_price=$_POST['unit_price'.$a];
               $description=$_POST['description'.$a];
               $quantity=$_POST['quantity'.$a];
               if(empty($_POST['id'.$a]))
                    $result=insertMaker('po_item_file',array('trans_no','item','description','quantity','unit_price'),array($trans_num,$item,$description,$quantity,$unit_price));
               else
               {
                    $id=$_POST['id'.$a];
                    $result= updateMaker('po_item_file',array('item','description','quantity','unit_price'),array($item,$description,$quantity,$unit_price), "where id='$id' and trans_no='$trans_num' ");
               }
               $text.="
Item:".$item;
                    $text.=" Description:".$description;
                    $text.=" ".$quantity;
                    $text.=" Price:".$unit_price;
                    $total_qty+=$quantity;
                    $total_amount+=($quantity*$unit_price);  
           }
            $result=updateMaker($table,array('total_amount'),array($total_amount),"where trans_no='$trans_num'");  
            if($type=="withoutpo")
            $type1="type=Without Po";
                $trans_no=$trans_num;
               if($status!='Save'&&$status!='pending')
               {
                    echo "<script>alert('Successfull Transaction');</script>";
           
                $select="select phone_number,smsc_id from master_address_file where account_type='Account Executive' and mas_status=1 and account_id='$requestor' limit 1";
                $result = $conn->query($select);

                $row=$result->fetch_assoc();
                
                 sendText($text,$row['phone_number'],$row['smsc_id']); 
                //$text=urlencode($text);
                //$response = file_get_contents("http://127.0.0.1:13013/cgi-bin/sendsms?user=sms-app&pass=app125&text=$text&to=".$row['phone_number']);
                	$trans_no="";
					$trans_num="";
					$number_code="";
               }
				if($status==$for_qa_approval)
				{
            		   echo "<script>document.getElementById('form1').action='view_data_combine.php?".$type1."';";
              			echo "document.form1.submit();";
				}
              echo " </script>";
        }
        else 
        {
           $select="select trans_no,date_created,number_code from ".$table." order by trans_no desc limit 1";
           $result = $conn->query($select);
           $trans_no=1;
           $date_created="";
           if ($result->num_rows >0)
           {
              $row = $result->fetch_assoc();
              $trans_no=$row['trans_no']+1;
              //$number_code=$row['number_code'];
              $date_created=date("m-d-y",strtotime($row['date_created']));
             /* $today=strtotime(date("m-d-y",time()));
              if(strtotime($date_created)!=$today)
              $number_code=0;
              $number_code++;*/
           }
           $letter_code=get_letter_code($number_code);
           $columns=array('trans_no', 'number_code', 'letter_code', 'requestor', 'title', 'company_name', 'secretary', 'supplier',
           'payment_type',  'date_created', 'status', 'jo', 'po','TXT&ID_NO','item_description','engineer','page','created_by');
           $val=array($trans_no, $number_code, $letter_code, $requestor, $title, $company_name, $secretary, $supplier,
           $payment_type, 'now()', $status, $jo, $po,$TXT_ID_NO,$item_description,$engineer,$page,$_SESSION['uname']);
           
           
           if(!empty($requestor) && $requestor!=0)
           {
            $select="select phone_number,concat(first_name,' ',last_name) as name,smsc_id from master_address_file where
            account_type='Account Executive' and mas_status=1 and account_id='$requestor' limit 1";
            $result = $conn->query($select);
            $row=$result->fetch_assoc();
            $phone_number=$row['phone_number'];
            $requestor_name=$row['name'];
			$smsc_id=$row['smsc_id'];
           }
           $select="select concat(first_name,' ',last_name) as name from master_address_file where
           account_type='Secretary' and mas_status=1 and account_id='$secretary' limit 1";
            $result = $conn->query($select);
            $row=$result->fetch_assoc();
            $secretary_name=$row['name'];
            
            $select="select concat(first_name,' ',last_name) as name from master_address_file where
           account_type='Engineer' and mas_status=1 and account_id='$engineer' limit 1";
            $result = $conn->query($select);
            $row=$result->fetch_assoc();
            $engineer_name=$row['name'];
           
           $text="Letter Code:".$letter_code."
Requestor:".$requestor_name."
Title:".$title."
Company Name:".$company_name."
Secretary:".$secretary_name."
Supplier:".$supplier."
Payment Type:".$payment_type;
if(!empty($_REQUEST['po']))
{
    $text.="
    Engineer:".$engineer_name."
    Jo:".$jo."
    Po#".$po;
}
else
{
	$text.="
    $TXT_ID_NO#".$TXT_ID_NO;
}
$text.="
Page:".$page;
           $result=insertMaker($table,$columns,$val);
          
           $item_no=$_POST['item_no'];
           $total_qty=0;
           $total_amount=0;
           for($a=1;$a<=$item_no;$a++)
           {
                $description=$_POST['description'.$a];
                $quantity=$_POST['quantity'.$a];
                $item=$_POST['item'.$a];
                $unit_price=$_POST['unit_price'.$a];
                if($description!='' || $quantity!='' || $item!='' || $unit_price!='')
                {
                    $result=insertMaker('po_item_file',array('trans_no','item','description','quantity','unit_price'),array($trans_no,$item,$description,$quantity,$unit_price));
                    $text.="
Item:".$item;
                    $text.=" Description:".$description;
                    $text.="Qty: ".$quantity;
                    $text.=" Price:".$unit_price;
                    $total_qty+=$quantity;
                    $total_amount+=($quantity*$unit_price);
                }
                
            }
            $text.="
Total Items:".$total_qty;
            $text.="
Total Amount:".$total_amount;
            $result=updateMaker($table,array('total_amount'),array($total_amount),"where trans_no='$trans_no'");  
            
            $type1="type=With Po";
            if($type=="withoutpo")
            $type1="type=Without Po";
            $trans_num=$trans_no;
               if($status!='Save' && $status!='pending')
               {
                  // $text=urlencode($text);
                   sendText($text,$phone_number,$smsc_id);
					$trans_no="";
					$trans_num="";
					$number_code="";	
                    echo "<script>alert('Successfull Transaction');</script>";
               }
			$_REQUEST['trans_num']=$trans_num;
			 if($status=="For Approval")
			{
				echo "<script>document.getElementById('form1').action='view_data_combine.php?".$type1."';";
	            echo " document.form1.submit();";
				
			}
			echo "</script>";
			$update_type="Edit";  
        }
   }
    if($type=="withoutpo")
    $type="Without PO";
    else
    $type="With Po";
    ?>  
        <input type='hidden' id='status' name='status'>
        <input type='hidden' id='update_type' name='update_type' value='<?php echo $update_type;?>' >
        <h2>
        <?php ECHO $type;?></h2>
    <table>
    <?php
        $requestor1 ="";
        $title="";
        $company_name="";
        $secretary1="";
        $engineer1="";
        $jo="";
        $po="";
        $page="";
        $supplier="";
        $payment_type="";
		$item_description="";
    if($trans_num!='')
    {
        echo "<input type='hidden' name='trans_num' id='trans_num' value='$trans_num'>";     
        $select="select * from po_file where trans_no='$trans_num' limit 1";
        $result = $conn->query($select);
        $row= $result->fetch_assoc();
        echo "<input type='hidden' name='number_code' id='number_code' value='".$row['number_code']."'>";
        $number_code=$row['number_code'];
       // echo "<br>".$row['letter_code'];
        $requestor1 =$row['requestor'];
        $title=$row['title'];
        $company_name=$row['company_name'];
        $secretary1=$row['secretary'];
        $engineer1=$row['engineer'];
        $jo=$row['jo'];
        $po=$row['po'];
        $TXT_ID_NO=$row['$TXT&ID_NO'];
        $item_description=$row['item_description'];
        $page=$row['page'];
        $supplier=$row['supplier'];
        $payment_type=$row['payment_type'];   
    }
    $requestor=array();
    $select="select account_id , concat(first_name,' ',last_name) as account_executive from master_address_file
    where mas_status=1 and account_type='account executive' order by account_executive";
   // echo $select;
    $result = $conn->query($select);    
    while($row=$result->fetch_assoc())
        $requestor[$row['account_id']]=$row['account_executive'];        
    $engineer=array();
    if(!empty($_REQUEST['trans_num']))
   { $select="select account_id , concat(first_name,' ',last_name) as engineer from master_address_file
    where mas_status=1 and account_type='engineer'  and account_executive_id='$requestor1'  order by engineer";
    $result = $conn->query($select);
    while($row=$result->fetch_assoc())
        $engineer[$row['account_id']]=$row['engineer'];
   } 
    $secretary=array();
     if(!empty($_REQUEST['trans_num']))
    {
        $select="select account_id , concat(first_name,' ',last_name) as secretary from master_address_file
        where mas_status=1 and account_type='secretary' and account_executive_id='$requestor1' order by secretary";
        $result = $conn->query($select);
        while($row=$result->fetch_assoc())
        $secretary[$row['account_id']]=$row['secretary'];
    }
if(empty($number_code))
{
    $select="select number_code from po_header_file where date=date_format(now(),'%Y-%m-%d') and user_name='".$_SESSION['uname']."'  limit 1";
    $result = $conn->query($select);
    $row=$result->fetch_assoc();
  //  echo "<br>".$select;
    if(count($row)>0)
    $number_code=$row['number_code'];
    else
    {
        $number_code=1;
      //  echo time()." ".date("Y-m-j",time());
        
        $select="select now() as date ";
        $result = $conn->query($select);
        $row=$result->fetch_assoc();
        $time=date("Y-m-d",strtotime($row['date']));
        $select="select number_code from po_file where date_created like '".$time."%' order by number_code desc limit 1";
        $result = $conn->query($select);
       // echo "<br>".$select;
        $row=$result->fetch_assoc();       
        if(count($row)>0)
        {
          $number_code1=$row['number_code']+1;
            if($number_code1>$number_code)
         $number_code=$number_code1;
        }      
        $select="delete from po_header_file where user_name='".$_SESSION['uname']."' limit 1";
        $result = $conn->query($select);
        $insert="insert into po_header_file (number_code,date,user_name)
        values($number_code,now(),'".$_SESSION['uname']."')
        ";
        $conn->query($insert);
        //echo $insert;
    }
    //echo "<br>".$number_code;
    echo "<input type='hidden' name='number_code' value='$number_code'>";
}

$letter_code=get_letter_code($number_code);
echo "<tr><th style='text-align:left'>Letter Code:</th><th style='text-align:left'>
".$letter_code."</th><td></td></tr>";
    echo selectMakerEach('Requestor','requestor',$requestor,'getRequestor(this.value)',$requestor1);
    echo textMaker('Title/Remarks','title',$title);
    echo textMaker('Company Name','company_name',$company_name);
    
    echo selectMakerEach('Secretary','secretary',$secretary,'',$secretary1);
    if($type=='With Po')
    echo selectMakerEach('Engineer','engineer',$engineer,'',$engineer1);
    else
    echo "<input type='hidden' id='engineer' name='engineer' value='N/A'>";
    if($type=='With Po')
    echo textMaker('jo','jo',$jo);
    else
    echo "<input type='hidden' id='jo' name='jo' value='N/A'>";
    
    if($type=='With Po')
    	echo textMaker('PO#','po',$po,'onchange="check_po(this.value)"');
    else
    	echo textMaker('TXT&ID NO#','TXT&ID_NO',$TXT_ID_NO,'');
    if($type=='With Po')
    echo textMaker('Item Description','item_description',$item_description);
    else
    echo "<input type='hidden' id='item_description' name='item_description' value='N/A'>";
    if($type=='With Po')
    echo textMaker('Page#','page',$page);
    else
    echo "<input type='hidden' id='page' name='page' value='N/A'>";
    
    echo textMaker('Supplier','supplier',$supplier);
    echo selectMaker('Payment Type','payment_type',array('Cash','Check'),'',$payment_type);
    ?>
    <tr>
        <th style='text-align:left;padding-top:10px'><h2>Items</h3></th>
    </tr>
    <tr>
        <td><input type='button' value='add items' style='color:blue;background-color:transparent;border:none' onclick='addMore()' ></td>
        <tr>
            <td colspan=2>
                <table>
                    <tbody id='table_items' style='display: block'>
                        <?php
                        $a=0;
                        $total_amount=0;
                        if($trans_num!='')
                        {
                            $select="select * from po_item_file where trans_no='$trans_num' ";
                            $result = $conn->query($select);
                            while($row= $result->fetch_assoc())
                            {
                                echo "<tr>";
                                echo "<td>".(++$a)."<img  src='assets/cross.jpg' name='image' width='20' height='20' onclick='clear_row(".$a.")'></td>";
                                echo "<td><input type='text' id='item".$a."' name='item".$a."' placeholder='item' value='".$row['item']."'></td>";
                                echo "<td><input type='text' id='description".$a."' name='description".$a."'  value='".$row['description']."' placeholder='description'></td>";
                                echo "<td><input type='text' id='quantity".$a."' name='quantity".$a."'  value='".$row['quantity']."' placeholder='quantity'  onchange='computeAmount($a)'></td>";
                                echo "<td><input type='text' id='unit_price".$a."' name='unit_price".$a."'  value='".$row['unit_price']."' placeholder='unit_price' onchange='computeAmount($a)'></td>";
                                echo "<td><input type='text' style='border:none;text-align:right'  id='total_amount".$a."' readonly name='total_amount".$a."'  value='".(number_format(($row['unit_price']*$row['quantity']),2))."' placeholder='amount'></td>";      
                                echo "<input type='hidden' name='id$a' value='".$row['id']."'>";
                                echo "</tr>";
                                $total_amount+=($row['unit_price']*$row['quantity']);
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot style='display:block'>
                        <?php
                        if(!empty($_REQUEST['trans_num']))
                        echo "<tr id='total_am' style=''>";
                        else
                        echo "<tr id='total_am' style='display:none'>";
                        ?>
                        
                            <th  style='text-align:right'>Total Amount</th>
                            <td>
                                <input type='text' id='grand_amount' readonly style='text-align:left;width:200px;border:none;background-color: transparent' value='<?php echo number_format($total_amount,2);?>'>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
        <input type='hidden' name='item_no' id='item_no' value='<?php echo $a;?>'>
    </tr>
    <tr>
        <td>
        
            <?php
            $type_sub="Add";
            if(!empty($_REQUEST['trans_num']))
            $type_sub="Edit";
            ?>
            <input type='button' name='submit_me' value='Save' onclick='save_this("<?php echo $type_sub;?>","Save")'>
            <input type='button' name='submit_me' value='Submit' onclick='submit_this("<?php echo $type_sub;?>","Submit")'>
            <input type='button' name='cancel' value='Cancel'>
        </td>
    </tr>
    </table>
    </form>
     									