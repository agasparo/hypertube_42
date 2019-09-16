document.getElementById('stream').onload = function () {

	if (document.getElementById('stream').contentWindow.document.getElementById('play_vid')) {
		document.getElementById('stream').contentWindow.document.getElementById('play_vid').onclick = function () {
			document.getElementById('stream').src = "../modules/stream.php?play=1";
		}
	}

	if (document.getElementById('stream').contentWindow.document.body.querySelector('video')) {

		let video = document.getElementById('stream').contentWindow.document.body.querySelector('video');
		video.currentTime = videoTime;
		
		document.getElementById('stream').contentWindow.document.body.querySelector('video').style.minWidth = '100%';
		document.getElementById('stream').contentWindow.document.body.querySelector('video').style.minHeight = '100%';

		$.post("../modules/get_sub.php", function(data) {
			data = JSON.parse(data);
			if (data.count > 0) {
				let video = document.getElementById('stream').contentWindow.document.body.querySelector('video');
				let i = 0;
				while (i < data.count) {
					let source = document.createElement("track");
					source.kind = "subtitles";
					source.srclang = data.lang[i];
					source.src = data.path[i];
					video.appendChild(source);
					i++;
				}
			}
		});
	}
}

if (document.getElementById('post_pub_1')) {

	document.getElementById('post_pub_1').onclick = function () {

		let tab_rep = [];

		let msg_user = document.getElementById('rep_pub_1').value;

		let separator = "<div class='clear'></div>";


		tab_rep['oui'] = ["AH moi aussi :)", "Tu vie aussi a Paris ?"];

		tab_rep['yes'] = ["You live in Paris too ?", "OMG Me too :)"];

		tab_rep['non'] = ["Tu preferes les macs ... :(", "Oh non c'est pas possible"];

		tab_rep['no'] = ["You prefere macs ... :(", "Oh no it's no possible"];

		tab_rep['questions'] = ["You like macs ?", "You like Paris ?"];

		let msg = "<div class='left_message_pub'>"+msg_user+"</div>";
		document.getElementById('rep_pub_1').value = "";

		let reps = Object.keys(tab_rep);
		let i = 0;
		let rep = 0;
		let rand = Math.floor(Math.random()*(1-0+1));
		let msg_bot;

		let a_say = document.querySelectorAll('.right_message_pub');

		while (i < reps.length) {

			if (msg_user.match(reps[i])) {

				rep = 1;
				if (a_say[a_say.length - 1].innerText == tab_rep[reps[i]][rand])
					msg_bot = tab_rep[reps[i]][(1 - rand) * 1];
				else
					msg_bot = tab_rep[reps[i]][rand];
				break;
			}
			i++;
		}

		if (rep == 0) {

			if (a_say[a_say.length - 1].innerText == tab_rep['questions'][rand])
				msg_bot = tab_rep['questions'][(1 - rand) * 1];
			else
				msg_bot = tab_rep['questions'][rand];
		}

		let msg1 = "<div class='right_message_pub'>"+msg_bot+"</div>";

		$('#pub_messages').append(msg+separator+msg1+separator);
		let x = document.getElementById('pub_messages');
		x.scrollTop = x.scrollHeight;

	}

}

if (document.getElementById('drop_pub_1')) {

	document.getElementById('drop_pub_1').onclick = function () {

		document.getElementById('pub_1').remove();
	}	
}

document.getElementById('post_comm').onclick = function () {
	$.post('../modules/add_comm.php', {mess:document.getElementById('post_comm_txt').value}, function (data) {
		$('#all_comment').load('../modules/get_comment.php');
		document.getElementById('post_comm_txt').value = "";
	});
}

document.getElementById('btnnav').onclick = function(){
	$('#navbarColor01').slideToggle();
}