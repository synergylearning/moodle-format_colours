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

require_once($CFG->libdir.'/formslib.php');

class format_colours_editcolours_form extends moodleform {
    public function definition() {
        $mform =& $this->_form;
        $section = $this->_customdata['section'];
        $courseid = $this->_customdata['courseid'];
        $recent = $this->_customdata['recent'];

        $recentdiv = '';
        if ($recent !== null) {
            foreach ($recent as $recentcolour) {
                $recentdiv .= '<div class="format_colours_recent" style="background: '.$recentcolour.';">&nbsp;</div>';
            }
        }

        $mform->addElement('header', 'general', get_string('sectionheading', 'format_colours', $section));

        $mform->addElement('text', 'headback', get_string('headback', 'format_colours'));
        $mform->addElement('static', 'headbackselector', null, $this->get_colourselector_static('headback', $recentdiv));

        $mform->addElement('text', 'headfore', get_string('headfore', 'format_colours'));
        $mform->addElement('static', 'headforeselector', null, $this->get_colourselector_static('headfore', $recentdiv));

        $mform->addElement('text', 'contentback', get_string('contentback', 'format_colours'));
        $mform->addElement('static', 'contentbackselector', null, $this->get_colourselector_static('contentback', $recentdiv));

        $mform->addElement('text', 'contentfore', get_string('contentfore', 'format_colours'));
        $mform->addElement('static', 'contentforeselector', null, $this->get_colourselector_static('contentfore', $recentdiv));

        $mform->addElement('text', 'link', get_string('linkcolour', 'format_colours'));
        $mform->addElement('static', 'linkselector', null, $this->get_colourselector_static('link', $recentdiv));

        $mform->addElement('hidden', 'courseid', $courseid);
        $mform->addElement('hidden', 'section', $section);

        $this->add_action_buttons();
    }

    protected function get_colourselector_static($id, $recentdiv) {
        $static = html_writer::tag('div', '', array('id' => 'yui-picker-'.$id));
        $static .= html_writer::tag('div', $recentdiv, array('class' => 'format_colours_recent_holder',
                                                             'id' => 'format_colours_recent_'.$id,
                                                             'style' => 'display: none;'));
        return $static;
    }
}
