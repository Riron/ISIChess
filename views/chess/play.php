<script>
	function validate(form){
		/*if(($('#startX').prop('selectedIndex')=='2' || $('#startX').prop('selectedIndex')=='7') && ($('#endX').prop('selectedIndex')=='1' || $('#endX').prop('selectedIndex')=='8') && $('.echiquier td img[data-pion]') && $('#casePromo').css('display')=='none'){
			$('#casePromo').css('display','block');
		}
		else{*/
			return true;
	}
</script>
<div class="row">
	<div class="span9">
		<?php
		echo $board;
		?>
	</div>
	<div class="span3">
		<div class="well">
			<h3>Déplacement</h3>
		<form id="deplacements" onsubmit="return validate(this);" action="<?php echo $this->generateUrl('chess', 'play', $id);?>" method="POST">
			<div class="control-group">
			    <label class="control-label" for="inputEmail">Depart :</label>
			    <div class="controls">
			    	<select id='startY' name="startY" class="moves">
						<option value="1">A</option>
						<option value="2">B</option>
						<option value="3">C</option>
						<option value="4">D</option>
						<option value="5">E</option>
						<option value="6">F</option>
						<option value="7">G</option>
						<option value="8">H</option>
					</select>
					<select id='startX' name="startX" class="moves">
						<option value="8">1</option>
						<option value="7">2</option>
						<option value="6">3</option>
						<option value="5">4</option>
						<option value="4">5</option>
						<option value="3">6</option>
						<option value="2">7</option>
						<option value="1">8</option>
					</select>
			    </div>
		  	</div>

		  	<div class="control-group">
			    <label class="control-label" for="inputEmail">Arrivée :</label>
			    <div class="controls">
					<select id="endY" name="endY" class="moves">
						<option value="1">A</option>
						<option value="2">B</option>
						<option value="3">C</option>
						<option value="4">D</option>
						<option value="5">E</option>
						<option value="6">F</option>
						<option value="7">G</option>
						<option value="8">H</option>
					</select>
					<select id="endX" name="endX" class="moves">
						<option value="8">1</option>
						<option value="7">2</option>
						<option value="6">3</option>
						<option value="5">4</option>
						<option value="4">5</option>
						<option value="3">6</option>
						<option value="2">7</option>
						<option value="1">8</option>
					</select>
			    </div>
		  	</div>
		  	<div style='display:none' id='casePromo' class="control-group">
			    <label class="control-label" for="inputEmail">Promotion :</label>
			    <div class="controls">
					<select id="promotion" name="promotion" class="moves">
						<option value="Reine">Reine</option>
						<option value="Fou">Fou</option>
						<option value="Tour">Tour</option>
						<option value="Cheval">Cheval</option>
					</select>
			    </div>
		  	</div>
			<div class="control-group">
			    <div class="controls">
			      <button type="submit" class="btn">Jouer !</button>
			    </div>
			</div>
		</form>
			<a href="<?php echo $this->generateUrl('chess', 'abandonner', array($id, $joueurActuel));?>" class="btn btn-danger"><i class="icon-remove-sign icon-white"></i> Abandonner</a>
			<p>Vous êtes le joueur <?php echo $joueurActuel; ?></p>
			<p>C'est au tour du joueur <?php echo $joueurQuiDoitJouer; ?></p>
		</div>
		<div class="well">
			<h3>Chat</h3>
			<?php
			    foreach ($messages as $k => $v) {
			    	echo '<strong>';
			    	echo ($this->session->readEntry('user')->id_utilisateur == $v->id_utilisateur) ? 'Moi' : 'Adversaire';
			    	echo ':</strong> <em>('. $v->dath_message.')</em><br>';
			    	echo $v->txt_message.'<br>';
			    }
			?>
			<form action="<?php echo $this->generateUrl('chess', 'play', $id);?>" method="POST">
			  <fieldset>
			    <legend>Votre message</legend>
			    <textarea rows="3" name="message" ></textarea>
			    <button type="submit" class="btn">Envoyer</button>
			  </fieldset>
			</form>
		</div>
	</div>
</div>
<script>
	$(document).ready(function (){
		var size = Math.floor(($('.container .span9').width()/8)/2) - 1;
		$('.echiquier td div').css({width: size+'px', height: size+'px'});
		function reinitialiserLesBackGround(){
			var maVar='';
			for(var i=1;i<=8;i++){
				for(var j=1;j<=8;j++){
					maVar=i+''+j;
					if((i+j)%2==0){
						$('#'+maVar).removeClass('positionActuelle positionsPossibles');
					}
					else{
						$('#'+maVar).removeClass('positionActuelle positionsPossibles');
					}
				}
			}
		}
		$('.echiquier td img[data-pos]').click(function(){
			reinitialiserLesBackGround();
			$(this).parent().parent().toggleClass("positionActuelle");
			var positionDepart=$(this).parent().parent().attr('id');
			console.log(positionDepart);
			$('#startX').prop('selectedIndex',8-positionDepart[0]);
			$('#startY').prop('selectedIndex',positionDepart[1]-1);
			var string=$(this).attr("data-pos");
			var tableau=string.split(' ');
			for(var i=0;i<tableau.length;i++){
				var maVar=tableau[i];
				console.log(maVar);
				$('#'+maVar).toggleClass("positionsPossibles");
				$('#'+maVar).click(function(){
					var uneVar=$(this).attr('id');
					$('#endX').prop('selectedIndex',8-uneVar[0]);
					$('#endY').prop('selectedIndex',uneVar[1]-1);
					$('#deplacements').submit();
				});
			}
		});

		setInterval(function() {
			var reload = $('.echiquier').attr('data-fen');
		    var url = '<?php echo $this->generateUrl('chess', 'update', $id);?>';
		    $.getJSON(url, function(data){
		        if((data['etat'] != reload && data['ended'] != 3) || data['ended'] == 5){
		        	$(location).attr('href',window.location);
		        }
		    });
		}, 3000);
	});
</script>