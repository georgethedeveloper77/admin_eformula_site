<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width'>
    <title><?= lang('privacy_policy'); ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            padding: 1em;
        }
    </style>
</head>

<body>
    <?php
    if ($setting) {
        echo $setting['message'];
    }
    ?>
</body>

</html>