<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <link href="/styles.css" rel="stylesheet">
	<title>Cashapp Transaction Tracker</title>
</head>
<body class = "body">
    <div class = "header">
        <h1>Cashapp Transaction Tracker</h1>
    </div>
    <div style= "text-align: center">
        <form action="" method="post" enctype="multipart/form-data">
        <p class = "upload-file"> Please upload your cash_app_report.csv file! </p><br>
            <div class="drop-zone">
                <span class="drop-zone__prompt">Drop Cashapp csv file here or click to upload</span>
                <input type="file" name="csv_file" id="csv_file" class="drop-zone__input">
            </div> <br>
            <input type = "hidden" name = "submit_file" id = "submit_file" value = "Submit File">
            <button class = "submit-button"> Submit File </button>
        </form>
    </div>
    <script src="/main.js"></script>
</body>
<?php

?>

</html>