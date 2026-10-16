<?php

/**
 * Exercises Tabler's current-session, group and administrator project boundaries.
 *
 * Related production file: Controller/TablerController.php.
 */

use Kanboard\Core\Controller\AccessForbiddenException;
use Kanboard\Core\Security\Role;
use Kanboard\Plugin\Tabler\Controller\TablerController;

$groupProjectId = $container['projectModel']->create(array('name' => 'Group-access project'));
$hiddenProjectId = $container['projectModel']->create(array('name' => 'Hidden project'));
$groupId = $container['groupModel']->create('Tabler regression group');
$userId = $container['userModel']->create(array(
    'username' => 'tabler-regression-user',
    'password' => 'regression-only',
    'role' => Role::APP_USER,
));

tablerAssertTrue($groupProjectId > 0, 'The group-access project must be created.');
tablerAssertTrue($hiddenProjectId > 0, 'The inaccessible project must be created.');
tablerAssertTrue($groupId > 0, 'The test group must be created.');
tablerAssertTrue($userId > 0, 'The non-administrator test user must be created.');
tablerAssertTrue($container['groupMemberModel']->addUser($groupId, $userId), 'The user must join the test group.');
tablerAssertTrue(
    $container['projectGroupRoleModel']->addGroup($groupProjectId, $groupId, Role::PROJECT_MEMBER),
    'The test group must receive project access.'
);

$container['userSession']->initialize($container['userModel']->getById($userId));
$controller = new TablerController($container);
$activeProjectsMethod = new ReflectionMethod($controller, 'getAccessibleActiveProjectIds');
$projectMethod = new ReflectionMethod($controller, 'getAccessibleProject');
$accessibleProjectIds = $activeProjectsMethod->invoke($controller);

tablerAssertTrue(
    in_array($groupProjectId, $accessibleProjectIds, true),
    'Group membership must grant access in the all-project view.'
);
tablerAssertFalse(
    in_array($hiddenProjectId, $accessibleProjectIds, true),
    'An unrelated project must not appear in the all-project view.'
);
tablerAssertSame(
    $groupProjectId,
    (int) $projectMethod->invoke($controller, $groupProjectId)['id'],
    'The project view must accept a project granted through group membership.'
);

$accessDenied = false;

try {
    $projectMethod->invoke($controller, $hiddenProjectId);
} catch (AccessForbiddenException $exception) {
    $accessDenied = true;
}

tablerAssertTrue($accessDenied, 'The project view must reject an inaccessible project.');

$adminId = $container['userModel']->create(array(
    'username' => 'tabler-regression-admin',
    'password' => 'regression-only',
    'role' => Role::APP_ADMIN,
));
$container['userSession']->initialize($container['userModel']->getById($adminId));
$adminProjectIds = $activeProjectsMethod->invoke($controller);

tablerAssertTrue(
    in_array($groupProjectId, $adminProjectIds, true) && in_array($hiddenProjectId, $adminProjectIds, true),
    'Administrators must receive every active project without direct membership rows.'
);

$container['projectModel']->remove($groupProjectId);
$container['projectModel']->remove($hiddenProjectId);
$container['groupModel']->remove($groupId);
$container['userModel']->remove($userId);
$container['userModel']->remove($adminId);
