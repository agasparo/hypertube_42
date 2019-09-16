function post_form(id_form, post_file, callback) {
	let tab_val = [];

	$("form#"+id_form+" :input").each(function() {
		if (this.value == "")
			change_input(this, 0);
		tab_val.push(this.value);
	});

	$.post(post_file, {tab_val}, function(data) {
		callback(JSON.parse(data));
	});
}

function change_input(input, val) {
	if (val == 1) {
		input.classList.remove('is-invalid');
		input.classList.add('is-valid');
	} else {
		input.classList.remove('is-valid');
		input.classList.add('is-invalid');
	}
}

function loadMime(file, callback) {
	var mimes = [
	{
		mime: 'image/jpeg',
		pattern: [0xFF, 0xD8, 0xFF],
		mask: [0xFF, 0xFF, 0xFF],
	},
	{
		mime: 'image/png',
		pattern: [0x89, 0x50, 0x4E, 0x47],
		mask: [0xFF, 0xFF, 0xFF, 0xFF],
	}

	];

	function check(bytes, mime) {
		for (var i = 0, l = mime.mask.length; i < l; ++i) {
			if ((bytes[i] & mime.mask[i]) - mime.pattern[i] !== 0) {
				return false;
			}
		}
		return true;
	}

	var blob = file.slice(0, 4);

	var reader = new FileReader();
	reader.onloadend = function(e) {
		if (e.target.readyState === FileReader.DONE) {
			var bytes = new Uint8Array(e.target.result);

			for (var i=0, l = mimes.length; i<l; ++i) {
				if (check(bytes, mimes[i])) return callback(mimes[i].mime);
			}

			return callback("unknown");
		}
	};
	reader.readAsArrayBuffer(blob);
}

function add_event(obj, func) {
	let i = 0;
	while (i < obj.length) {
		obj[i].addEventListener('click', func);
		i++;
	}
}

function remove_event(obj, func) {
	let i = 0;
	while (i < obj.length) {
		obj[i].removeEventListener('click', func);
		i++;
	}
}