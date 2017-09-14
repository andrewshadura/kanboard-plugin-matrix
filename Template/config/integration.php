<h3><img src="<?= $this->url->dir() ?>plugins/Matrix/matrix-icon.png"/>&nbsp;Matrix</h3>
<div class="panel">
    <?= $this->form->label(t('Matrix homeserver URL'), 'matrix_homeserver_url') ?>
    <?=
        $this->form->text('matrix_homeserver_url',
            (!isset($values['matrix_homeserver_url']) || $values['matrix_homeserver_url'] == '') ?
                array('matrix_homeserver_url' => 'https://matrix.org:8448') : $values)
    ?>
    <p class="form-help"><?= t('If you don\'t know what this is, use https://matrix.org:8448') ?></p>

    <?= $this->form->label(t('Matrix bot username'), 'matrix_username') ?>
    <?= $this->form->text('matrix_username', $values, array(), array('placeholder="username"')) ?>

    <?= $this->form->label(t('Matrix bot password'), 'matrix_password') ?>
    <?= $this->form->password('matrix_password', $values, array(), array('placeholder="password"')) ?>

    <?= $this->form->label(t('Matrix bot token'), 'matrix_token') ?>
    <?= $this->form->text('matrix_token', $values, array(), array('placeholder="token (optional)"')) ?>
    <p class="form-help"><?= t('If you\'ve got a token, you don\'t have to enter the username or the password') ?></p>

    <p class="form-help"><a href="https://kanboard.net/plugin/matrix" target="_blank"><?= t('Help on Matrix integration') ?></a></p>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</div>
