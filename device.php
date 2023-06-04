<?php

// Include the database connection file
require_once 'database.php';

// Function to handle device information
function handleDeviceInformation($deviceName, $deviceIP, $status) {
  // Generate a random device ID
  $deviceID = generateDeviceID();

  // Get the current timestamp
  $timestamp = date('Y-m-d H:i:s');

  // Save the device information to the database
  saveDeviceInfo($deviceID, $deviceName, $deviceIP, $status, $timestamp);

  // Display the connected devices
  displayConnectedDevices();
}

// Generate a random device ID
function generateDeviceID() {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $deviceID = '';
  $length = 10;

  for ($i = 0; $i < $length; $i++) {
    $deviceID .= $characters[rand(0, strlen($characters) - 1)];
  }

  return $deviceID;
}

// Save the device information to the database
function saveDeviceInfo($deviceID, $deviceName, $deviceIP, $status, $timestamp) {
  global $conn; // Access the global database connection variable

  // Insert the device information into the table
  $query = "INSERT INTO devices (device_id, device_name, device_ip, status, timestamp_connected) 
            VALUES ('$deviceID', '$deviceName', '$deviceIP', '$status', '$timestamp')";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to save device information: ' . mysqli_error($conn));
  }
}

// Display the connected devices
function displayConnectedDevices() {
  global $conn; // Access the global database connection variable

  // Fetch the connected devices from the database
  $query = "SELECT * FROM devices";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    die('Failed to fetch connected devices: ' . mysqli_error($conn));
  }

  // Check if there are any connected devices
  if (mysqli_num_rows($result) > 0) {
    echo "<h3>Connected Devices:</h3>";
    echo "<table>";
    echo "<tr><th>Device ID</th><th>Device Name</th><th>Device IP</th><th>Status</th></tr>";

    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>" . $row['device_id'] . "</td>";
      echo "<td>" . $row['device_name'] . "</td>";
      echo "<td>" . $row['device_ip'] . "</td>";
      echo "<td>" . $row['status'] . "</td>";
      echo "</tr>";
    }

    echo "</table>";
  } else {
    echo "<p>No connected devices</p>";
  }
}


// Handle the device information received from Arduino
if (isset($_POST['deviceName']) && isset($_POST['deviceIP']) && isset($_POST['status'])) {
  $deviceName = $_POST['deviceName'];
  $deviceIP = $_POST['deviceIP'];
  $status = $_POST['status'];

  handleDeviceInformation($deviceName, $deviceIP, $status);
}

?>
