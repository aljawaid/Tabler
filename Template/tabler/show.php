<?php

/**
 * Renders the accessible task table and its native interaction controls.
 *
 * Related test files: tests/TemplateRenderTest.php and tests/PresentationCompatibilityTest.php.
 */

$columns = array(
    array('key' => 'id', 'label' => t('ID'), 'type' => 'number', 'locked' => true),
    array('key' => 'project_name', 'label' => t('Project'), 'type' => 'text', 'locked' => true),
    array('key' => 'title', 'label' => t('Task'), 'type' => 'text', 'locked' => true),
    array('key' => 'is_active', 'label' => t('Status'), 'type' => 'text'),
    array('key' => 'column_name', 'label' => t('Column'), 'type' => 'text'),
    array('key' => 'swimlane_name', 'label' => t('Swimlane'), 'type' => 'text'),
    array('key' => 'category_name', 'label' => t('Category'), 'type' => 'text'),
    array('key' => 'priority', 'label' => t('Priority'), 'type' => 'number'),
    array('key' => 'date_started', 'label' => t('Start date'), 'type' => 'number'),
    array('key' => 'date_due', 'label' => t('Due date'), 'type' => 'number'),
    array('key' => 'date_modification', 'label' => t('Date modified'), 'type' => 'number'),
    array('key' => 'date_moved', 'label' => t('Date moved'), 'type' => 'number', 'default' => false),
    array('key' => 'date_creation', 'label' => t('Date created'), 'type' => 'number', 'default' => false),
    array('key' => 'owner_username', 'label' => t('Owner'), 'type' => 'text'),
    array('key' => 'score', 'label' => t('Score'), 'type' => 'number', 'default' => false),
    array('key' => 'time_spent', 'label' => t('Time spent'), 'type' => 'number', 'default' => false),
    array('key' => 'time_estimated', 'label' => t('Time estimated'), 'type' => 'number', 'default' => false),
);
$priorityLabels = array(
    0 => t('None'),
    1 => t('Low'),
    2 => t('Medium'),
    3 => t('High'),
);
$formatDate = function ($value) {
    return !empty($value) ? $this->dt->datetime((int) $value) : '';
};
?>

<?= $this->asset->css('plugins/Tabler/assets/css/style.css') ?>
<?= $this->asset->js('plugins/Tabler/assets/js/tabler.js') ?>

<?php if ($project !== null): ?>
    <?= $this->projectHeader->render($project, 'ProjectOverviewController', 'show') ?>
<?php endif ?>

