<?php 
    function createDateMatrix($startDate, $endDate) {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $dateMatrix = array();
        foreach ($period as $dt) {
            $insertDate = $dt->format("Y-m-d");
            $dateMatrix[$insertDate] = array("money_in" => 0, "money_out" => 0);
        }

        return $dateMatrix;
    }

?>
<!DOCTYPE html>
<html>
<head>
    <link href="/src/styles.css" rel="stylesheet">
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon"/>
	<title>Cashapp Transaction Tracker</title>
</head>
<body class = "body">
    <div class = "header">
        <h1>Cashapp Transaction Tracker</h1>
    </div>
    <?php if(!isset($_POST['submit_file'])) { ?>
    <div style= "text-align: center">
        <p class = "upload-file"> Please upload your cash_app_report.csv file! </p><br>
        <label for="view_method"> Report Method: </label>
        <select id = "view_method" name = "view_method" onchange='formSelect(this.value)'> 
                <option value="last_week" selected> Last 7 Days </option>
                <option value="time_frame"> Specific Timeframe</option>
        </select> <br> <br>
        
        <form action="" method="post" enctype="multipart/form-data">
            <div action="" method="post" id = "week_dates">
                <?php
                $endDate = date("Y-m-d");
                $startDate = date("Y-m-d", strtotime("-7 days"));
                ?>
                Start Date: <?php echo $startDate . " "; ?> End Date: <?php echo $endDate;?>
                <input type="hidden" id = "last_week_start" name="start_date" value="<?php echo $startDate;?>">
                <input type="hidden" id = "last_week_end" name="end_date" value="<?php echo $endDate;?>">
            </div>
            <div action="" method="post" id="date_input" style=display:none>
                Start Date: <input type='date' id='date_input_start' name='start_date'/>
                End Date: <input type='date' id='date_input_end' name= 'end_date'/>
            </div>
            <br>

            <div class="drop-zone">
                <span class="drop-zone__prompt">Drop Cashapp csv file here or click to upload</span>
                <input type="file" name="csv_file" id="csv_file" class="drop-zone__input">
            </div> <br>
            <input type = "hidden" name = "submit_file" id = "submit_file" value = "Submit File">
            <button type = "submit/button" class = "submit-button"> Submit File </button>
        </form>
    </div>
    <?php } ?>
    <script src="/src/main.js"></script>

    <?php 
        if(isset($_POST['submit_file'])) {
            
            if($_FILES['csv_file']['name'] != "" && str_ends_with($_FILES['csv_file']['name'], '.csv')) {
                echo "FUNCTIONALITY UNDER CONSTRUCTION <br>";
                echo "File name: ";
                echo $_FILES['csv_file']['name'];
                echo " " . $_FILES['csv_file']['tmp_name'] . "<br>";

                echo "Start Date: " . $_POST['start_date'] . "<br> End Date: " . $_POST['end_date'] . "<br>";
                #$dateMatrix = createDateMatrix();
                echo $_SESSION['start_date'] . "<br>";
                $fileTmpPath = $_FILES['csv_file']['tmp_name'];
                $fileName = $_FILES['csv_file']['name'];
                $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
                while($transaction = fgetcsv($file)) {
                    echo $transaction[1] . "<br>";
                }

            }
            else {
                echo "Please upload a .csv file! <br>";
            } 

            echo "<form action='' method='post'>";
            echo "<input type = 'submit' name = submit_new_file value = 'Submit New File'>";
            echo "</form>";
        }

        if(isset($_POST['submit_new_file'])) {
            header("Location:/src/index.php");
        }
        #document.getElementById("myText").disabled = true;
    ?>
</body>
    <script>
    function formSelect(_option) {
        if (_option == 'time_frame') {
            document.getElementById('date_input').style.display = "block";
            document.getElementById('week_dates').style.display = "none";
        }
        else if(_option == 'last_week'){
            document.getElementById('week_dates').style.display = "block";
            document.getElementById('date_input').style.display = "none";
        }
    }
    </script>
</html>