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
                    $rows=$result->num_rows;
                    $days[]="mond";
                    $days[]="tues";
                    $days[]="weds";
                    $days[]="thur";
                    $days[]="frid";
                    for ($j=0 ; $j<5 ; $j++){
                        echo '<div class="col-sm-2 col-sm-12 day';
                        if($j==0)echo ' col-sm-offset-1';
                        echo '">';
                            echo '<table>';
                                echo '<th class="table__header">' . $days[$j] . '</th>';
                                for($i=0 ; $i<64 ; $i++){
                                    $min= 15*($i%4);
                                    $h=6+$i/4;
                                    $h=(int)$h;
                                    echo '<tr class="table__row"><td class="table__date';
                                    if($i%4==0)echo ' table__HourTd';
                                    echo '" ';
                                    $i=serchForEvents($result, $days[$j], $min, $h, $i);
                                    if($i%4==0) echo "<strong>" . $h . ":00</strong>";
                                    echo '</td></tr>';
                                }
                            echo '</table>';
                        echo '</div>';
                    }
                    function serchForEvents ($result, $day, $min, $h, $index){
                        $event="";
                        for ($k=0 ; $k<$result->num_rows ; $k++){
                            $result->data_seek($k);
                            $row= $result->fetch_array(MYSQLI_ASSOC);
                            if(($day==$row['dat'])&&($min==$row['beginingMinutes'])&&($h==$row['beginingH'])){
                                $length=($row['endingH'] - $row['beginingH'])*4 + $row['endingMinutes']/15 - $row['beginingMinutes']/15;
                                echo ' style="height:' . $length*16 . 'px"';
                                $index=$index+$length-1;
                                $event=$row['beginingH'].":".$row['beginingMinutes']." - ".$row['endingH'].":"
                                        .$row['endingMinutes']."<br>". $row['title'];
                            }
                        }
                        echo '>';
                        if($event!=0){
                            echo  "<p class='event'>".$event."</p>";
                        }
                        return $index;
                    }
                    ?>
                    </div>
                </div>
                <div class="addingEvents col-sm-11 col-lg-10 col-sm-offset-1 col-lg-offset-2">
                    <?php
                    $conn=new mysqli('localhost','root','','calendar'); 
                    if($conn->connect_errno) die($conn->connect_errno);
                    echo <<<_END
                    <form action="index.php" method="POST">
                        <label for="Name">Event Name</label>
                        <input type="text" name="Name" class=eventName>
                        <label for="day">Day</label>
                        <select id="day" name="day">
                            <option>mond</option>
                            <option>tues</option>
                            <option>weds</option>
                            <option>thur</option>
                            <option>frid</option>
                        </select>
                        <label>Begining:</label>
_END;
                        echo "<input type='number' name='begH' min='6' max='21' placeholder='-' class='numb'>";
                        select('begMin');
                        echo "<label>Ending:</label>";
                        echo "<input type='number' name='endH' min='6' max='21' placeholder='-' class='numb'>";
                        select('endMin');
                        echo "<input type='submit' value='+'>";
                        echo "</form>";
                        if(isset($_POST['Name'])&&(isset($_POST['day']))&&(isset($_POST['begH']))&&(isset($_POST['begMin']))&&(isset($_POST['endH']))&&(isset($_POST['endMin']))){
                            $name=$_POST['Name'];
                            $day=$_POST['day'];
                            $begH=$_POST['begH'];
                            $begMin=$_POST['begMin'];
                            $endH=$_POST['endH'];
                            $endMin=$_POST['endMin'];
                            $query="INSERT INTO `events`(`title`, `dat`, `beginingMinutes`, `endingMinutes`, `beginingH`, `endingH`)".
                            "VALUES ('$name','$day','$begMin','$endMin','$begH','$endH')";
                            $result=$conn->query($query);
                            if(!$result) die($conn->error);
                            header('Location: RPG.php');
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
                    ?>
                </div>
            </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/calendar.js"></script>
    </body>
</html>