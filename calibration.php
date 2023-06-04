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
                upper_back FLOAT(8,2) NOT NULL,
                middle_back FLOAT(8,2) NOT NULL,
                lower_back FLOAT(8,2) NOT NULL,
                left_shoulder FLOAT(8,2) NOT NULL,
                right_shoulder FLOAT(8,2) NOT NULL,
                left_side FLOAT(8,2) NOT NULL,
                right_side FLOAT(8,2) NOT NULL,
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
              )";
  
    $result = mysqli_query($conn, $query);
  
    if (!$result) {
      die('Failed to create posture table: ' . mysqli_error($conn));
    }
  }  

// Send the calibration command to Arduino
function sendCalibrationCommand() {
  // Implement the code to send the calibration command to Arduino
  // This could be through a serial communication or any other method
  // specific to your Arduino setup.
  // Example: Serial.write("CALIBRATE");
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
