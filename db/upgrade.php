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

/**
 * Scipt to upgrade DB tables
 *
 * @package   format_colours
 * @copyright 2012 Davo Smith, Synergy Learning
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_format_colours_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2012080801) {

        // Define table format_colours to be created
        $table = new xmldb_table('format_colours');

        // Adding fields to table format_colours
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('section', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('headback', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('headfore', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('contentback', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('contentfore', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('link', XMLDB_TYPE_CHAR, '20', null, null, null, null);
        $table->add_field('modified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table format_colours
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));

        // Conditionally launch create table for format_colours
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // colours savepoint reached
        upgrade_plugin_savepoint(true, 2012080801, 'format', 'colours');
    }

    return true;
}