<?php
$conn = new mysqli("localhost", "root", "", "iot_irrigation");
if ($conn->connect_error) {
    die("DB ERROR");
}
