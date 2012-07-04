<?php
// This file is part of the Colours course format for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

/**
 * restore plugin class that provides the necessary information
 * needed to restore one colours course format
 */
class restore_format_colours_plugin extends restore_format_plugin {
    /**
     * Returns the paths to be handled by the plugin at course level
     */
    protected function define_course_plugin_structure() {

        $paths = array();

        // Add own format stuff
        $elename = $this->get_namefor('');
        $elepath = $this->get_pathfor('/sectioncolour');
        $paths[] = new restore_path_element($elename, $elepath);

        return $paths; // And we return the interesting paths
    }

    /**
     * Process the 'plugin_format_colours_course' element within the 'course' element in the 'course.xml' file in the '/course' folder
     * of the zipped backup 'mbz' file.
     */
    public function process_format_colours($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;

        // We only process this information if the course we are restoring to
        // has 'colours' format (target format can change depending of restore options)
        $format = $DB->get_field('course', 'format', array('id' => $this->task->get_courseid()));
        if ($format != 'colours') {
            return;
        }

        $data->courseid = $this->task->get_courseid();
        $newitemid = $DB->insert_record('format_colours', $data);
        $this->set_mapping($this->get_namefor('sectioncolour'), $oldid, $newitemid, true);
    }

    protected function after_execute_structure() { }
}
