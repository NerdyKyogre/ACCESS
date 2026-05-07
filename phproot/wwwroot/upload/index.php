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
</head>
<body>
    <h1>Welcome to the Friend Update Node!</h1>
    <h2>Have a lot of FUN :-]</h2>
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
        <label for="image">Select an image</label>
        <input type="file" name="imageUpload" id="image">
        <label for="bio">Bio: </label>
        <textarea name="bio" id="bio" rows="8" cols="40"><?= $info ? $info->bio : '' ?></textarea>
        <button type="submit" name="submit"><?= $id ? "Update your friend!" : "Add your friend!" ?></button>
    </form>
</body>
</html>
