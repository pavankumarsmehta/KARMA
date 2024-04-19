<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rename Image Using CSV</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .message-list {
            max-height: 200px; 
            overflow-y: auto; 
            border: 1px solid #ccc; 
            padding: 10px;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fade-in 1s;
            width: 80%;
        }

        h1 {
            color: #333;
        }

        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .btn {
            border: 2px solid gray;
            color: gray;
            background-color: white;
            border-radius: 8px;
            padding: 8px 20px;
            margin: 8px 0;
            display: inline-block;
            transition: all 0.3s;
            cursor: pointer;
        }

        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .upload-btn-wrapper .btn {
            border: 2px solid gray;
            color: gray;
            background-color: white;
        }

        .upload-btn-wrapper .btn:hover {
            background-color: #f1f1f1;
        }

        .file-name {
            padding-left: 10px;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="rename.php" method="POST" enctype="multipart/form-data">
            <h1>Upload CSV File</h1>
            <div class="upload-btn-wrapper">
                <button class="btn">Choose a file</button>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" >
                <span class="file-name">No file chosen</span>
            </div>
            <div>
                <button class="btn" type="submit" name="upload">Rename Images</button>
            </div>
        </form>

        <?php 
        if (isset($_SESSION['message'])) {
            echo '<div class="message">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['messages']) && is_array($_SESSION['messages']) && !empty($_SESSION['messages'])) {
            echo '<h1>Proccess Status</h1>
                  <ul class="message-list">';
            foreach ($_SESSION['messages'] as $message) {
                    echo '<li class="message">' . $message . '</li>';
            }
            echo '</ul>';
            // Clear the messages
            $_SESSION['messages'] = array();
            }
        ?>
    </div>

    <script>
        document.getElementById('csv_file').addEventListener('change', function() {
            const fileName = this.files[0].name;
            document.querySelector('.file-name').textContent = fileName;
        });
    </script>
</body>
</html>



<!-- <!DOCTYPE html>
<html>
<head>
    <title>Rename Images Using CSV File</title>
</head>
<body>
<form action="rename.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="csv_file" required>
    <input type="submit" name="upload" value="Upload CSV">
</form>
</body>
</html> -->