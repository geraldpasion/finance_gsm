<html style='font-size: 10px;-webkit-tap-highlight-color: transparent;'>
<link rel="stylesheet" type="text/css" href="menuCss.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script type="text/javascript" src="jquery-1.10.2.js"></script>
  <script type="text/javascript" src="jquery-ui.js"></script>
<body>
    <style>
        .navbar-brand {
    float: left;
    padding: 15px 15px;
    font-size: 18px;
    line-height: 20px;
    height: 50px;
}
body {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.428571429;
    color: #333333;
    background-color: #fff;
}
div{display:block;}
.container {
    margin-right: auto;
    margin-left: 55px;
   
    padding-right: 15px;
    min-width: 800px;
	width:90%;
	text-align:center;
}
.navbar-toggle {
    position: relative;
    float: right;
    margin-right: 15px;
    padding: 9px 10px;
    margin-top: 8px;
    margin-bottom: 8px;
    background-color: transparent;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
.navbar-toggle .icon-bar {
    margin:4px;
    border-radius: 1px;
    
    background-color: #888;border:1px solid #ddd;display:block;width:22px;height:1px;
}

h2 {
    display: block;
    font-size: 1.5em;
    -webkit-margin-before: 0.83em;
    -webkit-margin-after: 0.83em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;
    font-weight: bold;
}
.page_header
{
	background-color: #f8f8f8;border-color: #e7e7e7;height:141px;
}
  .administration
  {
  	position:relative;
  	top:-14;left:-1;
  	veritical-align:top;
  	border:none;text-align:center;
  	background-color:orange;
  	width:160px;font-weight:bold;font-size:16px
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
    <!--  
<div style='text-align:center;vertical-align:bottom;display:block;background-color: #f8f8f8;border-color: #e7e7e7;width:100%;height:141px;top:0px;left:0px'>
</div>-->
<?php
session_start();

include 'string.php';
error_reporting(0);
include 'connect.php';
include 'functions.php';
$str_request=str_replace("/finance_gsm","",$_SERVER["REQUEST_URI"]);
$str_request=str_replace("/","",$str_request);
$str_request=str_replace("/","",$str_request);
$file=$str_request;
$str_request2=explode(".php",$str_request);
$str_request=$str_request2[0];
$str_request2=explode("/",$str_request);
$str_request=$str_request2[count($str_request2)-1];
if ("login"== $str_request && empty($_SESSION['uname']))
echo "";
else if("login.php"!= $str_request && empty($_SESSION['uname']))
 echo "<script>alert(' Session Expired Please Log In');window.location.assign('login.php')</script>";
else
{
if($_SESSION['user_type']=='account executive')
$_SESSION['user_type']="Finance Head";
$file2=explode("?",$file);
$file3=$file2[0];
$page_name=$file;
if($file3=="view_datas.php")
$file="view_data_combine.php";
   /* $select="select a.type from user_access_type_file  as a inner join menu_file as k
    on a.menu_id=k.menu_id where (menu_php like'".addslashes($file)."%' or menu_php like'".addslashes($file3)."' )  and user_type='".$_SESSION['user_type']."' ";
    $result = $conn->query($select);
    $access=array();
    while($row=$result->fetch_assoc())
    {
        $access[$row['type']]=$row['type'];
    }*/
    $filter=" where user_type='".addslashes($_SESSION['user_type'])."'";
    if($_SESSION['user_type']=='admin')
   	{
   		$filter=" group by menu_id";
   		//echo "<br>kai".$_SESSION['user_type'];
   	}
   	//else
  // 	echo "<br>MARIA".$_SESSION['user_type'];
   	
    $select="select a.type,a.menu_id from user_access_file_new  as a ".$filter;
    $result = $conn->query($select);
    //echo "<br>".$select." ".$_SESSION['user_type'];
    $access=array();
    while($row=$result->fetch_assoc())
    {
        $access[$row['type']]=$row['type'];
        if($row['type']!='')
        $access_menu[$row['menu_id']]=$row['type'];
        else
        $access_menu[$row['menu_id']]="All";
        
    }
}
//print_r($access);
?>
<script >
    var xmlhttp;
    function loadXMLDoc(url,cfunc)
    {
       if (window.XMLHttpRequest)
         xmlhttp=new XMLHttpRequest();
       else
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
            cfunc(xmlhttp.responseText);
    }
    xmlhttp.open("GET",url,true);
    xmlhttp.send();
    }
   
</script>
<table class='page_header' style='width:80%'  align=center>
<tr>
<td style='padding-left:20px;padding-top:10px;margin:-10px'>
<img id="logo" src="images/logo.gif" alt="Sysgen" width="195" height="85">
</td>
<td style='text-align:right;veritical-align:top;'>

 <?php
//<input type='button' class='administration ui-icon-circle-triangle-s' id='test'  value='Administrator'>
            if(!empty($_SESSION['uname']))
                {
                    ?>
               
                <nav class="primary_nav_wrap">
               
               <?php
                        include 'menu.php';
                ?>
           </nav>
            <?php
            }
                ?>  
</td>
</tr>
<tr>
<td colspan=2 style='padding-right:30px'><br><br>
 <?php
	if(!empty($_SESSION['uname']))
                        include 'menu2.php';
				
                ?>
				</td>

</table>

<div style='height:51px'><br></div>
<div class='container'>
