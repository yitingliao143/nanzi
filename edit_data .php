<?php
include_once 'dbconfig.php';
if(isset($_GET['edit_id']))
{
 $sql_query="SELECT * FROM stu_list WHERE sID=".$_GET['edit_id'];
 $result_set=mysqli_query($link, $sql_query);
  $fetched_row=mysqli_fetch_array($result_set);
}
if(isset($_POST['btn-update']))
{
 // variables for input data
	$Name = $_POST['Name'];
	$Team = $_POST['Team'];
	$Skill = $_POST['Skill'];
	$City = $_POST['City'];
 // variables for input data
 
 // sql query for update data into database
 $sql_query = "UPDATE stu_list SET Name='$Name',Team='$Team',Skill='$Skill',City='$City' WHERE sID=".$_GET['edit_id'];
        
 // sql query for update data into database 
 // sql query execution function
 if(mysqli_query($link, $sql_query))
 {
  ?>
  <script type="text/javascript">
  alert('Data Are Updated Successfully');
  window.location.href='main.php';
  </script>
  <?php
 }
 else
 {
  ?>
  <script type="text/javascript">
  alert('error occured while updating data');
  </script>
  <?php
 }
 // sql query execution function
 if(isset($_POST['btn-cancel']))
 {
	header("Location: main.php");
 }
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<center>
<?php
	include "header.xxx";
?>
<div id="body">
 <div id="content">
	
    <form method="post">
    <table align="center">
    <tr>
    <td><input type="text" name="Name" placeholder="店家名字" value="<?php echo $fetched_row['Name']; ?>" required /></td>
    </tr>
    <tr>
    <td><input type="text" name="Team" placeholder="店家地址" value="<?php echo $fetched_row['Team']; ?>" required /></td>
    </tr>
    <tr>
	<td><input type="text" name="Skill" placeholder="聯絡方式" value="<?php echo $fetched_row['Skill']; ?>" required /></td>
    </tr>
	<tr>
    <td><input type="text" name="City" placeholder="營業時間" value="<?php echo $fetched_row['City']; ?>" required /></td>
    </tr>
    <tr>
    <td>
    <button type="submit" name="btn-update"><strong>UPDATE</strong></button>
	<button type="submit" name="btn-cancel"><strong>Cancel</strong></button>
    </td>
    </tr>
    </table>
    </form>
    </div>
</div>

</center>
</body>
</html>