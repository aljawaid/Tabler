/*
 * Provides dependency-free sorting, filtering, column choice and pagination.
 *
 * Related production template: Template/tabler/show.php.
 * Related test file: tests/PresentationCompatibilityTest.php.
 */

(function () {
    'use strict';

    var storageKey = 'kanboard-tabler-visible-columns-v1';

    function initialise(view) {
        var table = view.querySelector('[data-tabler-table]');

        if (!table) {
            return;
        }

        var body = table.tBodies[0];
        var entries = Array.prototype.map.call(
            body.querySelectorAll('[data-tabler-row]'),
            function (row) {
                return {
                    row: row,
                    description: row.nextElementSibling,
                    originalIndex: Number(row.getAttribute('data-original-index')),
                };
            }
        );
        var filterInputs = Array.prototype.slice.call(view.querySelectorAll('[data-tabler-column-filter]'));
        var columnToggles = Array.prototype.slice.call(view.querySelectorAll('[data-tabler-column-toggle]'));
        var globalFilter = view.querySelector('[data-tabler-global-filter]');
        var mobileSort = view.querySelector('[data-tabler-mobile-sort]');
        var pageSizeControl = view.querySelector('[data-tabler-page-size]');
        var pageStatus = view.querySelector('[data-tabler-page-status]');
        var noMatches = view.querySelector('[data-tabler-no-matches]');
        var pagination = view.querySelector('.tabler-pagination');
        var page = 1;
        var pageSize = Number(pageSizeControl.value);
        var sortRules = [];
        var collator = new Intl.Collator(undefined, {numeric: true, sensitivity: 'base'});

        function normalise(value) {
            return String(value || '').toLocaleLowerCase();
        }

        function readVisibleColumns() {
            var stored;

            try {
                stored = JSON.parse(window.localStorage.getItem(storageKey));
            } catch {
                stored = null;
            }

            columnToggles.forEach(function (toggle) {
                if (toggle.disabled) {
                    toggle.checked = true;
                } else if (Array.isArray(stored)) {
                    toggle.checked = stored.indexOf(Number(toggle.value)) !== -1;
                }
            });
        }

        function writeVisibleColumns() {
            var visibleColumns = columnToggles
                .filter(function (toggle) { return toggle.checked; })
                .map(function (toggle) { return Number(toggle.value); });

            try {
                window.localStorage.setItem(storageKey, JSON.stringify(visibleColumns));
            } catch {
                // Private browsing can deny local storage without affecting table use.
            }
        }

        function applyColumnVisibility() {
            var visibleCount = 0;

            columnToggles.forEach(function (toggle) {
                var index = Number(toggle.value);
                var visible = toggle.checked || toggle.disabled;
                var cells = table.querySelectorAll('[data-column-index="' + index + '"]');

                if (visible) {
                    visibleCount += 1;
                }

                Array.prototype.forEach.call(cells, function (cell) {
                    cell.hidden = !visible;
                });
            });

            entries.forEach(function (entry) {
                entry.description.cells[0].colSpan = visibleCount;
            });
        }

        function matchesFilters(entry) {
            var cells = Array.prototype.slice.call(entry.row.cells);
            var globalValue = normalise(globalFilter.value);

            if (globalValue && !cells.some(function (cell) {
                return normalise(cell.getAttribute('data-filter-value')).indexOf(globalValue) !== -1;
            })) {
                return false;
            }

            return filterInputs.every(function (input) {
                var filterValue = normalise(input.value);
                var cell = entry.row.cells[Number(input.getAttribute('data-tabler-column-filter'))];

                return !filterValue
                    || normalise(cell.getAttribute('data-filter-value')).indexOf(filterValue) !== -1;
            });
        }

        function compareEntries(first, second) {
            var result = 0;

            sortRules.some(function (rule) {
                var firstValue = first.row.cells[rule.index].getAttribute('data-sort-value');
                var secondValue = second.row.cells[rule.index].getAttribute('data-sort-value');

                if (rule.type === 'number') {
                    result = Number(firstValue) - Number(secondValue);
                } else {
                    result = collator.compare(firstValue, secondValue);
                }

                result *= rule.direction === 'descending' ? -1 : 1;
                return result !== 0;
            });

            return result || first.originalIndex - second.originalIndex;
        }

        function updateSortIndicators() {
            Array.prototype.forEach.call(table.tHead.rows[0].cells, function (header, index) {
                var rule = sortRules.find(function (candidate) { return candidate.index === index; });
                var icon = header.querySelector('.fa');

                header.setAttribute('aria-sort', rule ? rule.direction : 'none');
                icon.className = 'fa ' + (
                    !rule ? 'fa-sort' : rule.direction === 'ascending' ? 'fa-sort-asc' : 'fa-sort-desc'
                );
            });
        }

        function render() {
            var filteredEntries;
            var pageCount;
            var start;
            var end;

            entries.sort(compareEntries);
            entries.forEach(function (entry) {
                body.appendChild(entry.row);
                body.appendChild(entry.description);
            });

            filteredEntries = entries.filter(matchesFilters);
            pageCount = pageSize === 0 ? 1 : Math.max(1, Math.ceil(filteredEntries.length / pageSize));
            page = Math.min(page, pageCount);
            start = pageSize === 0 ? 0 : (page - 1) * pageSize;
            end = pageSize === 0 ? filteredEntries.length : start + pageSize;

            entries.forEach(function (entry) {
                var filteredIndex = filteredEntries.indexOf(entry);
                var visible = filteredIndex >= start && filteredIndex < end;
                var expanded = entry.row.querySelector('[data-tabler-description-toggle][aria-expanded="true"]');

                entry.row.hidden = !visible;
                entry.description.hidden = !visible || !expanded;
            });

            noMatches.hidden = filteredEntries.length !== 0;
            pagination.hidden = filteredEntries.length === 0;
            pageStatus.textContent = pagination.getAttribute('data-page-template')
                .replace('{current}', String(page))
                .replace('{total}', String(pageCount));

            Array.prototype.forEach.call(view.querySelectorAll('[data-tabler-page]'), function (button) {
                var action = button.getAttribute('data-tabler-page');
                button.disabled = (
                    (action === 'first' || action === 'previous') && page === 1
                ) || (
                    (action === 'next' || action === 'last') && page === pageCount
                );
            });
        }

        table.addEventListener('click', function (event) {
            var sortButton = event.target.closest('[data-tabler-sort]');
            var descriptionButton = event.target.closest('[data-tabler-description-toggle]');

            if (sortButton) {
                var header = sortButton.closest('th');
                var index = header.cellIndex;
                var existing = sortRules.find(function (rule) { return rule.index === index; });
                var nextDirection = existing && existing.direction === 'ascending' ? 'descending' : 'ascending';
                var nextRule = {
                    index: index,
                    direction: nextDirection,
                    type: header.getAttribute('data-sort-type'),
                };

                sortRules = event.shiftKey
                    ? sortRules.filter(function (rule) { return rule.index !== index; }).concat(nextRule)
                    : [nextRule];
                page = 1;
                updateSortIndicators();
                render();
            }

            if (descriptionButton) {
                var expanded = descriptionButton.getAttribute('aria-expanded') === 'true';
                var icon = descriptionButton.querySelector('.fa');
                var nextLabel = descriptionButton.getAttribute(
                    expanded ? 'data-show-label' : 'data-hide-label'
                );

                descriptionButton.setAttribute('aria-expanded', String(!expanded));
                descriptionButton.setAttribute('title', nextLabel);
                descriptionButton.querySelector('.tabler-visually-hidden').textContent = nextLabel;
                icon.classList.toggle('fa-plus-square-o', expanded);
                icon.classList.toggle('fa-minus-square-o', !expanded);
                render();
            }
        });

        globalFilter.addEventListener('input', function () {
            page = 1;
            render();
        });

        filterInputs.forEach(function (input) {
            input.addEventListener('input', function () {
                page = 1;
                render();
            });
        });

        columnToggles.forEach(function (toggle) {
            toggle.addEventListener('change', function () {
                if (!toggle.checked) {
                    filterInputs[Number(toggle.value)].value = '';
                }

                applyColumnVisibility();
                writeVisibleColumns();
                page = 1;
                render();
            });
        });

        mobileSort.addEventListener('change', function () {
            var header = table.tHead.rows[0].querySelector('[data-sort-key="' + mobileSort.value + '"]');

            sortRules = [{index: header.cellIndex, direction: 'ascending', type: header.getAttribute('data-sort-type')}];
            page = 1;
            updateSortIndicators();
            render();
        });

        pageSizeControl.addEventListener('change', function () {
            pageSize = Number(pageSizeControl.value);
            page = 1;
            render();
        });

        pagination.addEventListener('click', function (event) {
            var button = event.target.closest('[data-tabler-page]');

            if (!button) {
                return;
            }

            var filteredCount = entries.filter(matchesFilters).length;
            var pageCount = pageSize === 0 ? 1 : Math.max(1, Math.ceil(filteredCount / pageSize));
            var action = button.getAttribute('data-tabler-page');

            if (action === 'first') {
                page = 1;
            } else if (action === 'previous') {
                page = Math.max(1, page - 1);
            } else if (action === 'next') {
                page = Math.min(pageCount, page + 1);
            } else if (action === 'last') {
                page = pageCount;
            }

            render();
        });

        readVisibleColumns();
        applyColumnVisibility();
        sortRules = [{index: 0, direction: 'ascending', type: 'number'}];
        updateSortIndicators();
        render();
    }

    function start() {
        Array.prototype.forEach.call(document.querySelectorAll('[data-tabler-view]'), initialise);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
}());

