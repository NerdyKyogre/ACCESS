<?php
require_once('../src/helpers.php');
require_once('../src/parsedown/Parsedown.php');

$id = $_GET['id'] ?? null;
if(!$id)
    die("An ID must be passed in the query string when loading this page.");
$cursor = \accessCore\getPGConn();
$result = pg_query_params($cursor, "SELECT * FROM plushies.getPlushie($1)", [$id]) or die('Query failed: ' . pg_last_error());
$info = pg_fetch_object($result);
error_log(print_r($info, true));
pg_free_result($result);
pg_close($cursor);
?>

<html>
<head>
    <title><?= $info->name ? $info->name . ' - ' : '' ?> Creature View - ACCESS</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/dopetrope-theme/css/main.css" />
</head>
<body>
    <section id="main">
		<div class="container">
            <article class="box post">
                <section id="banner" style="background-color: transparent; padding: 0 0; margin: 0 0;">
                    <header class="container" style="display: flex; width: 100%; flex-direction: row; flex-wrap: wrap; justify-content: space-evenly; align-items: center; gap: 4em">
                        <img style="max-width: 100%; height: auto;" src="/images/<?= $info->imagepath ?: ''?>">
                        <div style="padding: 2em 0;">
                            <h2>Hi<?= $info->name ? ', I\'m ' . $info->name : ""?>!</h2>
                            <p><?= $info->pronouns ?: ''?></p>
                        </div>
                    </header>
                </section>
                <header class="major">
                    <h2>About Me</h2>
                </header>
                <p><strong>Species: </strong><?= $info->species ?: 'Unknown' ?></p>
                <p><strong>Colour(s): </strong><?= $info->colour ?: 'Unknown' ?></p>
                <p><strong>Height: </strong><?= $info->heightin ? $info->heightin . ' inches' : 'Unknown' ?></p>
                <?php
if($info->bio)
{ ?>
<?php
    $parsedown = new Parsedown();
    $parsedown->setSafeMode(true);
    echo $parsedown->text($info->bio);
} ?>
                <a href="/upload/?id=<?= $id ?>" class="button">Edit Friend</a>
                <a href="/" class="button alt">Back to Home</a>
            </article>
        </div>
    </section>
</body>
</html>
