<?php
include 'jsonHandler.php';
include 'userHandler.php';

$jsonHandler = new JSONHandler();
$userHandler = new UserHandler($jsonHandler);

$msglist = $userHandler->getMessageLogData();
$dataSize = count($msglist['id']);

?>

<html>
	<head>
		<title>Message View</title>
	</head>
	<body>
		<?php
			if($dataSize > 0) { ?>
			<table>
				<tr>
					<th>Message Server ID</th>
					<th>Who Send</th>
					<th>Who Receive</th>
					<th>Sender Name</th>
					<th>Receiver Name</th>
					<th>Message</th>
					<th>Message Key</th>
					<th>Message Hash</th>
					<th>Status</th>
				</tr>

				<?php
				for($i = 0; $i<$dataSize; $i++) { ?>
					<tr>
						<td><?php echo $msglist['id'][$i]; ?></td>
						<td><?php echo $msglist['id_sender'][$i]; ?></td>
						<td><?php echo $msglist['id_receiver'][$i]; ?></td>
						<td><?php echo $msglist['sender_name'][$i]; ?></td>
						<td><?php echo $msglist['receiver_name'][$i]; ?></td>
						<td><?php echo $msglist['message'][$i]; ?></td>
						<td><?php echo $msglist['message_key'][$i]; ?></td>
						<td><?php echo $msglist['message_hash'][$i]; ?></td>
						<td><?php echo $msglist['status'][$i]; ?></td>
					</tr>
				<?php }
				 ?>
			</table>
		<?php	}
		?>
	</body>
</html>