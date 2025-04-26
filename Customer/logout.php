<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fefefe;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .message {
            font-size: 20px;
            color: #333;
            opacity: 0.8;
        }
    </style>
    <script>
        setTimeout(function () {
            window.location.href = "../index.html";
        }, 1000);
    </script>
</head>
<body>
    <div class="message">Thank you! Redirecting...</div>
</body>
</html>
