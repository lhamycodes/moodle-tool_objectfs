<?php

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../../config.php');

defined('MOODLE_INTERNAL') || die;

print_r("Error files : " . fetchOtherFiles("error") . "\n");
print_r("Local files : " . fetchOtherFiles("local") . "\n");
print_r("Duplicated files : " . fetchOtherFiles("duplicated") . "\n");
print_r("External files : " . fetchOtherFiles("external") . "\n");

function fetchOtherFiles($type)
{
    global $DB;

    switch ($type) {
        case 'local':
            $location = 0;
            break;
        case 'duplicated':
            $location = 1;
            break;
        case 'external':
            $location = 2;
            break;
        default:
            $location = -1;
            break;
    }

    print_r("Fetching $type files\n");

    $count = $DB->count_records("tool_objectfs_objects", array("location" => $location));

    return $count;
}