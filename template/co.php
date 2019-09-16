<div class="cache"></div>
<div id="form_ins_co">
	<p class="lead"><?= $form_type; ?></p>
	<hr>
	<form class="form_co" id="form_co">
		<font color="red" id="error_form"></font>
		<fieldset>
			<div class="form-group">
		      <label for="exampleInputEmail1"><?= $util_name; ?></label>
		      <input type="text" class="form-control" id="exampleInputEmail1" placeholder="<?= $util_name; ?>" minlength=<?= $input_util; ?>>
		    </div>
		    <br><br>
		    <div class="form-group">
		      <label for="exampleInputPassword1"><?= $mdp; ?></label>
		      <input type="password" class="form-control" id="exampleInputPassword1" autocomplete="" placeholder="<?= $mdp; ?>" minlength=<?= $input_mdp; ?>>
		    </div>
		    <p id="mdp_oublier"><?= $mdp_oubli; ?></p>
		    <br>
		    <div style="margin-left: -20%; width: 65vw;">
		    	<font style="float: left; width: 8vw; height: 3vw  "><?php require 'matcha_connexion/api.php'; ?></font>
		    	<font style="float: left; width: 11vw; margin-left: 5%; top: 0.1vw"><?php require 'google_connexion/index.php'; ?></font>
		    	<font style="float: left; width: 9vw; margin-left: 5%;"><?php require 'insta_connexion/index.php'; ?></font>
		    	<font style="float: left; width: 8vw; margin-left: 5%;"><?php require '42_connexion/index.php'; ?></font>
		    </div>
		    <br><br><br>
		    <button type="submit" class="btn btn-primary" id="post_form_co" value="co_form"><?= $inscription.' / '.$connection; ?></button>
		</fieldset>
	</form>
</div>