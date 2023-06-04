<?php
// Include the database credentials
require 'database.php';

// Arduino IP address and port
$arduinoIP = '192.168.1.100';
$arduinoPort = 80;

// Command to send to Arduino
$command = '';

// Handle the button clicks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['calibrate_existing'])) {
        // Calibrate existing posture
        $postureName = $_POST['existing_posture'];
        $command = "calibrate_existing|$postureName";
    } elseif (isset($_POST['calibrate_new'])) {
        // Calibrate new posture
        $postureName = $_POST['new_posture'];
        $command = "calibrate_new|$postureName";
    } elseif (isset($_POST['monitor_posture'])) {
        // Monitor posture
        $command = "monitor_posture";
    }
}

// Send command to Arduino
if (!empty($command)) {
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

// Retrieve existing postures from the database
$existingPostures = [];
$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die('Failed to connect to the database: ' . mysqli_connect_error());
}

$query = "SELECT DISTINCT posture_name FROM calibration";
$result = mysqli_query($conn, $query);
if (!$result) {
    die('Failed to retrieve existing postures: ' . mysqli_error($conn));
}

while ($row = mysqli_fetch_assoc($result)) {
    $existingPostures[] = $row['posture_name'];
}

mysqli_close($conn);

?>

<!-- HTML Form for choosing calibration options -->
<form method="post">
    <h3>Choose Calibration Option:</h3>

    <!-- Calibrate Existing Posture -->
    <h4>Calibrate Existing Posture:</h4>
    <select name="existing_posture">
        <?php foreach ($existingPostures as $posture) : ?>
            <option value="<?php echo $posture; ?>"><?php echo $posture; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="calibrate_existing">Calibrate Existing</button>

    <!-- Calibrate New Posture -->
    <h4>Calibrate New Posture:</h4>
    <input type="text" name="new_posture" placeholder="Enter New Posture Name">
    <button type="submit" name="calibrate_new">Calibrate New</button>

    <!-- Monitor Posture -->
    <h4>Monitor Posture:</h4>
    <button type="submit" name="monitor_posture">Start Monitoring</button>
</form>
