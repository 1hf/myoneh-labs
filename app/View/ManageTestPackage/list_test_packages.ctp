<?php
	echo $this->Html->script("jquery.blockUI.js");
	echo $this->Html->script("jquery.json-2.2.min.js");
	echo $this->Html->script("jquery-ui-1.8.16.custom.min.js");
	echo $this->Html->css("jquery-ui-1.8.16.custom.css");
	
?>

<!--Details-box2 Start -->
<div class="details-box">

	<div class="details-box-header">
		<div class="details-box-left"></div>
		<div class="details-box-mid">
			<div class="details-icon">
				<?php echo $this->Html->image('details-icon2.png', array('border' => '0')); ?>
			</div>
			<span>Manage Test Package</span>
		</div>
		<div class="details-box-right"></div>
	</div>

	<div class="clear"></div>

	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="details-box-content">
	<tbody>
		<tr>
				<td height="5"></td>
		</tr>

		<tr valign="top">
			<td>
				
				<div class="mega-container grey">
					<ul class="mega-menu" style="margin-left: 4px;">
						<li id="personalDetails">
							<a  class="current" href="<?php echo $this->webroot;?>manage_test_package/list_test_packages">View / Edit Test Packages </a>
						</li>
						<li id="accountDetails">
							<a   href="<?php echo $this->webroot;?>manage_test_package/add_edit_test_package">Add New Test Package</a>
						</li>
						
					</ul>
				</div>
				
				<div class="clear"></div>
					
				<div id="tab2" class="tab_content1">
					<?php
						echo $this->Form->create('searchAreaForm', array('id'=>'searchAreaForm', 'onsubmit' => 'javascript: return search_Area();'));
					?>
					<table cellspacing="1" cellpadding="0" border="0" bgcolor="#ffffff" width="100%" style="border: 1px solid rgb(230, 229, 235);" class="formstyle">
					<tbody>
						<tr>
							<td>
								<table cellspacing="1" cellpadding="7" border="0" width="100%" class="tablestyle">
								<tbody>
									<tr>
										<td width="10%" class="text">Package Name</td>
										<td width="20%" class="textfield">
											<?php echo $this->Form->input('package_name',array('div'=>false, 'label'=>false, 'class'=>'inputfield', 'id'=>'package_name'));?><br />
										</td>
										<td width="10%" class="text">Clinic</td>
										<td width="20%" class="textfield">
											<?php
												
												
												echo $this->Form->input('clinic_id', array('div'=>false, 'label'=>false, 'class'=>'inputfield', 'type'=>'select', 'id' =>'clinic_id','options'=>$clinic_options, 'empty'=>'--Select--'));
											?>
										</td>
                                                                                <td width="10%" class="text">Category</td>
                                                                                <td width="20%" class="textfield">
                                                                                    <?php
												
											echo $this->Form->input('category_id', array('options'=>$package_categories,'div'=>false, 'label'=>false, 'class'=>'inputfield', 'type'=>'select', 'id' =>'category_id', 'empty'=>'--Select--'));
											
												
											?>
                                                                                </td>
                                                                                
										<td  align="right">
											<?php
												echo $this->Form->submit('search_btn.png', array('id'=>'searchButton', 'border' => '0', 'div' =>false, 'onclick' => 'return search_Package();')); ?>
										</td>										
									</tr>									
								</tbody>
								</table>
							</td>
						</tr>
					</tbody>
					</table>
					<?php echo $this->Form->end(); ?>
				
					<div id="listContainer">
						<?php echo $this->Element('ManageTestPackage/list_test_package_element'); ?>
					</div>
					
				</div>

			</td>
		</tr>
	</tbody>
	</table>
		
	<div class="clear"></div>
	<div class="details-box-btm"></div>
	<div id="confirmChangeStatus" title="Confirm Change Status" style="display:none;">
		<table width="100%" cellspacing="0" cellpadding="7" border="0" class="tble_head">
			<tr>
				<td valign="top" class="text">
					Are you sure you want to change the test package status..?
				</td>				
			</tr>
		</table>
	</div>
	<div id="confirmHomeCollectionStatus" title="Confirm Change Status" style="display:none;">
		<table width="100%" cellspacing="0" cellpadding="7" border="0" class="tble_head">
			<tr>
				<td valign="top" class="text">
					Are you sure you want to change the home collection status..?
				</td>				
			</tr>
		</table>
	</div>
	<div id="confirmDelete" title="Confirm Delete" style="display:none;">
		<table width="100%" cellspacing="0" cellpadding="7" border="0" class="tble_head">
			<tr>
				<td valign="top" class="text">
					Are you sure you want to delete this test package..?
				</td>				
			</tr>
		</table>
	</div>
	
	<div id="show_error"></div>

	
	<div id="load_add_form">

	
	</div>



</div>

<script type="text/javascript">
    
var url = 'manage_test_package/list_test_package_element/';

$(document).ready(function(){
	jString = get_json_string_for_searchPackage();	
});

function do_cancel(){

	$('#load_add_form').hide();
}

