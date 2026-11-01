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

use core_competency\api;
use core_customfield\field_controller;
use local_fieldssuite\external\get_values;
use local_fieldssuite\local\display;
use tool_competencycustomfields\customfield\competency_handler;
use tool_competencycustomfields\customfield\framework_handler;

/**
 * Tests for the competency and framework custom field areas.
 *
 * @package    tool_competencycustomfields
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \tool_competencycustomfields\customfield\competency_handler
 * @covers     \tool_competencycustomfields\customfield\framework_handler
 * @covers     \tool_competencycustomfields\observer
 */
final class plugin_test extends \advanced_testcase {
    /**
     * Creates a text field of an area.
     *
     * @param string $area 'competency' or 'framework'.
     * @param string $shortname
     * @return field_controller
     */
    private function create_field(string $area, string $shortname): field_controller {
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        $category = $generator->create_category(['component' => 'tool_competencycustomfields', 'area' => $area]);
        return $generator->create_field([
            'categoryid' => $category->get('id'),
            'shortname' => $shortname,
            'name' => ucfirst($shortname),
            'type' => 'text',
        ]);
    }

    /**
     * Competencies and frameworks give their name, URL and the framework context.
     */
    public function test_instance_info(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        /** @var \core_competency_generator $lpg */
        $lpg = $this->getDataGenerator()->get_plugin_generator('core_competency');
        $category = $this->getDataGenerator()->create_category();
        $catcontext = \context_coursecat::instance($category->id);
        $framework = $lpg->create_framework(['contextid' => $catcontext->id, 'shortname' => 'Digital skills']);
        $competency = $lpg->create_competency(['competencyframeworkid' => $framework->get('id'), 'shortname' => 'Spreadsheets']);

        $info = competency_handler::create()->get_instance_info($competency->get('id'));
        $this->assertSame('Spreadsheets', $info->name);
        $this->assertEquals($catcontext->id, $info->context->id);
        $this->assertEquals($framework->get('id'), $info->url->get_param('competencyframeworkid'));
        $this->assertEquals($catcontext->id, competency_handler::create()->get_instance_context($competency->get('id'))->id);

        $info = framework_handler::create()->get_instance_info($framework->get('id'));
        $this->assertSame('Digital skills', $info->name);
        $this->assertEquals($catcontext->id, $info->context->id);

        $this->assertNull(competency_handler::create()->get_instance_info(999999));
        $this->assertNull(framework_handler::create()->get_instance_info(999999));
        $this->assertEquals(\context_system::instance()->id, competency_handler::create()->get_instance_context(999999)->id);
    }

    /**
     * Values are saved in the framework context, shown with the edit link and returned by the web service.
     */
    public function test_values(): void {
        $this->resetAfterTest();
        $this->setAdminUser();
        /** @var \core_competency_generator $lpg */
        $lpg = $this->getDataGenerator()->get_plugin_generator('core_competency');
        $framework = $lpg->create_framework();
        $competency = $lpg->create_competency(['competencyframeworkid' => $framework->get('id')]);
        $this->create_field('competency', 'level');
        $this->create_field('framework', 'owner');

        competency_handler::create()->instance_form_save(
            (object)['id' => $competency->get('id'), 'customfield_level' => 'Advanced']
        );
        framework_handler::create()->instance_form_save((object)['id' => $framework->get('id'), 'customfield_owner' => 'IT team']);

        $html = display::render_values(competency_handler::create(), $competency->get('id'), true);
        $this->assertStringContainsString('Advanced', $html);
        $this->assertStringContainsString('area=competency', $html);

        $result = get_values::get_instances_values(framework_handler::create(), [$framework->get('id')]);
        $this->assertSame('IT team', $result['instances'][0]['fields'][0]['value']);
    }

    /**
     * Deleting a competency or a framework deletes its values.
     */
    public function test_deletion(): void {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();
        /** @var \core_competency_generator $lpg */
        $lpg = $this->getDataGenerator()->get_plugin_generator('core_competency');
        $framework = $lpg->create_framework();
        $first = $lpg->create_competency(['competencyframeworkid' => $framework->get('id')]);
        $second = $lpg->create_competency(['competencyframeworkid' => $framework->get('id')]);
        $level = $this->create_field('competency', 'level');
        $owner = $this->create_field('framework', 'owner');
        /** @var \core_customfield_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('core_customfield');
        $generator->add_instance_data($level, $first->get('id'), 'Basic');
        $generator->add_instance_data($level, $second->get('id'), 'Advanced');
        $generator->add_instance_data($owner, $framework->get('id'), 'IT team');

        api::delete_competency($first->get('id'));
        $this->assertFalse($DB->record_exists('customfield_data', [
            'fieldid' => $level->get('id'),
            'instanceid' => $first->get('id'),
        ]));
        $this->assertTrue($DB->record_exists('customfield_data', [
            'fieldid' => $level->get('id'),
            'instanceid' => $second->get('id'),
        ]));

        api::delete_framework($framework->get('id'));
        $this->assertFalse($DB->record_exists('customfield_data', ['fieldid' => $level->get('id')]));
        $this->assertFalse($DB->record_exists('customfield_data', ['fieldid' => $owner->get('id')]));
    }
}
