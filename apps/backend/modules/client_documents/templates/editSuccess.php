<?php
 //echo $_SERVER['PHP_SELF']; 
 
$Qry = mysql_query("select * from clientdocuments where id = '".$editId."'");
$qryObj = mysql_fetch_object($Qry);
?>
<div id="sf_admin_container">
<h1>Edit Document</h1>

<div id="sf_admin_header">
</div>

<div id="sf_admin_content">

<form action="" enctype="multipart/form-data" method="post" name="sf_admin_edit_form" id="sf_admin_edit_form">
<input type="hidden" value="<?php echo $editId;?>" id="id" name="id">
<fieldset class="" id="sf_fieldset_none">

<div class="form-row">
  <label class="required" for="faqs_question">Name:</label>  <div class="content">
  
  <input type="text" size="80" value="<?php echo $qryObj->title;?>" id="docTitle" name="docTitle" style="width: 240px;">    </div>
</div>

<div class="form-row">
  <label class="required" for="faqs_answer">Document File:</label>  <div class="content"> <input type="file"  value="" id="documentfile" name="documentfile" style="width: 240px;"><br /><br />
  <a href="http://landncall.zerocall.com/uploads/documents/<?php echo $qryObj->filename;?>" target="_blank"><strong>Old Document File</strong></a> </div>
</div>



<div class="form-row" style="display:none">
  <label for="faqs_status_id">Status:</label>  <div class="content">
  
  <select id="DocStatus" name="DocStatus"><option selected="selected" value=""></option>
<option value="Active">Active</option>
<option value="Pending">Pending</option>
<option value="Closed">Closed</option>
</select>    </div>
</div>

</fieldset>

<ul class="sf_admin_actions">
  <li><input type="button" onclick="document.location.href='http://landncall.zerocall.com/backend_dev.php/client_documents';" value="list" class="sf_admin_action_list"></li>
  <li><input type="submit" class="sf_admin_action_save" value="update" name="update"></li>
</ul>

</form>

<ul class="sf_admin_actions">
      <li class="float-left"></li>
  </ul>
</div>

<div id="sf_admin_footer">
</div>

</div>