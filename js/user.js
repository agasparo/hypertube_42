window.onload = function() {

	document.getElementById("Select_lang").onchange = function() {

		let e = document.getElementById("Select_lang");
		let res = e.options[e.selectedIndex].value;
		$.post("../modules/set_lang.php", {lang:res}, function(data) {

			location.reload();
		});
	}

	document.getElementById('img_change').onclick = function() {

		if (document.getElementById('get_img_in'))
			document.getElementById('get_img_in').remove();
		let get_img = document.createElement('input');
		get_img.type = 'file';
		get_img.id = 'get_img_in';
		document.body.appendChild(get_img);
		get_img.click();

		document.getElementById('get_img_in').onchange = function () {

			if (document.getElementById('get_img_in').files[0]) {
				let reader = new FileReader();

				reader.onload = function(e) {

					loadMime(document.getElementById('get_img_in').files[0], function(res) {

						if (res != "unknown") {

							$.post('../modules/insert_img.php', {link:reader.result}, function(data){
								data = JSON.parse(data);
								if (data['success'] == 1)
									location.reload();
							});
						} else {

							alert("Ficher non supporte");
							document.getElementById('get_img_in').remove();
						}
					});
				}

				reader.readAsDataURL(document.getElementById('get_img_in').files[0]);	
			}
		}


	}
	document.getElementById('btnnav').onclick = function(){
		$('#navbarColor01').slideToggle();
	}	
}