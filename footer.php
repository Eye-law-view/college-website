<?php
// Set the default timezone to Asia/Kathmandu (Nepal Time)
date_default_timezone_set('Asia/Kathmandu');

// Get current time in Nepal
$current_time = date('Y-m-d H:i:s');

// Output the current time in a clean format
echo '<footer>';
echo  $current_time . '</p>';
echo '</footer>';
?>
