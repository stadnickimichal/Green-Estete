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
                        <div class="col-sm-2 col-sm-12 col-md-offset-1 day monday">
                        
                        </div>
                        <div class="col-sm-2 col-sm-12 day">
                        
                        </div>
                        <div class="col-sm-2 col-sm-12 day">
                        
                        </div>
                        <div class="col-sm-2 col-sm-12 day">
                        
                        </div>
                        <div class="col-sm-2 col-sm-12 day">
                            
                        </div>
                    </div>
                </div>
                <div class="addingEvents col-sm-6 col-lg-offset-4 col-sm-offset-3">
                    <form>
                        <label for="Name">Day</label>
                        <select id="day">
                            <option>mon</option>
                            <option>tues</option>
                            <option>wed</option>
                            <option>thurs</option>
                            <option>fri</option>
                        </select>
                        <label for="hour">Day</label>
                        <select id="hour">
                            <?php
                            for($i=6 ; $i<22 ; $i++){
                                echo '<option>' . $i . '</option>';
                            }
                            ?>
                        </select>
                        <label for="Name">Day</label>
                        <select id="day">
                            <?php
                            for($i=0 ; $i<4 ; $i++){
                                $minutes=15*$i;
                                echo '<option>' . $minutes . '</option>';
                            }
                            ?>
                        </select>
                        <label for="Name">Event Name</label>
                        <input type="text" name="Name">
                    </form>
                </div>
            </div>
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="http://code.jquery.com/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/calendar.js"></script>
    </body>
</html>