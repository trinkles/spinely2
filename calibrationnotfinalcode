<?php

// Include the database connection file
require_once 'database.php';

// Handle the form submission if the button has been clicked
if (isset($_POST['start_calibration'])) {
  startCalibration();
}

echo '<button onclick="startCalibration()">Start Calibration</button>';

// Start the calibration process
function startCalibration() {
  global $conn;

  // Create the calibration table if it doesn't exist
  createCalibrationTable();

  // Create the table for the posture name if it doesn't exist
  $postureName = 'Sitting straight';
  createPostureTable($postureName);

  // Send the command to Arduino for calibration
  sendCalibrationCommand();

  // Save the posture details to the calibration table
  $query = "INSERT INTO calibration (posture_name) VALUES ('$postureName')";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to start calibration: ' . mysqli_error($conn));
  }

  echo 'Calibration started for posture: ' . $postureName;
}

// Create the calibration table if it doesn't exist
function createCalibrationTable() {
  global $conn;

  $query = "CREATE TABLE IF NOT EXISTS calibration (
              posture_id INT AUTO_INCREMENT PRIMARY KEY,
              posture_name VARCHAR(50) NOT NULL,
              timestamp_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
              timestamp_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to create calibration table: ' . mysqli_error($conn));
  }
}

// Create the table for a specific posture if it doesn't exist
function createPostureTable($postureName) {
  global $conn;

  $tableName = strtolower(str_replace(' ', '_', $postureName));

  $query = "CREATE TABLE IF NOT EXISTS $tableName (
              id INT AUTO_INCREMENT PRIMARY KEY,
              upper_back INT NOT NULL,
              middle_back INT NOT NULL,
              lower_back INT NOT NULL,
              left_shoulder INT NOT NULL,
              right_shoulder INT NOT NULL,
              left_side INT NOT NULL,
              right_side INT NOT NULL,
              timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to create posture table: ' . mysqli_error($conn));
  }
}

// Send the calibration command to Arduino
function sendCalibrationCommand() {
  // Arduino IP address and port
  $arduinoIP = '192.168.1.100';
  $arduinoPort = 80;

  // Command to send to Arduino
  $command = 'calibration';

  // Create a cURL resource
  $ch = curl_init();

  // Set the cURL options
  curl_setopt($ch, CURLOPT_URL, "http://{$arduinoIP}:{$arduinoPort}/");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $command);
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

// Handle the incoming Arduino data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve the Arduino data
  $upperBackValue = $_POST['upper_back'];
  $middleBackValue = $_POST['middle_back'];
  $lowerBackValue = $_POST['lower_back'];
  $leftShoulderValue = $_POST['left_shoulder'];
  $rightShoulderValue = $_POST['right_shoulder'];
  $leftSideValue = $_POST['left_side'];
  $rightSideValue = $_POST['right_side'];

  // Store the raw sensor values in the calibration table
  $query = "INSERT INTO calibration_raw (upper_back, middle_back, lower_back, left_shoulder, right_shoulder, left_side, right_side)
            VALUES ($upperBackValue, $middleBackValue, $lowerBackValue, $leftShoulderValue, $rightShoulderValue, $leftSideValue, $rightSideValue)";

  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to store raw sensor values: ' . mysqli_error($conn));
  }

  // Update the minimum and maximum angles in the posture table
  $postureName = 'Sitting straight';
  updateMinMaxAngles($postureName, $upperBackValue, $middleBackValue, $lowerBackValue, $leftShoulderValue, $rightShoulderValue, $leftSideValue, $rightSideValue);
}

// Update the minimum and maximum angles in the posture table
function updateMinMaxAngles($postureName, $upperBackValue, $middleBackValue, $lowerBackValue, $leftShoulderValue, $rightShoulderValue, $leftSideValue, $rightSideValue) {
  global $conn;

  $tableName = strtolower(str_replace(' ', '_', $postureName));

  // Retrieve the current minimum and maximum angles from the table
  $query = "SELECT * FROM $tableName";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to retrieve posture data: ' . mysqli_error($conn));
  }

  $row = mysqli_fetch_assoc($result);

  $minUpperBack = $row['min_upper_back'];
  $maxUpperBack = $row['max_upper_back'];
  $minMiddleBack = $row['min_middle_back'];
  $maxMiddleBack = $row['max_middle_back'];
  $minLowerBack = $row['min_lower_back'];
  $maxLowerBack = $row['max_lower_back'];
  $minLeftShoulder = $row['min_left_shoulder'];
  $maxLeftShoulder = $row['max_left_shoulder'];
  $minRightShoulder = $row['min_right_shoulder'];
  $maxRightShoulder = $row['max_right_shoulder'];
  $minLeftSide = $row['min_left_side'];
  $maxLeftSide = $row['max_left_side'];
  $minRightSide = $row['min_right_side'];
  $maxRightSide = $row['max_right_side'];

  // Update the minimum and maximum angles if necessary
  $minUpperBack = min($minUpperBack, $upperBackValue);
  $maxUpperBack = max($maxUpperBack, $upperBackValue);
  $minMiddleBack = min($minMiddleBack, $middleBackValue);
  $maxMiddleBack = max($maxMiddleBack, $middleBackValue);
  $minLowerBack = min($minLowerBack, $lowerBackValue);
  $maxLowerBack = max($maxLowerBack, $lowerBackValue);
  $minLeftShoulder = min($minLeftShoulder, $leftShoulderValue);
  $maxLeftShoulder = max($maxLeftShoulder, $leftShoulderValue);
  $minRightShoulder = min($minRightShoulder, $rightShoulderValue);
  $maxRightShoulder = max($maxRightShoulder, $rightShoulderValue);
  $minLeftSide = min($minLeftSide, $leftSideValue);
  $maxLeftSide = max($maxLeftSide, $leftSideValue);
  $minRightSide = min($minRightSide, $rightSideValue);
  $maxRightSide = max($maxRightSide, $rightSideValue);

  // Update the minimum and maximum angles in the table
  $query = "UPDATE $tableName SET
              min_upper_back = $minUpperBack,
              max_upper_back = $maxUpperBack,
              min_middle_back = $minMiddleBack,
              max_middle_back = $maxMiddleBack,
              min_lower_back = $minLowerBack,
              max_lower_back = $maxLowerBack,
              min_left_shoulder = $minLeftShoulder,
              max_left_shoulder = $maxLeftShoulder,
              min_right_shoulder = $minRightShoulder,
              max_right_shoulder = $maxRightShoulder,
              min_left_side = $minLeftSide,
              max_left_side = $maxLeftSide,
              min_right_side = $minRightSide,
              max_right_side = $maxRightSide";

  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to update minimum and maximum angles: ' . mysqli_error($conn));
  }
}

?>

<script>
  function startCalibration() {
    // Submit the form to start the calibration
    document.getElementById("calibrationForm").submit();
  }
</script>

<!-- Display the start calibration button -->
<form id="calibrationForm" method="POST">
  <input type="hidden" name="start_calibration" value="true">
</form>
