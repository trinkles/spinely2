<?php
require_once 'database.php';

function handleDeviceInformation($deviceName, $deviceIP, $status) {
    $deviceConnected = false;
    $deviceID = generateDeviceID();
    $timestamp = date('Y-m-d H:i:s');
    saveDeviceInfo($deviceID, $deviceName, $deviceIP, $status, $timestamp);

    if ($status == 'connected') {
        $deviceConnected = true;
        header('Location: calibration.php');
        exit();
    }
}

function displayLatestConnectedDevice() {
    global $conn;

    $query = "SELECT * FROM devices ORDER BY timestamp_connected DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to fetch connected device: ' . mysqli_error($conn));
    }

    $device = mysqli_fetch_assoc($result);

    return $device;
}

function generateDeviceID() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $deviceID = '';
    $length = 10;

    for ($i = 0; $i < $length; $i++) {
        $deviceID .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $deviceID;
}

function saveDeviceInfo($deviceID, $deviceName, $deviceIP, $status, $timestamp) {
    global $conn;

    $deviceID = mysqli_real_escape_string($conn, $deviceID);
    $deviceName = mysqli_real_escape_string($conn, $deviceName);
    $deviceIP = mysqli_real_escape_string($conn, $deviceIP);
    $status = mysqli_real_escape_string($conn, $status);
    $timestamp = mysqli_real_escape_string($conn, $timestamp);

    $query = "INSERT INTO devices (device_id, device_name, device_ip, status, timestamp_connected) 
              VALUES ('$deviceID', '$deviceName', '$deviceIP', '$status', '$timestamp')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to save device information: ' . mysqli_error($conn));
    }
}

if (isset($_POST['deviceName'], $_POST['deviceIP'], $_POST['status'])) {
    $deviceName = $_POST['deviceName'];
    $deviceIP = $_POST['deviceIP'];
    $status = $_POST['status'];

    handleDeviceInformation($deviceName, $deviceIP, $status);
}

// Fetch the latest connected device
$latestDevice = displayLatestConnectedDevice();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Device Information</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateDeviceInformation() {
            $.ajax({
                url: '',
                dataType: "json",
                success: function (data) {
                    if (data) {
                        $('#deviceName').text(data.device_name);
                        $('#deviceIP').text(data.device_ip);
                        $('#status').text(data.status);
                        <?php if (isset($latestDevice['timestamp'])) : ?>
                            $('#timestamp').text(data.timestamp);
                        <?php endif; ?>

                        if (data.status === 'waiting') {
                            $('#connectionButton').show();
                        } else {
                            $('#connectionButton').hide();
                        }
                    } else {
                        $('#deviceName').text('');
                        $('#deviceIP').text('');
                        $('#status').text('');
                        <?php if (isset($latestDevice['timestamp'])) : ?>
                            $('#timestamp').text('');
                        <?php endif; ?>
                        $('#connectionButton').hide();
                    }
                }
            });
        }

        $(document).ready(function () {
            updateDeviceInformation();
            setInterval(updateDeviceInformation, 5000);
        });
    </script>
</head>
<body>
    <h1>Device Information</h1>

    <h2>Latest Connected Device:</h2>
    <p>Device Name: <span id="deviceName"><?php echo $latestDevice['device_name']; ?></span></p>
    <p>Device IP: <span id="deviceIP"><?php echo $latestDevice['device_ip']; ?></span></p>
    <p>Status: <span id="status"><?php echo $latestDevice['status']; ?></span></p>
    <?php if (isset($latestDevice['timestamp'])) : ?>
        <p>Timestamp: <span id="timestamp"><?php echo $latestDevice['timestamp']; ?></span></p>
    <?php endif; ?>

    <?php if ($latestDevice['status'] === 'waiting') : ?>
        <button id="connectionButton" onclick="location.href='calibration.php';">Go to Calibration</button>
    <?php endif; ?>
</body>
</html>
