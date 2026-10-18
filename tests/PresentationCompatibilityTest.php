<?php

/**
 * Verifies responsive, theme-aware and accessible Tabler presentation contracts.
 *
 * Related production files: Assets/css/style.css, Assets/js/tabler.js and Template/tabler/show.php.
 */

$stylesheet = file_get_contents($pluginRoot . '/Assets/css/style.css');
$javascript = file_get_contents($pluginRoot . '/Assets/js/tabler.js');
$template = file_get_contents($pluginRoot . '/Template/tabler/show.php');

tablerAssertContains('var(--panel-background-color', $stylesheet, 'Surfaces must inherit Kanboard theme colours.');
tablerAssertContains('--tabler-transition', $stylesheet, 'Interactive controls must retain smooth transitions.');
tablerAssertContains('@media screen and (max-width: 768px)', $stylesheet, 'Task rows must adapt to mobile widths.');
tablerAssertContains('min-height: 44px', $stylesheet, 'Mobile controls must provide touch-sized targets.');
tablerAssertContains('@media (prefers-reduced-motion: reduce)', $stylesheet, 'Reduced-motion preferences must be respected.');
tablerAssertContains('@media (forced-colors: active)', $stylesheet, 'Forced-colour mode must retain visible boundaries.');
tablerAssertContains('@media print', $stylesheet, 'Printed task tables must omit interactive controls.');
tablerAssertNotContains('fonts.googleapis.com', $stylesheet, 'Tabler must not contact a third-party font service.');

tablerAssertContains("'use strict'", $javascript, 'The native table controller must use strict mode.');
tablerAssertContains('event.shiftKey', $javascript, 'Desktop sorting must preserve multi-column sorting.');
tablerAssertContains('window.localStorage', $javascript, 'Column choices must persist locally.');
tablerAssertNotContains('jQuery', $javascript, 'Tabler must not depend on the legacy jQuery table plug-in.');
tablerAssertNotContains('tablesorter', $javascript, 'Removed TableSorter code must not remain active.');

tablerAssertContains('$this->dt->datetime(', $template, 'Dates must respect the Kanboard user timezone.');
tablerAssertContains('$this->text->e(', $template, 'User-controlled table values must be escaped.');
tablerAssertContains('$this->text->markdown(', $template, 'Descriptions must use Kanboard Markdown sanitisation.');
tablerAssertContains('aria-sort="none"', $template, 'Sortable headings must expose their current state.');
tablerAssertContains('data-hide-label=', $template, 'Description controls must announce their expanded action.');
tablerAssertContains('<caption>', $template, 'The task table must include a caption.');
tablerAssertNotContains('href="#" class="toggle"', $template, 'Description expansion must use a button, not a placeholder link.');
tablerAssertNotContains('<script>', $template, 'The template must remain compatible with Kanboard CSP.');
