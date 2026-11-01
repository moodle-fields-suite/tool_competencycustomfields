<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace tool_competencycustomfields;

use core\hook\output\after_standard_main_region_html_generation;
use local_fieldssuite\local\display;
use tool_competencycustomfields\customfield\competency_handler;
use tool_competencycustomfields\customfield\framework_handler;

/**
 * Hook callbacks of tool_competencycustomfields.
 *
 * @package    tool_competencycustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Shows the values, and the link to edit them, on the pages of a competency and of a framework.
     *
     * - Edit competency page: values of the competency.
     * - Edit framework page and framework page (competency tree): values of the framework.
     *
     * @param after_standard_main_region_html_generation $hook
     */
    public static function after_standard_main_region_html_generation(after_standard_main_region_html_generation $hook): void {
        global $PAGE;
        if (!$PAGE->has_set_url()) {
            return;
        }
        switch ($PAGE->pagetype) {
            case 'admin-tool-lp-editcompetency':
                $id = (int)$PAGE->url->get_param('id');
                if ($id) {
                    display::add_to_page($hook, competency_handler::create(), $id, 'h2', true);
                }
                break;
            case 'admin-tool-lp-editcompetencyframework':
                $id = (int)$PAGE->url->get_param('id');
                if ($id) {
                    display::add_to_page($hook, framework_handler::create(), $id, 'h2', true);
                }
                break;
            case 'admin-tool-lp-competencies':
                $id = (int)$PAGE->url->get_param('competencyframeworkid');
                if ($id) {
                    display::add_to_page($hook, framework_handler::create(), $id, '', true);
                }
                break;
        }
    }
}
