<?php
echo "<nav class='primary_nav_wrap'>";
               
			$menu_table=0;
			           $select="select menu_head,menu_name,menu_order,menu_type,menu_php,menu_table from menu_file as a 
    inner join user_access_file_new as k on a.menu_id=k.menu_id
    where mas_status=1  ".$filter." and menu_table!=0
    group by a.menu_type,a.menu_id 
    order by menu_head,menu_type,menu_order
    ";
    $result = $conn->query($select);
	
    $temp_head="-1";
	$menu_table=0;
	echo "<br><table align=right>";
	echo "<tr>";
    while($row=$result->fetch_assoc())
    {
		if($menu_table!=$row['menu_table'])
		{
			if($menu_table!=0)
			{
				echo "</div></li></ul></td>";
				/* "</div>
						</td>
						</tr>
						</table>
					</li>
					</ul>";*/
			}
			$menu_table=$row['menu_table'];
			echo "<td>";
				echo "<ul>";
				echo "<li style='width:150px;text-align:left'  class='dropdown li2'>";
				echo "<a href=''  class='administration ui-icon-circle-triangle-s dropbtn'>".$row['menu_name']."</a>";
				echo "<div class='dropdown-content'>";
		}
			else
				echo "<a href='".$row['menu_php']."'  class='administration ui-icon-circle-triangle-s'>".$row['menu_name']."</a>";
	  
			
				
		}
		echo "</div></li></ul>";
		echo "</td>";
		echo"</table></nav>";
		?>
		
		