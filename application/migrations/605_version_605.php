<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_605 extends CI_Migration
{
    public function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("UPDATE `tbl_config` SET `value` = '6.0.5' WHERE `tbl_config`.`config_key` = 'version';");
    }
}