function get_json_string_for_searchPackage()
{
	var package_name = $('#package_name').val();
	package_name = package_name.replace(" ","_RemSp_");
	var clinic_id = $('#clinic_id').val();
        var category_id = $('#category_id').val();
	
	var filter = {package_name:package_name,
				clinic_id:clinic_id,
                            category_id:category_id};
	jString = $.toJSON(filter);
	return jString;
}

function search_Package()
{
	var jString = get_json_string_for_searchPackage();

	var searchUrl = medtest_root_path+"manage_test_package/list_test_package_element/";
	
	show_loading();
	$.post(
		searchUrl,
		{jString:jString},
		function(responseText)
		{
			$("#listContainer").html(responseText);
			hide_loading();	
		}		
	);
		
	return false;	
}


function get_json_string(action, packageId, currentStatus, statusIndex)
{
	changeStatusTo = 0;
	if(action == "ChangeStatus"){
		changeStatusTo = $("#status_id_"+statusIndex).val();
	}	
	
	var filter = {
				 action:action,
				 packageId:packageId,
				 changeStatusTo:changeStatusTo};
	jString = $.toJSON(filter);
	
	return jString;
}





function perform_operation_package_status(action, packageId,currentStatus,statusIndex)
{
	var jString = get_json_string(action, packageId, currentStatus,0);

	var status_url = medtest_root_path+'manage_test_package/perform_operation_package_status/';
	var redirect_url = medtest_root_path+'manage_test_package/list_test_packages/';

	var data = {packageId : packageId,currentStatus:currentStatus};

	
	if(action == "ChangeStatus")
	{
		$('#confirmChangeStatus').dialog({
		//autoOpen: true,
		modal: true,
		width: 600,
		buttons: {
				"Yes": function(){
					$.ajax
					({
						type	: 'post',
						url		: status_url,
						data	: data,
						async	: false,
						success : function(response)
						{
							if(response.length != 0){
								//alert(response);
								if (response != '') {
									//alert(alert_msg);
									window.location.href=redirect_url;
								}
								//$('#delete_response').html(response);
							}
							// this is for unblocking tat preloader 
							//hide_loading(); 
						},
						error:function(XMLHttpRequest, textStatus, errorThrown)
						{
							alert(textStatus+' '+errorThrown);
						}
					});
				}, 
				"No": function(){
					$("#status_id_"+statusIndex).val(currentStatus);
					$(this).dialog("close");
				} 
			}
		});
	}


	else
	{
		window.location = medtest_root_path+"manage_test_package/list_test_packages/?jString="+jString;
	}
	
	
	return false;	
}

function perform_home_collection_status(action, packageId,home_collection,statusIndex)
{
	

	var status_url = medtest_root_path+'manage_test_package/perform_operation_test_home_collection/';
	var redirect_url = medtest_root_path+'manage_test_package/list_test_packages/';

	var data = {packageId : packageId,home_collection:home_collection};

	
	if(action == "ChangeStatus")
	{
		$('#confirmHomeCollectionStatus').dialog({
		//autoOpen: true,
		modal: true,
		width: 600,
		buttons: {
				"Yes": function(){
					$.ajax
					({
						type	: 'post',
						url	: status_url,
						data	: data,
						async	: false,
						success : function(response)
						{
							if(response.length != 0){
								//alert(response);
								if (response != '') {
									//alert(alert_msg);
									window.location.href=redirect_url;
								}
								//$('#delete_response').html(response);
							}
							// this is for unblocking tat preloader 
							//hide_loading(); 
						},
						error:function(XMLHttpRequest, textStatus, errorThrown)
						{
							alert(textStatus+' '+errorThrown);
						}
					});
				}, 
				"No": function(){
					$("#home_coll_id_"+statusIndex).val(home_collection);
					$(this).dialog("close");
				} 
			}
		});
	}


	else
	{
		window.location = medtest_root_path+"manage_test_package/list_test_packages/?jString="+jString;
	}
	
	
	return false;	
}

function perform_delete(packageId)
{
	//var jString = get_json_string(action, categoryId, currentStatus,0);

	var status_url = medtest_root_path+'event_category/delete_event_category/';
	var redirect_url = medtest_root_path+'event_category/list_eventcategories/';

	var data = {categoryId : categoryId};

	
	if(categoryId != "")
	{
		$('#confirmDelete').dialog({
		//autoOpen: true,
		modal: true,
		width: 600,
		buttons: {
				"Yes": function(){
					$.ajax
					({
						type	: 'post',
						url		: status_url,
						data	: data,
						async	: false,
						success : function(response)
						{
							if(response.length != 0){
								//alert(response);
								if (response != '') {
									//alert(alert_msg);
									window.location.href=redirect_url;
								}
								//$('#delete_response').html(response);
							}
							// this is for unblocking tat preloader 
							//hide_loading(); 
						},
						error:function(XMLHttpRequest, textStatus, errorThrown)
						{
							alert(textStatus+' '+errorThrown);
						}
					});
				}, 
				"No": function(){
					//$("#status_id_"+statusIndex).val(currentStatus);
					$(this).dialog("close");
				} 
			}
		});
	}


	else
	{
		//window.location = eventnize_root_path+"event_category/list_eventcategories/?jString="+jString;
	}
	
	
	return false;	
}

</script>
