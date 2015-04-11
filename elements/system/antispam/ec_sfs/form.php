<?php
defined('C5_EXECUTE') or die('Access denied.');

$form = Core::make('helper/form');
$config = Package::getByHandle('ec_sfs')->getConfig();
?>

<div class="formGroup">
    <?= $form->label('apiKey', t('Stop Forum Spam API Key')) ?>
    <?= $form->text('apiKey', $config->get('api.key', '')) ?>
    <p class="help-block"><?= t('An API key is only required to submit spam reports to Stop Forum Spam.') ?></p>
</div>