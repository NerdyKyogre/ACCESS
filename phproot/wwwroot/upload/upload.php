<?php
require_once('../../src/helpers.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    die("This script may only be called using a POST request");

$id = $_POST['id'] ?: null;
$name = $_POST['name'] ?: null;
$species = $_POST['species'] ?: null;
$colour = $_POST['colour'] ?: null;
$pronouns = $_POST['pronouns'] ?: null;
$height = $_POST['height'] ?: null;
$bio = $_POST['bio'] ?: null;

if($_FILES['imageUpload']['name'])
{
    if ($_FILES["imageUpload"]["error"] !== 0)
        die("Failed to upload image. Error code: " . $_FILES["imageUpload"]["error"]);
    $imageName = basename($_FILES['imageUpload']['name']);
    $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
    $newImageName = uniqid() . "." . $imageExt;
    $targetPath = \accessCore\CONFIG['IMG_PATH'] . "/" . $newImageName;
    move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetPath) or die("Failed to upload image.");
}
else
    $newImageName = null;

$cursor = \accessCore\getPGConn(false);
pg_query_params(
    $cursor,
    "CALL plushies.enterPlushie($1, $2, $3, $4, $5, $6, $7, $8)",
    [
        $name,
        $species,
        $colour,
        $pronouns,
        $newImageName,
        $height,
        $bio,
        $id
    ]
) or die("Upload failed.");

header("Location: /");
exit();
?>
