<?php
include("db/db1.php");
$type=$_POST['type'];
$type=base64_decode($type);
if($type=="addstudent"){
	$name=addslashes(trim(base64_decode($_POST['name'])));
	$subject=addslashes(trim(base64_decode($_POST['subject'])));
	$studentid=addslashes(trim(base64_decode($_POST['studentid']))); 
	$marks=addslashes(trim(base64_decode($_POST['marks'])));   
	$checkrecordsql=$conn->prepare("SELECT * FROM student WHERE (name='$name' OR studentId='$studentid') AND subject='$subject' AND status='1'");
	$checkrecordsql->execute();
	$checkrowcount=$checkrecordsql->rowCount();
	if($checkrowcount==0){
		$insertsql=$conn->prepare("INSERT INTO student(name,studentId,subject,marks,created_by)VALUES('$name','$studentid','$subject','$marks','admin')");
		if($insertsql->execute()){
			$response=['msg'=>'Student Record Added successfully','status'=>'success'];
		}else{
			$response=['msg'=>'Error In Adding Student Record !','status'=>'error'];
		}
	}else{
		$fetchrow=$checkrecordsql->fetch(PDO::FETCH_ASSOC);
		$id=$fetchrow['id'];
		$updatesql=$conn->prepare("UPDATE student SET marks='$marks',modified_by='admin' WHERE id='$id'");
		if($updatesql->execute()){
			$response=['msg'=>'Same Student Name OR Student Id And Subject Already Exists So Marks Updated successfully','status'=>'success'];
		}else{
			$response=['msg'=>'Same Student Name OR Student Id And Subject Already Exists And Error IN Updating  Marks','status'=>'error'];	
		}
	}
	echo json_encode($response);
}else if($type=="displaystudents"){
	$studentidsearch=addslashes(trim(base64_decode($_POST['studentidsearch'])));
	$namesearch=addslashes(trim(base64_decode($_POST['namesearch'])));
	$subjectsearch=addslashes(trim(base64_decode($_POST['subjectsearch']))); 
	$condition="";
	$condition.=($studentidsearch!='')?"AND studentId LIKE '%$studentidsearch%'":"";
	$condition.=($namesearch!='')?"AND name LIKE '%$namesearch%'":"";
	$condition.=($subjectsearch!='')?"AND subject LIKE '%$subjectsearch%'":"";
	$record_per_page = 5;
	if ($_POST['page_num']) {
		$page_num = base64_decode($_POST['page_num']);
	} else {
		$page_num = 1;
	}
	$start_from = ($page_num - 1) * $record_per_page;
	$fetchallrecords=$conn->prepare("SELECT * FROM student WHERE status='1' $condition LIMIT $start_from, $record_per_page");
	$fetchallrecords->execute();
	$count=$fetchallrecords->rowCount();
	if($count>0){
		?>
		<table border="1" class="uniform-table">
			<tr>
				<th>Sl No</th>
				<th>Student Name</th>
				<th>Student ID</th>
				<th>Subject</th>
				<th>Marks</th>
				<th colspan="2">action</th>
			</tr>
			<?php
			if ($start_from) {
				$i = $start_from + 1;
			} else {
				$i = 1;
			}
			while($row=$fetchallrecords->fetch(PDO::FETCH_ASSOC)){
				?>
				<tr>
					<td><?=$i?></td>
					<td><?=$row['name']?></td>
					<td><?=$row['studentId']?></td>
					<td><?=$row['subject']?></td>
					<td><?=$row['marks']?></td>
					<td >
						<i class="bi bi-pencil" onclick="editdata(<?=$row['id']?>)" data-toggle="modal" data-target="#editstudentdata" style="color: green;"></i>
						<td >
							<i class="bi bi-trash" onclick="deletedata(<?=$row['id']?>)" style="color: red;"></i>
						</td>
					</tr>
					<?php
					$i++;
				}
				?>
			</table>
			<div class="row" style="margin-top: 10px; padding-bottom: 10px;">
				<div class="col-md-4"></div>
				<div class="col-md-4" align="center">
					<section class="comp-section" id="comp_pagination">
						<div class="pagination-box">
							<div>
								<ul class="pagination" style="width: fit-content;">
									<?php
									$query="SELECT * FROM student WHERE status='1'";
									$page_result = $conn->prepare("SELECT * FROM student WHERE status='1' $condition");
									$page_result->execute();
									$total_records = $page_result->rowcount();
									$total_pages = ceil($total_records / $record_per_page);
									$start_loop = $page_num;
									$difference = $total_pages - $page_num;
									$class_page = '';
									if ($total_records >5) {
										if ($difference <= 5) {
											$start_loop = $total_pages - 5;
										}
										$end_loop = $start_loop + 4;
										?>
										<a class='page-link' <?php if ($page_num == 1) {
											echo 'style="background-color: #ddddda;"';
										} ?> onclick="displaystudents(1)" href='javascript:void(0);'>
										<font color="#28a745">First</font>
									</a>
									<?php
									if ($page_num > 1) {
										$first = $page_num - 1;
										?>
										<a class='page-link' <?php if ($page_num == $first) {
											echo 'style="background-color: #ddddda;"';
										} ?> onclick="displaystudents(<?= $first ?>)" href='javascript:void(0);'>
										<font color="#28a745">
											<< </font>
										</a>
										<?php
									}
									for ($i = $start_loop; $i <= $end_loop; $i++) {
										if ($i > 0) {
											?>
											<a class='page-link' <?php if ($page_num == $i) {
												echo 'style="background-color: #ddddda;"';
											} ?> onclick="displaystudents(<?= $i ?>)" href='javascript:void(0);'>
											<font color="#28a745"><?= $i ?></font>
										</a>
										<?php
									}
								}
								if ($page_num <= $end_loop) {
									$last = $page_num + 1;
									?>
									<a class='page-link' <?php if ($page_num == $last) {
										echo 'style="background-color: #ddddda;"';
									} ?> onclick="displaystudents(<?= $last ?>)" href='javascript:void(0);'>
									<font color="#28a745">>></font>
								</a>
								<a class='page-link' <?php if ($page_num == $total_pages) {
									echo 'style="background-color: #ddddda;"';
								} ?> onclick="displaystudents(<?= $total_pages ?>)" href='javascript:void(0);'>
								<font color="#28a745">Last</font>
							</a>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</section>
</div>
<div align="right" class="col-md-4">
	<a  href="student_xl.php?Enc=<?= urlencode($query)?>" style=" padding: 10px;
	background-color: #28a745;
	color: white;
	border: none;
	border-radius: 4px;
	margin-right: 15px;
	cursor: pointer;">Export Excel</a>
</div>
</div>
<?php
}else{
	echo "<div align='center'><font color='red'>No data Found!</font></div>";
}
}else if($type=="editdata"){
	$id=addslashes(trim(base64_decode($_POST['id'])));
	$checkrecordsql=$conn->prepare("SELECT * FROM student WHERE id='$id' AND status='1'");
	$checkrecordsql->execute();
	$fetchrow=$checkrecordsql->fetch(PDO::FETCH_ASSOC);
	$name=$fetchrow['name'];
	$studentId=$fetchrow['studentId'];
	$subject=$fetchrow['subject'];
	$marks=$fetchrow['marks'];
	?>
	<form id="editstudentform" name="editstudentform">
		<div>
			<label style="margin: 0;">Student Name &nbsp;<font color="red">*</font></label>
			<input type="text" name="editname" id="editname" placeholder="Student Name" required value="<?=$name?>" style="margin: 0;">
		</div>
		<br>
		<div>
			<label style="margin: 0;">Student Id &nbsp;<font color="red">*</font></label>
			<input type="text" name="editstudentid" id="editstudentid" placeholder="Student Id" required value="<?=$studentId?>" style="margin: 0;">
		</div>
		<br>
		<div>
			<label  style="margin: 0;">Subject&nbsp;<font color="red">*</font></label>
			<input type="text" name="editsubject" id="editsubject" placeholder="Subject" required value="<?=$subject?>" style="margin: 0;">
		</div>
		<br>
		<div>
			<label  style="margin: 0;">Marks&nbsp;<font color="red">*</font></label>
			<input type="number" name="editmarks" id="editmarks" placeholder="Marks" required value="<?=$marks?>" oninput="this.value = this.value.replace(/[^0-9]/g, '');" style="margin: 0;">
		</div>
		<br>
		<br>
		<center><button type="button"  onclick="Updatestudentdata(<?=$id?>)" id="updbtn">Update</button></center>
	</form>
	<?php
}else if($type=="Updatestudentdata"){
	$id=addslashes(trim(base64_decode($_POST['id'])));
	$name=addslashes(trim(base64_decode($_POST['name'])));
	$subject=addslashes(trim(base64_decode($_POST['subject'])));
	$studentid=addslashes(trim(base64_decode($_POST['studentid']))); 
	$marks=addslashes(trim(base64_decode($_POST['marks'])));   
	$checkrecordsql=$conn->prepare("SELECT * FROM student WHERE (name='$name' OR studentId='$studentid') AND subject='$subject' AND status='1' AND id!='$id'");
	$checkrecordsql->execute();
	$checkrowcount=$checkrecordsql->rowCount();
	if($checkrowcount==0){
		$updatesql=$conn->prepare("UPDATE student SET marks='$marks',name='$name',subject='$subject',studentId='$studentid',modified_by='admin' WHERE id='$id'");
		if($updatesql->execute()){
			$response=['msg'=>'Student Record  Updated successfully','status'=>'success'];
		}else{
			$response=['msg'=>'Error IN Updating  Student Record','status'=>'error'];	
		}
	}else{
		$response=['msg'=>'Same Student Name or Student Id and Subject Exists for Other Student','status'=>'error'];	
	}
	echo json_encode($response);
}else if($type=="deletedata"){
	$id=addslashes(trim(base64_decode($_POST['id'])));
	$updatesql=$conn->prepare("UPDATE student SET status='0',modified_by='admin' WHERE id='$id'");
	if($updatesql->execute()){
		$response=['msg'=>'Student Record Deleted successfully','status'=>'success'];
	}else{
		$response=['msg'=>'Error IN Deleting Student Record','status'=>'error'];	
	}
	echo json_encode($response);
}
else if($type=="logout"){
	session_destroy();
}
?>