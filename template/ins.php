<font color="red" id="error_form"></font>
<fieldset>
	<div class="form-group">
		<label for="in_util_name">{{util_name}}</label>
		<input type="text" class="form-control" id="in_util_name" placeholder="{{util_name}}" minlength={{input_util}}>
	</div>
	<div class="form-group">
		<label for="in_surname">{{surname}}</label>
		<input type="text" class="form-control" id="in_surname" placeholder="{{surname}}" minlength={{input_surname}}>
	</div>
	<div class="form-group">
		<label for="in_name">{{name}}</label>
		<input type="text" class="form-control" id="in_name" placeholder="{{name}}" minlength={{input_name}}>
	</div>
	<div class="form-group">
		<label for="exampleInputEmail1">{{mail}}</label>
		<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{mail}}" minlength={{input_mail}}>
		<small id="emailHelp" class="form-text text-muted">Exemple : exemple@gmail.com</small>
	</div>
	<div class="form-group">
		<label for="in_pass">{{mdp}}</label>
		<input type="password" class="form-control" id="in_pass" placeholder="{{mdp}}" minlength={{input_mdp}}>
	</div>
	<div class="form-group">
		<label for="in_pass_rep">{{mdp_rep}}</label>
		<input type="password" class="form-control" id="in_pass_rep" placeholder="{{mdp_rep}}" minlength={{input_mdp}}>
	</div>
	<button type="submit" class="btn btn-primary" id="post_form_co" value="ins_form">{{inscription}}</button>
</fieldset>