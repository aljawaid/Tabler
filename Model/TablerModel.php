<?php

namespace Kanboard\Plugin\Tabler\Model;

use Kanboard\Core\Base;
use Kanboard\Filter\TaskProjectFilter;
use Kanboard\Model\TaskModel;

class TablerModel extends Base
{

	const TABLE = 'projects';
	const TABLEcolumns = 'columns';
	const TABLEtasks = 'tasks';
	const TABLEcategory = 'project_has_categories';
	const TABLEusers = 'users';
	const TABLEswimlanes = 'swimlanes';
	const TABLEaccess = 'project_has_users';

	public function TablerGetProjectid($user_id) // Get all project_id where user has access
        {

        return $this->db
                ->table(self::TABLEaccess)
		->columns(
			self::TABLEaccess.'.project_id',
			'tblPro.name AS project_name'
		)
		->eq('user_id', $user_id)
		->left(self::TABLE, 'tblPro', 'id', self::TABLEaccess, 'project_id')
		->asc('project_id')
                ->findAll();
        }

	public function TablerFullTasksList($project_id)
        {

		return  $this->db
                        ->table(self::TABLEtasks)
                        ->columns(
				self::TABLEtasks.'.id', 
				self::TABLEtasks.'.title', 
				self::TABLEtasks.'.description', 
				self::TABLEtasks.'.date_creation', 
				self::TABLEtasks.'.color_id', 
				self::TABLEtasks.'.project_id',
				'tblPro.name AS project_name',
				'tblCol.title AS column_name',
				'tblUsers.username AS owner_username',
				self::TABLEtasks.'.position',
				self::TABLEtasks.'.is_active', 
				self::TABLEtasks.'.date_completed', 
				self::TABLEtasks.'.score', 
				self::TABLEtasks.'.date_due',
				'tblCat.name AS category_name',
				// self::TABLEtasks.'.creator_id', 
				self::TABLEtasks.'.date_modification', 
				// self::TABLEtasks.'.reference', 
				self::TABLEtasks.'.date_started', 
				self::TABLEtasks.'.time_spent', 
				self::TABLEtasks.'.time_estimated', 
				//self::TABLEtasks.'.swimlane_id', 
				'tblSwim.name AS swimlane_name',
				self::TABLEtasks.'.date_moved', 
				self::TABLEtasks.'.priority'
			)
			->left(self::TABLE, 'tblPro',  'id', self::TABLEtasks, 'project_id')
			->left(self::TABLEcolumns, 'tblCol',  'id', self::TABLEtasks, 'column_id')
			->left(self::TABLEcategory, 'tblCat',  'id', self::TABLEtasks, 'category_id')
			->left(self::TABLEusers, 'tblUsers',  'id', self::TABLEtasks, 'owner_id')
			->left(self::TABLEswimlanes, 'tblSwim',  'id', self::TABLEtasks, 'swimlane_id')
			->eq(self::TABLEtasks.'.project_id', $project_id)
                        ->findAll();
	 }

	public function TablerFullTasksListAll($projectAccess)
        {

		foreach($projectAccess as $u) $uids[] = $u['project_id'];
		$projectAccess = implode(", ",$uids);
		substr_replace($projectAccess, "", -2);
		$projectAccess = explode(', ', $projectAccess);

		return  $this->db
                        ->table(self::TABLEtasks)
                        ->columns(
				self::TABLEtasks.'.id', 
				self::TABLEtasks.'.title', 
				self::TABLEtasks.'.description', 
				self::TABLEtasks.'.date_creation', 
				self::TABLEtasks.'.color_id', 
				self::TABLEtasks.'.project_id',
				'tblPro.name AS project_name',
				'tblCol.title AS column_name',
				'tblUsers.username AS owner_username',
				self::TABLEtasks.'.position',
				self::TABLEtasks.'.is_active', 
				self::TABLEtasks.'.date_completed', 
				self::TABLEtasks.'.score', 
				self::TABLEtasks.'.date_due',
				'tblCat.name AS category_name',
				// self::TABLEtasks.'.creator_id', 
				self::TABLEtasks.'.date_modification', 
				// self::TABLEtasks.'.reference', 
				self::TABLEtasks.'.date_started', 
				self::TABLEtasks.'.time_spent', 
				self::TABLEtasks.'.time_estimated', 
				//self::TABLEtasks.'.swimlane_id', 
				'tblSwim.name AS swimlane_name',
				self::TABLEtasks.'.date_moved', 
				self::TABLEtasks.'.priority'
			)
			->left(self::TABLE, 'tblPro',  'id', self::TABLEtasks, 'project_id')
			->left(self::TABLEcolumns, 'tblCol',  'id', self::TABLEtasks, 'column_id')
			->left(self::TABLEcategory, 'tblCat',  'id', self::TABLEtasks, 'category_id')
			->left(self::TABLEusers, 'tblUsers',  'id', self::TABLEtasks, 'owner_id')
			->left(self::TABLEswimlanes, 'tblSwim',  'id', self::TABLEtasks, 'swimlane_id')
			->in(self::TABLEtasks.'.project_id', $projectAccess)
                        ->findAll();
	 }

}
