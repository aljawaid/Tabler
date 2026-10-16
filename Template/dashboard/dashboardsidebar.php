<?php

/**
 * Adds the all-project Tabler view to dashboard navigation.
 *
 * Related test file: tests/TemplateRenderTest.php.
 */
?>

<li>
    <?= $this->url->icon(
        'table',
        t('Tabler'),
        'TablerController',
        'projectAll',
        array('plugin' => 'Tabler')
    ) ?>
</li>
