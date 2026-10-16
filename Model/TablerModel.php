<?php

/**
 * Builds read-only task lists for project identifiers approved by the controller.
 *
 * Related test file: tests/TablerModelTest.php.
 */

namespace Kanboard\Plugin\Tabler\Model;

use Kanboard\Core\Base;

class TablerModel extends Base
{
    const TABLE_PROJECTS = 'projects';
    const TABLE_COLUMNS = 'columns';
    const TABLE_TASKS = 'tasks';
    const TABLE_CATEGORIES = 'project_has_categories';
    const TABLE_USERS = 'users';
    const TABLE_SWIMLANES = 'swimlanes';

    public function getTasksByProjectId($projectId)
    {
        return $this->getTaskQuery()
            ->eq(self::TABLE_TASKS . '.project_id', (int) $projectId)
            ->asc(self::TABLE_TASKS . '.id')
            ->findAll();
    }

    public function getTasksByProjectIds(array $projectIds)
    {
        $projectIds = array_values(array_unique(array_filter(array_map('intval', $projectIds))));

        if (empty($projectIds)) {
            return array();
        }

        return $this->getTaskQuery()
            ->in(self::TABLE_TASKS . '.project_id', $projectIds)
            ->asc('tabler_projects.name')
            ->asc(self::TABLE_TASKS . '.id')
            ->findAll();
    }

    private function getTaskQuery()
    {
        return $this->db
            ->table(self::TABLE_TASKS)
            ->columns(
                self::TABLE_TASKS . '.*',
                'tabler_projects.name AS project_name',
                'tabler_columns.title AS column_name',
                'tabler_users.username AS owner_username',
                'tabler_categories.name AS category_name',
                'tabler_swimlanes.name AS swimlane_name'
            )
            ->left(self::TABLE_PROJECTS, 'tabler_projects', 'id', self::TABLE_TASKS, 'project_id')
            ->left(self::TABLE_COLUMNS, 'tabler_columns', 'id', self::TABLE_TASKS, 'column_id')
            ->left(self::TABLE_CATEGORIES, 'tabler_categories', 'id', self::TABLE_TASKS, 'category_id')
            ->left(self::TABLE_USERS, 'tabler_users', 'id', self::TABLE_TASKS, 'owner_id')
            ->left(self::TABLE_SWIMLANES, 'tabler_swimlanes', 'id', self::TABLE_TASKS, 'swimlane_id');
    }
}
