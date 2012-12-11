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

function format_colour_edit_icon($courseid, $sectionid) {
    global $OUTPUT, $PAGE;

    static $editstr = null;
    static $copystr = null;
    static $canedit = null;

    if (!$PAGE->user_is_editing()) {
        return '';
    }

    if ($canedit === null) {
        $context = context_course::instance($courseid);
        $canedit = has_capability('format/colours:editcolours', $context);
    }
    if (!$canedit) {
        return '';
    }

    if ($editstr === null) {
        $editstr = get_string('editcolours', 'format_colours');
        $copystr = get_string('copycolours', 'format_colours');
    }

    $editurl = new moodle_url('/course/format/colours/editcolours.php', array('courseid'=>$courseid, 'section'=>$sectionid));
    $output = '';
    $icon = $OUTPUT->pix_icon('editcolours', $editstr, 'format_colours', array('title' => $editstr));
    $output .= html_writer::link($editurl, $icon, array('class' => 'format_colour_editcolours'));
    $icon = $OUTPUT->pix_icon('copycolours', $copystr, 'format_colours', array('title' => $copystr));
    $output .= html_writer::link('#', $icon, array('class' => 'format_colour_copycolours',
                                                  'id' => 'format_colour_copycolours-'.$sectionid));

    return $output;
}

function format_colour_clean_html_colour($colour) {
    return preg_replace('/[^a-zA-Z0-9#]/', '', $colour);
}

function format_colour_output_style_tag($courseid, $displaysection = 0) {
    global $DB;

    $colours = $DB->get_records('format_colours', array('courseid' => $courseid));
    if (empty($colours)) {
        return '';
    }

    $output = "\n";
    foreach ($colours as $colour) {
        $basepath = 'li#section-'.$colour->section.' ';
        if ($colour->headback || $colour->headfore) {
            if ($displaysection && $colour->section == $displaysection) {
                $output .= '.course-content .single-section .section-navigation.header, ';
            }
            $output .= $basepath.".section-header, ";
            $output .= $basepath."h3.section-title, ";
            $output .= $basepath."h3.sectionname {";
            if ($colour->headback) {
                $output .= 'background: '.format_colour_clean_html_colour($colour->headback).";";
            }
            if ($colour->headfore) {
                $output .= 'color: '.format_colour_clean_html_colour($colour->headfore).";";
            }
            $output .= "}\n";
        }
        if ($colour->contentback || $colour->contentfore) {
            $output .= $basepath.'{';
            if ($colour->contentback) {
                $output .= 'background: '.format_colour_clean_html_colour($colour->contentback).";";
            }
            if ($colour->contentfore) {
                $output .= 'color: '.format_colour_clean_html_colour($colour->contentfore).";";
            }
            $output .= "}\n";
        }
        if ($colour->link) {
            $output .= $basepath."div.summary a:link, ";
            $output .= $basepath."div.summary a:visited, ";
            $output .= $basepath."ul.section a:link, ";
            $output .= $basepath."ul.section a:visited {";
            $output .= 'color: '.format_colour_clean_html_colour($colour->link).";";
            $output .= "}\n";
        }
    }

    return html_writer::tag('style', $output, array('type' => 'text/css'));
}

function format_colour_add_javascript($courseid, $numsections) {
    global $PAGE;

    $jsmodule = array(
        'name' => 'format_colours_copy',
        'fullpath' => new moodle_url('/course/format/colours/copycolours.js'),
        'strings' => array(array('copysrc', 'format_colours'), array('docopycolours', 'format_colours'), array('cancel', 'core')),
        'requires' => array('node', 'event', 'panel')
    );
    $options = array('courseid' => $courseid, 'numsections' => $numsections);
    $PAGE->requires->js_init_call('M.copycolours.init', array($options), true, $jsmodule);
}
