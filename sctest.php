<?php
/* Casey Balzer
   Showclix Puzzle
   crb68@pitt.edu
   3/23/13
   test page
*/
include 'SCpuzzleFunctions.php';
$res=array("R1C4","R1C6","R2C3","R2C7","R3C9","R3C10");//initial reserved array
$map=build(3,11,$res);

if(isset($_POST['sub']))//if submitting a reservation
{
    if($_POST['seatnum'] > 10)//Cannot request more than 10 seats
    {
    echo "<font color=\"red\">Sorry Maximum Seats Allowed to Purchase is 10!</font>"; 
    }
      else//send request
      {
     $map=reserve($map,$_POST['seatnum']);
     }
}

?>
<html>
<head>

</head>
<body>
<center><h1>Showclix Puzzle Test Page</h1></center></br>
<hr></br>
<form action="sctest.php" method="post"></br>
<center>Number of Seats:&nbsp; <input type="text" name="seatnum"></center></br></br>
<center><input type="submit" name="sub"></center></br>
</form>
</body>
</html>