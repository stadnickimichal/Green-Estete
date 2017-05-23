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
                    for ($j=0 ; $j<5 ; $j++){
                        echo '<div class="col-sm-2 col-sm-12 day';
                        if($j==0){echo ' col-sm-offset-1';}
                        echo '">';
                            echo '<table>';
                                echo '<th class="table__header">Piniedzia�ek</th>';
                                for($i=0 ; $i<64 ; $i++){
                                    echo '<tr class="table__row"><td class="table__date';
                                    if($i%4==3){
                                            echo ' table__HourTd';
                                        }
                                    echo '">';
                                    if($i%4==0){
                                            echo 6+$i/4 . ":00";
                                        }
                                    echo '</td></tr>';
                                }
                            echo '</table>';
                        echo '</div>';
                    }
                    ?>
                    </div>
                </div>
            </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/calendar.js"></script>
    </body>
</html>