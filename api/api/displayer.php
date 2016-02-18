<?php
if(isset($_GET["to"])){
	?>
		<div class="row huge">
			<button class="btn btn-default pull-right" onClick="javascript:location.reload();">HomePage</button>
			<h2>View all Insights</h2>
			<h4 class="to">To: <strong><?php echo $_GET["to"] ?></strong></h4>
		<?php
			$dataF = file_get_contents("data.json");
			$data = json_decode($dataF);
			foreach($data as $i){
				if($i->name == $_GET["to"]){
					foreach($i->insights as $ins){
						$label = $ins->tag=="Consider"?'<label class="label label-info label-lg">Consider</label>':$ins->tag=="Continue"?'<label class="label label-success label-lg">Continue</label>':'<label class="label label-default label-lg">Continue/Consider</label>';
						echo '
			<div class="ins">
				<div class="well">
						<p>'.str_replace("\n", "<br>",$ins->insight).'</p>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<p class="text-left">'.$label.'</p>
							</div>
							<div class="col-xs-6">
								<p class="text-right"><strong>'.$ins->from.'</strong> &nbsp; <small>'.$ins->date.'</small>
							</div>
						</div>
					<div class="callout">
						<img src="img/'.$ins->from.'.jpg">
					</div>
				</div>
			</div>
						';
					}
				}
			}
		?>
		</div>
	<?php
}
?>