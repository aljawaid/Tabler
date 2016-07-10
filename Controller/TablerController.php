<?php

namespace Kanboard\Plugin\Tabler\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Controller\TaskModificationController;

class TablerController extends BaseController
{
    public function project()
    {
	$project = $this->getProject();

	$TablerData = $this->tablerModel->TablerFullTasksList($project['id']);

        $this->response->html($this->helper->layout->app('tabler:project/show', array('title' => t('Tabler'),
            'project' => $project,
	    'TablerData' => $TablerData
        )));
    }

    public function projectAll()
    {
	//$project = $this->getProject();
	$user = $this->getUser();

	$projectAccess = $this->tablerModel->TablerGetProjectid($user['id']);
	$TablerData = $this->tablerModel->TablerFullTasksListAll($projectAccess);

        $this->response->html($this->helper->layout->app('tabler:project_overview/show', array('title' => t('Tabler'),
            'project' => 'Allprojects',
	    'TablerData' => $TablerData
        )));
    }

}
