<?php
include("db/db1.php");
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="StudentReport.xls"');
$sql_work = $_REQUEST['Enc'];
$sql_work = urldecode($sql_work);
$sql_work = $conn->prepare($sql_work);
$sql_work->execute();
?>
<table border="1">
	<tr>
		<th>Sl No</th>
		<th>Student Name</th>
		<th>Student ID</th>
		<th>Subject</th>
		<th>Marks</th>
	</tr>
	<?php
	$i = 1;
	while($row=$sql_work->fetch(PDO::FETCH_ASSOC)){
		?>
		<tr>
			<td><?=$i?></td>
			<td><?=$row['name']?></td>
			<td><?=$row['studentId']?></td>
			<td><?=$row['subject']?></td>
			<td><?=$row['marks']?></td>
		</tr>
		<?php
		$i++;
	}
	?>
</table>