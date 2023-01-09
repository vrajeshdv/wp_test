<?php


if (!class_exists('WP_List_Table')) {
	require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
  

function user_default_settings_callback() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // table 1
    $table_name1 = $wpdb->prefix . 'user_address';

    $table_sql1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_name text DEFAULT '' NOT NULL,
    note text DEFAULT '' NOT NULL,
    created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY id (id) ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $insert_query="INSERT INTO ".$table_name1." (user_name,note) SELECT 'user1','hello this is testing' WHERE NOT EXISTS(SELECT id FROM ".$table_name1." WHERE user_name = 'user1')";
    $insert_second_query="INSERT INTO ".$table_name1." (user_name,note) SELECT 'user2','hello this is second testing' WHERE NOT EXISTS(SELECT id FROM ".$table_name1." WHERE user_name = 'user2')";
    $wpdb->get_results( $insert_query );
    $wpdb->get_results( $insert_second_query );
    dbDelta($table_sql1);
}

 

    user_default_settings_callback();

class user_address_list extends WP_List_Table
{ 
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'User',
            'plural' => 'Users',
        ));
    }


    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }


    function column_user_name($item)
    {

        $actions = array(
            'edit' => sprintf('<a href="?page=user_address_form&id=%s">%s</a>', $item['id'], __('Edit', 'my_text_domain')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'my_text_domain')),
        );

        return sprintf('%s %s',
            $item['user_name'],
            $this->row_actions($actions)
        );
    }


    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', 
            'user_name' => __('User', 'my_text_domain'),
            'note' => __('Note', 'my_text_domain'),
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'user_name' => array('user_name', true),
        );
        return $sortable_columns;
    }

    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_address'; 

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    function prepare_items()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'user_address'; 

        $per_page = 10;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

        $OFFSET = $paged * $per_page;

        $search_term = isset($_REQUEST['s']) ? trim($_REQUEST['s']) : "";

        if(!empty($search_term)){
            $this->items = $wpdb->get_results("SELECT * FROM $table_name WHERE user_name LIKE '%".$search_term."%' ORDER BY ". $orderby ." ". $order ." LIMIT ".$per_page." OFFSET ".$OFFSET, ARRAY_A);
            $total_items = $wpdb->get_results("SELECT * FROM $table_name WHERE user_name LIKE '%".$search_term."%' ORDER BY ". $orderby ." ". $order);
            $total_items = count($total_items);
        }else{
            $this->items = $wpdb->get_results("SELECT * FROM $table_name WHERE user_name LIKE '%".$search_term."%' ORDER BY ". $orderby ." ". $order ." LIMIT ".$per_page." OFFSET ".$OFFSET, ARRAY_A);
            $total_items = $wpdb->get_results("SELECT * FROM $table_name ORDER BY $orderby $order");
            $total_items = count($total_items);
        }


        $this->set_pagination_args(array(
            'total_items' => $total_items, 
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page) 
        ));
    }
}

function swlp_admin_menu(){
    add_menu_page(__('User wp list', 'my_text_domain'), __('User wp list', 'my_text_domain'), 'activate_plugins', 'user_list', 'user_address_page_handler', 'dashicons-admin-site-alt3', 55);

    add_submenu_page('user_list', __('user Addresses', 'my_text_domain'), __('User Addresses', 'my_text_domain'), 'activate_plugins', 'user_address', 'user_address_page_handler');
    add_submenu_page('user_list', __('Add User Address', 'my_text_domain'), __('Add User Address', 'my_text_domain'), 'manage_options', 'user_address_form', 'user_address_form_page_handler');

    remove_submenu_page('user_list', 'user_list');
}

add_action('admin_menu', 'swlp_admin_menu');


function user_address_page_handler()
{
    global $wpdb;

    $table = new user_address_list();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'my_text_domain'), (is_array($_REQUEST['id'])?count($_REQUEST['id']):1)) . '</p></div>';
    }
    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php _e('IP Address', 'my_text_domain')?> <a class="add-new-h2"
         href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=user_address_form');?>"><?php _e('Add new User address', 'my_text_domain')?></a>
     </h2>
     <?php echo $message; ?>

     <form id="contacts-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php 
        $table->search_box("Search Post", "search_post_id");
        $table->display();
        ?>
    </form>

</div>
<?php
}


function user_address_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'user_address'; 

    $message = '';
    $notice = '';


    $default = array(
        'id' => 0,
        'user_name' => '',
        'note' => '',
    );


    if ( isset($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {

        $item = shortcode_atts($default, $_REQUEST);     

        $item_valid = my_validate_user_name($item);
        if ($item_valid === true) {
            if ($item['id'] == 0) {
                $user_exist = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE user_name = '".$item['user_name']."'" ));
                if(empty($user_exist)){
                    $result = $wpdb->insert($table_name, array('user_name'=>$item['user_name'], 'note'=>$item['note']));
                    $item['id'] = $wpdb->insert_id;
                    $message = __('Item successfully saved', 'my_text_domain');
                }else{
                    $notice = __('user  already exist..!!!', 'my_text_domain');
                }
            } else {
                $result = $wpdb->update($table_name, array('user_name'=>$item['user_name'], 'note'=>$item['note']), array('id' => $item['id']));
                $message = __('Item successfully updated', 'my_text_domain');
            }
        } else {

            $notice = $item_valid;
        }
    }

    $item = $default;
    if (isset($_GET['id'])) {
        $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
        if (!$item) {
            $item = $default;
            $notice = __('Item not found', 'my_text_domain');
        }
    }

    
    add_meta_box('user_name_form_meta_box', __('User Address data', 'my_text_domain'), 'user_address_form_meta_box_handler', 'user_name', 'normal', 'default');

    ?>
    <div class="wrap">
        <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
        <h2><?php _e('Users', 'my_text_domain')?> <a class="add-new-h2"
            href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=user_address');?>"><?php _e('back to list', 'my_text_domain')?></a>
        </h2>

        <?php if (!empty($notice)): ?>
            <div id="notice" class="error"><p><?php echo $notice ?></p></div>
        <?php endif;?>
        <?php if (!empty($message)): ?>
            <div id="message" class="updated"><p><?php echo $message ?></p></div>
        <?php endif;?>

        <form id="form" method="POST">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>

            <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

            <div class="metabox-holder" id="poststuff">
                <div id="post-body">
                    <div id="post-body-content">
                        <?php do_meta_boxes('user_name', 'normal', $item); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php
}

function user_address_form_meta_box_handler($item)
{
    ?>
    <tbody>
        <div class="formdata">
            <form>
                <p>			
                  <label for="user_name"><?php _e('User name:', 'my_text_domain')?></label>
                  <br>	
                  <input id="user_name" name="user_name" type="text" style="width: 100%" value="<?php echo esc_attr($item['user_name'])?>"
                  required>                  
              </p>
              <p>
                <label for="note"><?php _e('Note:', 'my_text_domain')?></label>
                <br>  
                <textarea id="note" name="note" rows="6" style="width: 100%"><?php echo esc_attr($item['note'])?></textarea>     
            </p>
            <p>
              <input type="submit" value="<?php _e('Save', 'my_text_domain')?>" id="submit" class="button-primary" name="submit" style='margin-top: 15px;'>
          </p>
      </form>
  </div>
</tbody>
<?php
}

function my_validate_user_name($item){
    $messages = array();

    if (empty($item['user_name'])) $messages[] = __('IP is required', 'my_text_domain');    

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}
