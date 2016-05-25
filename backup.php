<?php
include 'page_header.php';
$return="";
error_reporting(1);

backup_tables($servername,$username,password,$dbname);

/* backup the db OR just a table */
echo $return;
function backup_tables($host,$user,$pass,$name,$tables = '*')
{
	global $return;
$insert="";
global $conn;
if ($conn->connect_error) {
  
    die("Connection failed: " . $conn->connect_error);
}
$link=$conn;
	//$link = mysql_connect($host,$user,$pass);
	//mysql_select_db($name,$link);
	
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();

	//	$result = mysql_query('SHOW TABLES');
		$result= $conn->query('SHOW TABLES');

    	print_r($result);	
			ECHO "<BR>=".mysql_affected_rows();
		while($row=$result->fetch_assoc())
		{
			$tables[] = $row['Tables_in_a4887060_gsm'];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
		ECHO "SHIgure";
	}
echo "<br>";	
	print_r($tables);
	//cycle through
$prev_table="";
	foreach($tables as $table)
	{
		//$result = mysql_query('SELECT * FROM '.$table);
		$result= $conn->query('SELECT * FROM '.$table);
		$num_fields = mysql_num_fields($result);
	//	echo "<br>SELECT * FROM ".$table."=".mysql_affected_rows()."<br>";
		$row=mysql_affected_rows();
		//$return.= 'DROP TABLE '.$table.';';
		$new_table=$table;
		if($prev_table!=$table)
		{
			 $prev_table=$new_table;
	//	echo '<br><br>DROP TABLE '.$new_table." ".mysql_affected_rows();
		$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
		$result= $conn->query('SELECT * FROM '.$table);
		
		$return.= "\n\n".str_replace($table,$new_table,$row2[1]).";\n\n";
		$create_table=str_replace($table,$new_table,$row2[1]);
		
		if( $row>0)
	{
			//$row2 = mysql_fetch_row(mysql_query($create_table));
		$insert.=$create_table.";";
		//echo "<br>".$num_fields." ".$new_table.";";
		for ($i = 0; $i <=$num_fields; $i++) 
		{
 			//
			$insert.= '';
			$val="";
			while($row = mysql_fetch_row($result))
			{
				
				if($num_fields>0)
				{
if($val!='')
$val.=",";
					$val.= '(';
					for($j=0; $j <$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
					//	$row[$j] = ereg_replace("\n","\\n",$row[$j]);
						if (isset($row[$j])) { $val.= '"'.$row[$j].'"' ; } else { $val.= '""'; }
						if ($j < ($num_fields-1)) { $val.= ','; }
						
					}
				}
				$val.= ")";
					//

		
			}
$insert.= 'INSERT INTO '.$new_table.' VALUES'.$val;
//$row2 = mysql_fetch_row(mysql_query($insert));

		//	echo "<br><textarea style='width:800px;height:100px'>".$insert."</textarea>";

			//$row2 = mysql_fetch_row(mysql_query($insert));
		
		//	echo  "<br>".mysql_affected_rows().$new_table;
			//print_r($row2);
		}

}	
	//$return.="\n\n\n";
		//$row2 = mysql_fetch_row(mysql_query('DROP TABLE '.$new_table));
		}
	}

echo $return;	
	// $result = $conn->query($return);

   //		$row = $result->fetch_assoc();
	//save file
	$myfile = fopen("newfile.sql", "w") or die("Unable to open file!");
$txt = "John Doe\n";
fwrite($myfile, $return);
//$txt = "Jane Doe\n";
//fwrite($myfile, $txt);
fclose($myfile);

$myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
$txt = "John Doe\n";
fwrite($myfile, $return	);
$txt = "Jane Doe\n";
fwrite($myfile, $return);
fclose($myfile);

}
?>