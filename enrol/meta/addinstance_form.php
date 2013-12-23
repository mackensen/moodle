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
 * Adds instance form
 *
 * @package    enrol_meta
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class enrol_meta_addinstance_form extends moodleform {
    protected $course;

    /** @var array Included javascript for course selector. */
    private static $jsmodule = array(
        'name' => 'course_selector',
        'fullpath' => '/enrol/meta/module.js',
        'requires' => array('node', 'event-custom', 'datasource', 'json'));

    function definition() {
        global $CFG, $DB, $PAGE;

        $mform  = $this->_form;
        $course = $this->_customdata;
        $this->course = $course;

        $mform->disable_form_change_checker();

        $searchtext = optional_param('link_searchtext', '', PARAM_TEXT);
        $result  = enrol_meta_course_search($course->id, $searchtext, true);
        $display = array();
        foreach ($result->results->display as $item) {
            $display[$item->courseid] = $item->name;
        }
        $listdata = array($result->results->label => $display);

        $mform->addElement('header','general', get_string('pluginname', 'enrol_meta'));
        $mform->addElement('selectgroups', 'link', '', $listdata, array('size' => 10, 'multiple' => true));
        $mform->addElement('submit', 'link_submitbutton', get_string('linkselected', 'enrol_meta'));

        $searchgroup = array();
        $searchgroup[] = &$mform->createElement('text', 'link_searchtext');
        $mform->setType('link_searchtext', PARAM_TEXT);
        $searchgroup[] = &$mform->createElement('submit', 'link_searchbutton', get_string('search'));
        $mform->registerNoSubmitButton('link_searchbutton');
        $searchgroup[] = &$mform->createElement('submit', 'link_clearbutton', get_string('clear'));
        $mform->registerNoSubmitButton('link_clearbutton');
        $mform->addGroup($searchgroup, 'searchgroup', get_string('search') , array(''), false);

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('static', 'cancel', html_writer::link(
            new moodle_url('/enrol/instances.php', array('id' => $course->id)), get_string('cancel'))
        );
        $mform->closeHeaderBefore('cancel');

        $this->set_data(array('id'=>$course->id));

        $PAGE->requires->js_init_call('M.core_enrol.init_course_selector', array('link', $course->id), true, self::$jsmodule);
    }

    function validation($data, $files) {
        global $DB, $CFG;
        $errors = array();
        return $errors;
    }
}
