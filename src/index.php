<?php 
    function createDateMatrix($startDate, $endDate) {
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end = $end->setTime(0,0,1);
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
        <p style="font-size: 18px;"> Put in one or more cash_app_report.csv files for a compact report of money sent and received by day! </p>
    </div>
    <?php if(!isset($_POST['submit_file'])) { ?>
    <div style= "text-align: center">
        <p class = "upload-file"> Please upload your cash_app_report.csv file! </p><br>
        <label for="view_method"> Report Method: </label>
        <select id = "view_method" name = "view_method" onchange='formSelect(this.value)'> 
                <option value="week_dates" selected> Last 7 Days </option>
                <option value="date_input"> Specific Timeframe</option>
        </select> <br> <br>
        
        <form action="" method="post" enctype="multipart/form-data">
            <div action="" method="post" id = "week_dates">
                <?php
                $endDate = date("Y-m-d");
                $startDate = date("Y-m-d", strtotime("-6 days"));
                ?>
                Start Date: <?php echo $startDate . " "; ?> End Date: <?php echo $endDate;?>
                <input type="hidden" id = "last_week_start" name="start_date" value="<?php echo $startDate;?>">
                <input type="hidden" id = "last_week_end" name="end_date" value="<?php echo $endDate;?>">
            </div>
            <div action="" method="post" id="date_input" style=display:none>
                Start Date: <input disabled type='date' id='date_input_start' name='start_date'/>
                End Date: <input disabled type='date' id='date_input_end' name= 'end_date'/>
            </div>
            <br>

            <div class="drop-zone">
                <span class="drop-zone__prompt">Drop Cashapp csv file here or click to upload</span>
                <input type="file" name="csv_file[]" id="csv_file" class="drop-zone__input" multiple>
            </div> <br>

            Eastern to Central Time (Minus One Hour): 
            <input type="checkbox" name="ESTtoCST" value="Yes" checked/> <br> <br>

            <input type = "hidden" name = "submit_file" id = "submit_file" value = "Submit File">
            <button type = "submit/button" class = "submit-button"> Submit File </button>
        </form>
    </div>
    <?php } ?>
    <script src="/src/main.js"></script>
    <?php 
        if(isset($_POST['submit_file'])) {
            echo "<br> <div style= 'text-align: center'><form action='' method='post'>";
            echo "<input type = 'submit' name = submit_new_file class = 'submit-button' align='center' value = 'Submit New File'>";
            echo "</form> </div>";
            $dateMatrix = createDateMatrix($_POST['start_date'], $_POST['end_date']);
            $fileCount = count($_FILES['csv_file']['name']);
            if ($fileCount == 0) {
                echo "Please upload a file! <br>";
                return;
            }
            for($i=0; $i<$fileCount; $i++) {
                if(!str_ends_with($_FILES['csv_file']['name'][0], '.csv')) {
                    echo "<h1 align='center'>Please upload a .csv file!</h1>";
                    return;
                }
            }

            echo "<h1 align='center'>Start Date: " . $_POST['start_date'] . "<br> End Date: " . $_POST['end_date'] . "</h1><br>";
            for($i=0; $i<$fileCount; $i++) {
                $file = fopen($_FILES['csv_file']['tmp_name'][$i], 'r');
                while($transaction = fgetcsv($file)) {
                    if($transaction[0] != "Transaction ID" && $transaction[2] != "Cash out" && !(floatval(str_replace('$', '', $transaction[6])) > 0 && str_contains($transaction[13], "MasterCard"))){ #Omits first row from parse and ensures it is a transaction only, not a "cash out"
                        if(isset($_POST["ESTtoCST"])){
                            $longDate = substr($transaction[1], 0, 19);
                            $tempDate = new DateTime($longDate);
                            $subDate = new DateInterval('PT1H');
                            $tempDate->sub($subDate);
                            $dateSubString = $tempDate -> format('Y-m-d');
                        }
                        else {
                            $dateSubString = substr($transaction[1], 0, 10);
                        }
                        if(array_key_exists($dateSubString, $dateMatrix)) {
                            $moneyAmount = floatval(str_replace('$', '', $transaction[6]));
                            if($moneyAmount < 0) {
                                $dateMatrix[$dateSubString]["money_out"] += $moneyAmount; 
                            }
                            else {
                                $dateMatrix[$dateSubString]["money_in"] += $moneyAmount; 
                            }
                        }
                    }
                }
            }

            echo "<table id='report' class='center'>";
                echo "<th>Date</th><th>Money In</th><th>Money Out</th><th>Net</th>";
                foreach($dateMatrix as $date => $values) {
                    echo "<tr> <td> $date </td>";
                    if ($values["money_in"] == 0) { #Money in column
                        echo "<td>";
                    }
                    else {
                        echo "<td class = 'positive'>";
                    }
                    echo "$" . number_format($values["money_in"], 2, '.') . "</td>";
                    
                    if ($values["money_out"] == 0) { #Money out column
                        echo "<td>";
                    }
                    else {
                        echo "<td class = 'negative'>";
                    }
                    echo "$" . number_format($values["money_out"], 2, '.') . "</td>";

                    if($values["money_in"] + $values["money_out"] < 0) { #Net column
                        echo "<td class = 'negative'>";
                    }
                    else {
                        echo "<td class = 'positive'>";
                    }
                    echo "$" . number_format($values["money_in"] + $values["money_out"], 2, '.') . "</td>
                    </tr>";
                }
                echo "</table>";
        }

        if(isset($_POST['submit_new_file'])) {
            header('index.php');
        }
        
    ?>

</body>
    <script>
    function formSelect(_option) {
        if (_option == 'date_input') {
            document.getElementById('date_input').style.display = "block";
            document.getElementById("date_input_start").disabled = false;
            document.getElementById("date_input_end").disabled = false;

            document.getElementById('week_dates').style.display = "none";
            document.getElementById("last_week_start").disabled = true;
            document.getElementById("last_week_end").disabled = true;
        }
        else if(_option == 'week_dates'){
            document.getElementById('week_dates').style.display = "block";
            document.getElementById("date_input_start").disabled = false;
            document.getElementById("date_input_end").disabled = false;

            document.getElementById('date_input').style.display = "none";
            document.getElementById("last_week_start").disabled = true;
            document.getElementById("last_week_end").disabled = true;
        }
    }
    </script>
</html>