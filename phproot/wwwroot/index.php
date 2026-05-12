<?php
require_once('../src/helpers.php');

$cursor = \accessCore\getPGConn();
$result = pg_query($cursor, "SELECT * FROM plushies.getPlushie()") or die('Query failed: ' . pg_last_error());
$entries = pg_fetch_all($result);
pg_free_result($result);
$result = pg_query($cursor, "SELECT * FROM plushies.getFilterValues()") or die('Query failed: ' . pg_last_error());
$filterValues = json_decode(pg_fetch_result($result, 0, 0), true);
pg_free_result($result);
pg_close($cursor);

define('FILTER_ATTRS', array_keys($filterValues));
?>

<html>
<head>
    <title>Home - ACCESS</title>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="/dopetrope-theme/css/main.css" />
</head>
<body class="left-sidebar is-preload">
    <div id="page-wrapper">
        <section id="header">
            <h1>ACCESS</h1>
            <p style="margin-left: 10%; margin-right: 10%">Adorable Creature Census, Enumeration, and Search System</p>
            <a href="/upload/" class="button">Add a Friend</a>
        </section>
        <section id="main">
            <div class="container">
                <div class="row">
                    <div class="col-4 col-12-medium" id="nav">
                        <section class="box">
                            <header class="major">
                                <h2><a>Filter and Search</a></h2>
                            </header>
                            <input type="text" class="sys-search" placeholder="Search for a friend...">
                            <br>
                            <div class="sys-filter-sliders" data-attribute="heightin">
                                <h3 style="margin-bottom: 10px;">Height</h3>
                                <div style="display: flex; align-items: center">
                                    <label for="height-min">Min </label>
                                    <input
                                        type="range"
                                        min="<?= $filterValues['height']['min'] ?>"
                                        max="<?= $filterValues['height']['max'] ?>"
                                        value="<?= $filterValues['height']['min'] ?>"
                                        step="1"
                                        style="appearance: revert; accent-color: #d52349; margin: 0 10px;"
                                        id="height-min"
                                        name="height-min"
                                        value="height-min"
                                        data-range-side="min"
                                        oninput="this.nextElementSibling.value = this.value + '&quot;'">
                                    <output><?= $filterValues['height']['min'] ?>&quot;</output>
                                </div>
                                <div style="display: flex; align-items: center">
                                    <label for="height-max">Max</label>
                                    <input
                                        type="range"
                                        min="<?= $filterValues['height']['min'] ?>"
                                        max="<?= $filterValues['height']['max'] ?>"
                                        value="<?= $filterValues['height']['max'] ?>"
                                        step="1"
                                        style="appearance: revert; accent-color: #d52349; margin: 0 10px;"
                                        id="height-max"
                                        name="height-max"
                                        value="height-max"
                                        data-range-side="max"
                                        oninput="this.nextElementSibling.value = this.value + '&quot;'">
                                    <output><?= $filterValues['height']['max'] ?>&quot;</output>
                                </div>
                            </div>
                            <br>
<?php
foreach(FILTER_ATTRS as $attr)
{
    if($attr != 'height')
    { ?>
                            <div class="sys-filter-checkboxes" data-attribute="<?= $attr ?>">
                                <h3 style="margin-bottom: 10px;"><?= ucfirst($attr) ?></h3>
<?php
        sort($filterValues[$attr]);
        foreach($filterValues[$attr] as $value)
        { ?>
                                <input type="checkbox" class="sys-filter-checkbox" style="appearance: revert; accent-color: #d52349;" id="<?= $value ?>" name="<?= $value ?>" value="<?= $value ?>">
                                <label for="<?= $value ?>"><?= $value ?></label>
<?php
        } ?>
                            </div>
                            <br>
<?php
    }
} ?>
                        </section>
                    </div>
                    <div class="col-8 col-12-medium imp-medium container">
                        <div class="row">
<?php
foreach($entries as $card)
{ ?>
                            <div class="col-4 col-6-medium col-12-small sys-creature-card" id="sys-card-<?= $card['id'] ?>">
                                <section class="box" style="height: 100%;">
                                    <a href="/creature.php/?id=<?= $card['id'] ?>" class="image featured"><img src="/images/<?= $card['imagepath'] ?: ''?>"></a>
                                    <header>
                                        <a href="/creature.php/?id=<?= $card['id'] ?>">
                                            <h3><?= $card['name'] ?></h3>
                                        </a>
                                    </header>
                                    <p><strong>Species: </strong><?= $card['species'] ?: 'Unknown' ?></p>
                                    <p><strong>Colour(s): </strong><?= $card['colour'] ?: 'Unknown' ?></p>
                                    <p><strong>Height: </strong><?= $card['heightin'] ? $card['heightin'] . ' inches' : 'Unknown' ?></p>
                                </section>
                            </div>
<?php
} ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div style="display: none;" id="sys-entry-list" data-entries="<?= htmlspecialchars(json_encode($entries), ENT_QUOTES, 'UTF-8') ?>"></div>
</body>
<script src="dopetrope-theme/js/jquery.min.js"></script>
<script src="dopetrope-theme/js/jquery.dropotron.min.js"></script>
<script src="dopetrope-theme/js/browser.min.js"></script>
<script src="dopetrope-theme/js/breakpoints.min.js"></script>
<script src="dopetrope-theme/js/util.js"></script>
<script src="dopetrope-theme/js/main.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.mjs"></script>
<script type="module" src="/js/search.js"></script>
</html>
