<?php

/**
 * Provides assertion helpers for Tabler's focused regression tests.
 *
 * Related test runner: tests/run.php.
 */

function tablerAssertTrue($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function tablerAssertFalse($condition, $message)
{
    tablerAssertTrue(!$condition, $message);
}

function tablerAssertSame($expected, $actual, $message)
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            $message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.'
        );
    }
}

function tablerAssertContains($needle, $haystack, $message)
{
    tablerAssertTrue(strpos($haystack, $needle) !== false, $message);
}

function tablerAssertNotContains($needle, $haystack, $message)
{
    tablerAssertFalse(strpos($haystack, $needle) !== false, $message);
}
