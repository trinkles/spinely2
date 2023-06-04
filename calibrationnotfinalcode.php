<?php

$arduinoIP = '192.168.1.100';
$arduinoPort = 80;
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

// Handle the incoming Arduino data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the Arduino data
    $sensorData = $_POST;

    // Store the raw sensor values in the calibration table
    $query = "INSERT INTO calibration_raw (upper_back, middle_back, lower_back, left_shoulder, right_shoulder, left_side, right_side)
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    $types = "iiiiiii";
    $values = [];
    foreach ($sensorData as $key => $value) {
        $values[] = $value;
    }

    array_unshift($values, $stmt, $types);
    call_user_func_array('mysqli_stmt_bind_param', $values);

    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Failed to store raw sensor values: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);

    // Update the minimum and maximum angles in the posture table
    $postureName = 'Sitting straight';
    updateMinMaxAngles($postureName, $sensorData);
}

// Update the minimum and maximum angles in the posture table
function updateMinMaxAngles($postureName, $sensorData)
{
    global $conn;

    $tableName = str_replace(' ', '_', strtolower($postureName));

    // Retrieve the current minimum and maximum angles from the table
    $query = "SELECT * FROM $tableName";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to retrieve posture data: ' . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);

    // Update the minimum and maximum angles if necessary
    $updateQuery = "UPDATE $tableName SET ";
    $params = [];
    foreach ($sensorData as $key => $value) {
        $minKey = "min_$key";
        $maxKey = "max_$key";
        $params[] = "$minKey = LEAST($minKey, $value), $maxKey = GREATEST($maxKey, $value)";
    }

    $updateQuery .= implode(", ", $params) . " WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);

    mysqli_stmt_bind_param($stmt, "i", $row['id']);

    $result = mysqli_stmt_execute($stmt);

    if (!$result) {
        die('Failed to update posture data: ' . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);

    echo 'Posture data updated successfully';
}
?>

<form method="post">
  <button type="submit" name="start_calibration">Start Calibration</button>
</form>
