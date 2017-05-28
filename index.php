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
                <span class="header__title">TWÓJ KALENDARZ</span>
            </div>
        </nav>
            <div class="main container">
                <div class="row">
                    <div class="calendar">
                    <?php
                    $conn=new mysqli('localhost','root','','calendar');
                    if($conn->connect_errno) die($conn->connect_errno);
                    $query="SELECT * FROM events";
                    $result= $conn->query($query);
                    if(!$result) die ($conn->error);
                    $days= array("mond" ,"tues", "weds", "thur", "frid");
                    for ($j=0 ; $j<5 ; $j++){
                        echo '<div class="col-sm-2 col-sm-12 day';
                        if($j==0)echo ' col-sm-offset-1';
                        echo '">';
                            echo '<table>';
                                echo '<th class="table__header">' . $days[$j] . '</th>';
                                for($i=0 ; $i<64 ; $i++){
                                    $min= 15*($i%4);
                                    $h=(int)(6+$i/4);
                                    $i=addingRows($result, $days[$j], $min, $h, $i);
                                }
                            echo '</table>';
                        echo '</div>';
                    }
                    function addingRows ($result, $day, $min, $h, $index){
                        $prev_index=$index;
                        $event="";
                        echo '<tr class="table__row"><td class="table__date';
                        if($index%4==0) echo ' table__HourTd';
                        echo '" ';
                        for ($k=0 ; $k<$result->num_rows ; $k++){
                            $result->data_seek($k);
                            $row= $result->fetch_array(MYSQLI_ASSOC);
                            if(($day==$row['dat'])&&($min==$row['beginingMinutes'])&&($h==$row['beginingH'])){
                                $length=($row['endingH'] - $row['beginingH'])*4 + $row['endingMinutes']/15 - $row['beginingMinutes']/15;
                                echo ' style="height:' . $length*16 . 'px"';
                                $index=$index+$length-1;
                                $event=$row['beginingH'].":".$row['beginingMinutes']." - ".$row['endingH']
                                        .":".$row['endingMinutes']."<br>". $row['title'];
                                echo '>';
                                //removingButton($day,$min,$h,$index);
                            }
                        }
                        if($event=="") echo '>';
                        if($event!=0) echo  "<p class='event'>".$event."</p>";
                        if(($prev_index==$index)&&($index%4==0)) echo "<strong>" . $h . ":00</strong>";
                        echo '</td></tr>';
                        return $index;
                    }
                    function removingButton($d, $m, $h, $index){
                        if(isset($_POST['execute'.$index])){
                            echo "wchodzi";
                            global $conn;
                            $query="DELETE FROM `events` WHERE `dat`='$d' AND`beginingMinutes`='$m' AND`beginingH`='$h';";
                            $result=$conn->query($query);
                            if(!$result) die($conn->error);
                            header('Location: RPG.php');
                        }
                        echo <<<_END
                        <form action="index.php" method="post" class="deleteBtn">
                            <input type="hidden" name="execute$index" value="true">
                            <input type="submit" value="X" class="deleteBtn">
                        </form>
_END;
                        //header('Location: RPG.php');
                    }
                    ?>
                    </div>
                </div>
                <div class="addingEvents col-sm-11 col-lg-10 col-sm-offset-1 col-lg-offset-2">
                    <?php
                    
                    eventForm();
                    function eventForm(){
                        global $result, $conn, $days;
                        $doubleInsert=1;
                        echo <<<_END
                        <form action="index.php" method="POST">
                        <label for="Name">Event Name</label>
                        <input type="text" name="Name" class=eventName>
                        <label for="day">Day</label>
                        <select id="day" name="day">
_END;
                        for($i=0 ; $i<5 ; $i++){
                            echo "<option>$days[$i]</option>";}
                        echo "</select>";
                        echo "<label>Begining:</label>";
                        echo "<input type='number' name='begH' min='6' max='21' placeholder='-' class='numb'>";
                        select('begMin');
                        echo "<label>Ending:</label>";
                        echo "<input type='number' name='endH' min='6' max='21' placeholder='-' class='numb'>";
                        select('endMin');
                        echo "<input type='submit' value='+'>";
                        echo "</form>";
                        for ($i=0 ; $i<$result->num_rows ; $i++){
                            $result->data_seek($i);
                            $row2= $result->fetch_array(MYSQLI_ASSOC);
                            if(($_POST['day']==$row2['dat'])&&($_POST['begMin']==$row2['beginingMinutes'])&&
                                ($_POST['begH']==$row2['beginingH']))
                                $doubleInsert=0;
                        }
                        if(($doubleInsert==1)&&isset($_POST['Name'])&&(isset($_POST['day']))&&(isset($_POST['begH']))&&
                                (isset($_POST['begMin']))&&(isset($_POST['endH']))&&(isset($_POST['endMin']))){
                            $values=array(mysql_fix_string($conn,$_POST['Name']), mysql_fix_string($conn,$_POST['day']),
                                mysql_fix_string($conn,$_POST['begH']), mysql_fix_string($conn,$_POST['begMin']),
                                mysql_fix_string($conn,$_POST['endH']), mysql_fix_string($conn,$_POST['endMin']));
                            $query="INSERT INTO `events`(`title`, `dat`, `beginingMinutes`, `endingMinutes`, `beginingH`, `endingH`)".
                            "VALUES ('$values[0]','$values[1]','$values[2]','$values[3]','$values[4]','$values[5]')";
                            $result=$conn->query($query);
                            if(!$result) die($conn->error);
                        }
                    }
                        function select($name){
                            echo "<select name='" . $name . "' class='numb'>";
                            echo "<option value='' disabled selected>-</option>";
                                for($i=0 ; $i<4 ; $i++){
                                    $minutes=15*$i;
                                    echo '<option>' . $minutes . '</option>';
                                }
                            echo "</select>";
                        }
                        function mysql_fix_string($conn, $string){
                            if(get_magic_quotes_gpc()) $string=stripslashes($string);
                            return $conn->real_escape_string($string);
                        }
                    ?>
                </div>
            </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/calendar.js"></script>
    </body>
</html>