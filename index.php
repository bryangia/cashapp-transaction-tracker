<!DOCTYPE html>
<html>
<head>
    <link href="/styles.css" rel="stylesheet">
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
            <div class="drop-zone">
                <span class="drop-zone__prompt">Drop Cashapp csv file here or click to upload</span>
                <input type="file" name="csv_file" id="csv_file" class="drop-zone__input">
            </div> <br>
            <input type = "hidden" name = "submit_file" id = "submit_file" value = "Submit File">
            <button class = "submit-button"> Submit File </button>
        </form>
    </div>
    <?php } ?>
    <script src="/main.js"></script>

    <?php 
        if(isset($_POST['submit_file'])) {
            echo "FUNCTIONALITY UNDER CONSTRUCTION";
            if($_FILES['csv_file']['name'] != "") {
                echo "File name: ";
                echo $_FILES['csv_file']['name'];
            }
            else {
                echo "Please upload a file!";
            } 

            echo "<form action='' method='post'>";
            echo "<input type = 'submit' name = submit_new_file value = 'Submit New File'>";
            echo "</form>";
        }

        if(isset($_POST['submit_new_file'])) {
            header("Location:/index.php");
        }
    ?>
</body>

</html>