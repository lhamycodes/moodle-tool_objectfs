<?php

define('CLI_SCRIPT', true);

require(__DIR__ . '/../../../../config.php');

defined('MOODLE_INTERNAL') || die;

print_r(resetErrorTasks());
// clearLock();

// for ($i = 0; $i < 10; $i++) {
//     execCron('php /var/www/html/admin/cli/scheduled_task.php --execute="tool_objectfs\task\push_objects_to_storage"');
//     clearLock();
//     execCron('php /var/www/html/admin/cli/scheduled_task.php --execute="tool_objectfs\task\delete_local_objects"');
//     clearLock();
// }

function resetErrorTasks()
{
    global $DB;

    $reset = $DB->execute("UPDATE {tool_objectfs_objects} too SET location=0 WHERE location=-1");

    return $reset;
}

function clearLock()
{
    global $DB;

    $releaseAdhoc = $DB->delete_records("lock_db", array("resourcekey" => '%adhoc%task%%'));
    $releaseScheduleRunner = $DB->delete_records("lock_db", array("resourcekey" => '%schedule%runner%'));
    $releaseResources = $DB->execute("UPDATE {lock_db} SET owner=NULL where expires IS NOT NULL AND resourcekey like '%objectfs%'");

    // $clear = $DB->get_record_sql("DELETE FROM {mdl_lock_db} WHERE resourcekey LIKE '%adhoc%task%%';DELETE FROM {mdl_lock_db} WHERE resourcekey LIKE '%schedule%runner%';UPDATE  {mdl_lock_db} SET owner=NULL where expires IS NOT NULL AND resourcekey like '%objectfs%';");

    return [$releaseAdhoc, $releaseScheduleRunner, $releaseResources];
}

function execCron($cron)
{
    return shell_exec($cron);
}
