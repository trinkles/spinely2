<?php
// Include the database connection file
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

    // Store sensor values in the monitoring table
    storeSensorValues();
}

// Function to store sensor values in the monitoring table
function storeSensorValues()
{
    global $conn;

    // Retrieve sensor values from Arduino
    $sensorData = $_POST; // Assuming the sensor values are passed in the POST request

    // Prepare the SQL statement
    $query = "INSERT INTO monitoring (posture_name, upperbackangle, middlebackangle, lowerbackangle, leftshoulderangle, rightshoulderangle, leftsideangle, rightsideangle)
              VALUES ('{$sensorData['posture_name']}', '{$sensorData['upperbackangle']}', '{$sensorData['middlebackangle']}', '{$sensorData['lowerbackangle']}', '{$sensorData['leftshoulderangle']}', '{$sensorData['rightshoulderangle']}', '{$sensorData['leftsideangle']}', '{$sensorData['rightsideangle']}')";

    // Execute the query
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to store sensor values: ' . mysqli_error($conn));
    }
}
?>

<!-- HTML Form for starting monitoring -->
<form method="post">
    <label for="posture_name">Posture Name:</label>
    <input type="text" name="posture_name" id="posture_name" required>
    <br>
    <button type="submit" name="start_monitoring">Start Monitoring</button>
</form>
