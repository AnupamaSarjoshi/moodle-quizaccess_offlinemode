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
 * Script to upload responses saved from the emergency download link.
 *
 * @package   quizaccess_offlinemode
 * @copyright 2014 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../config.php');
require_once($CFG->dirroot . '/repository/lib.php');
require_once($CFG->dirroot . '/mod/quiz/accessrule/offlinemode/lib/LZString.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

$cmid = optional_param('id', 0, PARAM_INT);
list($course, $cm) = get_course_and_cm_from_cmid($cmid, 'quiz');
$quiz = $DB->get_record('quiz', array('id' => $cm->instance), '*', MUST_EXIST);
$quizurl = new moodle_url('/mod/quiz/view.php', array('id' => $cm->id));
$context = context_module::instance($cm->id);

$PAGE->set_url('/mod/quiz/accessrule/offlinemode/upload.php', array('id' => $cmid));
require_login($course, false, $cm);
require_capability('quizaccess/offlinemode:uploadresponses', $context);

$form = new \quizaccess_offlinemode\form\upload_responses($PAGE->url);
if ($form->is_cancelled()) {
    redirect($quizurl);

} else if ($fromform = $form->get_data()) {

    // Process submission.
    $title = get_string('uploadingresponsesfor', 'quizaccess_offlinemode',
            format_string($quiz->name, true, array('context' => $context)));
    $PAGE->navbar->add($title);
    $PAGE->set_pagelayout('admin');
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);

    $files = get_file_storage()->get_area_files(context_user::instance($USER->id)->id,
            'user', 'draft', $fromform->responsefiles, 'id');
    $filesprocessed = 0;

    $key = null;
    $privatekey = get_config('quizaccess_offlinemode', 'privatekey');
    if ($privatekey) {
        $key = openssl_get_privatekey($privatekey);
    }

    echo $OUTPUT->header();
    echo $OUTPUT->heading($title);

    foreach ($files as $file) {
        if ($file->get_filepath() !== '/') {
            continue; // Should not happen due to form validation.
        }
        if ($file->is_external_file()) {
            continue; // Should not happen due to form validation.
        }

        if ($file->is_directory()) {
            continue; // Not interesting.
        }

        echo $OUTPUT->heading(get_string('processingfile', 'quizaccess_offlinemode', s($file->get_filename())), 3);

        $originalpost = null;
        try {
            $rawdata = json_decode($file->get_content());
            echo html_writer::tag('textarea', s(print_r($rawdata, true)), array('readonly' => 'readonly'));

            if (!$rawdata) {
                if (function_exists('')) {
                    throw new coding_exception(json_last_error_msg());
                } else {
                    throw new coding_exception('JSON error: ' . json_last_error());
                }
            }
            if (!isset($rawdata->responses)) {
                throw new coding_exception('This file does not appear to contain responses.');
            }

            if (isset($rawdata->envelope)) {
                if (!$key) {
                    throw new coding_exception('Got apparently encrypted responses, but there is no decryption key.');
                }
                $responses = '';
                $ok = openssl_open(base64_decode($rawdata->responses), $responses,
                        base64_decode($rawdata->envelope), $key);
                if (!$ok) {
                    throw new coding_exception(openssl_error_string());
                }

            } else {
                $responses = $rawdata->responses;
            }

            $postdata = array();
            parse_str($rawdata, $postdata);
            if (!isset($postdata['attempt'])) {
                throw new coding_exception('The uploaded data did not include an attempt id.');
            }

            echo html_writer::tag('textarea', s(print_r($postdata, true)), array('readonly' => 'readonly'));

            $attemptobj = quiz_attempt::create($postdata['attempt']);
            if ($attemptobj->get_cmid() != $cmid) {
                throw new coding_exception('The uploaded data does not belong to this quiz.');
            }

            $originalpost = $_POST;
            $_POST = $postdata;
            $attemptobj->process_submitted_actions(time());
            $_POST = $originalpost;
            $originalpost = null;
            echo $OUTPUT->notification('Data processed successfully', 'notifysuccess');

        } catch (Exception $e) {
            if ($originalpost !== null) {
                $_POST = $originalpost;
            }
            echo $OUTPUT->box_start();
            echo $OUTPUT->heading('The upload failed', 4);
            echo $OUTPUT->notification($e->getMessage());
            echo format_backtrace($e->getTrace());
            echo $OUTPUT->box_end();
        }
    }
    openssl_pkey_free($key);

    echo $OUTPUT->confirm(get_string('processingcomplete', 'quizaccess_offlinemode', 3),
            new single_button($PAGE->url, get_string('uploadmoreresponses', 'quizaccess_offlinemode'), 'get'),
            new single_button($quizurl, get_string('backtothequiz', 'quizaccess_offlinemode'), 'get'));
    echo $OUTPUT->footer();

} else {

    // Show the form.
    $title = get_string('uploadresponsesfor', 'quizaccess_offlinemode',
            format_string($quiz->name, true, array('context' => $context)));
    $PAGE->navbar->add($title);
    $PAGE->set_pagelayout('admin');
    $PAGE->set_title($title);
    $PAGE->set_heading($course->fullname);

    echo $OUTPUT->header();
    echo $OUTPUT->heading($title);
    $form->display();
    echo $OUTPUT->footer();

}