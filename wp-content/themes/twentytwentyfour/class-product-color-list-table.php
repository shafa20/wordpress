<?php

if ( ! class_exists( 'Example_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';

    class Example_List_Table extends WP_List_Table {
       
        public function __construct() {
            parent::__construct( array(
                'singular' => 'product_color',
                'plural'   => 'product_colors',
                'ajax'     => false,
            ) );
        }

        public function get_columns() {
            return array(
                'product'       => __( 'Product', 'textdomain' ),
                'color_image'   => __( 'Color Image', 'textdomain' ),
                'colors'        => __( 'Colors', 'textdomain' ),
            );
        }

        public function column_default( $item, $column_name ) {
            switch ( $column_name ) {
                case 'product':
                    return '<strong>' . esc_html( $item['product_name'] ) . '</strong><br><em>' . esc_html( 'SKU: ' . $item['product_sku'] ) . '</em>';
                case 'color_image':
                    return $item['color_image'] ? '<img src="' . esc_url( $item['color_image'] ) . '"  />' : __( 'No image', 'textdomain' );
                case 'colors':
                    return $this->format_colors( $item['product_id'], $item['colors'] );
                default:
                    return print_r( $item, true );
            }
        }

        // private function format_colors( $product_id, $colors ) {
        //     $all_colors = get_terms(array(
        //         'taxonomy' => 'product_color',
        //         'hide_empty' => false
        //     ));

        //     $color_list = '<div style="display: flex; flex-wrap: wrap;">';
        //     $count = 0;

        //     foreach ( $all_colors as $color ) {
        //         $checked = in_array( $color->term_id, $colors ) ? 'checked' : '';
        //         if ($count % 5 == 0 && $count > 0) {
        //             $color_list .= '</div><div style="display: flex; flex-wrap: wrap;">';
        //         }
        //         $color_list .= '<div style="margin-right: 20px;">
        //             <label>
        //                 <input type="checkbox" name="product_colors[' . esc_attr($product_id) . '][]" value="' . esc_attr($color->term_id) . '" ' . $checked . ' data-product-id="' . esc_attr($product_id) . '" data-color-id="' . esc_attr($color->term_id) . '">
        //                 ' . esc_html($color->name) . '
        //             </label>
        //         </div>';
        // $color_list .= '<div class="col-12 mb-3">
        //         //     <div class="row align-items-center">
        //         //         <div class="col-md-6">
        //         //             <label style="display: flex; align-items: center;">
        //         //                 <input type="checkbox" name="product_colors[' . esc_attr($product_id) . '][]" value="' . esc_attr($color->term_id) . '" ' . $checked . ' data-product-id="' . esc_attr($product_id) . '" data-color-id="' . esc_attr($color->term_id) . '">
        //         //                 <span style="margin-left: 10px;">' . esc_html($color->name) . '</span>
        //         //             </label>
        //         //         </div>
        //         //         <div class="col-md-6">
        //         //             <div class="gradient-box ' . esc_attr($color_class) . '" style="width: 100%; height: 40px;"></div>
        //         //         </div>
        //         //     </div>
        //         // </div>';
        //         $count++;
        //     }
        //     $color_list .= '</div>';

        //     return $color_list;
        // }
     
        private function format_colors($product_id, $colors) {
            // Retrieve all color terms from the 'product_color' taxonomy
            $all_colors = get_terms(array(
                'taxonomy' => 'product_color',
                'hide_empty' => false
            ));
        
            // Start the container for color items
            $color_list = '<div style="display: flex; flex-wrap: wrap; gap: 10px;">'; // Added gap for spacing between items
        
            // Iterate through each color term
            foreach ($all_colors as $color) {
                
                // Determine if the current color term is checked
                $checked = in_array($color->term_id, $colors) ? 'checked' : '';
        
                // Map color name to a class (make sure to sanitize and replace spaces)
                $color_class = strtolower(str_replace(' ', '-', $color->name));
        
                // Add color item to the list
                $color_list .= '<div style="display: flex; align-items: center; width: calc(25% - 10px); box-sizing: border-box; margin-bottom: 5px;">
                    <label style="display: flex; align-items: center; flex: 1;">
                        <input type="checkbox" name="product_colors[' . esc_attr($product_id) . '][]" value="' . esc_attr($color->term_id) . '" ' . $checked . ' data-product-id="' . esc_attr($product_id) . '" data-color-id="' . esc_attr($color->term_id) . '">
                        <span style="margin-left: 10px;">' . esc_html($color->name) . '</span>
                    </label>
                    <div class="gradient-box ' . esc_attr($color_class) . '" style="width: 40px; height: 20px; margin-left: 10px;"></div>
                </div>';
            }
        
            // Close the container for color items
            $color_list .= '</div>';
        
            return $color_list;
        }
        
        public function prepare_items() {
            $per_page = 5;
            $current_page = $this->get_pagenum();
            
            // Retrieve SKU list from session if available
            session_start();
            $sku_list = isset($_SESSION['product_sku_list']) ? $_SESSION['product_sku_list'] : array();
        
            // Calculate total items based on SKU list
            $total_items = $this->get_total_products_count($sku_list);
        
            $this->items = $this->get_products($per_page, $current_page, $sku_list);
            $this->set_pagination_args(array(
                'total_items' => $total_items,
                'per_page'    => $per_page,
                'total_pages' => ceil($total_items / $per_page),
            ));
            $this->_column_headers = array($this->get_columns(), array(), array());
        }
        
        private function get_total_products_count($sku_list) {
            global $wpdb;
        
            $sku_filter = !empty($sku_list) ? "AND pm_sku.meta_value IN ('" . implode("','", array_map('esc_sql', $sku_list)) . "')" : '';
        
            $query = "
                SELECT COUNT(p.ID) AS count
                FROM {$wpdb->prefix}posts p
                LEFT JOIN {$wpdb->prefix}postmeta pm_sku ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
                WHERE p.post_type = 'product' AND p.post_status = 'publish' $sku_filter
            ";
        
            $result = $wpdb->get_var($query);
            return intval($result);
        }
        
       
        private function get_products( $per_page, $current_page ) {
            global $wpdb;
        
            $offset = ( $current_page - 1 ) * $per_page;
        
            // Check if a session exists and contains SKU list
            session_start();
            $sku_list = isset($_SESSION['product_sku_list']) ? $_SESSION['product_sku_list'] : array();
        
            if (!empty($sku_list)) {
                // Convert SKU list to a comma-separated string
                $sku_order_string = implode(',', array_map('intval', $sku_list));
        
                $query = $wpdb->prepare(
                    "
                    SELECT p.ID AS product_id, p.post_title AS product_name, 
                           COALESCE(pm_sku.meta_value, '') AS product_sku, 
                           pm_color_image.meta_value AS color_image, 
                           (SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = p.ID AND meta_key = '_thumbnail_id') AS thumbnail_id
                    FROM {$wpdb->prefix}posts p
                    LEFT JOIN {$wpdb->prefix}postmeta pm_sku ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
                    LEFT JOIN {$wpdb->prefix}postmeta pm_color_image ON p.ID = pm_color_image.post_id AND pm_color_image.meta_key = 'color_image'
                    WHERE p.post_type = 'product' AND p.post_status = 'publish'
                    AND pm_sku.meta_value IN ($sku_order_string)
                    ORDER BY FIELD(pm_sku.meta_value, $sku_order_string)
                    LIMIT %d OFFSET %d
                    ",
                    $per_page,
                    $offset
                );
            } else {
                // Default query when no SKU filter is applied
                $query = $wpdb->prepare(
                    "
                    SELECT p.ID AS product_id, p.post_title AS product_name, 
                           COALESCE(pm_sku.meta_value, '') AS product_sku, 
                           pm_color_image.meta_value AS color_image, 
                           (SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE post_id = p.ID AND meta_key = '_thumbnail_id') AS thumbnail_id
                    FROM {$wpdb->prefix}posts p
                    LEFT JOIN {$wpdb->prefix}postmeta pm_sku ON p.ID = pm_sku.post_id AND pm_sku.meta_key = '_sku'
                    LEFT JOIN {$wpdb->prefix}postmeta pm_color_image ON p.ID = pm_color_image.post_id AND pm_color_image.meta_key = 'color_image'
                    WHERE p.post_type = 'product' AND p.post_status = 'publish'
                    ORDER BY p.post_title
                    LIMIT %d OFFSET %d
                    ",
                    $per_page,
                    $offset
                );
            }
        
            $results = $wpdb->get_results( $query, ARRAY_A );
        
            foreach ( $results as &$product ) {
                $product['colors'] = wp_get_object_terms( $product['product_id'], 'product_color', array( 'fields' => 'ids' ) );
                $product['color_image'] = wp_get_attachment_image_src( $product['thumbnail_id'], 'thumbnail' )[0] ?? '';
            }
        
            return $results;
        }
        
    }
}
