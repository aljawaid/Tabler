<?php

/**
 * Registers Tabler routes, navigation, translations and authenticated access.
 *
 * Related test file: tests/ControllerContractTest.php.
 */

namespace Kanboard\Plugin\Tabler;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Core\Translator;

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

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__ . '/Locale');
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
        return 'TTJ, maintained by aljawaid';
    }

    public function getPluginVersion()
    {
        return '1.0.2';
    }

    public function getCompatibleVersion()
    {
        // The focused regression matrix targets the supported Kanboard baseline.
        return '>=1.2.54';
    }

    public function getPluginDescription()
    {
        return t('Browse accessible tasks in a sortable and filterable table.');
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/aljawaid/Tabler';
    }
}
