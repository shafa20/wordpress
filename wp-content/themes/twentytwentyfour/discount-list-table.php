<?php
class Example_List_Table2 extends WP_List_Table {

    public function __construct() {
        parent::__construct([
            'singular' => __('Discount', 'sp'),
            'plural'   => __('Discounts', 'sp'),
            'ajax'     => false
        ]);
    }

    public function get_columns() {
        return [
            'priority'  => __('Priority', 'sp'),
            'name'      => __('Name', 'sp'),
            'type'      => __('Type', 'sp'),
            'applies_to' => __('Applies to', 'sp'),
            'enabled'   => __('Enabled', 'sp')
        ];
    }

    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [$columns, $hidden, $sortable];

        $this->items = [
            ['priority' => 1, 'name' => 'Summer Sale', 'type' => 'Percentage', 'applies_to' => 'All Products', 'enabled' => 1],
        ];
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'priority':
            case 'name':
            case 'type':
            case 'applies_to':
                return $item[$column_name];
            case 'enabled':
                return '<label class="switch">
                            <input type="checkbox" ' . checked(1, $item[$column_name], false) . '>
                            <span class="slider round"></span>
                        </label>';
            default:
                return print_r($item, true);
        }
    }

    public function display() {
        $singular = $this->_args['singular'];

        $this->display_tablenav('top');

        $this->screen->render_screen_reader_content('heading_list');
        ?>
        <table class="wp-list-table <?php echo implode(' ', $this->get_table_classes()); ?>">
            <?php $this->print_table_description(); ?>
            <thead>
                <tr>
                    <?php $this->print_column_headers(); ?>
                </tr>
            </thead>

            <tbody id="the-list" <?php
                                    if ($singular) {
                                        echo " data-wp-lists='list:$singular'";
                                    }
                                    ?>>
                <?php $this->display_rows_or_placeholder(); ?>
                <tr>
                    <td colspan="5">
                        <div id="form-placeholder" style="display:none;">
                            <!-- Form will be injected here -->
                        </div>
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr>
                    <?php $this->print_column_headers(false); ?>
                </tr>

            </tfoot>

        </table>
        <?php
        $this->display_tablenav('bottom');
    }
}


?>