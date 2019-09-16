window.onload = function() {

	check_input();
	infinite_scroll();
	postit();

	var req_ids = "";
	var req_type = "";
	var req_search = "";

	function postit() {
		if (document.getElementById('post_form_co')) {
			document.getElementById('post_form_co').onclick = function env(e) {
				e.preventDefault();
				post_form('form_co', 'modules/connexion_ins.php', function (data) {
					if (data['success'] === 0)
						document.getElementById('error_form').innerText = data['responce'];
					if (data['success'] == 2)
						document.getElementById('form_co').innerHTML = data['responce'];
					if (data['success'] == 1)
						location.reload();
					document.getElementById('post_form_co').onclick = function(e) {env(e)};
					img();
					check_input();
				});
			}
		}
	}

	if (document.getElementById('mdp_oublier')) {
		document.getElementById('mdp_oublier').onclick = function() {
			$.post('modules/pass.php', function (data) {
				document.getElementById('form_co').innerHTML = data;
				postit();
			});
		}
	}

	function check_input() {
		$("form#form_co :input").each(function() {
			this.removeEventListener('keyup', check_input_val);
			this.addEventListener('keyup', check_input_val);
		});
	}

	function check_input_val(e) {
		if (e.target.getAttribute('minlength') > e.target.value.length) {
			change_input(e.target, 0);
		} else {
			if (e.target.type == "password") {
				if (check_mdp(e.target.value))
					change_input(e.target, 1);
				else
					change_input(e.target, 0);
			} else
				change_input(e.target, 1);
		}
	}

	function check_mdp(value) {
		if (value.replace(/[0-9]/g, "").length > 0) {
			if (value.replace(/[a-z]/g, "").length > 0) {
				if (value.match(/^[a-z0-9]+$/i))
					return (1);
			}
		}
		return (0);
	}

	function img() {
		if (document.getElementById('click_img')) {
			document.getElementById('click_img').onclick = function() {
				document.getElementById('file_add').click();
			}

			document.getElementById('file_add').onchange = function(e) {
				if (document.getElementById('file_add').files[0]) {
					let reader = new FileReader();

					reader.onload = function(e) {
						loadMime(document.getElementById('file_add').files[0], function(res) {
							if (res != "unknown") {
								$.post('modules/insert_img.php', {link:reader.result}, function(data){
									data = JSON.parse(data);
									if (data['success'] == 1)
										document.getElementById('click_img').innerHTML = data['infos'];
								});
							} else {
								document.getElementById('error_form').innerText = "Ficher non supporte";
							}
						});
					}

					reader.readAsDataURL(document.getElementById('file_add').files[0]);	
				}
			}
		}
	}

	function infinite_scroll() {
		var ias = jQuery.ias({
		  container:  '#all_movies',
		  item:       '.gal_films',
		  pagination: '#pagination',
		  next:       '.next'
		});

		ias.extension(new IASSpinnerExtension({
	    	src: 'photos/charge.gif',
		}));
		ias.on('next', function(event) {
		    manage_note();
		});
		ias.on('noneLeft', function(event) {
		    manage_note();
		});
	}

	function manage_note() {
		let note = document.querySelectorAll('.note');
		remove_event(note, new_note);
		add_event(note, new_note);
	}

	function new_note(e) {
		e.preventDefault();
		$.post('modules/add_note.php', {id:e.target.id}, function(data) {
			let d = e.target.id.split('_');
			if (data != 'error')
				document.getElementById(d[0]+'_note_re').innerHTML = data;
			let note = document.querySelectorAll('.note');
			remove_event(note, new_note);
			add_event(note, new_note);
		});
	}

	let trie = document.querySelectorAll('.trie');
	let tab_trie = add_trie(trie);

	function add_trie(trie) {
		let i = 0;
		let tab_trie = new Array();
		while (i < trie.length) {
			trie[i].addEventListener('mouseover', mouse_on_trie);
			trie[i].addEventListener('click', go_trie);
			tab_trie[trie[i].id] = 0;
			i++;
		}
		return tab_trie;
	}

	function mouse_on_trie(e) {
		if (e.target.innerText[0] != '▲' && e.target.innerText[0] != '▼') {
			e.target.innerText = '▲ '+e.target.innerText;
			e.target.addEventListener('mouseout', mouse_on_trie_back);
		} else if (e.target.innerText[0] == '▲') {
			e.target.innerText = e.target.innerText.replace('▲', '▼');
			e.target.addEventListener('mouseout', mouse_on_trie_back);
		} else if (e.target.innerText[0] == '▼') {
			e.target.innerText = e.target.innerText.replace('▼', '▲');
			e.target.addEventListener('mouseout', mouse_on_trie_back);
		}
	}

	function mouse_on_trie_back(e) {
		if (tab_trie[e.target.id] == 0)
			e.target.innerText = e.target.innerText.replace('▲', '');
		if (tab_trie[e.target.id] == 1)
			e.target.innerText = e.target.innerText.replace('▼', '▲');
	}

	function go_trie(e) {
		let trie = document.querySelectorAll('.trie');
		let i = 0;
		while (i < trie.length) {
			if (trie[i].id != e.target.id) {
				trie[i].innerText = trie[i].innerText.replace('▲', '');
				trie[i].innerText = trie[i].innerText.replace('▼', '');
			}
			i++;
		}
		$.post('modules/set_new_req_gallerie.php', {id:e.target.id, type:tab_trie[e.target.id], serach_mov:req_search}, function (data) {
			req_ids = e.target.id;
			req_type = tab_trie[e.target.id];
			document.getElementById('all_movies').innerHTML = "";
			document.getElementById('all_movies').innerHTML = data;
			if (tab_trie[e.target.id] == 0)
				tab_trie[e.target.id] = 1;
			else
				tab_trie[e.target.id] = 0;
			infinite_scroll();
		});
	}

	document.getElementById('search_movies').onclick = function(e) {
		e.preventDefault();
		let val = document.getElementById('input_search').value;
		$.post('modules/set_new_req_gallerie.php', {id:req_ids, type:req_type, serach_mov:val}, function (data) {
			req_search = val;
			document.getElementById('all_movies').innerHTML = "";
			if (isJson(data))
				document.getElementById('all_movies').innerHTML = JSON.parse(data);
			else
				document.getElementById('all_movies').innerHTML = data;
			infinite_scroll();
		});
	}

	function isJson(str) {
	    try {
	        JSON.parse(str);
	    } catch (e) {
	        return false;
	    }
	    return true;
	}

	document.getElementById('btnnav').onclick = function(){
		$('#navbarColor01').slideToggle();
	}	
}