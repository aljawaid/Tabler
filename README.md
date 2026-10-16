# Tabler

Tabler adds accessible, sortable and filterable task tables to Kanboard. It provides a project view and an all-project view while respecting the current user's Kanboard project permissions.

## Requirements

- Kanboard 1.2.54 or later.
- PHP 8.2, 8.3, 8.4 or 8.5.
- A current browser with JavaScript enabled for interactive sorting, filtering and pagination.

## Features

- View tasks from one project or every active project available to the current user.
- Sort any column; hold Shift while selecting headings to sort by multiple columns.
- Search all visible task data or filter individual columns.
- Choose visible columns, with preferences retained in browser-local storage.
- Open task descriptions without leaving the table.
- Follow task titles to the standard Kanboard task view.
- Use the standard task menu where the current user has edit access.
- Paginate at 10, 20 or 50 tasks, or display all matching tasks.
- Use a mobile task-card layout with touch-sized controls.
- Inherit Kanboard light, dark, automatic and custom theme colours.
- Print a focused task table without interactive controls.

## Installation

1. Download or clone this repository into the Kanboard `plugins` directory.
2. Ensure the resulting directory is named `Tabler` with the same capitalisation.
3. Sign in to Kanboard and confirm that **Tabler** appears in the dashboard and project navigation.

The expected layout is:

```text
plugins/
└── Tabler/
    ├── Controller/
    ├── Locale/
    ├── Model/
    ├── Template/
    ├── assets/
    └── Plugin.php
```

## Use

- Select **Tabler** from a project's menu or sidebar to view that active project.
- Select **Tabler** from the dashboard sidebar to view tasks from all accessible active projects.
- Select a column heading to sort it. Hold Shift while selecting another heading to add it to the sort order.
- Use **Choose columns** to reduce the information shown. ID, project and task columns remain visible so rows keep their context.
- Select the description button beside a task title to reveal its Kanboard-formatted description.

## Access and privacy

- Every Tabler route requires an authenticated Kanboard user.
- Project views verify the current user's access before querying tasks.
- The all-project view uses Kanboard's complete permission model, including direct, group and administrator access.
- Tabler does not replace or weaken Kanboard's Content Security Policy.
- Column preferences remain in the current browser's local storage and are not sent to a third party.
- Tabler has no external runtime dependencies and makes no external network requests.

## Compatibility checks

Continuous integration performs:

- PHP syntax and focused plugin regression checks on PHP 8.2 through 8.5.
- A real plugin boot against the supported Kanboard 1.2.54 baseline.
- Model regressions for project-scoped, multi-project and empty-access queries.
- Template rendering checks for escaped task data and safe Markdown descriptions.
- CSS, JavaScript, Markdown, YAML and workflow linting.

Run the focused regression suite locally from this repository with a Kanboard 1.2.54 checkout:

```text
php tests/run.php C:\path\to\kanboard
```

Set `KANBOARD_PLUGINS_DIR` when the tested Kanboard checkout does not already contain this repository as `plugins/Tabler`.

## Licence

Tabler is released under the MIT Licence. See [LICENSE](LICENSE).

Kanboard is separate software maintained by the [Kanboard project](https://github.com/kanboard/kanboard).
