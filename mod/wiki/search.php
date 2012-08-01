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
 * @package mod-wiki
 * @copyright 2010 Dongsheng Cai <dongsheng@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/mod/wiki/lib.php');
require_once($CFG->dirroot . '/mod/wiki/locallib.php');
require_once($CFG->dirroot . '/mod/wiki/pagelib.php');

$search = optional_param('searchstring', null, PARAM_ALPHANUMEXT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$searchcontent = optional_param('searchwikicontent', 0, PARAM_INT);
$cmid = optional_param('cmid', 0, PARAM_INT);
$swid = optional_param('swid', 0, PARAM_INT);

if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourseid');
}
if (!$cm = get_coursemodule_from_id('wiki', $cmid)) {
    print_error('invalidcoursemodule');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/wiki:viewpage', $context, NULL, true, 'noviewpagepermission', 'wiki');

$returnurl = new moodle_url('/mod/wiki/view.php', array('id' => $cm->id));

if (!$wiki = wiki_get_wiki($cm->instance)) {
    print_error('incorrectwikiid', 'wiki');
}

if (empty($swid)) {
    notice(get_string('nosubwiki', 'wiki'), $returnurl);
} else if (!$subwiki = wiki_get_subwiki($swid)) {
    print_error('incorrectsubwikiid', 'wiki');
}

if (!wiki_user_can_view($subwiki)) {
    notice(get_string('searcherror', 'wiki'), $returnurl);
}

$wikipage = new page_wiki_search($wiki, $subwiki, $cm);

$wikipage->set_search_string($search, $searchcontent);

$wikipage->set_title(get_string('search'));

$wikipage->print_header();

$wikipage->print_content();

$wikipage->print_footer();
