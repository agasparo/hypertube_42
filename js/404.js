const position_y_init = 48.99;
const perso = document.getElementById('perso');
const return_div = document.getElementById('txt_zone');
let gravity_var;
let up = 0;
let speed_obj = 100;
let speed_gravity = 40;
let start = 0;
let add_decorts_var;
let move_var;
let obj;
let obj_pass = 0;
let perso_skin;
let animate_speed = 400;
let score = 0;
let add = 10;
let h_score = 0;
let decord_1 = document.getElementById('1');
let decord_2 = document.getElementById('2');
let decors_inter;

decord_1.style.left = "0%";
decord_2.style.left = "100%";

function bouge() {

	let tronc;

	tronc = decord_1.style.left.split("%")
	decord_1.style.left = parseInt(tronc[0]) - 1 + "%";
	if (parseInt(tronc[0]) <= -98)
		decord_1.style.left = "100%";

	tronc = decord_2.style.left.split("%")
	decord_2.style.left = parseInt(tronc[0]) - 1 + "%";
	if (parseInt(tronc[0]) <= -98)
		decord_2.style.left = "100%";
}


$("#txt_zone").text("Appuyer sur [entrer] pour commencer");

document.onkeydown = function(e) {

	if (e.which == 32 && up == 0 && start == 1) {

		$("#jump").removeClass('jump_off');
		$("#jump").addClass('jump_on');
		setTimeout(function(){
			$("#jump").removeClass('jump_on');
			$("#jump").addClass('jump_off');
		}, 150);
		up = 1;
		gravity_var = setInterval( function() { gravity("-", position_y_init - 20); }, speed_gravity );
	}

	if (e.which == 13 && start == 0) {
		return_div.style.display = "none";
		add_decors();
		start = 1;
	}
}

function restart() {

	obj.remove();
	add = 10;
	score = 0;
	animate_speed = 400;
	obj_pass = 0;
	start = 0;
	speed_gravity = 40;
	speed_obj = 100;
	up = 0;
	$("#score").text("Score : 0");
	$("#txt_zone").text("Appuyer sur [entrer] pour commencer");
}

function animate_player() {

	let costume = 'marche1';
	match = perso.style.backgroundImage.match(costume);
	if (match)
		perso.style.backgroundImage = "url('http://192.168.99.100.xip.io:41062/www/hypertube/photos/404/marche2.png')";
	else
		perso.style.backgroundImage = 'url("http://192.168.99.100.xip.io:41062/www/hypertube/photos/404/marche1.png")';
}

function colison() {

	let pos_obs = $("#obstacle").position();

	let pos_joueur = $("#perso").position();

	let pos_objs_max_left = pos_obs.left + ($("#obstacle").width() / 2);
	let pos_obs_min_left = pos_obs.left - $("#perso").width();

	let pos_objs_max_top = pos_obs.top + ($("#obstacle").height() / 2);
	let pos_obs_min_top = pos_obs.top - $("#perso").height();

	if (pos_joueur.left >= pos_obs_min_left && pos_joueur.left <= pos_objs_max_left) {

		if (pos_joueur.top >= pos_obs_min_top && pos_joueur.top <= pos_objs_max_top)
			return (1);
	}

	return (0);
}

function gravity(sign, pos) {

	let tronc = perso.style.top.split("%");
	if (pos <= parseInt(tronc[0]) && sign == "+") {
		clearInterval(gravity_var);
		up = 0;
	} else if (pos >= parseInt(tronc[0]) && sign == "-") {
		clearInterval(gravity_var);
		gravity_var = setInterval( function() { gravity("+", position_y_init); }, speed_gravity );
	} else {
		if (sign == "+")
			perso.style.top = parseInt(tronc[0]) + 1 + "%";
		else
			perso.style.top = parseInt(tronc[0]) - 1 + "%";
	}
}

function add_decors() {

	obj = generate_decors();
	if (obj_pass % 6 == 0) {
		if (speed_obj >= 10) {

			clearInterval(decors_inter);
			decors_inter = setInterval(bouge, speed_obj);

			speed_obj = speed_obj / 1.6;
			speed_gravity = parseInt(speed_gravity / 1.4);
			animate_speed = animate_speed / 1.4;

			clearInterval(perso_skin);
			perso_skin = setInterval(animate_player, animate_speed);
			add = add + 20;

		} else if (speed_obj >= 8) {

			speed_obj = speed_obj / 1.2;
			speed_gravity = parseInt(speed_gravity / 1.1);
			add = add + 20;
		}
	}
	move_var = setInterval(move_decors, speed_obj);
}

function move_decors() {

	let tronc = obj.style.left.split("%");
	obj.style.left = parseInt(tronc[0]) - 1 + "%";
	if (tronc[0] <= 5) {
		clearInterval(move_var);
		obj_pass++;
		score = score + add;
		$("#score").text("Score : "+score);
		obj.remove();
		add_decors();
	}

	if (colison()) {
		clearInterval(move_var);
		clearInterval(perso_skin);
		clearInterval(decors_inter);
		$("#txt_zone").text("Perdu score : "+score+" le jeu se reset dans 3 secondes ...");
		if (score > h_score) {
			$("#score_h").text("High score : "+score);
			h_score = score;
		}
		return_div.style.display = "block";
		setTimeout(function(){ restart(); }, 3000);
	}
}

function generate_decors() {

	let div = document.createElement("div");
	div.style.position = "fixed";
	if (Math.random() >= 0.5) {
		div.style.bottom = "45%";
		div.style.backgroundImage = 'url("http://192.168.99.100.xip.io:41062/www/hypertube/photos/404/voiture.png")';
	}
	else {
		div.style.bottom = "55%";
		div.style.backgroundImage = 'url("http://192.168.99.100.xip.io:41062/www/hypertube/photos/404/helico.png")';
	}
	div.style.left = "80%";
	div.style.height = "43px";
	div.style.width = "47px";
	div.style.zIndex = 3;
	div.style.backgroundSize = "cover";
	div.id = "obstacle";
	document.body.appendChild(div);
	return (div);
}