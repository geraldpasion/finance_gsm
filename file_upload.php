<?php
include 'connect.php';
//error_reporting(0);


$trans_num=$_REQUEST['trans_num'];
$type=$_REQUEST['type'];

$target_dir = "uploads/";
$file_name=basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_REQUEST['type'])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

//  $check = getimagesize($_FILES["test"]["tmp_name"]);
    if($check !== false) {
        $update="update po_file set received_date=now(), status='Received' , file_name='".addslashes($file_name)."' where trans_no='$trans_num'";
        $conn->query($update);
        echo "<script>alert('Successfully Uploaded Image')</script>";
        //echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "<script>alert( 'File is not an image.')</script>";
        $uploadOk = 0;
    }
}

echo "<form id='form1' name='form1' method=post>";
echo "<input type='hidden' id='type' name='type' value='$type'>";
echo "</form>";

?>
<script>
    function back() {
        
    document.getElementById('form1').action = "view_data_combine.php";
    document.form1.submit();
    }
    
</script>
<?php
$select="select requestor,letter_code,date_created from po_file where trans_no='$trans_num' limit 1";
$result = $conn->query($select);
$row=$result->fetch_assoc();
$date=$row['date_created'];
$requestor=$row['requestor'];
$letter_code=$row['letter_code'];
$select="select phone_number from master_address_file where account_id='$requestor'  and account_type='Account Executive' limit 1";
$result = $conn->query($select);
$row=$result->fetch_assoc();
$phone_number=$row['phone_number'];
$text="Letter Code:".$letter_code."
";
$text.="Date:".date("F d, Y h:m:i a",strtotime($date))."
Received ";
$text=urlencode($text);

try {
$response = file_get_contents("http://127.0.0.1:13013/cgi-bin/sendsms?user=sms-app&pass=app125&text=$text&to=".$phone_number);
}
catch(Exception $e)
{
		echo "<script>alert('Failed to send message')</script>";
}
catch (ErrorException $e) {
    // ...
}
               
?>
<body onload='back()'></body>