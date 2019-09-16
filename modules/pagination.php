<?php

if (!isset($fims_par_page) || !isset($film_total))
	exit(0);

$page_total = ceil($film_total / $fims_par_page) + 1;

for ($i=1; $i < $page_total; $i++) {
	if ($i == $page_courrante)
		echo $i;
	else if ($i == $page_courrante + 1)
		echo '<a href="'.$i.'" class="next">'.$i.'</a>';
	else
		echo '<a href="'.$i.'">'.$i.'</a>';
}

?>