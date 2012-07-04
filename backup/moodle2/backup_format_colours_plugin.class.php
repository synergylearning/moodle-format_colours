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
 * Provides the information to backup colours course format
 */
class backup_format_colours_plugin extends backup_format_plugin {
    /**
     * Returns the format information to attach to course element
     */
    protected function define_course_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '/course/format', 'colours');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());
        $sectioncolour = new backup_nested_element('sectioncolour', array('id'), array('section', 'headback', 'headfore', 'contentback', 'contentfore', 'link'));

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);
        $pluginwrapper->add_child($sectioncolour);

        // set source to populate the data
        $sectioncolour->set_source_table('format_colours', array('courseid' => backup::VAR_PARENTID));

        return $plugin;
    }
}
