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
 * Strings for the quizaccess_offlinemode plugin.
 *
 * @package   quizaccess_offlinemode
 * @copyright 2014 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['backtothequiz'] = 'Back to the quiz';
$string['dataprocessedsuccessfully'] = 'Data processed successfully ({$a}).';
$string['description'] = 'This quiz uses offline mode. Adminstrators can {$a}';
$string['descriptionlink'] = 'upload exported responses';
$string['downloadlink'] = 'Emergency response export';
$string['offlinemodeenabled'] = 'Experimental offline attempt mode';
$string['offlinemodeenabled_desc'] = 'Whether the offline attempt mode should be enabled by default for new quizzes, and also whether it should be an advanced settings (behind \'Show more ...\') on the quiz settings form.';
$string['offlinemodeenabled_help'] = 'The goal of this experimental option is to let students attempt a quiz even if the network connection is not reliable. For example on a train going through tunnels, or just with bad wi-fi. The students can move between pages of the quiz even if the server is not avaialble, and all their answers are stored locally, and sent to the server when possible.';
$string['offlinemode:uploadresponses'] = 'Upload responses';
$string['pluginname'] = 'Quiz offline attempt mode';
$string['privatekey'] = 'Encryption private key';
$string['privatekey_desc'] = 'You can use public-key cryptography to protect the downloaded responses. To do that, you need to supply a private/public key pair. You can generate a private key using <code>openssl genrsa -out rsa_1024_priv.pem 1024</code> at the command-line (if you have OpenSSL installed from https://www.openssl.org/). Then paste the content of the rsa_1024_priv.pem file into this box.';
$string['processingcomplete'] = 'Processing complete';
$string['processingfile'] = 'Processing file {$a}';
$string['publickey'] = 'Encryption public key';
$string['publickey_desc'] = 'This must correspond to the private key. You can generate it from the private key using <code>openssl rsa -pubout -in rsa_1024_priv.pem -out rsa_1024_pub.pem</code> then past the contents of rsa_1024_pub.pem here.';
$string['responsefiles'] = 'Response files';
$string['responsefiles_help'] = 'You can upload one or more response files downloaded using the \'Emergency response export\' link during a quiz attempt.';
$string['reviewthisattempt'] = 'Review this attempt';
$string['uploadfailed'] = 'The upload failed';
$string['uploadingresponsesfor'] = 'Uploading responses for {$a}';
$string['uploadmoreresponses'] = 'Upload more responses';
$string['uploadresponses'] = 'Upload responses';
$string['uploadresponsesfor'] = 'Upload responses for {$a}';
