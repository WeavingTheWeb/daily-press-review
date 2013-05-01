<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130424101225 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_book (bok_id INT AUTO_INCREMENT NOT NULL, bok_status TINYINT(1) NOT NULL, bok_isbn VARCHAR(32) NOT NULL, bok_feed LONGTEXT NOT NULL, PRIMARY KEY(bok_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_folder (fld_id INT AUTO_INCREMENT NOT NULL, fld_status TINYINT(1) NOT NULL, fld_type TINYINT(1) NOT NULL, fld_name VARCHAR(255) NOT NULL, fld_path VARCHAR(255) NOT NULL, PRIMARY KEY(fld_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_github (id INT AUTO_INCREMENT NOT NULL, rep_github_id INT NOT NULL, rep_forks INT NOT NULL, rep_watchers INT NOT NULL, rep_status INT NOT NULL, rep_owner_id INT NOT NULL, rep_owner VARCHAR(255) NOT NULL, rep_language VARCHAR(255) NOT NULL, rep_name VARCHAR(255) NOT NULL, rep_avatar_url VARCHAR(255) NOT NULL, rep_clone_url VARCHAR(255) NOT NULL, rep_description LONGTEXT NOT NULL, rep_created_at DATETIME NOT NULL, rep_updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_github_starred_repositories (jsn_id INT AUTO_INCREMENT NOT NULL, jsn_status TINYINT(1) DEFAULT '0' NOT NULL, jsn_type TINYINT(1) NOT NULL, jsn_hash VARCHAR(32) NOT NULL, jsn_value LONGTEXT NOT NULL, UNIQUE INDEX jsn_hash (jsn_hash), INDEX jsn_status (jsn_status, jsn_type), PRIMARY KEY(jsn_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_json_github (jsn_id INT AUTO_INCREMENT NOT NULL, jsn_status TINYINT(1) DEFAULT '0' NOT NULL, jsn_type TINYINT(1) NOT NULL, jsn_hash VARCHAR(32) NOT NULL, jsn_value LONGTEXT NOT NULL, UNIQUE INDEX jsn_hash (jsn_hash), INDEX jsn_status (jsn_status, jsn_type), PRIMARY KEY(jsn_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_json_twitter (jsn_id INT AUTO_INCREMENT NOT NULL, jsn_status TINYINT(1) DEFAULT '0' NOT NULL, jsn_type TINYINT(1) NOT NULL, jsn_hash VARCHAR(32) NOT NULL, jsn_value LONGTEXT NOT NULL, UNIQUE INDEX jsn_hash (jsn_hash), INDEX jsn_status (jsn_status, jsn_type), PRIMARY KEY(jsn_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_query (qry_id INT AUTO_INCREMENT NOT NULL, qry_status INT NOT NULL, qry_type INT NOT NULL, qry_value LONGTEXT NOT NULL, qry_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, qry_date_update DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL, INDEX query (qry_value), PRIMARY KEY(qry_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE IF NOT EXISTS weaving_status (id BIGINT DEFAULT NULL, message VARCHAR(255) DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE weaving_arc CHANGE arc_status arc_status TINYINT(1) DEFAULT '1' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX arc_type ON weaving_arc (arc_type, arc_source, arc_destination)");
        $this->addSql("CREATE INDEX etp_id ON weaving_arc (arc_type)");
        $this->addSql("CREATE UNIQUE INDEX author_id ON weaving_author (author_id)");
        $this->addSql("ALTER TABLE weaving_contact CHANGE cnt_status cnt_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE cnt_type cnt_type TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX cnt_value ON weaving_contact (cnt_value)");
        $this->addSql("ALTER TABLE weaving_content CHANGE ctt_status ctt_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE ctt_type ctt_type TINYINT(1) DEFAULT '0' NOT NULL, CHANGE ctt_date_creation ctt_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE ctt_date_modification ctt_date_modification DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL, CHANGE ctt_date_deletion ctt_date_deletion DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX rte_id ON weaving_content (rte_id)");
        $this->addSql("CREATE UNIQUE INDEX title ON weaving_content (ctt_title)");
        $this->addSql("ALTER TABLE weaving_content_type CHANGE cty_id cty_id INT NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX cty_id ON weaving_content_type (cty_id)");
        $this->addSql("ALTER TABLE weaving_edge CHANGE edg_status edg_status TINYINT(1) DEFAULT '1' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX edg_type ON weaving_edge (ety_id, edg_key)");
        $this->addSql("ALTER TABLE weaving_entity CHANGE ety_date_creation ety_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE ety_date_modification ety_date_modification DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX ety_name ON weaving_entity (ety_name)");
        $this->addSql("CREATE UNIQUE INDEX ett_table_name ON weaving_entity_table (ett_table_name)");
        $this->addSql("CREATE UNIQUE INDEX ett_table_alias ON weaving_entity_table (ett_table_alias)");
        $this->addSql("CREATE UNIQUE INDEX ett_column_prefix ON weaving_entity_table (ett_column_prefix)");
        $this->addSql("CREATE INDEX ety_id ON weaving_entity_table (ety_id)");
        $this->addSql("ALTER TABLE weaving_entity_type CHANGE etp_status etp_status TINYINT(1) DEFAULT '1' NOT NULL COMMENT 'active or not', CHANGE etp_value etp_value VARCHAR(100) DEFAULT '0' NOT NULL COMMENT 'value corresponding to an entity type'");
        $this->addSql("CREATE UNIQUE INDEX type ON weaving_entity_type (ety_id, etp_name, etp_value)");
        $this->addSql("ALTER TABLE weaving_event CHANGE evt_occurrence evt_occurrence INT DEFAULT 1, CHANGE evt_date evt_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
        $this->addSql("CREATE INDEX evt_source ON weaving_event (evt_source)");
        $this->addSql("ALTER TABLE weaving_feed CHANGE fd_id fd_id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE fd_date_creation fd_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE fd_date_publication fd_date_publication DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX identifier ON weaving_feed (fd_index, fd_parent_id)");
        $this->addSql("CREATE INDEX fd_status ON weaving_feed (fd_status)");
        $this->addSql("ALTER TABLE weaving_feedback CHANGE fdb_status fdb_status TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX fdb_hash ON weaving_feedback (fdb_hash)");
        $this->addSql("ALTER TABLE weaving_file CHANGE fil_status fil_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE fil_size fil_size INT DEFAULT 0 NOT NULL, CHANGE fil_type fil_type TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX fil_path ON weaving_file (fil_path)");
        $this->addSql("CREATE INDEX fil_name ON weaving_file (fil_name)");
        $this->addSql("CREATE INDEX fld_id ON weaving_file (fld_id)");
        $this->addSql("ALTER TABLE weaving_flag CHANGE flg_date_creation flg_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
        $this->addSql("ALTER TABLE weaving_form CHANGE prv_id prv_id INT DEFAULT 2, CHANGE frm_status frm_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE frm_type frm_type INT DEFAULT 0 NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX frm_identifier ON weaving_form (frm_identifier)");
        $this->addSql("CREATE INDEX rte_id ON weaving_form (rte_id)");
        $this->addSql("CREATE INDEX str_id ON weaving_form (str_id)");
        $this->addSql("CREATE INDEX prv_id ON weaving_form (prv_id)");
        $this->addSql("ALTER TABLE weaving_github_repositories CHANGE rep_updated_at rep_updated_at DATETIME NOT NULL");
        $this->addSql("CREATE INDEX rep_github_id ON weaving_github_repositories (rep_github_id)");
        $this->addSql("CREATE INDEX id ON weaving_github_repositories (id, rep_github_id)");
        $this->addSql("ALTER TABLE weaving_header CHANGE hdr_date_creation hdr_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE hdr_date_update hdr_date_update DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX hdr_imap_uid ON weaving_header (hdr_imap_uid)");
        $this->addSql("CREATE INDEX rcl_id ON weaving_header (rcl_id)");
        $this->addSql("CREATE INDEX cnt_id ON weaving_header (cnt_id)");
        $this->addSql("ALTER TABLE weaving_insight CHANGE isg_status isg_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE ety_id ety_id TINYINT(1) DEFAULT '0' NOT NULL, CHANGE isg_date_creation isg_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE isg_date_modification isg_date_modification DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("ALTER TABLE weaving_insight_moderation CHANGE imo_status imo_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE imo_date_creation imo_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE imo_date_modification imo_date_modification DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE INDEX isn_id ON weaving_insight_moderation (isn_id)");
        $this->addSql("ALTER TABLE weaving_insight_node CHANGE isn_status isn_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE isn_type isn_type TINYINT(1) DEFAULT '0' NOT NULL, CHANGE isn_date_creation isn_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE isn_date_modification isn_date_modification DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX isn_id ON weaving_insight_node (isn_id, isg_id)");
        $this->addSql("CREATE INDEX isg_id ON weaving_insight_node (isg_id)");
        $this->addSql("ALTER TABLE weaving_insight_sharing CHANGE ish_status ish_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE ish_type ish_type TINYINT(1) DEFAULT '0' NOT NULL, CHANGE ish_date_creation ish_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE ish_date_modification ish_date_modification DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE INDEX isn_id ON weaving_insight_sharing (isn_id)");
        $this->addSql("ALTER TABLE weaving_json CHANGE jsn_status jsn_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE jsn_type jsn_type TINYINT(1) NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX jsn_hash ON weaving_json (jsn_hash)");
        $this->addSql("CREATE INDEX jsn_status ON weaving_json (jsn_status, jsn_type)");
        $this->addSql("ALTER TABLE weaving_language CHANGE lang_status lang_status TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("ALTER TABLE weaving_language_item CHANGE lgi_status lgi_status TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX nsp_id ON weaving_language_item (nsp_id, lgi_name, lang_id)");
        $this->addSql("ALTER TABLE weaving_link CHANGE lnk_status lnk_status INT DEFAULT 0 NOT NULL, CHANGE lnk_date_creation lnk_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE lnk_date_update lnk_date_update DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("ALTER TABLE weaving_location CHANGE latitude latitude NUMERIC(10, 3) NOT NULL, CHANGE longitude longitude NUMERIC(10, 3) NOT NULL");
        $this->addSql("ALTER TABLE weaving_log CHANGE log_occurrence log_occurrence INT DEFAULT 1 NOT NULL, CHANGE log_creation_date log_creation_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE log_update_date log_update_date DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE INDEX ent_id ON weaving_log (ent_id)");
        $this->addSql("ALTER TABLE weaving_message CHANGE msg_type msg_type INT DEFAULT 0 NOT NULL");
        $this->addSql("CREATE INDEX hdr_id ON weaving_message (hdr_id)");
        $this->addSql("ALTER TABLE weaving_namespace CHANGE nsp_status nsp_status TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("ALTER TABLE weaving_outgoing CHANGE out_date_creation out_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
        $this->addSql("ALTER TABLE weaving_perspective DROP per_description, CHANGE per_status per_status INT NOT NULL, CHANGE per_type per_type INT NOT NULL, CHANGE per_value per_value LONGTEXT NOT NULL, CHANGE per_date_creation per_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE per_date_update per_date_update DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE INDEX query ON weaving_perspective (per_value)");
        $this->addSql("ALTER TABLE weaving_photograph CHANGE pht_status pht_status TINYINT(1) DEFAULT '1' NOT NULL, CHANGE licence_id licence_id INT DEFAULT 0 NOT NULL, CHANGE location_id location_id INT DEFAULT 0 NOT NULL, CHANGE pht_date_creation pht_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
        $this->addSql("ALTER TABLE weaving_placeholder CHANGE plh_type plh_type INT DEFAULT 0 NOT NULL, CHANGE plh_status plh_status INT DEFAULT 0 NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX plh_name ON weaving_placeholder (plh_name)");
        $this->addSql("ALTER TABLE weaving_privilege CHANGE prv_id prv_id TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE weaving_recipient CHANGE rcp_date_creation rcp_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE rcp_date_update rcp_date_update DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX cnt_id ON weaving_recipient (cnt_id)");
        $this->addSql("CREATE UNIQUE INDEX rcl_id ON weaving_recipient (rcl_id, usr_id, cnt_id, rcp_full_name)");
        $this->addSql("ALTER TABLE weaving_recipient_list CHANGE rcl_status rcl_status TINYINT(1) DEFAULT '0' NOT NULL, CHANGE rcl_date_creation rcl_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE rcl_date_update rcl_date_update DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX rcl_name ON weaving_recipient_list (rcl_name)");
        $this->addSql("ALTER TABLE weaving_route CHANGE rte_parent_hub rte_parent_hub INT DEFAULT 16 NOT NULL, CHANGE rte_level rte_level TINYINT(1) DEFAULT '0' NOT NULL, CHANGE rte_type rte_type TINYINT(1) DEFAULT '3' NOT NULL, CHANGE rte_status rte_status TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX rte_uri ON weaving_route (rte_uri)");
        $this->addSql("CREATE INDEX cty_id ON weaving_route (cty_id)");
        $this->addSql("CREATE INDEX ety_id ON weaving_route (ety_id)");
        $this->addSql("ALTER TABLE weaving_serialization CHANGE sn_type sn_type TINYINT(1) DEFAULT '1' NOT NULL, CHANGE sn_date_creation sn_date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX sn_uri ON weaving_serialization (sn_uri)");
        $this->addSql("ALTER TABLE weaving_store CHANGE str_type str_type INT DEFAULT 1 NOT NULL, CHANGE str_status str_status INT DEFAULT 0 NOT NULL");
        $this->addSql("CREATE INDEX entity type ON weaving_store (etp_id)");
        $this->addSql("CREATE INDEX entity ON weaving_store (ety_id)");
        $this->addSql("ALTER TABLE weaving_store_item CHANGE str_id str_id INT DEFAULT 1 NOT NULL, CHANGE sti_status sti_status INT DEFAULT 0 NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX list item ON weaving_store_item (sti_id, str_id, sti_index)");
        $this->addSql("CREATE INDEX entity ON weaving_store_item (ety_id)");
        $this->addSql("CREATE INDEX list ON weaving_store_item (str_id)");
        $this->addSql("CREATE INDEX entity type ON weaving_store_item (etp_id)");
        $this->addSql("ALTER TABLE weaving_stylesheet CHANGE sts_type sts_type INT DEFAULT 0 NOT NULL, CHANGE sts_status sts_status TINYINT(1) DEFAULT '0' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX sts_type ON weaving_stylesheet (sts_type, sts_name)");
        $this->addSql("ALTER TABLE weaving_template CHANGE lang_id lang_id TINYINT(1) DEFAULT '1' NOT NULL, CHANGE tpl_status tpl_status INT DEFAULT 0 NOT NULL, CHANGE tpl_type tpl_type TINYINT(1) DEFAULT '1' NOT NULL, CHANGE tpl_modification_date tpl_modification_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL");
        $this->addSql("ALTER TABLE weaving_token CHANGE tkn_status tkn_status TINYINT(1) DEFAULT '1' NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX tkn_value ON weaving_token (tkn_value)");
        $this->addSql("CREATE INDEX ety_id ON weaving_token (ety_id, tkn_type)");
        $this->addSql("ALTER TABLE weaving_user DROP usr_twitter_id, DROP usr_twitter_username, DROP usr_full_name, CHANGE grp_id grp_id INT NOT NULL, CHANGE usr_avatar usr_avatar INT NOT NULL, CHANGE usr_last_name usr_last_name VARCHAR(255) NOT NULL");
        $this->addSql("CREATE UNIQUE INDEX usr_user_name ON weaving_user (usr_user_name)");
        $this->addSql("CREATE UNIQUE INDEX usr_email ON weaving_user (usr_email)");

    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("DROP TABLE weaving_book");
        $this->addSql("DROP TABLE weaving_folder");
        $this->addSql("DROP TABLE Store");
        $this->addSql("DROP TABLE weaving_github");
        $this->addSql("DROP TABLE weaving_github_starred_repositories");
        $this->addSql("DROP TABLE weaving_json_github");
        $this->addSql("DROP TABLE weaving_json_twitter");
        $this->addSql("DROP TABLE weaving_query");
        $this->addSql("DROP TABLE weaving_status");
        $this->addSql("DROP INDEX rep_github_id ON weaving_github_repositories");
        $this->addSql("DROP INDEX id ON weaving_github_repositories");
        $this->addSql("ALTER TABLE weaving_github_repositories CHANGE rep_updated_at rep_updated_at DATETIME DEFAULT NULL");
        $this->addSql("DROP INDEX jsn_hash ON weaving_json");
        $this->addSql("DROP INDEX jsn_status ON weaving_json");
        $this->addSql("ALTER TABLE weaving_json CHANGE jsn_status jsn_status TINYINT(1) NOT NULL, CHANGE jsn_type jsn_type INT NOT NULL");
        $this->addSql("DROP INDEX arc_type ON weaving_arc");
        $this->addSql("DROP INDEX etp_id ON weaving_arc");
        $this->addSql("ALTER TABLE weaving_arc CHANGE arc_status arc_status TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX author_id ON weaving_author");
        $this->addSql("DROP INDEX cnt_value ON weaving_contact");
        $this->addSql("ALTER TABLE weaving_contact CHANGE cnt_status cnt_status TINYINT(1) NOT NULL, CHANGE cnt_type cnt_type TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX rte_id ON weaving_content");
        $this->addSql("DROP INDEX title ON weaving_content");
        $this->addSql("ALTER TABLE weaving_content CHANGE ctt_status ctt_status TINYINT(1) NOT NULL, CHANGE ctt_type ctt_type TINYINT(1) NOT NULL, CHANGE ctt_date_creation ctt_date_creation DATETIME NOT NULL, CHANGE ctt_date_modification ctt_date_modification DATETIME NOT NULL, CHANGE ctt_date_deletion ctt_date_deletion DATETIME NOT NULL");
        $this->addSql("DROP INDEX cty_id ON weaving_content_type");
        $this->addSql("ALTER TABLE weaving_content_type CHANGE cty_id cty_id INT AUTO_INCREMENT NOT NULL");
        $this->addSql("DROP INDEX edg_type ON weaving_edge");
        $this->addSql("ALTER TABLE weaving_edge CHANGE ety_id ety_id INT NOT NULL, CHANGE edg_status edg_status TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX ety_name ON weaving_entity");
        $this->addSql("ALTER TABLE weaving_entity CHANGE ety_date_creation ety_date_creation DATETIME NOT NULL, CHANGE ety_date_modification ety_date_modification DATETIME NOT NULL");
        $this->addSql("DROP INDEX ett_table_name ON weaving_entity_table");
        $this->addSql("DROP INDEX ett_table_alias ON weaving_entity_table");
        $this->addSql("DROP INDEX ett_column_prefix ON weaving_entity_table");
        $this->addSql("DROP INDEX ety_id ON weaving_entity_table");
        $this->addSql("DROP INDEX type ON weaving_entity_type");
        $this->addSql("ALTER TABLE weaving_entity_type CHANGE ety_id ety_id INT NOT NULL, CHANGE etp_status etp_status TINYINT(1) NOT NULL, CHANGE etp_default etp_default TINYINT(1) DEFAULT NULL, CHANGE etp_index etp_index TINYINT(1) NOT NULL, CHANGE etp_name etp_name VARCHAR(100) NOT NULL, CHANGE etp_value etp_value VARCHAR(100) NOT NULL, CHANGE etp_description etp_description LONGTEXT NOT NULL");
        $this->addSql("DROP INDEX evt_source ON weaving_event");
        $this->addSql("ALTER TABLE weaving_event CHANGE ety_id ety_id INT NOT NULL, CHANGE evt_occurrence evt_occurrence INT DEFAULT NULL, CHANGE evt_success evt_success TINYINT(1) DEFAULT NULL, CHANGE evt_date evt_date DATETIME NOT NULL");
        $this->addSql("DROP INDEX identifier ON weaving_feed");
        $this->addSql("DROP INDEX fd_status ON weaving_feed");
        $this->addSql("ALTER TABLE weaving_feed CHANGE fd_id fd_id INT AUTO_INCREMENT NOT NULL, CHANGE fd_date_creation fd_date_creation DATETIME NOT NULL, CHANGE fd_date_publication fd_date_publication DATETIME NOT NULL");
        $this->addSql("DROP INDEX fdb_hash ON weaving_feedback");
        $this->addSql("ALTER TABLE weaving_feedback CHANGE fdb_status fdb_status TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX fil_path ON weaving_file");
        $this->addSql("DROP INDEX fil_name ON weaving_file");
        $this->addSql("DROP INDEX fld_id ON weaving_file");
        $this->addSql("ALTER TABLE weaving_file CHANGE fil_status fil_status TINYINT(1) NOT NULL, CHANGE fil_size fil_size INT NOT NULL, CHANGE fil_type fil_type TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE weaving_flag CHANGE flg_date_creation flg_date_creation DATETIME NOT NULL");
        $this->addSql("DROP INDEX frm_identifier ON weaving_form");
        $this->addSql("DROP INDEX rte_id ON weaving_form");
        $this->addSql("DROP INDEX str_id ON weaving_form");
        $this->addSql("DROP INDEX prv_id ON weaving_form");
        $this->addSql("ALTER TABLE weaving_form CHANGE prv_id prv_id INT DEFAULT NULL, CHANGE frm_status frm_status TINYINT(1) NOT NULL, CHANGE frm_type frm_type INT NOT NULL");
        $this->addSql("DROP INDEX hdr_imap_uid ON weaving_header");
        $this->addSql("DROP INDEX rcl_id ON weaving_header");
        $this->addSql("DROP INDEX cnt_id ON weaving_header");
        $this->addSql("ALTER TABLE weaving_header CHANGE cnt_id cnt_id INT NOT NULL, CHANGE hdr_date_creation hdr_date_creation DATETIME NOT NULL, CHANGE hdr_date_update hdr_date_update DATETIME NOT NULL");
        $this->addSql("ALTER TABLE weaving_insight CHANGE isg_status isg_status TINYINT(1) NOT NULL, CHANGE ety_id ety_id TINYINT(1) NOT NULL, CHANGE isg_date_creation isg_date_creation DATETIME NOT NULL, CHANGE isg_date_modification isg_date_modification DATETIME NOT NULL");
        $this->addSql("DROP INDEX isn_id ON weaving_insight_moderation");
        $this->addSql("ALTER TABLE weaving_insight_moderation CHANGE imo_status imo_status TINYINT(1) NOT NULL, CHANGE imo_date_creation imo_date_creation DATETIME NOT NULL, CHANGE imo_date_modification imo_date_modification DATETIME NOT NULL");
        $this->addSql("DROP INDEX isn_id ON weaving_insight_node");
        $this->addSql("DROP INDEX isg_id ON weaving_insight_node");
        $this->addSql("ALTER TABLE weaving_insight_node CHANGE isn_status isn_status TINYINT(1) NOT NULL, CHANGE isn_type isn_type TINYINT(1) NOT NULL, CHANGE isn_date_creation isn_date_creation DATETIME NOT NULL, CHANGE isn_date_modification isn_date_modification DATETIME NOT NULL");
        $this->addSql("DROP INDEX isn_id ON weaving_insight_sharing");
        $this->addSql("ALTER TABLE weaving_insight_sharing CHANGE ish_status ish_status TINYINT(1) NOT NULL, CHANGE ish_type ish_type TINYINT(1) NOT NULL, CHANGE ish_date_creation ish_date_creation DATETIME NOT NULL, CHANGE ish_date_modification ish_date_modification DATETIME NOT NULL");
        $this->addSql("ALTER TABLE weaving_language CHANGE lang_status lang_status TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX nsp_id ON weaving_language_item");
        $this->addSql("ALTER TABLE weaving_language_item CHANGE lgi_status lgi_status TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE weaving_link CHANGE lnk_status lnk_status INT NOT NULL, CHANGE lnk_date_creation lnk_date_creation DATETIME NOT NULL, CHANGE lnk_date_update lnk_date_update DATETIME NOT NULL");
        $this->addSql("ALTER TABLE weaving_location CHANGE latitude latitude NUMERIC(10, 0) NOT NULL, CHANGE longitude longitude NUMERIC(10, 0) NOT NULL");
        $this->addSql("DROP INDEX ent_id ON weaving_log");
        $this->addSql("ALTER TABLE weaving_log CHANGE log_occurrence log_occurrence INT NOT NULL, CHANGE log_creation_date log_creation_date DATETIME NOT NULL, CHANGE log_update_date log_update_date DATETIME NOT NULL");
        $this->addSql("DROP INDEX hdr_id ON weaving_message");
        $this->addSql("ALTER TABLE weaving_message CHANGE msg_type msg_type INT NOT NULL");
        $this->addSql("ALTER TABLE weaving_namespace CHANGE nsp_status nsp_status TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE weaving_outgoing CHANGE out_date_creation out_date_creation DATETIME NOT NULL");
        $this->addSql("ALTER TABLE weaving_photograph CHANGE pht_status pht_status TINYINT(1) NOT NULL, CHANGE licence_id licence_id INT NOT NULL, CHANGE location_id location_id INT NOT NULL, CHANGE pht_date_creation pht_date_creation DATETIME NOT NULL");
        $this->addSql("DROP INDEX plh_name ON weaving_placeholder");
        $this->addSql("ALTER TABLE weaving_placeholder CHANGE plh_type plh_type INT NOT NULL, CHANGE plh_status plh_status INT NOT NULL");
        $this->addSql("ALTER TABLE weaving_privilege CHANGE prv_id prv_id TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX cnt_id ON weaving_recipient");
        $this->addSql("DROP INDEX rcl_id ON weaving_recipient");
        $this->addSql("ALTER TABLE weaving_recipient CHANGE rcp_date_creation rcp_date_creation DATETIME NOT NULL, CHANGE rcp_date_update rcp_date_update DATETIME NOT NULL");
        $this->addSql("DROP INDEX rcl_name ON weaving_recipient_list");
        $this->addSql("ALTER TABLE weaving_recipient_list CHANGE rcl_status rcl_status TINYINT(1) NOT NULL, CHANGE rcl_date_creation rcl_date_creation DATETIME NOT NULL, CHANGE rcl_date_update rcl_date_update DATETIME NOT NULL");
        $this->addSql("DROP INDEX rte_uri ON weaving_route");
        $this->addSql("DROP INDEX cty_id ON weaving_route");
        $this->addSql("DROP INDEX ety_id ON weaving_route");
        $this->addSql("ALTER TABLE weaving_route CHANGE rte_parent_hub rte_parent_hub INT NOT NULL, CHANGE rte_level rte_level TINYINT(1) NOT NULL, CHANGE rte_type rte_type TINYINT(1) NOT NULL, CHANGE rte_status rte_status TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX sn_uri ON weaving_serialization");
        $this->addSql("ALTER TABLE weaving_serialization CHANGE sn_type sn_type TINYINT(1) NOT NULL, CHANGE sn_date_creation sn_date_creation DATETIME NOT NULL");
        $this->addSql("DROP INDEX entity type ON weaving_store");
        $this->addSql("DROP INDEX entity ON weaving_store");
        $this->addSql("ALTER TABLE weaving_store CHANGE str_type str_type INT NOT NULL, CHANGE str_status str_status INT NOT NULL");
        $this->addSql("DROP INDEX list item ON weaving_store_item");
        $this->addSql("DROP INDEX entity ON weaving_store_item");
        $this->addSql("DROP INDEX list ON weaving_store_item");
        $this->addSql("DROP INDEX entity type ON weaving_store_item");
        $this->addSql("ALTER TABLE weaving_store_item CHANGE str_id str_id INT NOT NULL, CHANGE sti_status sti_status INT NOT NULL");
        $this->addSql("DROP INDEX sts_type ON weaving_stylesheet");
        $this->addSql("ALTER TABLE weaving_stylesheet CHANGE sts_type sts_type INT NOT NULL, CHANGE sts_status sts_status TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE weaving_template CHANGE lang_id lang_id TINYINT(1) NOT NULL, CHANGE tpl_status tpl_status INT NOT NULL, CHANGE tpl_type tpl_type TINYINT(1) NOT NULL, CHANGE tpl_modification_date tpl_modification_date DATETIME NOT NULL");
        $this->addSql("DROP INDEX tkn_value ON weaving_token");
        $this->addSql("DROP INDEX ety_id ON weaving_token");
        $this->addSql("ALTER TABLE weaving_token CHANGE tkn_status tkn_status TINYINT(1) NOT NULL");
        $this->addSql("DROP INDEX query ON weaving_perspective");
        $this->addSql("ALTER TABLE weaving_perspective ADD per_description LONGTEXT DEFAULT NULL, CHANGE per_status per_status INT DEFAULT NULL, CHANGE per_type per_type INT DEFAULT NULL, CHANGE per_value per_value LONGTEXT DEFAULT NULL, CHANGE per_date_creation per_date_creation DATETIME DEFAULT NULL, CHANGE per_date_update per_date_update DATETIME DEFAULT NULL");
        $this->addSql("DROP INDEX usr_user_name ON weaving_user");
        $this->addSql("DROP INDEX usr_email ON weaving_user");
        $this->addSql("ALTER TABLE weaving_user ADD usr_twitter_id VARCHAR(255) NOT NULL, ADD usr_twitter_username VARCHAR(255) NOT NULL, ADD usr_full_name VARCHAR(255) DEFAULT NULL, CHANGE grp_id grp_id INT DEFAULT NULL, CHANGE usr_avatar usr_avatar INT DEFAULT NULL, CHANGE usr_last_name usr_last_name VARCHAR(255) DEFAULT NULL");
    }
}