<?php

/**
 * Adds the project-scoped Tabler view to the project action menu.
 *
 * Related test file: tests/TemplateRenderTest.php.
 */
?>

<li>
    <?= $this->url->icon(
        'table',
        t('Tabler'),
        'TablerController',
        'project',
        array('plugin' => 'Tabler', 'project_id' => $project['id'])
    ) ?>
</li>

