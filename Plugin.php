<?php

namespace Kanboard\Plugin\Tabler;

use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {
	// WARNING !! This is changing the security policy
	$this->setContentSecurityPolicy(array('default-src' => '* \'unsafe-inline\' \'unsafe-eval\''));

	$this->template->hook->attach('template:dashboard:sidebar', 'tabler:dashboard/dashboardsidebar');
	$this->template->hook->attach('template:project:dropdown', 'tabler:project/dropdown');
	$this->template->hook->attach('template:project:sidebar', 'tabler:project/sidebar');

    }

    public function getClasses()
    {
        return array(
            'Plugin\Tabler\Model' => array(
		'TablerModel'
             )
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
    public function getPluginDescription()
    {
        return 'Table';
    }
    public function getPluginHomepage()
    {
        return '';
    }
}
