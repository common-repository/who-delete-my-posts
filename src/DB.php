<?php
namespace Whodeletemyposts;

class DB
{
	private $wpdb;
	private $records_table_name;

	public function __construct()
	{
		global $wpdb;
		$this->wpdb =& $wpdb;
		$this->records_table_name = $this->wpdb->prefix.'who_delete_my_posts';
	}

	private function get_current_date()
	{
		$current_date = current_datetime();
		return $current_date->format("Y-m-d H:i:s");
	}

	public function create_tables()
	{
		require_once ABSPATH.'wp-admin/includes/upgrade.php';
		$charset = $this->wpdb->get_charset_collate();

		$records_table_name = $this->records_table_name;
		if(!$this->wpdb->query($this->wpdb->prepare("show tables like %s", $records_table_name))){
			$sql_records = "CREATE TABLE $records_table_name(
								id bigint(20) unsigned NOT NULL auto_increment,
								username varchar(300) NOT NULL default '',
								post_title varchar(600) NOT NULL default '',
								status varchar(100) NOT NULL default '',
								created_at datetime NOT NULL default '1000-01-01 00:00:00',
								updated_at datetime NOT NULL default '1000-01-01 00:00:00',
								PRIMARY KEY  (id)
							) $charset";

			dbDelta($sql_records);
		}
	}

	public function drop_tables()
	{
		$records_table_name = $this->records_table_name;
		if($this->wpdb->query($this->wpdb->prepare("show tables like %s", $records_table_name))){
			$this->wpdb->query("DROP TABLE $records_table_name");
		}
	}

	public function create($username, $post_title, $post_status)
	{
		if(!$username){
			return;
		}

		$records_table_name = $this->records_table_name;
		$this->wpdb->insert(
			$records_table_name,
			array(
				'username' => $username,
				'post_title' => $post_title,
				'status' => $post_status,
				'created_at' => $this->get_current_date(),
				'updated_at' => $this->get_current_date()
			),
			array('%s', '%s', '%s', '%s', '%s')
		);
	}

	public function get_records()
	{
		$records_table_name = $this->records_table_name;
		return $this->wpdb->get_results("SELECT username, post_title, status, created_at FROM $records_table_name ORDER BY created_at DESC");
	}
}