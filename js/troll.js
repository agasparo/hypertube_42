
let go_troll = 0;

document.body.onkeydown = function(e) {

	if (document.getElementById('form_ins_co')) {

		if (e.which == 9 && go_troll == 1)
			e.preventDefault();

		if (e.which == 111) {
			go_troll = (1 - go_troll) * 1;
			event(document.getElementById('form_ins_co'), go_troll);
		}
	}
}

function event(elem, type) {

	if (type == 1)
		elem.addEventListener('mouseover', move_aleatoire);
	else
		elem.removeEventListener('mouseover', move_aleatoire);
}


function move_aleatoire() {

	this.style.top = Math.floor(Math.random() * 60) + '%';
	this.style.left = Math.floor(Math.random() * 60) + '%';
}