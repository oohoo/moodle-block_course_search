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

//Replace get_context_instance by the class for moodle 2.6+
if(class_exists('context_module'))
{
    $context = context_course::instance($courseid);
}
else
{
    $context = get_context_instance(CONTEXT_COURSE, $courseid);
}

$PAGE->set_pagelayout('incourse');
$PAGE->set_url('/blocks/course_search/results.php', array('id' => $courseid));
$PAGE->set_title(get_string('pagetitle', 'block_course_search'));
$PAGE->set_heading(get_string('pagetitle', 'block_course_search'));
$PAGE->set_context($context);
$PAGE->requires->css('/blocks/course_search/styles.css');

echo $OUTPUT->header();

//get all mods regardless if it's in the course or not.
$modules = $DB->get_records_sql('SELECT name FROM {modules}');

echo "<div class=\"clearfix\">";
echo "<div class=\"info\">";
echo "<h2>" . get_string('results', 'block_course_search') . " $q</h2>";
$course = $DB->get_record('course', array('id' => $courseid));
$modinfo = get_fast_modinfo($course);

echo "<ul class='block_course_search_result_list'>";
$i = 1;
$printed = false;
foreach ($modules as $module)
{
    $functionname = "block_course_search_search_module_" . $module->name;
    $module_result = '';
    //If a specific function exists called
    if (function_exists($functionname))
    {
        $module_result = call_user_func($functionname, $courseid, $module, $q, $modinfo);
    }
    else
    {
        $module_result = block_course_search_search_module($courseid, $module, $q);
    }
    if ($module_result != '')
    {
        $printed = true;
    }
    echo $module_result;
    $i++;
}

//Sections must be searched apart*****************************
$section_result = block_course_search_search_section($courseid, $q);
if ($section_result != '')
{
    $printed = true;
}
echo $section_result;
//End section search**************************************

echo "</ul>";
if (!$printed)
{
    echo '<i>' . get_string('no_result', 'block_course_search') . '</i>';
}

echo "<hr/>";
echo "<h3>" . get_string('new_search', 'block_course_search') . "</h3>";
echo "<div class=\"searchform\">";
echo "<form style='display:inline;' name='course_search_form' id='block_course_search_form' action='$CFG->wwwroot/blocks/course_search/results.php' method='post'>";
echo "<fieldset class=\"invisiblefieldset\">";
echo get_string('searchfor', 'block_course_search') . "<br>";
echo "<input type='hidden' name='courseid' id='courseid' value='$course->id'/>";
echo "<input type='text' name='q' id='q' value=''/>";
echo "<br><input type='submit' id='searchform_button' value='" . get_string('submit', 'block_course_search') . "'>";
echo "</fieldset>";
echo "</form>";
echo "</div>";

echo "<br><button onclick='window.location = \"" . $CFG->wwwroot . "/course/view.php?id=" . $courseid . "\"'>" . get_string('return_course', 'block_course_search') . "</button>";
echo "</div>";
echo "</div>";
echo $OUTPUT->footer();