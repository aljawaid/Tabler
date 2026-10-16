<?php

/**
 * Verifies Tabler's authenticated and project-access boundaries.
 *
 * Related production files: Plugin.php and Controller/TablerController.php.
 */

$pluginSource = file_get_contents($pluginRoot . '/Plugin.php');
$controllerSource = file_get_contents($pluginRoot . '/Controller/TablerController.php');

tablerAssertContains(
    "applicationAccessMap->add('TablerController', '*', Role::APP_USER)",
    $pluginSource,
    'Every Tabler action must require an authenticated application user.'
);
tablerAssertNotContains('setContentSecurityPolicy', $pluginSource, 'Tabler must preserve Kanboard content security policy.');
tablerAssertContains(
    "getIntegerParam('project_id')",
    $controllerSource,
    'Project identifiers must be read as integers.'
);
tablerAssertContains(
    'isUserAllowed((int) $projectId, $this->userSession->getId())',
    $controllerSource,
    'Project views must verify access for the current session user.'
);
tablerAssertContains(
    'getActiveProjectIds($this->userSession->getId())',
    $controllerSource,
    'Cross-project views must use the complete active permission model.'
);
tablerAssertContains(
    'getAllByStatus(ProjectModel::ACTIVE)',
    $controllerSource,
    'Administrators must receive active projects without relying on direct membership rows.'
);
tablerAssertNotContains('getUser()', $controllerSource, 'A request-selected user must not control project visibility.');
