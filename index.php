<?php
session_start();
if (!isset($_SESSION['count'])){
    $_SESSION['count'] = 1;} 
else {
    $_SESSION['count']++;}
?>
<html>
    <head>
        <title>Kalendarz</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>
        <nav class="navabr navbar-inverse">
            <div class="header container-fluid">
                <div class="navbar-header hidden-xs">
                    <i class="header__logo fa fa-calendar" aria-hidden="true"></i>
                </div>
                <span class="header__title">YOUR CALENDAR</span>
            </div>
        </nav>
        <div class="main container">
            <div class="row">
                <div class="calendar">
                    <?php
                    $conn=new mysqli('localhost','root','','calendar');
                    if($conn->connect_errno) die($conn->connect_errno);
                    $result=changeDatabase($conn);
                    $days= array("mond" ,"tues", "weds", "thur", "frid");
                    createDays($days,$result);
                    ?>
                </div>
            </div>
            <div class="addingEvents">
                <?php
                eventForm($result, $conn, $days);
                ?>
            </div>
        </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/calendar.js"></script>
    </body>
</html>
<?php
function createDays($days,$result){
    for ($j=0 ; $j<5 ; $j++){
        echo ($j==0)?'<div class="col-sm-2 col-sm-12 day col-sm-offset-1">':'<div class="col-sm-2 col-sm-12 day">';
            echo "<table id='$days[$j]'>";
                echo '<th class="table__header">' . $days[$j] . '</th>';
                for($i=0 ; $i<64 ; $i++){
                    $min= 15*($i%4);
                    $h=(int)(6+$i/4);
                    $i=addingRows($result, $days[$j], $min, $h, $i);
                }
            echo '</table>';
        echo '</div>';
    }
}
function changeDatabase($conn){
    $result=loadEvents($conn);
    $num_rows=$result->num_rows;
    if(isset($_POST['Name'])&&(isset($_POST['day']))&&(isset($_POST['begH']))&&(isset($_POST['begMin']))
      &&(isset($_POST['endH']))&&(isset($_POST['endMin']))){
        if(double_form_send($result,$_POST['day'],$_POST['begMin'],$_POST['begH'])){ 
            $values=array(mysql_fix_string($conn,$_POST['Name']), mysql_fix_string($conn,$_POST['day']),
                    mysql_fix_string($conn,$_POST['begMin']), mysql_fix_string($conn,$_POST['endMin']),
                    mysql_fix_string($conn,$_POST['begH']), mysql_fix_string($conn,$_POST['endH']));
            $query="INSERT INTO `events`(`title`, `dat`, `beginingMinutes`, `endingMinutes`, `beginingH`, `endingH`)".
                    "VALUES ('$values[0]','$values[1]','$values[2]','$values[3]','$values[4]','$values[5]')";
            $result=$conn->query($query);
            if(!$result) die($conn->error);
        }
    }
    for($i=0 ; $i<$num_rows ; $i++){
        if (is_object($result)) {
            $result->data_seek($i);
            $row= $result->fetch_array(MYSQLI_ASSOC);
            if(isset($_POST["execute" . $i . ($_SESSION['count']-1)])){
                $query="DELETE FROM `events` WHERE `dat`='".$row['dat']."' AND`beginingMinutes`='".$row['beginingMinutes']
                        ."' AND`beginingH`='".$row['beginingH']."';";
                $result=$conn->query($query);
                if(!$result) die($conn->error);
            }
        }
     }
    return loadEvents($conn);
}
function loadEvents($conn){
    $query="SELECT * FROM events";
    $result= $conn->query($query);
    if(!$result) die ($conn->error);
    return $result;
}
function addingRows ($result, $day, $min, $h, $index){
    $prev_index=$index;
    $event="";
    echo '<tr class="table__row">';
        echo "<td id='$day-$index' ";
    echo ($index%4==0)?'class="table__date table__HourTd':'class="table__date ';
    for ($k=0 ; $k<$result->num_rows ; $k++){
        $result->data_seek($k);
        $row= $result->fetch_array(MYSQLI_ASSOC);
        if(($day==$row['dat'])&&($min==$row['beginingMinutes'])&&($h==$row['beginingH'])){
            $length=($row['endingH'] - $row['beginingH'])*4 + $row['endingMinutes']/15 - $row['beginingMinutes']/15+1;
            $index=$index-1+$length;
            $event=$row['beginingH'].":".$row['beginingMinutes']." - ".$row['endingH'].":".$row['endingMinutes']."<br>". $row['title'];
            echo ' event" style="height:' . $length*16 . 'px">';
            removingButton($k);
         }
    }
    echo ($event=="")?'">':"<p>".$event."</p>";
    if(($prev_index==$index)&&($index%4==0)) echo "<strong>" . $h . ":00</strong>";
    echo '</td></tr>';
    return $index;
}
function removingButton($index){
    echo "<form action='index.php' method='post' class='deleteBtn'>";
    echo    "<input type='hidden' name='execute" . $index . $_SESSION['count'] . "' value='true'>";
    echo    "<input type='submit' value='X'>";
    echo "</form>";
}
function eventForm($result, $conn, $days){
    echo '<form action="index.php" method="post" class="eventForm">';
        echo '<i class="fa fa-calendar-plus-o" aria-hidden="true"></i>';
        echo '<label for="Name">Name:</label>';
        echo '<input type="text" name="Name" class="eventName">';
        echo '<label for="day">Day:</label>';
        select('day',$days);
        echo "<label>Begining:</label>";
        echo "<input type='number' name='begH' min='6' max='21' placeholder='-' class='numb'>:";
        select('begMin',$days);
        echo "<label>Ending:</label>";
        echo "<input type='number' name='endH' min='6' max='21' placeholder='-' class='numb'>:";
        select('endMin',$days);
        echo "<input type='submit' value='+' class='subnitBtn'>";
    echo "</form>";
}
function double_form_send($result, $day, $min, $h){
    $doubleInsert=1;
    for ($i=0 ; $i<$result->num_rows ; $i++){
        $result->data_seek($i);
        $row2= $result->fetch_array(MYSQLI_ASSOC);
            if(($_POST['day']==$row2['dat'])&&($_POST['begMin']==$row2['beginingMinutes'])&&($_POST['begH']==$row2['beginingH']))
                $doubleInsert=0;
    }
    return $doubleInsert;
}
function select($name,$days){
    echo "<select name='" . $name . "' class='numb'>";
        echo "<option value='' disabled selected>-</option>";
    if ($name=='day'){
      for($i=0 ; $i<5 ; $i++)
        echo "<option value=$days[$i]>$days[$i]</option>";
    }
    else {
      for($i=0 ; $i<4 ; $i++){
        $minutes=15*$i;
        echo '<option>' . $minutes . '</option>';
      }
    }
    echo "</select>";
}
function mysql_fix_string($conn, $string){
    if(get_magic_quotes_gpc()) $string=stripslashes($string);
    return $conn->real_escape_string($string);
}
?>