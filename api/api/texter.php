<?php
if(isset($_GET["to"])){
	?>
		<div class="row huge">
			<h2>Create New Insight</h2>
			<h4 class="to">To: <strong><?php echo $_GET["to"] ?></strong></h4>
			<textarea name="insight" class="form-control" rows="5" maxlength="500" placeholder="Enter your Insight Here"></textarea>
			<div class="countdisplay"><strong><span class="counter">0</span>/<span>500</span></strong></div>
			<div class="errorinsight"></div>
			<div class="separator"></div>
				<div class="well">
					<h3><strong>Tag as:</strong></h3>
					<div class="btn-group btn-group-lg" data-toggle="buttons">
						<label class="btn btn-primary">
							<input type="radio" name="options" id="continue" value="Continue" autocomplete="off"> Continue
						</label>
						<label class="btn btn-primary">
							<input type="radio" name="options" id="consider" value="Consider" autocomplete="off"> Consider
						</label>
						<label class="btn btn-primary">
							<input type="radio" name="options" id="continue_consider" value="Continue/Consider" autocomplete="off"> Continue/Consider
						</label>
					</div>
				</div>
			<div class="errortags"></div>
			<div class="separator"></div>
			<div class="input-group input-group-lg">
				<span class="input-group-addon">I am: </span>
				<select class="form-control input-lg" name="emp">
					<option disabled="disabled" selected="selected">Choose from this list!</option>
					<?php
						$people = scandir('../img');
						foreach($people as $pict){
							if(!in_array($pict, array(".", "..", "GE_white_monogram.png", "pict.png", $_GET["to"].".jpg"))){
								echo '<option value="'.substr($pict,0,-4).'">'.substr($pict,0,-4).'</option>';
							}
						}
					?>
				</select>
			</div>
			<div class="erroremp"></div>
			<br>
			<div class="text-right">
				<button class="btn btn-default btn-lg" onClick="javascript:location.reload();">Cancel</button>
				<button class="btn btn-info btn-lg" id="submiter">Submit</button>
			</div>
			<div class="text-center">
				<small>Please fill in the form and click the submit button to share your Insight! The site is not linked directly to PD@GE, but you will recieve these insights in a summarized e-mail!<br>Thank You for your visit! Hope you have a nice and productive time!</small>
			</div>
		</div>
		<script>
			$(function(){
				$('textarea[name="insight"]').on('keyup', function(){
					$('.counter').text($(this).val().length);
				});
				$('#submiter').on('click', function(){
					if($('textarea[name="insight"]').val().length == 0){
						$('.errorinsight').html("<span class='label label-warning' style='margin: 20px;'>Attention!</span><p style='text-indent: 5%;'>Why submitting an empty Insight? Please write your feedback, fill in the forms other fields and push the <strong>Submit</strong> button!</p>").addClass('alert alert-warning');
						$('textarea[name="insight"]').focus();
						setTimeout(function(){$('.errorinsight').html('').removeClass('alert alert-warning');}, 15000);
						return false;
					} else if($('input[name="options"]:checked').val() == undefined){
						$('.errortags').html("<span class='label label-warning' style='margin: 20px;'>Attention!</span><p style='text-indent: 5%;'>Please tag your insight before submit</p>").addClass('alert alert-warning');
						setTimeout(function(){$('.errortags').html('').removeClass('alert alert-warning');}, 5000);
						return false;
					} else if($('select[name="emp"]').val() == null){
						$('.erroremp').html("<span class='label label-warning' style='margin: 20px;'>Attention!</span><p style='text-indent: 5%;'>Please select your Name from the list!</p><p style='text-indent:5%'>This page is not linked to any log-in or identification process, You have to give your name in order to create the Insight with your name!</p>").addClass('alert alert-warning');
						$('select[name="emp"]').focus();
						setTimeout(function(){$('.erroremp').html('').removeClass('alert alert-warning');}, 15000);
						return false;
					} else {
						var to = $('.to strong').text();
						var ins = $('textarea[name="insight"]').val();
						var tag = $('input[name="options"]:checked').val();
						var emp = $('select[name="emp"]').val();
						$.ajax({
							method: "POST",
							url: "api/saveInsight.php",
							data: {to: to, ins: ins, tag: tag, emp:emp}
						}).done(function(data){
							location.reload();
						});
					}
				});
			});
		</script>
	<?php
}
?>