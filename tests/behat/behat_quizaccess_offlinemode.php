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
 * Steps definitions related to quizaccess_offlinemode.
 *
 * @package   quizaccess_offlinemode
 * @category  test
 * @copyright 2014 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Step\Given,
    Behat\Mink\Exception\ExpectationException;

/**
 * Steps definitions related to quizaccess_offlinemode.
 *
 * @copyright 2014 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_quizaccess_offlinemode extends behat_question_base {
    /** @var string path to where the downloaded responses were stored. */
    protected $downloadedfile = null;

    /**
     * On the attempt page, simulate clicking the download link to get the responses.
     * @When /^I follow the emergency download link$/
     */
    public function i_follow_the_emergency_download_link() {
        $session = $this->getSession();

        $session->evaluateScript('
                window.behat_quizaccess_offlinemode_click_handler = function(e) {
                    e.preventDefault();
                };
                document.body.addEventListener("click", window.behat_quizaccess_offlinemode_click_handler);');

        $linknode = $this->find_link(get_string('downloadlink', 'quizaccess_offlinemode'));
        $this->ensure_node_is_visible($linknode);
        $linknode->click();

        $session->evaluateScript('
                document.body.removeEventListener("click", window.behat_quizaccess_offlinemode_click_handler);');

        // Get the link again, or we won't seen the new URL.
        $linknode = $this->find_link(get_string('downloadlink', 'quizaccess_offlinemode'));
        $url = $linknode->getAttribute('href');
        if (substr($url, 0, 30) !== 'data:application/octet-stream,') {
            throw new ExpectationException('Expected the URL to look like a data URL, but it didn\'t.', $session);
        }

        $tempdir = make_temp_directory('behat_quizaccess_offlinemode');
        $this->downloadedfile = tempnam($tempdir, 'download');
        file_put_contents($this->downloadedfile, substr($url, 30));
    }

    /**
     * Upload the responses previously saved above, to a given filepicker.
     * @Given /^I upload the saved responses file to "([^"]*)" filemanager$/
     */
    public function i_upload_the_saved_responses_file_to_filemanager($fieldlabel) {
        $session = $this->getSession();

        if ($this->downloadedfile === null) {
            throw new ExpectationException('No responses downloaded yet, so we can\'t upload them.', $session);
        }

        $uploadcontext = behat_context_helper::get('behat_repository_upload');
        $uploadcontext->i_upload_file_to_filemanager($this->downloadedfile, $fieldlabel);
    }
}
