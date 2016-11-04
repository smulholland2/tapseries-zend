<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * A migration class. It either upgrades the databases schema (moves it to new state)
 * or downgrades it to the previous state.
 */
class Version20160901114938 extends AbstractMigration
{
    /**
     * Returns the description of this migration.
     */
    public function getDescription()
    {
        $description = 'This migration adds indexes and foreign key constraints.';
        return $description;
    }
    
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Add index to page table
        $table = $schema->getTable('page');
        $table->addIndex(['date_created'], 'date_created_index');
        
        // Add index and foreign key to comment table
        $table = $schema->getTable('comment');
        $table->addIndex(['page_id'], 'page_id_index');
        $table->addForeignKeyConstraint('page', ['page_id'], ['id'], [], 'comment_page_id_fk');
        
        // Add indexes and foreign keys to page_tag table
        $table = $schema->getTable('page_tag');
        $table->addIndex(['page_id'], 'page_id_index');
        $table->addIndex(['tag_id'], 'tag_id_index');
        $table->addForeignKeyConstraint('page', ['page_id'], ['id'], [], 'page_tag_page_id_fk');
        $table->addForeignKeyConstraint('tag', ['tag_id'], ['id'], [], 'page_tag_tag_id_fk');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $table = $schema->getTable('page_tag');
        $table->removeForeignKey('page_tag_page_id_fk');
        $table->removeForeignKey('page_tag_tag_id_fk');
        $table->dropIndex('page_id_index');
        $table->dropIndex('tag_id_index'); 
        
        $table = $schema->getTable('comment');
        $table->dropIndex('page_id_index'); 
        $table->removeForeignKey('comment_page_id_fk');
        
        $table = $schema->getTable('page');
        $table->dropIndex('date_created_index');       
    }
}
