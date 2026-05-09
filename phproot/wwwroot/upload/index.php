<?php
require_once('../../src/helpers.php');

$id = $_GET['id'] ?? null;
if($id)
{
    $cursor = \accessCore\getPGConn();
    $result = pg_query_params($cursor, "SELECT * FROM plushies.getPlushie($1)", [$id]) or die('Query failed: ' . pg_last_error());
    $info = pg_fetch_object($result);
    error_log(print_r($info, true));
    pg_free_result($result);
    pg_close($cursor);
}
else
    $info = null;
?>

<html>
<head>
    <title>Friend Update Node - ACCESS</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/dopetrope-theme/css/main.css" />
</head>
<body>
    <section id="main">
		<div class="container">
            <article class="box post">
                <header>
                    <h2>Welcome to the Friend Update Node!</h2>
                    <p>Have a lot of FUN :-]</p>
                </header>
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="id" style="display: none;" value="<?= $id ?>">
                    <label for="name">Name: </label>
                    <input type="text" name="name" id="name" value="<?= $info ? $info->name : '' ?>" required>
                    <label for="species">Species: </label>
                    <input type="text" name="species" id="species" value="<?= $info ? $info->species : '' ?>">
                    <label for="colour">Colours: </label>
                    <input type="text" name="colour" id="colour" value="<?= $info ? $info->colour : '' ?>" placeholder="Comma-separated, e.g. 'red, pink, white'">
                    <label for="pronouns">Pronouns: </label>
                    <input type="text" name="pronouns" id="pronouns" value="<?= $info ? $info->pronouns : '' ?>">
                    <label for="height">Height (inches): </label>
                    <input type="text" name="height" id="height" value="<?= $info ? $info->heightin : '' ?>">
                    <br>
                    <label for="image" class="button alt">Select an image</label>
                    <input type="file" accept="image/*" name="imageUpload" id="image" style="display: none;">
                    <label for="bio">Bio: </label>
                    <textarea name="bio" id="bio" rows="8" cols="40" placeholder="Supports GitHub-flavoured markdown"><?= $info ? $info->bio : '' ?></textarea>
                    <br>
                    <button type="submit" name="submit" class="large"><?= $id ? "Update your friend!" : "Add your friend!" ?></button>
                </form>
            </article>
        </div>
    </section>
</body>
<script src="/js/upload.js"></script>
</html>
