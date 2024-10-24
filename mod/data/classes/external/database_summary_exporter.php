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
 * Class for exporting partial database data.
 *
 * @package    mod_data
 * @copyright  2017 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace mod_data\external;
defined('MOODLE_INTERNAL') || die();

use core\external\exporter;
use renderer_base;
use core_external\external_files;
use core_external\util as external_util;

/**
 * Class for exporting partial database data (some fields are only viewable by admins).
 *
 * @copyright  2017 Juan Leyva <juan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class database_summary_exporter extends exporter {

    protected static function define_properties() {

        return [
            'id' => [
                'type' => PARAM_INT,
                'description' => 'Database id'],
            'course' => [
                'type' => PARAM_INT,
                'description' => 'Course id'],
            'name' => [
                'type' => PARAM_RAW,
                'description' => 'Database name'],
            'intro' => [
                'type' => PARAM_RAW,
                'description' => 'The Database intro',
            ],
            'introformat' => [
                'choices' => [FORMAT_HTML, FORMAT_MOODLE, FORMAT_PLAIN, FORMAT_MARKDOWN],
                'type' => PARAM_INT,
                'default' => FORMAT_MOODLE
            ],
            'advancedsearchdefault' => [
                'type' => PARAM_BOOL,
                'description' => 'Advanced search enabled by default',
            ],
            'lang' => [
                'type' => PARAM_LANG,
                'description' => 'Forced activity language',
                'null' => NULL_ALLOWED,
            ],
            'comments' => [
                'type' => PARAM_BOOL,
                'description' => 'comments enabled',
            ],
            'timeavailablefrom' => [
                'type' => PARAM_INT,
                'description' => 'timeavailablefrom field',
            ],
            'timeavailableto' => [
                'type' => PARAM_INT,
                'description' => 'timeavailableto field',
            ],
            'timeviewfrom' => [
                'type' => PARAM_INT,
                'description' => 'timeviewfrom field',
            ],
            'timeviewto' => [
                'type' => PARAM_INT,
                'description' => 'timeviewto field',
            ],
            'requiredentries' => [
                'type' => PARAM_INT,
                'description' => 'requiredentries field',
            ],
            'requiredentriestoview' => [
                'type' => PARAM_INT,
                'description' => 'requiredentriestoview field',
            ],
            'maxentries' => [
                'type' => PARAM_INT,
                'description' => 'maxentries field',
            ],
            'rssarticles' => [
                'type' => PARAM_INT,
                'description' => 'rssarticles field',
            ],
            'singletemplate' => [
                'type' => PARAM_RAW,
                'description' => 'singletemplate field',
                'null' => NULL_ALLOWED,
            ],
            'listtemplate' => [
                'type' => PARAM_RAW,
                'description' => 'listtemplate field',
                'null' => NULL_ALLOWED,
            ],
            'listtemplateheader' => [
                'type' => PARAM_RAW,
                'description' => 'listtemplateheader field',
                'null' => NULL_ALLOWED,
            ],
            'listtemplatefooter' => [
                'type' => PARAM_RAW,
                'description' => 'listtemplatefooter field',
                'null' => NULL_ALLOWED,
            ],
            'addtemplate' => [
                'type' => PARAM_RAW,
                'description' => 'addtemplate field',
                'null' => NULL_ALLOWED,
            ],
            'rsstemplate' => [
                'type' => PARAM_RAW,
                'description' => 'rsstemplate field',
                'null' => NULL_ALLOWED,
            ],
            'rsstitletemplate' => [
                'type' => PARAM_RAW,
                'description' => 'rsstitletemplate field',
                'null' => NULL_ALLOWED,
            ],
            'csstemplate' => [
                'type' => PARAM_RAW,
                'description' => 'csstemplate field',
                'null' => NULL_ALLOWED,
            ],
            'jstemplate' => [
                'type' => PARAM_RAW,
                'description' => 'jstemplate field',
                'null' => NULL_ALLOWED,
            ],
            'asearchtemplate' => [
                'type' => PARAM_RAW,
                'description' => 'asearchtemplate field',
                'null' => NULL_ALLOWED,
            ],
            'approval' => [
                'type' => PARAM_BOOL,
                'description' => 'approval field',
            ],
            'manageapproved' => [
                'type' => PARAM_BOOL,
                'description' => 'manageapproved field',
            ],
            'scale' => [
                'type' => PARAM_INT,
                'description' => 'scale field',
                'optional' => true,
            ],
            'assessed' => [
                'type' => PARAM_INT,
                'description' => 'assessed field',
                'optional' => true,
            ],
            'assesstimestart' => [
                'type' => PARAM_INT,
                'description' => 'assesstimestart field',
                'optional' => true,
            ],
            'assesstimefinish' => [
                'type' => PARAM_INT,
                'description' => 'assesstimefinish field',
                'optional' => true,
            ],
            'defaultsort' => [
                'type' => PARAM_INT,
                'description' => 'defaultsort field',
            ],
            'defaultsortdir' => [
                'type' => PARAM_INT,
                'description' => 'defaultsortdir field',
            ],
            'editany' => [
                'type' => PARAM_BOOL,
                'description' => 'editany field (not used any more)',
                'optional' => true,
            ],
            'notification' => [
                'type' => PARAM_INT,
                'description' => 'notification field (not used any more)',
                'optional' => true,
            ],
            'timemodified' => [
                'type' => PARAM_INT,
                'description' => 'Time modified',
                'optional' => true,
            ],
        ];
    }

    protected static function define_related() {
        return [
            'context' => 'context'
        ];
    }

    protected static function define_other_properties() {
        return [
            'coursemodule' => [
                'type' => PARAM_INT
            ],
            'introfiles' => [
                'type' => external_files::get_properties_for_exporter(),
                'multiple' => true,
                'optional' => true,
            ],
        ];
    }

    protected function get_other_values(renderer_base $output) {
        $context = $this->related['context'];

        $values = [
            'coursemodule' => $context->instanceid,
            'introfiles' => external_util::get_area_files($context->id, 'mod_data', 'intro', false, false),
        ];

        return $values;
    }

    /**
     * Get the formatting parameters for the intro.
     *
     * @return array
     */
    protected function get_format_parameters_for_intro() {
        return [
            'component' => 'mod_data',
            'filearea' => 'intro',
            'options' => ['noclean' => true],
        ];
    }
}
