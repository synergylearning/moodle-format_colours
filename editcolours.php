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

require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once($CFG->dirroot.'/course/format/colours/editcolours_form.php');

$courseid = required_param('courseid', PARAM_INT);
$section = required_param('section', PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    throw new moodle_exception('invalidcourse');
}

if ($section < 0 || $section > $course->numsections) {
    throw new moodle_exception('invalidsection', 'format_colours');
}

$PAGE->set_url(new moodle_url('/course/format/colours/editcolours.php', array('courseid' => $course->id, 'section' => $section)));

require_login($course);
require_capability('format/colours:editcolours', get_context_instance(CONTEXT_COURSE, $course->id));

$courseurl = new moodle_url('/course/view.php', array('id' => $course->id));
$custom = array('section' => $section, 'courseid' => $course->id, 'recent' => null);

$currdata = $DB->get_record('format_colours', array('courseid' => $course->id, 'section' => $section));

$allcolours = $DB->get_records('format_colours', array('courseid' => $course->id));
if (!empty($allcolours)) {
    $recent = array();
    foreach ($allcolours as $colour) {
        $recent[] = $colour->headback;
        $recent[] = $colour->headfore;
        $recent[] = $colour->contentback;
        $recent[] = $colour->contentfore;
        $recent[] = $colour->link;
    }
    $recent = array_unique($recent);
    $recent = array_diff($recent, array(null));
    if (count($recent) > 32) {
        $recent = array_slice($recent, 0, 32);
    }

    $custom['recent'] = $recent;
}

$form = new format_colours_editcolours_form(null, $custom);


if ($currdata) {
    $form->set_data($currdata);
}

if ($form->is_cancelled()) {
    redirect($courseurl);
}

if ($data = $form->get_data()) {
    if ($currdata) {
        $data->id = $currdata->id;
        $DB->update_record('format_colours', $data);
    } else {
        $DB->insert_record('format_colours', $data);
    }

    redirect($courseurl);
}

// Using YUI2 colour picker, as YUI3 does not exist yet
$PAGE->requires->yui2_lib('dom');
$PAGE->requires->yui2_lib('event');
$PAGE->requires->yui2_lib('element');
$PAGE->requires->yui2_lib('dragdrop');
$PAGE->requires->yui2_lib('slider');
$PAGE->requires->yui2_lib('colorpicker');
$PAGE->requires->yui2_lib('get');
$jsmodule = array(
                  'name' => 'format_colours',
                  'fullpath' => new moodle_url('/course/format/colours/editcolours.js')
                  );
$pickerthumb = $OUTPUT->pix_url('picker_thumb', 'format_colours').'';
$huethumb = $OUTPUT->pix_url('hue_thumb', 'format_colours').'';

$PAGE->requires->js_init_call('editcolours.init', array($pickerthumb, $huethumb), true, $jsmodule);

$PAGE->set_pagelayout('standard');
$PAGE->set_title($course->shortname.': '.get_string('editcolours', 'format_colours'));
$PAGE->set_heading(get_string('editcolours', 'format_colours'));

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();
