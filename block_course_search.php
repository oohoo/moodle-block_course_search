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
class block_course_search extends block_base
{

    /**
     * Init the block
     */
    function init()
    {
        $this->title = get_string('pluginname', 'block_course_search');
    }

    /**
     * Applicable formats
     * @return array
     */
    function applicable_formats()
    {
        return array('all' => true);
    }

    /**
     * 
     * @return boolean
     */
    function instance_allow_multiple()
    {
        return false;
    }

    /**
     * Return the content
     * @global stdClass $CFG
     * @return string
     */
    function get_content()
    {
        global $CFG;

        require_once($CFG->dirroot . '/course/lib.php');
        $course = $this->page->course;

        if ($this->content !== NULL)
        {
            return $this->content;
        }

        $this->content = new stdClass;

        $context = get_context_instance(CONTEXT_COURSE, $course->id);

        $this->content->footer = '&nbsp;';
        $text = "<div class=\"searchform\">";
        $text .= "<form style='display:inline;' name='course_search_form' id='block_course_search_form' action='$CFG->wwwroot/blocks/course_search/results.php' method='post'>";
        $text .= "<fieldset class=\"invisiblefieldset\">";
        $text .= get_string('searchfor', 'block_course_search') . "<br>";
        $text .= "<input type='hidden' name='courseid' id='courseid' value='$course->id'/>";
        $text .= "<input type='text' name='q' id='q' value=''/>";
        $text .= "<br><input type='submit' id='searchform_button' value='" . get_string('submit', 'block_course_search') . "'>";
        $text .= "</form>";
        $text .= "</fieldset>";
        $text .= "</div>";
        $this->content->text = $text;

        return $this->content;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked()
    {
        return (!empty($this->title) && parent::instance_can_be_docked());
    }

}