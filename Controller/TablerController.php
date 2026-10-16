<?php

/**
 * Serves project-scoped task tables without crossing Kanboard permissions.
 *
 * Related test files: tests/ControllerAccessTest.php and tests/ControllerContractTest.php.
 */

namespace Kanboard\Plugin\Tabler\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Controller\AccessForbiddenException;
use Kanboard\Model\ProjectModel;

class TablerController extends BaseController
{
    public function project()
    {
        $project = $this->getAccessibleProject($this->request->getIntegerParam('project_id'));

        $this->response->html($this->helper->layout->app('Tabler:tabler/show', array(
            'title' => t('Tabler'),
            'project' => $project,
            'tasks' => $this->tablerModel->getTasksByProjectId((int) $project['id']),
        )));
    }

    public function projectAll()
    {
        $projectIds = $this->getAccessibleActiveProjectIds();

        $this->response->html($this->helper->layout->app('Tabler:tabler/show', array(
            'title' => t('Tabler - All projects'),
            'project' => null,
            'tasks' => $this->tablerModel->getTasksByProjectIds($projectIds),
        )));
    }

    private function getAccessibleProject($projectId)
    {
        $project = $this->projectModel->getById((int) $projectId);

        if (
            empty($project)
            || (int) $project['is_active'] !== ProjectModel::ACTIVE
            || !$this->projectPermissionModel->isUserAllowed((int) $projectId, $this->userSession->getId())
        ) {
            throw new AccessForbiddenException();
        }

        return $project;
    }

    private function getAccessibleActiveProjectIds()
    {
        if ($this->userSession->isAdmin()) {
            return array_map('intval', array_column(
                $this->projectModel->getAllByStatus(ProjectModel::ACTIVE),
                'id'
            ));
        }

        return array_map(
            'intval',
            $this->projectPermissionModel->getActiveProjectIds($this->userSession->getId())
        );
    }
}
