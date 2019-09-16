<style type="text/css">
	#fond {
	    position: absolute;
	    top:0;
	    z-index: 1;
	    left: 0;
	    background-color: black;
	    width: 100%;
	    height: 100%;
	    text-align: center;
	}
	#fond img {
		height: 100%;
		width: auto;
		max-width: 100%;
	}
	#play_vid {
		position: absolute;
		z-index: 2;
		top: 40%;
		left: 45%;
		width: 12vw;
		height: 15vh;
		cursor: pointer;
	}
</style>
<div id="fond">
	<img src="<?= $film_img_cover; ?>">
</div>
<img src="../photos/play.png" class="play_vid" id="play_vid">