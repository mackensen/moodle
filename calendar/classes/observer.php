<?php
// This file is part of Moodle - http://moodle.org/
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
 * Event observers used in calendar.
 *
 * @package    core_calendar
 * @copyright  2015 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class core_calendar_observer {
    /** 
     * Observer for \core\event\course_module_updated event.
     *
     * @param \core\event\course_module_updated $event
     * @return void
     */
    public static function update_event_names(\core\event\course_module_updated $event) {
        global $DB;
        $events = $DB->get_records('event', array(
            'courseid' => $event->courseid,
            'modulename' => $event->other['modulename'],
            'instance'   => $event->other['instanceid']
        ));
        if (!empty($events)) {
            foreach ($events as $item) {
                $item->name = $event->other['name'];
                $DB->update_record('event', $item);
            }
        }
    }
}
