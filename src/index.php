<?php 
    function createDateMatrix($startDate, $endDate) {
        $begin = new DateTime('2010-05-01');
        $end = new DateTime('2010-05-10');
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
        <form action="" method="post" enctype="multipart/form-data">
            <p class = "upload-file"> Please upload your cash_app_report.csv file! </p><br>
            <label for="view_method"> Report Method: </label>
            <select id="method" name = "view_method" onchange='formSelect(this.value)'> 
                    <option value="last_week" selected> Last 7 Days </option>
                    <option value="time_frame"> Specific Timeframe</option>
            </select> <br> <br>
            <div id="date_input" style="display:none">    
                Start Date: <input type='date' id='start' name='sales_start' required/>
                End Date: <input type='date' id='end' name='sales_end' required/>
            </div> <br>
                <div class="drop-zone">
                    <span class="drop-zone__prompt">Drop Cashapp csv file here or click to upload</span>
                    <input type="file" name="csv_file" id="csv_file" class="drop-zone__input">
                </div> <br>
                <input type = "hidden" name = "submit_file" id = "submit_file" value = "Submit File">
                <button class = "submit-button"> Submit File </button>
        </form>
    </div>
    <?php } ?>
    <script src="/src/main.js"></script>

    <?php 
        if(isset($_POST['submit_file'])) {
            
            if($_FILES['csv_file']['name'] != "" && str_ends_with($_FILES['csv_file']['name'], '.csv')) {
                echo "FUNCTIONALITY UNDER CONSTRUCTION";
                echo "File name: ";
                echo $_FILES['csv_file']['name'];
                echo " " . $_FILES['csv_file']['tmp_name'] . "<br>";
                $dateMatrix = createDateMatrix();
                
                $fileTmpPath = $_FILES['csv_file']['tmp_name'];
                $fileName = $_FILES['csv_file']['name'];
                $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
                while($transaction = fgetcsv($file)) {
                    echo $transaction[1];
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
    ?>
</body>
    <script>
    function formSelect(_option) {
        if (_option == 'time_frame') {
            document.getElementById('date_input').style.display = "block";
        }
        else {
            document.getElementById('date_input').style.display = "none";
        }
    }
    </script>
</html>