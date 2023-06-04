<?php
// Include the database.php file
require_once 'database.php';

// Function to retrieve existing postures from the calibration table
function getExistingPostures()
{
    global $conn;

    $query = "SELECT DISTINCT posture_name FROM calibration";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die('Failed to retrieve existing postures: ' . mysqli_error($conn));
    }

    $postures = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $postures[] = $row['posture_name'];
    }

    return $postures;
}

// Retrieve existing postures
$existingPostures = getExistingPostures();

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

<?php
// Handle the button clicks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['calibrate_existing'])) {
        // Calibrate existing posture
        $postureName = $_POST['existing_posture'];
        $command = "calibrate_existing|$postureName";
        // Redirect to calibrate.php with the selected posture as a query parameter
        header("Location: calibrate.php?posture=$postureName");
        exit;
    } elseif (isset($_POST['calibrate_new'])) {
        // Calibrate new posture
        $postureName = $_POST['new_posture'];
        $command = "calibrate_new|$postureName";
        // Redirect to calibrate.php with the new posture as a query parameter
        header("Location: calibrate.php?posture=$postureName");
        exit;
    } elseif (isset($_POST['monitor_posture'])) {
        // Monitor posture
        $command = "monitor_posture";
        // Redirect to monitor.php
        header("Location: monitor.php");
        exit;
    }
}
?>
