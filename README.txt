--------------------------------------------------------------------------------
----------------------------- OOHOO - Course Search ----------------------------
--------------------------------------------------------------------------------

--------------------------------------------------------------------------------
Description
--------------------------------------------------------------------------------

OOHOO Course Search do a search in all the content of a course including:
 - Section Name and description
 - All activities of the course (even all new plugins that you will setup on 
   your moodle) based on the "name", "intro" and "content" fields

The result appears as a list of links.

--------------------------------------------------------------------------------
Prerequisites
--------------------------------------------------------------------------------

None

--------------------------------------------------------------------------------
Installation
--------------------------------------------------------------------------------

 1. Rename the folder to 'course_search'
 2. Copy the folder tts to moodle/blocks
 3. Install the plugin

Note: If a plugin needs a specific search, just add a function 
"block_course_search_search_module_NAMEOFTHEPLUGIN" in the lib.php file of the 
module. 
The parameters are:
 - int $courseid The course ID
 - stdClass $module The module object
 - string $q The string searched
 - stdClass $modinfo The modinfo object

The return must be a string of links in li tags. 
See the course_search/lib.php for example.

--------------------------------------------------------------------------------

For more informations, please go to the online documentation => http://oohoo.biz