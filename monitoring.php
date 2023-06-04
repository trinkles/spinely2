<?php
// Include the database.php file
require_once 'database.php';

// Arduino IP address and port
$arduinoIP = '192.168.1.100';
$arduinoPort = 80;

// Handle the start_monitoring button click
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_monitoring'])) {
    // Construct the command to start monitoring
    $command = "monitoring";

    // Create a cURL resource
    $ch = curl_init();

    // Set the cURL options
    curl_setopt($ch, CURLOPT_URL, "http://{$arduinoIP}:{$arduinoPort}/");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['command' => $command]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        die('Failed to send command to Arduino: ' . curl_error($ch));
    }

    // Close the cURL resource
    curl_close($ch);

    // Display the response from Arduino
    echo 'Command sent to Arduino: ' . $command;
}

// Store sensor values in the monitoring table
function storeSensorValues($postureName, $sensorData)
{
    global $conn;

    // Prepare the SQL statement to insert the sensor values
    $query = "INSERT INTO monitoring (posture_name, upperbackangle, middlebackangle, lowerbackangle, leftshoulderangle, rightshoulderangle, leftsideangle, rightsideangle) VALUES ('{$postureName}', '{$sensorData['upperbackangle']}', '{$sensorData['middlebackangle']}', '{$sensorData['lowerbackangle']}', '{$sensorData['leftshoulderangle']}', '{$sensorData['rightshoulderangle']}', '{$sensorData['leftsideangle']}', '{$sensorData['rightsideangle']}')";

    // Execute the insert query
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to store sensor values: ' . mysqli_error($conn));
    }
}

// Check if monitoring values are within the calibration range
function checkCalibrationRange()
{
    global $conn, $arduinoIP, $arduinoPort;

    // Prepare the SQL statement to retrieve the latest monitoring values
    $query = "SELECT * FROM monitoring ORDER BY timestamp DESC LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to retrieve monitoring values: ' . mysqli_error($conn));
    }

    $monitoringValues = mysqli_fetch_assoc($result);

    // Retrieve the calibration ranges for the corresponding posture
    $calibrationQuery = "SELECT * FROM calibration_angles WHERE posture_name = '{$monitoringValues['posture_name']}'";
    $calibrationResult = mysqli_query($conn, $calibrationQuery);

    if (!$calibrationResult) {
        die('Failed to retrieve calibration ranges: ' . mysqli_error($conn));
    }

    $calibrationRanges = mysqli_fetch_assoc($calibrationResult);

    $isWithinRange = true;

    // Iterate through the calibration ranges and check if the monitoring values are within the range
    foreach ($monitoringValues as $key => $value) {
        if ($key === 'posture_name' || $key === 'timestamp') {
            continue; // Skip posture name and timestamp fields
        }

        $angleKey = substr($key, 0, -5); // Remove 'angle' from the key to match the calibration range key

        $minAngleKey = "{$angleKey}_min";
        $maxAngleKey = "{$angleKey}_max";

        $minValue = $calibrationRanges[$minAngleKey];
        $maxValue = $calibrationRanges[$maxAngleKey];

        if ($value < $minValue || $value > $maxValue) {
            $isWithinRange = false;
            break; // No need to continue checking if any value is outside the range
        }
    }

    // If the values remain outside the range for 5 seconds, send a command to the Arduino
    if (!$isWithinRange) {
        sleep(5); // Wait for 5 seconds

        // Check the monitoring values again after waiting
        $result = mysqli_query($conn, $query);

        if (!$result) {
            die('Failed to retrieve monitoring values: ' . mysqli_error($conn));
        }

        $monitoringValues = mysqli_fetch_assoc($result);

        $isWithinRange = true;

        foreach ($monitoringValues as $key => $value) {
            if ($key === 'posture_name' || $key === 'timestamp') {
                continue;
            }

            $angleKey = substr($key, 0, -5);

            $minAngleKey = "{$angleKey}_min";
            $maxAngleKey = "{$angleKey}_max";

            $minValue = $calibrationRanges[$minAngleKey];
            $maxValue = $calibrationRanges[$maxAngleKey];

            if ($value < $minValue || $value > $maxValue) {
                $isWithinRange = false;
                break;
            }
        }

        // If the values are still outside the range, send a command to the Arduino to turn on the motor
        if (!$isWithinRange) {
            $command = "turn_on_motor";

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, "http://{$arduinoIP}:{$arduinoPort}/");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['command' => $command]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            if ($response === false) {
                die('Failed to send command to Arduino: ' . curl_error($ch));
            }

            curl_close($ch);

            echo 'Command sent to Arduino: ' . $command;
        }
    }

    // Output message indicating whether the monitoring values are within the calibration range or not
    if ($isWithinRange) {
        echo 'Monitoring values are within the calibration range.';
    } else {
        echo 'Monitoring values are outside the calibration range.';
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['posture_name'])) {
    $postureName = $_POST['posture_name'];

    // Call the storeSensorValues() function to store the received sensor values in the database
    storeSensorValues($postureName, $_POST);

    // Call the checkCalibrationRange() function to check if the monitoring values are within the calibration range
    checkCalibrationRange();
}
?>

<!-- HTML Form for starting monitoring -->
<form method="post">
    <input type="text" name="posture_name" placeholder="Posture Name" required>
    <button type="submit" name="start_monitoring">Start Monitoring</button>
</form>
