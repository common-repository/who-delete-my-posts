<?php
namespace Whodeletemyposts;

class App
{
	private $db;

	public function __construct()
	{
		$this->db = new DB();

		register_activation_hook(WHODELETEMYPOSTS_FILE, array(Installation::class, 'install'));
		register_deactivation_hook(WHODELETEMYPOSTS_FILE, array(Installation::class, 'uninstall'));

		add_action('admin_menu', array($this, 'register_admin_menu'));

		add_action('trashed_post', array($this, 'record_trash'));
		add_action('deleted_post', array($this, 'record_delete'), 10, 2);
	}

	/**
	 * @param 	int 	$post_id
	 *
	 */
	public function record_trash($post_id)
	{
		$post = get_post($post_id);
		$user = wp_get_current_user();
		$this->db->create($user->user_login, $post->post_title, $post->post_status);
	}

	/**
	 * @param 	int 		$post_id
	 * @param 	WP_Post 	$post
	 *
	 */
	public function record_delete($post_id, $post)
	{
		if($post->post_status == 'inherit')
			return;
		if($post->post_type !== 'post' and $post->post_type !== 'page')
			return;
		$user = wp_get_current_user();
		$this->db->create($user->user_login, $post->post_title, "deleted");
	}

	public function register_admin_menu()
	{
		add_submenu_page(
			'tools.php',
			'who delete my posts',
			'Who Delete My Posts?',
			'manage_options',
			'who-delete-my-posts',
			array($this, 'admin_callback')
		);
	}

	public function admin_callback()
	{
		$records = $this->db->get_records();
		?>
		<h1>Who delete my posts?</h1>
		<table class="wp-list-table widefat fixed striped table-view-list posts">
			<thead>
				<tr>
					<td>User</td>
					<td>Post Title</td>
					<td>Status</td>
					<td>Date</td>
				</tr>
			</thead>
			<tbody>
			<?php 
			if($records): 
				foreach ($records as $row):
				?>
				<tr>
					<td><?php echo esc_html($row->username); ?></td>
					<td><?php echo esc_html($row->post_title); ?></td>
					<td><?php echo esc_html($row->status); ?></td>
					<td><?php echo esc_html($row->created_at); ?></td>
				</tr>
				<?php
				endforeach;
			endif;
			?>
			</tbody>
		</table>
		<?php
	}
}