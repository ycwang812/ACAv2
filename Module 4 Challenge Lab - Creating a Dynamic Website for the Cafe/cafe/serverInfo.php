<?php

if ($showServerInfo == 'true') {

	// Retrieve the instance's Public IP address and Instance ID.
	
	$ipAddress = file_get_contents('http://169.254.169.254/latest/meta-data/public-ipv4');
	$instanceID = file_get_contents('http://169.254.169.254/latest/meta-data/instance-id');

	// Display instance metadata.
	
	echo '<hr>';
	echo '<div class="center">';
	echo '	<h3>Server Information</h3>';
	echo '	<p>IP Address: ' . $ipAddress . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Region/Availability Zone: ' . $az . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Instance ID: ' . $instanceID . '</p>';
	echo '</div>';
}

?>