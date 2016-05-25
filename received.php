<?php
include 'page_header.php';
?>
<script>
function sent_message(a)
{
 	url="xstatus=send_message&message="+$('#message'+a).val()+"&phone_number="+$('#phone_number'+a).val()
 	loadXMLDoc('get_type.php?'+url,reloadPage)
}
function reloadPage(result)
{
	alert(result)
}
</script>
<form method=post>
<?php
$select="select * from received_file order by received_date desc";
$result = $conn->query($select);
echo "<table>";
$a=0;
while($row=$result->fetch_assoc())
{
	echo "<tr>";
		echo "<td>".$row['phone_number']."</td>";
		echo "<td>".$row['message']."</td>";
		echo "<td>".$row['received_date']."</td>";
	
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan=3>";
		echo "<textarea id='message".$a."'></textarea>";
		echo "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan=3><input type='button' value='Send' onclick='sent_message(".$a.")'></td>";
	echo "</tr>";
	echo "<input type='hidden' id='phone_number".$a."' value='".$row['phone_number']."'>";
	$a++;
}
echo "</table>";
?>

</form>