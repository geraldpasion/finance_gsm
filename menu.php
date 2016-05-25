<?php
    $select="select menu_head,menu_name,menu_type,menu_php from
    ((select menu_head,menu_name,menu_order,menu_type,menu_php from menu_file as a inner join user_access_file_new as k
    on a.menu_id=k.menu_id or a.menu_id=21 or a.menu_id=16
    where a.mas_status=1 and user_type='".$_SESSION['user_type']."' group by a.menu_id
    )union all
    (select menu_head,menu_name,menu_order,menu_type,menu_php from menu_file where menu_head in
    (select menu_head from menu_file as a inner join user_access_file_new as k on a.menu_id=k.menu_id
    where a.mas_status=1 and user_type='".$_SESSION['user_type']."' group by menu_head) and menu_type=1 )) as a
    order by menu_head,menu_type,menu_order
    ";
   
    $select="select menu_head,menu_name,menu_order,menu_type,menu_php from menu_file as a 
    inner join user_access_file_new as k on a.menu_id=k.menu_id
    where mas_status=1
    order by menu_table,menu_head,menu_type,menu_order
    ";
   //  echo "<br><br>".$select;
    //  echo "<br><br>".$select;
    
    $result = $conn->query($select);
	
    $temp_head="-1";
    /*while($row=$result->fetch_assoc())
    {
        if($row['menu_type']==1)
        {
            if($temp_head!=$row['menu_head'] &&$temp_head!=-1)
            echo "</ul></li>";
            echo "<li style='width:100px;text-align:center' class='current-menu-item'>";
            echo "<a href=''>".$row['menu_name']."</a><ul>";
            $temp_head=$row['menu_head'];
        }
        else
            echo "<li style='width:150px;text-align:left'><a href='".$row['menu_php']."'>".$row['menu_name']."</a> </li>";
    }
    echo "</ul></li>";
    */
    //echo "</ul></li>";
   ?>
   <style>
   ul {
    list-style-type: none;
    margin: 0;
   
    padding: 0;
   
}

li a {
    display: block;
    width: 160px;
    
}


 .li2 a,.dropbtn, .dropbtn2 {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 6px 7px;
    text-decoration: none;
}
li.dropdown {
    display: inline-block;
    
}

li a:hover, .dropdown:hover .dropbtn {
    background-color: 	#FF4500;
    
}



.dropdown-content {
    display: none;
    position: absolute;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
      background-color: orange; 
    //  margin-top:-14px;
	
      
}


.dropdown-content a {
    color: black;
    padding: 4px 6px;
    text-decoration: none;
    display: block;
    text-align: left;
    width:100px;
    font-size:14px;
    
   
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
    display: block;
}
.test2
{
list-style-type: none;
    margin: 0;
    padding: 0;
    width: 160px;
    overflow: auto;
}

 .dropdown-content2 {
    display: none;
    position: absolute;
    min-width: 160px; 
    
   
}
.dropdown-content2 a {
    color: black;
    padding: 6px 12px;
    text-decoration: none;
    display: block;
    text-align: left;
    width: 160px;
    background-color:#FF8C00;
    font-size:14px;
    
}
.dropdown2:hover .dropdown-content2 {
    display: block;
	z-index:10;
}


li.dropdown2 {
    display: inline-block;
    
}

   </style>
   <?php
   echo "<ul>";
    echo "<li style='width:150px;text-align:left'  class='dropdown'>";
            echo "<a href=''  class='administration ui-icon-circle-triangle-s dropbtn'>Administrator</a>";
            echo "<div class='dropdown-content'>";
      		/*	<a href=''>Link 1</a>
      			<a href=''>Link 2</a>
      			<a href=''>Link 3</a>
      			";
      			//echo "<a href=''>";
      			echo "<ul class='test2'><li class='dropdown2'>
      				<table><tr><td style='min-width: 100px; '><a href=''>Test</a></td>
      				<td style='vertical-align:top'>
      				<div class='dropdown-content2'>
      					<a href='' class='administration ui-icon-circle-triangle-s'>Link 1</a>
      					<a href='' class='administration ui-icon-circle-triangle-s'>Link 2</a>
      					<a href='' class='administration ui-icon-circle-triangle-s'>Link 3</a>
      				</div>
      				</td>
      				</tr>
      				</table>
      			</li>
      			</ul>
      			";
      			//echo "</a>";
      			*/
            $filter="";
            if($_SESSION['user_type']!='admin')
            $filter="and user_type='".$_SESSION['user_type']."'";
             $select="select menu_head,menu_name,menu_order,menu_type,menu_php from menu_file as a 
    inner join user_access_file_new as k on a.menu_id=k.menu_id
    where mas_status=1  ".$filter." and menu_table=0
    group by a.menu_type,a.menu_id 
    order by menu_head,menu_type,menu_order
    ";
  //  echo $select;
    $result = $conn->query($select);
	
    $temp_head="-1";
	$menu_table=0;
    while($row=$result->fetch_assoc())
    {
		
        if($row['menu_type']==1)
        {
            if($temp_head!=$row['menu_head'] &&$temp_head!=-1)
            echo "</div>
      				</td>
      				</tr>
      				</table>
      			</li>
      			</ul>";
            
            echo "<ul class='test2'><li class='dropdown2 li2'>
      				<table><tr><td ><a href=''  >".$row['menu_name']."</a></td>
      				<td style='vertical-align:top'>
      				<div class='dropdown-content2'>
            ";
            
            $temp_head=$row['menu_head'];
        }
        else
            echo "<a href='".$row['menu_php']."'  class='administration ui-icon-circle-triangle-s'>".$row['menu_name']."</a>";
    }
    echo "</div>
      				</td>
      				</tr>
      				</table>
      			</li>
      			</ul>";
    echo "<ul class='test2'><li class='dropdown2  li2'>
      				<table><tr><td >";
      		
    echo "<a style='margin-top:10px' class='administration ui-icon-circle-triangle-s' href='logout.php'>Log Out</a>
    	    ";
             echo "</div>
      				</td>
      				</tr>
      				</table>
      			</li>
      			</ul>";
            echo "<ul>";
			
    echo "</ul>";
	/*
	echo "<br> </nav>";
	
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
				
			}
			$menu_table=$row['menu_table'];
			echo "<td>";
				echo "<ul>";
				echo "<li style='width:150px;text-align:left'  class='dropdown'>";
				echo "<a href=''  class='administration ui-icon-circle-triangle-s dropbtn'>".$row['menu_name']."</a>";
				echo "<div class='dropdown-content'>";
		}
			else
				echo "<a href='".$row['menu_php']."'  class='administration ui-icon-circle-triangle-s'>".$row['menu_name']."</a>";
	  
			
				
		}
		echo "</div></li></ul>";
		echo "</td>";
		echo"</table>";*/
?>