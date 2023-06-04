<?php
// Database credentials
$servername = 'localhost';
$username = 'your_username';
$password = 'your_password';
$dbname = 'your_database_name';

// Create a database connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the connection was successful
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Create the necessary tables if they do not exist
createTables();

// Function to create the necessary tables
function createTables()
{
    global $conn;
    
    // Create the calibration table if it doesn't exist
    $calibrationTableQuery = "CREATE TABLE IF NOT EXISTS calibration (
        posture_id INT AUTO_INCREMENT PRIMARY KEY,
        posture_name VARCHAR(50) NOT NULL,
        created_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $calibrationTableQuery);

    // Create the calibration_raw table if it doesn't exist
    $calibrationRawTableQuery = "CREATE TABLE IF NOT EXISTS calibration_raw (
        posture_name VARCHAR(50) NOT NULL,
        upper_back INT NOT NULL,
        middle_back INT NOT NULL,
        lower_back INT NOT NULL,
        left_shoulder INT NOT NULL,
        right_shoulder INT NOT NULL,
        left_side INT NOT NULL,
        right_side INT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $calibrationRawTableQuery);
    
    // Create the calibration_angles table if it doesn't exist
    $calibrationAnglesTableQuery = "CREATE TABLE IF NOT EXISTS calibration_angles (
        posture_name VARCHAR(50) NOT NULL,
        angle1_min INT NOT NULL,
        angle1_max INT NOT NULL,
        angle2_min INT NOT NULL,
        angle2_max INT NOT NULL,
        angle3_min INT NOT NULL,
        angle3_max INT NOT NULL,
        angle4_min INT NOT NULL,
        angle4_max INT NOT NULL,
        angle5_min INT NOT NULL,
        angle5_max INT NOT NULL,
        angle6_min INT NOT NULL,
        angle6_max INT NOT NULL,
        angle7_min INT NOT NULL,
        angle7_max INT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $calibrationAnglesTableQuery);

    // Create the monitoring table if it doesn't exist
    $monitoringTableQuery = "CREATE TABLE IF NOT EXISTS monitoring (
        id INT AUTO_INCREMENT PRIMARY KEY,
        posture_name VARCHAR(50) NOT NULL,
        upperbackangle FLOAT NOT NULL,
        middlebackangle FLOAT NOT NULL,
        lowerbackangle FLOAT NOT NULL,
        leftshoulderangle FLOAT NOT NULL,
        rightshoulderangle FLOAT NOT NULL,
        leftsideangle FLOAT NOT NULL,
        rightsideangle FLOAT NOT NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    mysqli_query($conn, $monitoringTableQuery);
}

// Update the minimum and maximum angles in the calibration_angles table
// Update the minimum and maximum angles in the calibration table
function updateMinMaxAngles($postureName, $sensorData) {
    global $conn;
    
    // Calculate the minimum and maximum angles
    $minAngles = [];
    $maxAngles = [];
    
    foreach ($sensorData as $key => $value) {
        if (strpos($key, 'min_') === 0) {
            $angleKey = substr($key, 4);
            $minAngles[$angleKey] = $value;
        } elseif (strpos($key, 'max_') === 0) {
            $angleKey = substr($key, 4);
            $maxAngles[$angleKey] = $value;
        }
    }
    
    // Prepare the SQL statement
    $query = "UPDATE calibration SET ";
    
    foreach ($minAngles as $angleKey => $minValue) {
        $query .= "{$angleKey}_min = {$minValue}, ";
    }
    
    foreach ($maxAngles as $angleKey => $maxValue) {
        $query .= "{$angleKey}_max = {$maxValue}, ";
    }
    
    // Remove the trailing comma and space
    $query = rtrim($query, ', ');
    
    $query .= " WHERE posture_name = '{$postureName}'";
    
    // Execute the update query
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die('Failed to update minimum and maximum angles: ' . mysqli_error($conn));
    }
}

?>
