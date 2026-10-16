<?php

/**
 * Verifies Tabler's task queries, lower-case aliases and empty-access handling.
 *
 * Related production file: Model/TablerModel.php.
 */

$tablerModel = $container['tablerModel'];
$firstProjectId = $container['projectModel']->create(array('name' => 'Alpha project'));
$secondProjectId = $container['projectModel']->create(array('name' => 'Beta project'));

tablerAssertTrue($firstProjectId > 0, 'The first model test project must be created.');
tablerAssertTrue($secondProjectId > 0, 'The second model test project must be created.');

$firstTaskId = $container['taskCreationModel']->create(array(
    'title' => 'First task',
    'project_id' => $firstProjectId,
));
$secondTaskId = $container['taskCreationModel']->create(array(
    'title' => 'Second task',
    'project_id' => $secondProjectId,
));

tablerAssertTrue($firstTaskId > 0, 'The first model test task must be created.');
tablerAssertTrue($secondTaskId > 0, 'The second model test task must be created.');
tablerAssertSame(array(), $tablerModel->getTasksByProjectIds(array()), 'Empty project access must return no tasks.');

$firstProjectTasks = $tablerModel->getTasksByProjectId($firstProjectId);
tablerAssertSame(1, count($firstProjectTasks), 'A project-scoped query must return its task.');
tablerAssertSame('First task', $firstProjectTasks[0]['title'], 'The task title must be retained.');
tablerAssertSame('Alpha project', $firstProjectTasks[0]['project_name'], 'The joined project name must be retained.');

$accessibleTasks = $tablerModel->getTasksByProjectIds(array($secondProjectId, $firstProjectId, $firstProjectId));
tablerAssertSame(2, count($accessibleTasks), 'All and only accessible project tasks must be returned.');
tablerAssertSame('Alpha project', $accessibleTasks[0]['project_name'], 'Cross-project tasks must sort by project name.');
tablerAssertSame('Beta project', $accessibleTasks[1]['project_name'], 'Cross-project sorting must remain deterministic.');

$container['projectModel']->remove($firstProjectId);
$container['projectModel']->remove($secondProjectId);
