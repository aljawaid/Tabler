<?php

/**
 * Renders empty and populated Tabler views with warnings promoted to failures.
 *
 * Related production files: Template/tabler/show.php and navigation templates.
 */

$renderErrorHandler = function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
};
$sampleTask = array(
    'id' => 42,
    'title' => 'Task <sample>',
    'description' => '<script>alert(1)</script> **Safe description**',
    'date_creation' => 1760572800,
    'color_id' => 'blue',
    'project_id' => 7,
    'column_id' => 3,
    'owner_id' => 2,
    'position' => 1,
    'is_active' => 1,
    'date_completed' => 0,
    'score' => 5,
    'date_due' => 1760659200,
    'category_id' => 0,
    'date_modification' => 1760576400,
    'date_started' => 1760572800,
    'time_spent' => 30,
    'time_estimated' => 60,
    'swimlane_id' => 1,
    'date_moved' => 1760574600,
    'priority' => 2,
    'project_name' => 'Project <sample>',
    'column_name' => 'In progress',
    'owner_username' => 'owner <sample>',
    'category_name' => '',
    'swimlane_name' => 'Default swimlane',
);

set_error_handler($renderErrorHandler);

try {
    $emptyView = $container['template']->render('Tabler:tabler/show', array(
        'project' => null,
        'tasks' => array(),
        'title' => 'Tabler - All projects',
    ));
    $populatedView = $container['template']->render('Tabler:tabler/show', array(
        'project' => null,
        'tasks' => array($sampleTask),
        'title' => 'Tabler - All projects',
    ));

    tablerAssertContains('No accessible tasks are available.', $emptyView, 'The empty state must render.');
    tablerAssertContains('id="tabler-task-table"', $populatedView, 'The populated task table must render.');
    tablerAssertContains('Task &lt;sample&gt;', $populatedView, 'Task titles must be escaped.');
    tablerAssertContains('Project &lt;sample&gt;', $populatedView, 'Project names must be escaped.');
    tablerAssertNotContains('<script>alert(1)</script>', $populatedView, 'Descriptions must be sanitised by Kanboard Markdown.');
    tablerAssertContains('<strong>Safe description</strong>', $populatedView, 'Safe task Markdown must still render.');
    tablerAssertContains('aria-expanded="false"', $populatedView, 'Description controls must expose expansion state.');

    $container['template']->render('Tabler:dashboard/dashboardsidebar');
    $container['template']->render('Tabler:project/dropdown', array('project' => array('id' => 7)));
    $container['template']->render('Tabler:project/sidebar', array('project' => array('id' => 7)));
} finally {
    restore_error_handler();
}
