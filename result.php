<?php
// Start the session
session_start();


// Save hyperparameters in the session
$_SESSION['parameters'] = $hyperparameters;

// Convert hyperparameters to JSON
$json_params = json_encode($hyperparameters);

// Define the command to execute the Python script
$command = escapeshellcmd("python3 model.py '$json_params'");

// Execute the Python script and capture the output
$output = shell_exec($command);

// Display the output
echo "<pre>$output</pre>";

// Optionally, print the saved parameters from the session for debugging
echo "<h3>Saved Parameters in Session:</h3>";
echo "<pre>" . print_r($_SESSION['parameters'], true) . "</pre>";
?>
