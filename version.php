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
 * Version details
 *
 * @package    filter
 * @subpackage hearthrating
 * @author     Lukas Celinak <lukascelinak@gmail.com>
 * @copyright  2022 Lukas Celinak, Edumood, <lukascelinak@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/** @var core\plugininfo\ $plugin */
$plugin->component = 'filter_ratingprompts';
$plugin->version   = 2022061800;
$plugin->requires  = 2017051500;
$plugin->maturity = MATURITY_ALPHA;
$plugin->dependencies = array(
    'mod_ratingprompts' => ANY_VERSION,
);
$plugin->release = 'v1.0'; // updated settings
