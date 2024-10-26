<?php
include("db/db1.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MySchool</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="style/style1.css" rel="stylesheet">
</head>
<body>
  <div style="display: flex;">
    <h5 style="padding:20px;width: 70%;">Welcome <?=$_SESSION['user']?>,</h5>
    <div align="right" style="padding: 20px;width: 30%;">
     <a href="javascript:void(0)">Home</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="logout()">Logout</a>
   </div>
 </div>
 <div>
  <div style="float:right;padding: 20px;">
    <button type="button"  data-toggle="modal" data-target="#myModal">
      Add Student
    </button>
  </div>
  <br>
  <br>
  <?php
  $fetchallrecords=$conn->prepare("SELECT * FROM student WHERE status='1'");
  $fetchallrecords->execute();
  $count=$fetchallrecords->rowCount();
  if($count>0){
    ?>
    <div style="display: flex;">
      <input type="text" name="namesearch" id="namesearch" placeholder="Student Name" required style="margin-left: 10px;width: 20%;">
      &nbsp;&nbsp;&nbsp;
      <input type="text" name="studentidsearch" id="studentidsearch" placeholder="Student Id" required style="width: 20%;">
      &nbsp;&nbsp;&nbsp;
      <input type="text" name="subjectsearch" id="subjectsearch" placeholder="Subject" required style="width: 20%;">&nbsp;&nbsp;&nbsp;
      <div width="">
        <button type="button" style="margin-top: 10px;" onclick="displaystudents(1)">
          Search
        </button>
      </div>
    </div>
    <?php
  }
  ?>
  <div id="studentdata"></div>
</div>
<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-m">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add Student</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body">
       <form id="studentform" name="studentform">
         <div>
          <label style="margin: 0;">Student Name &nbsp;<font color="red">*</font></label>
          <input type="text" name="name" id="name" placeholder='Student Name' required  style="margin: 0;">
        </div>
        <br>
        <div>
          <label style="margin: 0;">Student Id &nbsp;<font color="red">*</font></label>
          <input type="text" name="studentid" id="studentid" placeholder="Student Id" required style="margin: 0;">
        </div>
        <br>
        <div>
          <label  style="margin: 0;">Subject&nbsp;<font color="red">*</font></label>
          <input type="text" name="subject" id="subject" placeholder="Subject" required  style="margin: 0;"></div>
          <br>
          <div>
           <label  style="margin: 0;">Marks&nbsp;<font color="red">*</font></label>
           <input type="number" name="marks" id="marks" placeholder="Marks" required oninput="this.value = this.value.replace(/[^0-9]/g, '');"  style="margin: 0;"></div>
           <br>
           <center><button type="button"  onclick="addstudent()" id="addstudentbtn">Add</button></center>
         </form>
       </div>
       <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editstudentdata">
  <div class="modal-dialog modal-m">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Student Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="editresponse"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
  $(document).ready(function () {
    displaystudents(1);
  });
  addstudent=()=>{
    var isValid = true;
    var inputs = document.querySelectorAll('#studentform input');
    inputs.forEach(function(input) {
      if (input.value.trim() === '') {
        isValid = false;
        input.style.borderColor = 'red';
      } else {
        input.style.borderColor = '';
      }
    });
    if (!isValid) {
      event.preventDefault();
      alert("Please Fill all the fields");
      return false;
    }
    var name=document.getElementById("name").value;
    var studentid=document.getElementById("studentid").value;
    var subject=document.getElementById("subject").value;
    var marks=document.getElementById("marks").value;
    var type="addstudent";
    $.ajax({
      type: "POST",
      url: "operations_ajax.php",
      data: "type="+btoa(type)+"&name="+btoa(name)+"&subject="+btoa(subject)+"&studentid="+btoa(studentid)+"&marks="+btoa(marks),
      beforeSend:function()
      {
        $("#addstudentbtn").attr("disabled",true);
        $("#addstudentbtn").html("Please wait...");
      },
      success: function (response) {
       $("#addstudentbtn").attr("disabled",false);
       $("#addstudentbtn").html("Add");
       var JSONObj=JSON.parse(response);
       alert(JSONObj.msg);
       if(JSONObj.status=="success"){
        $("#studentform")[0].reset();
        $("#myModal").modal('toggle');
        displaystudents(1);
      }
    }
  });
  }
  displaystudents=(page_num)=>{
    if(document.getElementById("namesearch")){
      var namesearch=document.getElementById("namesearch").value;
    }else{
      var namesearch="";
    }
    if(document.getElementById("subjectsearch")){
      var subjectsearch=document.getElementById("subjectsearch").value;
    }else{
     var subjectsearch=""; 
   }
   if(document.getElementById("studentidsearch")){
    var studentidsearch=document.getElementById("studentidsearch").value;
  }else{
    var studentidsearch="";
  }
  var type="displaystudents";
  $.ajax({
    type: "POST",
    url: "operations_ajax.php",
    data: "type="+btoa(type)+"&page_num="+btoa(page_num)+"&subjectsearch="+btoa(subjectsearch)+"&namesearch="+btoa(namesearch)+"&studentidsearch="+btoa(studentidsearch),
    beforeSend:function()
    {
    },
    success: function (response) {
     $("#studentdata").html(response);
   }
 });
}
editdata=(id)=>{
 var type="editdata";
 $.ajax({
  type: "POST",
  url: "operations_ajax.php",
  data: "type="+btoa(type)+"&id="+btoa(id),
  beforeSend:function()
  {
  },
  success: function (response) {
   $("#editresponse").html(response);
 }
});
}
Updatestudentdata=(id)=>{
  var isValid = true;
  var inputs = document.querySelectorAll('#editstudentform input');
  inputs.forEach(function(input) {
    if (input.value.trim() === '') {
      isValid = false;
      input.style.borderColor = 'red';
    } else {
      input.style.borderColor = '';
    }
  });
  if (!isValid) {
    event.preventDefault();
    alert("Please Fill all the fields");
    return false;
  }
  if(confirm("Are you sure to Update Record?")){
    var name=document.getElementById("editname").value;
    var studentid=document.getElementById("editstudentid").value;
    var subject=document.getElementById("editsubject").value;
    var marks=document.getElementById("editmarks").value;
    var type="Updatestudentdata";
    $.ajax({
      type: "POST",
      url: "operations_ajax.php",
      data: "type="+btoa(type)+"&name="+btoa(name)+"&subject="+btoa(subject)+"&studentid="+btoa(studentid)+"&marks="+btoa(marks)+"&id="+btoa(id),
      beforeSend:function()
      {
        $("#updbtn").attr("disabled",true);
        $("#updbtn").html("Please wait...");
      },
      success: function (response) {
       $("#updbtn").attr("disabled",false);
       $("#updbtn").html("Update");
       var JSONObj=JSON.parse(response);
       alert(JSONObj.msg);
       if(JSONObj.status=="success"){
        $("#editstudentdata").modal('toggle');
        displaystudents(1);
      }
    }
  });
  }
}
deletedata=(id)=>{
  if(confirm("Are you Sure to Delete Record?")){
    var type="deletedata";
    $.ajax({
      type: "POST",
      url: "operations_ajax.php",
      data: "type="+btoa(type)+"&id="+btoa(id),
      beforeSend:function()
      {
      },
      success: function (response) {
        var JSONObj=JSON.parse(response);
        alert(JSONObj.msg);
        displaystudents(1);
      }
    });
  }
}
logout=()=>{
 var type="logout";
 $.ajax({
  type: "POST",
  url: "operations_ajax.php",
  data: "type="+btoa(type),
  beforeSend:function()
  {
  },
  success: function (response) {
    window.location.href="Login.html";
  }
});
}
</script>
</body>
</html>