<section class="tabler-view" data-tabler-view>
    <div class="tabler-introduction">
        <h2><?= $this->text->e($title) ?></h2>
        <p>
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <?= t('Sort, filter and choose columns without changing task data.') ?>
        </p>
    </div>

    <div class="tabler-toolbar" aria-label="<?= $this->text->e(t('Table controls')) ?>">
        <div class="tabler-control">
            <label for="tabler-global-filter"><?= t('Search visible task data') ?></label>
            <input id="tabler-global-filter" type="search" data-tabler-global-filter autocomplete="off">
        </div>

        <div class="tabler-control tabler-mobile-sort">
            <label for="tabler-mobile-sort"><?= t('Sort tasks') ?></label>
            <select id="tabler-mobile-sort" data-tabler-mobile-sort>
                <?php foreach ($columns as $column): ?>
                    <option value="<?= $this->text->e($column['key']) ?>">
                        <?= $this->text->e($column['label']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <details class="tabler-column-selector">
            <summary><?= t('Choose columns') ?></summary>
            <div class="tabler-column-options" role="group" aria-label="<?= $this->text->e(t('Visible columns')) ?>">
                <?php foreach ($columns as $index => $column): ?>
                    <label>
                        <input
                            type="checkbox"
                            value="<?= $index ?>"
                            data-tabler-column-toggle
                            <?= !isset($column['default']) || $column['default'] ? 'checked' : '' ?>
                            <?= !empty($column['locked']) ? 'disabled' : '' ?>
                        >
                        <?= $this->text->e($column['label']) ?>
                    </label>
                <?php endforeach ?>
            </div>
        </details>
    </div>

    <?php if (empty($tasks)): ?>
        <p class="alert alert-info" data-tabler-empty><?= t('No accessible tasks are available.') ?></p>
    <?php else: ?>
        <p class="alert alert-info" data-tabler-no-matches hidden><?= t('No tasks match the current filters.') ?></p>

        <div class="tabler-table-region" role="region" aria-label="<?= $this->text->e(t('Task table')) ?>" tabindex="0">
            <table id="tabler-task-table" class="tabler-table" data-tabler-table>
                <caption><?= $project === null ? t('Tasks across accessible projects') : t('Tasks in this project') ?></caption>
                <thead>
                    <tr>
                        <?php foreach ($columns as $index => $column): ?>
                            <th
                                scope="col"
                                data-column-index="<?= $index ?>"
                                data-sort-key="<?= $this->text->e($column['key']) ?>"
                                data-sort-type="<?= $this->text->e($column['type']) ?>"
                                aria-sort="none"
                            >
                                <button
                                    type="button"
                                    class="tabler-sort"
                                    data-tabler-sort
                                    aria-label="<?= $this->text->e(t('Sort by %s', $column['label'])) ?>"
                                >
                                    <span><?= $this->text->e($column['label']) ?></span>
                                    <i class="fa fa-sort" aria-hidden="true"></i>
                                </button>
                            </th>
                        <?php endforeach ?>
                    </tr>
                    <tr class="tabler-filter-row">
                        <?php foreach ($columns as $index => $column): ?>
                            <th data-column-index="<?= $index ?>">
                                <label class="tabler-visually-hidden" for="tabler-filter-<?= $index ?>">
                                    <?= t('Filter %s', $column['label']) ?>
                                </label>
                                <input
                                    id="tabler-filter-<?= $index ?>"
                                    type="search"
                                    data-tabler-column-filter="<?= $index ?>"
                                    aria-label="<?= $this->text->e(t('Filter %s', $column['label'])) ?>"
                                    autocomplete="off"
                                >
                            </th>
                        <?php endforeach ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $rowIndex => $task): ?>
                        <?php
                        $status = (int) $task['is_active'] === 1 ? t('Active') : t('Closed');
                        $priority = isset($priorityLabels[(int) $task['priority']])
                            ? $priorityLabels[(int) $task['priority']]
                            : (string) (int) $task['priority'];
                        $dateValues = array(
                            'date_started' => $formatDate($task['date_started']),
                            'date_due' => $formatDate($task['date_due']),
                            'date_modification' => $formatDate($task['date_modification']),
                            'date_moved' => $formatDate($task['date_moved']),
                            'date_creation' => $formatDate($task['date_creation']),
                        );
                        $filterValues = array(
                            (string) $task['id'],
                            $task['project_name'],
                            $task['title'],
                            $status,
                            $task['column_name'],
                            $task['swimlane_name'],
                            $task['category_name'],
                            $priority,
                            $dateValues['date_started'],
                            $dateValues['date_due'],
                            $dateValues['date_modification'],
                            $dateValues['date_moved'],
                            $dateValues['date_creation'],
                            $task['owner_username'],
                            (string) $task['score'],
                            (string) $task['time_spent'],
                            (string) $task['time_estimated'],
                        );
                        $sortValues = array(
                            (int) $task['id'],
                            $task['project_name'],
                            $task['title'],
                            $status,
                            $task['column_name'],
                            $task['swimlane_name'],
                            $task['category_name'],
                            (int) $task['priority'],
                            (int) $task['date_started'],
                            (int) $task['date_due'],
                            (int) $task['date_modification'],
                            (int) $task['date_moved'],
                            (int) $task['date_creation'],
                            $task['owner_username'],
                            (int) $task['score'],
                            (int) $task['time_spent'],
                            (int) $task['time_estimated'],
                        );
                        ?>
                        <tr class="tabler-task-row" data-tabler-row data-original-index="<?= $rowIndex ?>">
                            <?php foreach ($columns as $index => $column): ?>
                                <td
                                    data-column-index="<?= $index ?>"
                                    data-label="<?= $this->text->e($column['label']) ?>"
                                    data-filter-value="<?= $this->text->e($filterValues[$index]) ?>"
                                    data-sort-value="<?= $this->text->e((string) $sortValues[$index]) ?>"
                                >
                                    <?php if ($column['key'] === 'id'): ?>
                                        <div class="task-table color-<?= $this->text->e($task['color_id']) ?>">
                                            <?php if ($this->user->hasProjectAccess('TaskModificationController', 'edit', $task['project_id'])): ?>
                                                <?= $this->render('task/dropdown', array('task' => $task)) ?>
                                            <?php else: ?>
                                                <strong>#<?= (int) $task['id'] ?></strong>
                                            <?php endif ?>
                                        </div>
                                    <?php elseif ($column['key'] === 'project_name'): ?>
                                        <?= $this->text->e($task['project_name']) ?>
                                    <?php elseif ($column['key'] === 'title'): ?>
                                        <span class="tabler-task-title">
                                            <?= $this->url->link(
                                                $this->text->e($task['title']),
                                                'TaskViewController',
                                                'show',
                                                array('task_id' => $task['id'])
                                            ) ?>
                                            <?php if (!empty($task['description'])): ?>
                                                <button
                                                    type="button"
                                                    class="tabler-description-toggle"
                                                    data-tabler-description-toggle
                                                    aria-expanded="false"
                                                    aria-controls="tabler-description-<?= (int) $task['id'] ?>"
                                                    data-show-label="<?= $this->text->e(t('Show task description')) ?>"
                                                    data-hide-label="<?= $this->text->e(t('Hide task description')) ?>"
                                                    title="<?= $this->text->e(t('Show task description')) ?>"
                                                >
                                                    <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                                                    <span class="tabler-visually-hidden"><?= t('Show task description') ?></span>
                                                </button>
                                            <?php endif ?>
                                        </span>
                                    <?php elseif ($column['key'] === 'is_active'): ?>
                                        <span class="tabler-status tabler-status-<?= (int) $task['is_active'] === 1 ? 'active' : 'closed' ?>">
                                            <?= $this->text->e($status) ?>
                                        </span>
                                    <?php elseif ($column['key'] === 'priority'): ?>
                                        <?= $this->text->e($priority) ?>
                                    <?php elseif (isset($dateValues[$column['key']])): ?>
                                        <?= $this->text->e($dateValues[$column['key']]) ?>
                                    <?php else: ?>
                                        <?= $this->text->e($task[$column['key']]) ?>
                                    <?php endif ?>
                                </td>
                            <?php endforeach ?>
                        </tr>
                        <tr
                            id="tabler-description-<?= (int) $task['id'] ?>"
                            class="tabler-description-row"
                            data-tabler-description-row
                            hidden
                        >
                            <td colspan="<?= count($columns) ?>">
                                <div class="markdown tabler-description-content">
                                    <?= $this->text->markdown($task['description']) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <nav
            class="tabler-pagination"
            aria-label="<?= $this->text->e(t('Task table pagination')) ?>"
            data-page-template="<?= $this->text->e(t('Page {current} of {total}')) ?>"
        >
            <button type="button" class="btn" data-tabler-page="first"><?= t('First') ?></button>
            <button type="button" class="btn" data-tabler-page="previous"><?= t('Previous') ?></button>
            <span data-tabler-page-status aria-live="polite"></span>
            <button type="button" class="btn" data-tabler-page="next"><?= t('Next') ?></button>
            <button type="button" class="btn" data-tabler-page="last"><?= t('Last') ?></button>
            <label for="tabler-page-size"><?= t('Tasks per page') ?></label>
            <select id="tabler-page-size" data-tabler-page-size>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="0"><?= t('All') ?></option>
            </select>
        </nav>
    <?php endif ?>
</section>
