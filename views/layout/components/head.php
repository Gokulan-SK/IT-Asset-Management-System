<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Asset Management System' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/workspace.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/style.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>public/css/form.css" />

    <?php
    if (isset($pageStyles)) {
        foreach ($pageStyles as $style) {
            echo "<link rel='stylesheet' href='$style' />\n";
        }
    }
    ?>

</head>