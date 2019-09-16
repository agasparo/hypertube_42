<?php

session_start();

require '../modules/bdd.php';

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
	if (isset($_POST['id'])) {
		$ids = explode('_', $_POST['id']);

		$ids[1] = intval($ids[1]) + 1;

		$ajj = 0;

		$chekc_note = $bdd->prepare("SELECT * FROM note WHERE id_user = ? AND id_film = ?");
		$chekc_note->execute(array($_SESSION['id'], $ids[0]));
		if ($chekc_note->rowCount() == 0) {
			$notation = $bdd->prepare('INSERT INTO note(id_user, id_film, note) VALUES (?, ?, ?)');
			$notation->execute(array($_SESSION['id'], $ids[0], $ids[1]));
		} else {
			$ajj = 1;
			$notation = $bdd->prepare('UPDATE note set note = ? WHERE id_user = ? AND id_film = ?');
			$notation->execute(array($ids[1], $_SESSION['id'], $ids[0]));
		}

		$recal = $bdd->prepare("SELECT * FROM note WHERE id_film = ?");
		$recal->execute(array($ids[0]));
		$note = 0;
		$divi = 0;

		while ($notes = $recal->fetch()) {
			$divi++;
			$note = $note + intval($notes['note']);
		}

		$get_an_note = $bdd->prepare("SELECT nb_vote, note FROM films WHERE id = ?");
		$get_an_note->execute([$ids[0]]);
		$an_note = $get_an_note->fetch();
		
		$note_an = intval($an_note['note']) * intval($an_note['nb_vote']);
		$divi = $divi + intval($an_note['nb_vote']);
		$note = $note + $note_an;

		if ($divi == 0)
			$divi = 1;
		$note_final = intval($note / $divi);

		$update_film = $bdd->prepare('UPDATE films set note = ?, nb_vote = ? WHERE id = ?');
		$update_film->execute(array($note_final, $divi, intval($ids[0] - $ajj)));

		$note = "";
		$a = 0;
		while ($a < $note_final) {
			$note.= '<a href="#" id="'.$ids[0].'_'.$a.'" class="note active_note">☆</a>';
			$a++;
		}
		while ($a < 10) {
			$note.= '<a href="#" id="'.$ids[0].'_'.$a.'" class="note not_active_note">☆</a>';
			$a++;
		}
		echo $note;
	} else {
		echo 'error';
	}
} else {
	echo "error";
}


?>