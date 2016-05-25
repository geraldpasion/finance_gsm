<?php
include 'page_header.php';
if(!empty($_POST['sub_btn']))
{
    $username=$_POST['username'];
    $password=$_POST['password'];
    $select="select user_type from user_file where user_name='".addslashes($username)."' and password='".addslashes($password)."' and mas_status=1 limit 1";
    $result = $conn->query($select);
//echo $select;
    if ($result->num_rows > 0) 
    {
        $row=$result->fetch_assoc();
        $_SESSION['uname']=$username;
        $_SESSION['user_type']=$row['user_type'];
        
        $filter=" WHERE user_type='".$_SESSION['user_type']."' ";
        if($_SESSION['user_type']=='admin')
        $filter=" group by a.menu_id ";
        $SELECT="SELECT * from user_access_file_new as a inner join access_file_20161 as k on a.menu_id=k.menu_id $filter";
    	$result = $conn->query($SELECT);
    	echo $SELECT;
    	$access_type=array();
    	while($row=$result->fetch_assoc())
    	{
        	$access_type[$row['head_name']][$row['type']]="yes";
        	$access_type_type[$row['type']][$row['type']]="yes";
        	$access_menu_type[$row['menu_name']][$row['type']]="yes";
        }
    	$_SESSION['access_type']=$access_type;
    	$_SESSION['access_type_type']=$access_type_type;
    	$_SESSION['access_menu_type']=$access_menu_type;
        print_r($_SESSION);
        echo "<script>
        document.getElementById('login_form').action = 'main_page.php';
        document.login_form.submit();</script>";
        
       // echo "<script>window.location.assign('main_page.php')</script>";
    
    } else {
        echo "<script>alert('Incorrect Password')</script>";
    }    
}
if(!empty($_SESSION['uname']))
    echo "<script>window.location.assign('main_page.php')</script>";
?>
<form id='login_form' name='login_form' method=post>
<div class='container'>
<br><br><br><h2>Log In</h2>
<table>
    <tr>
        <th style='text-align:left'>Username</th>
    </tr>
    <tr>
        <td><input type='text' name='username' id='username'></td>
    </tr>
    <tr>
        <th style='text-align:left'>Password</th>
    </tr>
    <tr>
        <td><input type='password' name='password' id='password'></td>
    </tr>
    <!--<tr>
        <td><input type='checkbox' id='remember' name='remember'> Remember me</td>
    </tr>-->
    <tr>
        <td><input  NAME='sub_btn' type='submit' value='Log in'></td>
    </tr>
    
</table>

</div>
</form>