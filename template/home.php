<html>
<head>
	<meta charset="utf-8">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script type="text/javascript" src="js/infinite_scroll.min.js"></script>
  <link rel="stylesheet" type="text/scss" href="<?= $link_scss; ?>">
  <link rel="stylesheet" type="text/scss" href="<?= $link_scss_1; ?>">
  <link rel="stylesheet" type="text/css" href="<?= $link_css; ?>">
  <link rel="stylesheet" type="text/css" href="<?= $link_css_1; ?>">
  <link rel="icon" type="image/png" href="<?= $link_fav; ?>" />
  <title><?= $title_home; ?></title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="."><?= $title_home; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation" id="btnnav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href=".">Home <span class="sr-only">(current)</span></a>
        </li>
        <?php
        if (isset($_SESSION['id']) && !empty($_SESSION['id']))
        {
          ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= 'user/'.$_SESSION['id']; ?>"><?= $profil; ?></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="modules/deco.php"><?= $deco; ?></a>
          </li>
          <?php
        }
        ?>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" placeholder="<?= $search_it_f; ?>" id="input_search">
      <button class="btn btn-secondary my-2 my-sm-0" type="submit" id="search_movies"><?= $search_it; ?></button>
    </form>
  </div>
</nav>
<?php require 'template/trie.php'; ?>
<div id="all_movies">
  <?php
  require 'modules/galerie.php';
  ?>
</div>
<?php
if (!isset($_SESSION['id']) || empty($_SESSION['id']))
  require 'template/co.php';
?>
<div id="pagination">
 <?php require 'modules/pagination.php'; ?>
</div>
</body>
<script type="text/javascript">
  window.onbeforeunload = function(e) {
    $.post('modules/modules_reset.php');
  }
</script>
<script type="text/javascript" src="<?= $link_js_home; ?>"></script>
<script type="text/javascript" src="<?= $link_js_lib; ?>"></script>
<script type="text/javascript" src="<?= $link_js_troll; ?>"></script>
</html>
