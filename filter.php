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
 * Basic email protection filter.
 *
 * @package    filter
 * @subpackage hearthrating
 * @author     Lukas Celinak <lukascelinak@gmail.com>
 * @copyright  2022 Lukas Celinak, Edumood, <lukascelinak@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
/**
 * This filter looks for content tags in Moodle text and
 * replaces them with defined Hearth Rating Prompt.
 * @see filter_manager::apply_filter_chain()
 */
class filter_hearthrating extends moodle_text_filter {

    /**
     *
     */
    const PLACEHOLDER_PATTERN = '/\{\{\bPROMPT\b:[a-zA-Z0-9_]+\}\}/';

    /**
     * This function looks for tags in Moodle text and
     * replaces them with prompt from Hearth Rating Prompts.
     * Tags have the format {{PROMPT:xxx}} where:
     *          - xxx is is unique idnumber of Hearth Rating Prompt [shortname]
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    function filter($text, array $options = array()) {
        global $PAGE;
        // Basic test to avoid work
        if (!($matches = $this->get_matches($text))) {
            return $text;
        }

        $def_config = get_config('filter_hearthrating');
        $starttag = $def_config->starttag;
        $endtag = $def_config->endtag;

        list($context, $course, $cm) = get_context_info_array($this->context->id);

        if (!$cm) {
            return $text;
        }

        // There may be a tag in here somewhere so continue
        // Get the contents and positions in the text and call the
        // renderer to deal with them
        return $this->insert_content($text, $cm,$matches,$starttag,$endtag);
    }

    /**
     * Looks for placeholders.
     *
     * @param string $text
     * @return null|array
     */
    private function get_matches(string $text) {
        if (!is_string($text) || empty($text) || strpos($text, '{{PROMPT:') === false ||
            !preg_match_all(self::PLACEHOLDER_PATTERN, $text, $matches)) {
            return null;
        }
        return $matches;
    }

    /**
     * Insert hearth rating prompt upon a match
     *
     * @param $str
     * @param $cm
     * @param $matches
     * @return void
     */
    function insert_content($text, $cm, $matches,$starttag,$endtag) {
        global $OUTPUT;
        $newstring=$text;
        foreach ($matches as $match){
            $initpos = strpos($newstring, $starttag);
            if ($initpos !== false) {
                $pos = $initpos + strlen($starttag);  // get up to string
                $endpos = strpos($newstring, $endtag);
                $content = substr($newstring, $pos, $endpos - $pos); // extract content
                // Clean the string
                $promptidnumber = filter_var($content, FILTER_SANITIZE_STRING);

                $prompt = new \mod_hearthrating\output\prompt($cm, $promptidnumber);
                $data = $prompt->export_for_template($OUTPUT);
                return $OUTPUT->render_from_template('mod_hearthrating/prompt', $data);
            }
        }
    }
}
