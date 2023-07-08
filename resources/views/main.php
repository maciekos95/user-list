<?php

use Framework\Classes\Lang; 

$languages = Lang::list();
$currentLanguage = $_COOKIE['language'] ?? 'en';

if (!isset($messages)) {
    $messages = [];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $subpageTitle ?> - <?= Lang::get('app.main.main_title') ?></title>
    <link rel="stylesheet" href="/resources/css/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans">
</head>

<body>
    <div class="header-container">
        <h1><?= Lang::get('app.main.main_title') ?></h1>

        <div class="lang-container">
        <?php foreach ($languages as $language): ?>
            <button class="lang-button" data-lang="<?= $language ?>" <?= ($language == $currentLanguage) ? 'disabled' : '' ?>>
                <?= strtoupper($language) ?>
            </button>
        <?php endforeach ?>
        </div>
    </div>

    <?php if (is_array($messages) && !empty($messages)): ?>
        <div class="messages-container">
            <?php foreach ($messages as $message): ?>
                <div class="message-container <?= $message['type']->value ?>">
                    <h2><?= $message['content'] ?></h2>
                    <button class="close-button">×</button>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php if ($partialFile): ?>
        <div class="content-container">
            <?php include $partialFile . '.php'; ?>
        </div>
    <?php endif ?>

    <script src="/resources/js/script.js"></script>
</body>
</html>
