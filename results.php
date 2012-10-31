<?php

/**
 * *************************************************************************
 * *                       OOHOO - Course Search                          **
 * *************************************************************************
 * @package     block                                                     **
 * @subpackage  coursesearch                                              **
 * @name        Course Search                                             **
 * @copyright   oohoo.biz                                                 **
 * @link        http://oohoo.biz                                          **
 * @author      Patrick Thibaudeau                                        **
 * @author      Nicolas Bretin                                            **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */
require('../../config.php');
require('lib.php');


$courseid = required_param('courseid', PARAM_INT);
$q = required_param('q', PARAM_TEXT);

require_login($courseid, true); //Use course 1 because this has nothing to do with an actual course, just like course 1

global $CFG, $PAGE, $OUTPUT, $DB, $USER;

$context = get_context_instance(CONTEXT_COURSE, $courseid);

$PAGE->set_url('/blocks/course_search/results.php', array('id' => $courseid));
$PAGE->set_title(get_string('pagetitle', 'block_course_search'));
$PAGE->set_heading(get_string('pagetitle', 'block_course_search'));
$PAGE->set_context($context);
$PAGE->requires->css('/blocks/course_search/styles.css');

echo $OUTPUT->header();

//get all mods regardless if it's in the course or not.
$modules = $DB->get_records_sql('SELECT name FROM {modules}');

echo "<div class=\"coursebox clearfix\">";
echo "<div class=\"info\">";
echo "<h2>" . get_string('results', 'block_course_search') . " $q</h2>";
$course = $DB->get_record('course', array('id' => $courseid));
$modinfo = get_fast_modinfo($course);

echo "<ul class='block_course_search_result_list'>";
$i = 1;
foreach ($modules as $module)
{

    $functionname = "block_course_search_search_module_" . $module->name;
    //If a specific function exists called
    if (function_exists($functionname))
    {
        echo call_user_func($functionname, $courseid, $module, $q, $modinfo);
    }
    else
    {
        echo block_course_search_search_module($courseid, $module, $q);
    }
    $i++;
}

//Sections must be searched apart*****************************
echo block_course_search_search_section($courseid, $q);
//End section search**************************************

echo "</ul>";
echo "<br><button onclick='window.location = \"" . $CFG->wwwroot . "/course/view.php?id=" . $courseid . "\"'>" . get_string('return_course', 'block_course_search') ."</button>";
echo "</div>";
echo "</div>";
echo $OUTPUT->footer();
?>
