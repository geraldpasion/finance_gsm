<?php include 'page_header.php';
$select ="";
  $select="select head_name,type,menu_name,sub,sub_status from access_file_2016";
        $result = $conn->query($select);
        $finance_head=array();
        $finance_value=array();
        
        while($row=$result->fetch_assoc())
        {
            $array[$row['head_name']][$row['type']][$row['menu_name']][$row['sub']]=$row['sub_status'];
            
            /*if($row['menu_name']=='')
            {
            	$row['sub']
            }
            else
            {
            }*/
            
            
            if(empty($a1[$row['head_name']]))
            $a1[$row['head_name']]=1;
            $a1[$row['head_name']]++;
            
            
        }
        echo "<table border=1 style='border-collapse:collapse'>";	
        echo "<tr>";
        	echo "<th colspan=4>SOFTWARE USER:</th>";
        	echo "<th colspan=4>Finance Head</th>";
        	
        echo "</tr>";
        $s=4;
        foreach($array as $head1=>$d1)
        {
        	echo "<tr><td  rowspan='".($a1[$head1])."'>1".$head1."=".$a1[$head1]."</td></tr>";
    		$a=0;
    	    foreach($d1 as $head2=>	$d2)
        	{
        		//if($a==0)
        		echo "<tr>";
        		$b=0;
        		//rowspan='".($a2[$head1][$head2]+1)."'
        		echo"<td colspan=".(4+(4*1))."' style=''>2".$head2."</td>";
        		echo "</tr>";
        		$a++;
        		foreach($d2 as $head3=>	$d3)
        		{
        			//if($b==0)
        			echo "<tr>";
        			//rowspan='".($a3[$head1][$head2][$head3])."'
        			//rowspan='".($a3[$head1][$head2][$head3])."'
        			echo "<td style='width:10px;'> </td><td colspan=".(3+(4*1))."'>3".$head3."</td>";
        			echo "</tr>";
        			$c=0;
        			foreach($d3 as $head4=>	$d4)
        			{
        				if($head4!='')
        				{
        				//if($c!=0)
        				echo "<tr>";
        				//rowspan='".($a4[$head1][$head2][$head3][$head])."'
        				echo "<td colspan=2></td><td >4".$head4."</td>";
        				$c++;
        				$head=$d4;
        				//foreach($d4 as $head=>	$d5)
        				//{
        					if($head==1)
        					{
        						$array=array('Add','Edit','View','Del');
        						for($aa=0;$aa<4;$aa++)
        						echo "<td ><input type='checkbox' value='".$array[$aa]."'></td>";
        					}
        					ELSE 
        					echo "<td colspan=4><input type='checkbox' ></td>";
        				//}
        				//if($c!=0)
        					echo "</tr>";
        					}
        				$c++;
        			}
        			//if($b!=0)
        			
        			$b++;
        		}
        		//if($a!=0)
        		
    		
    		}
        }
        echo "</table>";
      

?>