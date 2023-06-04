<?php
// Require the file containing the database connection code
require_once 'database.php';

// Fetch the minimum flex sensor values from the calibration_raw table
$query = "SELECT MIN(upper_back) AS sensor1, MIN(middle_back) AS sensor2, MIN(lower_back) AS sensor3,
          MIN(left_shoulder) AS sensor4, MIN(right_shoulder) AS sensor5, MIN(left_side) AS sensor6,
          MIN(right_side) AS sensor7 FROM calibration_raw";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Failed to fetch minimum flex sensor values: ' . mysqli_error($conn));
}

// Fetch the minimum values from the result set
$row = mysqli_fetch_assoc($result);
$minFlexValues = array(
    'sensor1' => $row['sensor1'],
    'sensor2' => $row['sensor2'],
    'sensor3' => $row['sensor3'],
    'sensor4' => $row['sensor4'],
    'sensor5' => $row['sensor5'],
    'sensor6' => $row['sensor6'],
    'sensor7' => $row['sensor7']
);

// Close the database connection
mysqli_close($conn);

// Convert the minimum flex values to JSON format
$minFlexValuesJSON = json_encode($minFlexValues);

// Send the JSON response
header('Content-Type: application/json');
echo $minFlexValuesJSON;
?>
