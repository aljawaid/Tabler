<?php

/**
 * Registers Tabler routes, navigation and authenticated access.
 *
 * Related test file: tests/ControllerContractTest.php.
 */

namespace Kanboard\Plugin\Tabler;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Security\Role;

class Plugin extends Base
{
    public function initialize()
    {
        // Task listings are available only to authenticated application users.
        $this->applicationAccessMap->add('TablerController', '*', Role::APP_USER);

        // Named routes remain portable when Kanboard is hosted below a URL subdirectory.
        $this->route->addRoute('/tabler/project/:project_id', 'TablerController', 'project', 'Tabler');
        $this->route->addRoute('/tabler/projects', 'TablerController', 'projectAll', 'Tabler');

        $this->template->hook->attach('template:dashboard:sidebar', 'Tabler:dashboard/dashboardsidebar');
        $this->template->hook->attach('template:project:dropdown', 'Tabler:project/dropdown');
        $this->template->hook->attach('template:project:sidebar', 'Tabler:project/sidebar');
    }

    public function getClasses()
    {
        return array(
            'Plugin\Tabler\Model' => array(
                'TablerModel',
            ),
        );
    }

    public function getPluginName()
    {
        return 'Tabler';
    }

    public function getPluginAuthor()
    {
        return 'TTJ';
    }

    public function getPluginVersion()
    {
        return '0.0.1';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.54';
    }

    public function getPluginDescription()
    {
        return 'Table';
    }

    public function getPluginHomepage()
    {
        return '';
    }
}
