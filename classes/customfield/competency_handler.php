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

namespace tool_competencycustomfields\customfield;

use core_competency\api;
use core_competency\competency;
use local_fieldssuite\customfield\area_handler;

/**
 * Custom field handler for competencies.
 *
 * The instance id is the competency id; the values live in the context of its framework.
 *
 * @package    tool_competencycustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class competency_handler extends area_handler {
    /**
     * Capabilities of the area.
     *
     * @param string $type One of the CAPABILITY_* constants.
     * @return string
     */
    public function get_capability(string $type): string {
        return [
            self::CAPABILITY_CONFIGURE => 'tool/competencycustomfields:configurecustomfields',
            self::CAPABILITY_EDIT => 'tool/competencycustomfields:edit',
            self::CAPABILITY_CHANGELOCKED => 'tool/competencycustomfields:changelockedcustomfields',
        ][$type];
    }

    /**
     * Admin page that manages the fields.
     *
     * @return string
     */
    public function get_admin_section(): string {
        return 'tool_competencycustomfields_competency';
    }

    /**
     * Context of the framework of a competency.
     *
     * @param int $instanceid Competency id.
     * @return \context
     */
    public function get_instance_context(int $instanceid = 0): \context {
        if ($instanceid > 0 && ($competency = competency::get_record(['id' => $instanceid]))) {
            return $competency->get_context();
        }
        return \context_system::instance();
    }

    /**
     * A competency the current user can see.
     *
     * @param int $instanceid Competency id.
     * @return \stdClass|null
     */
    public function get_instance_info(int $instanceid): ?\stdClass {
        try {
            $competency = api::read_competency($instanceid);
        } catch (\moodle_exception $e) {
            return null;
        }
        $context = $competency->get_context();
        return (object)[
            'name' => format_string($competency->get('shortname'), true, ['context' => $context]),
            'url' => new \moodle_url('/admin/tool/lp/competencies.php', [
                'competencyframeworkid' => $competency->get('competencyframeworkid'),
                'pagecontextid' => $context->id,
            ]),
            'context' => $context,
        ];
    }

    /**
     * The edit page belongs to the competency: competency navigation of tool_lp.
     *
     * @param int $instanceid Competency id.
     * @param \moodle_url $url
     */
    public function set_up_edit_page(int $instanceid, \moodle_url $url): void {
        global $PAGE;
        $competency = api::read_competency($instanceid);
        $framework = api::read_framework($competency->get('competencyframeworkid'));
        \tool_lp\page_helper::setup_for_competency($competency->get_context()->id, $url, $framework, $competency);
        $PAGE->set_url($url);
    }
}
