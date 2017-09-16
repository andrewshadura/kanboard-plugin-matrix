<h3><img src="<?= $this->url->dir() ?>plugins/Matrix/matrix-icon.png"/>&nbsp;Matrix</h3>
<div class="panel">
    <?= $this->form->label(t('Chat room'), 'matrix_room') ?>
    <?= $this->form->text('matrix_room', $values, array(), array('placeholder="#room:matrix.org"')) ?>

    <?= $this->form->hidden('matrix_use_colours', array('matrix_use_colours' => 0)) ?>
    <?= $this->form->checkbox('matrix_use_colours', t('Use colour codes in the messages'), 1, !isset($values['matrix_use_colours']) || $values['matrix_use_colours'] == 1) ?>

    <p class="form-help"><a href="https://kanboard.net/plugin/matrix" target="_blank"><?= t('Help on Matrix integration') ?></a></p>

    <div class="form-actions">
        <input type="submit" value="<?= t('Save') ?>" class="btn btn-blue"/>
    </div>
</div>
