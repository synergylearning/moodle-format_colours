<?php

function xmldb_format_colours_install() {
    global $CFG;

    if ($CFG->version > 2012062500) {
        die(get_string('notmoodle23', 'format_colours'));
    }
}
