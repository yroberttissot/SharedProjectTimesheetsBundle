<?php
/**
 * This file is part of the SharedProjectTimesheetsBundle for Kimai 2.
 * All rights reserved by Fabian Vetter (https://vettersolutions.de).
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

declare(strict_types=1);

namespace KimaiPlugin\SharedProjectTimesheetsBundle\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use KimaiPlugin\SharedProjectTimesheetsBundle\Model\RecordMergeMode;

final class Version2020120600000 extends AbstractMigration
{
    const SHARED_PROJECT_TIMESHEETS_TABLE_NAME = "kimai2_shared_project_timesheets";

    public function getDescription(): string
    {
        return "Create table for shared project timesheets";
    }

    public function up(Schema $schema): void
    {
        if (!$schema->hasTable(self::SHARED_PROJECT_TIMESHEETS_TABLE_NAME)) {
            $table = $schema->createTable(self::SHARED_PROJECT_TIMESHEETS_TABLE_NAME);
            $table->addColumn('id', Types::INTEGER, ['autoincrement' => true, 'notnull' => true]);
            $table->addColumn('project_id', Types::INTEGER, ['notnull' => true]);
            $table->addColumn('share_key', Types::STRING, ['length' => 20, 'notnull' => true]);
            $table->addColumn('password', Types::STRING, ['length' => 255, 'default' => null, 'notnull' => false]);
            $table->addColumn('entry_user_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);
            $table->addColumn('entry_rate_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);
            $table->addColumn('entry_budget_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);

            $table->addColumn('entry_time_budget_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);

            $table->addColumn('entry_activity_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);

            $table->addColumn('annual_chart_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);
            $table->addColumn('monthly_chart_visible', Types::BOOLEAN, ['default' => false, 'notnull' => true]);

            $table->setPrimaryKey(['id']);
            $table->addIndex(['share_key']);
            $table->addUniqueIndex(['project_id', 'share_key']);
            $table->addForeignKeyConstraint(
                'kimai2_projects',
                ['project_id'],
                ['id'],
                [
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE',
                ]
            );
            $table->addColumn(
                'record_merge_mode',
                Types::STRING,
                ['length' => 50, 'notnull' => true, 'default' => RecordMergeMode::MODE_NONE]
            );
        }
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable(self::SHARED_PROJECT_TIMESHEETS_TABLE_NAME)) {
            $schema->dropTable(self::SHARED_PROJECT_TIMESHEETS_TABLE_NAME);
        }
    }
}
