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

require_once(dirname(__FILE__).'/../../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$from = required_param('from', PARAM_INT);
$to = required_param('to', PARAM_INT);

$course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);

if ($from < 0 || $from > $course->numsections) {
    throw new moodle_exception('invalidsection', 'format_colours');
}
if ($to < 0 || $to > $course->numsections) {
    throw new moodle_exception('invalidsection', 'format_colours');
}

$courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
$PAGE->set_url($courseurl); // We want to go back to course page if there is a problem

require_login($course);
require_capability('format/colours:editcolours', get_context_instance(CONTEXT_COURSE, $course->id));
require_sesskey();

$srcdata = $DB->get_record('format_colours', array('courseid' => $course->id, 'section' => $from));

if ($srcdata) {
    $update = new stdClass();
    $update->headback = $srcdata->headback;
    $update->headfore = $srcdata->headfore;
    $update->contentback = $srcdata->contentback;
    $update->contentfore = $srcdata->contentfore;
    $update->link = $srcdata->link;
}

if (!$destdata = $DB->get_record('format_colours', array('courseid' => $course->id, 'section' => $to), 'id')) {
    if ($srcdata) {
        // Source section has data, but dest is empty => create a new record
        $update->courseid = $course->id;
        $update->section = $to;
        $update->id = $DB->insert_record('format_colours', $update);
    }
} else {
    if (!$srcdata) {
        // Source section has no data => delete the dest section data
        $DB->delete_records('format_colours', array('id' => $destdata->id));
    } else {
        // Source and destination sections have data => update the existing record
        // for the destination section
        $update->id = $destdata->id;
        $DB->update_record('format_colours', $update);
    }
}

redirect($courseurl);
