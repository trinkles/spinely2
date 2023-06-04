<?php
// Include the database.php file
require_once 'database.php';

// Arduino IP address and port
$arduinoIP = '192.168.1.100';
$arduinoPort = 80;

// Handle the incoming Arduino data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the start_calibration button is clicked
    if (isset($_POST['start_calibration'])) {
        // Command to send to Arduino
        $command = 'calibration';

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

        // Retrieve the Arduino data
        $sensorData = $_POST;

        // Store the raw sensor values in the calibration_raw table
        $query = "INSERT INTO calibration_raw (posture_name, upper_back, middle_back, lower_back, left_shoulder, right_shoulder, left_side, right_side, timestamp)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($conn, $query);

        $values = array_values($sensorData);
        array_unshift($values, 'Sitting straight');
        $types = str_repeat('i', count($values));

        mysqli_stmt_bind_param($stmt, $types, ...$values);

        $result = mysqli_stmt_execute($stmt);

        if (!$result) {
            die('Failed to store raw sensor values: ' . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);

        // Update the minimum and maximum angles in the calibration table
        updateMinMaxAngles("Sitting straight", $sensorData);

        echo 'Posture data updated successfully';
    }
}
?>

<!-- HTML Form for starting calibration -->
<form method="post">
    <button type="submit" name="start_calibration">Start Calibration</button>
</form>
