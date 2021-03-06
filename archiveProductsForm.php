<?php
session_start();
require_once('config.php');

if(!$_SESSION['username'] && !$_SESSION['password']){
	header("Location: error.php");
}
else{
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
}	

require_once("class.database.inc");
$db = User::getInstance();
$db -> setUsername($_SESSION['username']);
$db -> setPassword($_SESSION['password']);
$db -> login();

$profile = $db -> getProfile();
$fname = $db -> getFirstname();
$lname = $db -> getLastname();
$id = $db -> getId();

if (isset($_GET['message'])) {
	echo '<script language="javascript">';
	echo "alert('$_GET[message]')";
	echo '</script>';
}
?>

<html>
<title>Karts Burger - Users</title>
<head>

<link rel = "stylesheet" rev = "stylesheet" href = "archiveProductsForm.css">



<script>
function showResult(str){
	//var word = document.getElementById("txtSearch").value;
	if(str.length == 0){
		//document.getElementById("display").className = menuList;
		location.reload();
		return;
		document.getElementById("txtSearch").focus();
	}
	if(window.XMLHttpRequest){
		xhr = new XMLHttpRequest();
	}
	else{
		if(window.ActiveXObject){
			try{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){}
		}
	}
	
	if(xhr){
		xhr.onreadystatechange = showContents;
		xhr.open("GET", "showArchiveProduct.php?q="+str, true);
		xhr.send(null);
	}
	else{
		alert("couldnt create XMLHttpRequest");
	}
}

function showContents(){
	if(xhr.readyState == 4){
		if(xhr.status == 200){
			outMsg = xhr.responseText;
		}
		else{
			alert("status "+xhr.status);
		}
		document.getElementById("display").innerHTML = outMsg;
	}
}
</script>
</head>


<body>


<div class = "topBar">	
	<div id = "title">
	Administrative Panel
	</div>
</div>


<div class = "naviBar">
	<div class = "userProf">
		<div class = "naviBarpic">
			<img src = "<?php echo $profile ?>" style = "height:100%; width:100%; border-radius:33px;"></img>
		</div>
		<div id = "name"><?php echo $fname." ".$lname ?>
		</div>
		<div class = "editprof">
		<a href = "editProfileForm.php?id=<?php echo $id ?>">Edit Profile</a>
		</div>
	</div>
	<form action = "orders.php" method = "post">
		<img src = "Images/admin.png" style = "height:4%; width:10%" class = "padmin">
		<input type = "submit" value = "Administrator" class = "admin"/>
	</form>
	<form action = "userProfilesForm.php" method = "post">
		<img src = "Images/user1.png" style = "height:4%; width:10%" class = "pusers">
		<input type = "submit" value = "User Profiles" class = "profile"/>
	</form>
	<form action = "addUserForm.php" method = "post">
		<img src = "Images/add.png" style = "height:3%; width:7%" class = "paddUser">
		<input type = "submit" value = "Add User" class = "addUser"/>
	</form>
	<form action = "userArchiveForm.php?" method = "post">
		<img src = "Images/file.png" style = "height:3%; width:7%" class = "parcUser">
		<input type = "submit" value = "User Archives" class = "arcUser"/>
	</form>
	<!--<div class = "line1">
	</div>-->
	<form action = "ProductsForm.php?" method = "post">
		<img src = "Images/list.png" style = "height:4%; width:10%" class = "porders">
		<input type = "submit" value = "Products" class = "orders"/>
	</form>
	<form action = "addProductsForm.php?" method = "post">
		<img src = "Images/add.png" style = "height:3%; width:7%" class = "paddProd">
		<input type = "submit" value = "Add Products" class = "addProd"/>
	</form>
	<form action = "archiveProductsForm.php?" method = "post">
		<img src = "Images/file.png" style = "height:3%; width:7%" class = "parcProd">
		<input type = "submit" value = "Product Archives" class = "arcProd"/>
	</form>
	<form action = "generateReportsForm.php?" method = "post">
		<img src = "Images/report.png" style = "height:4%; width:10%" class = "preport">
		<input type = "submit" value = "Reports" class = "report"/>
	</form>
	<div class = "line2">
	</div>
	<form action = "sessiondestroy.php" method = "post">
		<img src = "Images/logout.png" style = "height:4%; width:10%" class = "plogout">
		<input type = "submit" value = "Logout" class = "logout"/>
	</form>
	<div id = "footer">
		Karts Burger and Restobar<br>
		All Rights Reserved 2017
	</div>
</div>

<div class = "menuBar">
	<form action = "#">
		<input type = "submit" value = "View Archive" class = "add"/>
	</form>
	<form action = "#">
		<input type = "submit" value = "Generate Reports" class = "gen"/>
	</form>
</div>


<div class = "menutitleBar">
	<div id = "title">
	Product Archive List
	</div>
	<div id = "search">
	Search
	</div>
	<div class = "searchForm">
	<input type = "text" id = "txtSearch" name = "txtSearch" placeholder = "by user name" onkeyup = "showResult(this.value)" />
	</div>
</div>

<div class = "menuList" id = "display">
	<?php
		$topdiv = 15;
		$leftdiv = 10;
		$ctr = 0;
		$prodctr = 0;
		
	
		$sql = $db->query("SELECT * from tbl_products WHERE archived = 'yes' ORDER BY prodId ASC");
		
		?>
		<div class = "menuTable">
		<table border = "1"><tr>
		<th>Product Image</th>
		<th>Product ID</th>
		<th>Product Name</th>
		<th>Product Price</th>
		<th>Restore Product</th>
		</tr>	
		<?php
			
			
		if($sql){
			while($rows = $db->fetch_array($sql)){
				$imagesrc = $rows['prodImgPath'];
				$name = $rows['prodDesc'];
				$price = $rows['prodPrice'];
				$id = $rows['prodId'];
				?>
				
				
				<tr><td><img src = "<?php echo $imagesrc ?>" style = "width:24px; height:24px;"></img></td>
				<td><?php echo $id ?></td>
				<td><?php echo $name ?></td>
				<td><?php echo $price ?></td>
				<td><a href = "restoreProduct.php?id=<?php echo $id ?>"><img src="Images/restore.png" style = "width:70px; height:24px;"></a></td></tr>
				
				<?php 
			}
		}
	?>
		</table>
		</div>
	
</div>





</body>
</html>