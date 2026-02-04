<?php
/**
 * Registers the custom post type 'our_factory'.
 */
function create_our_factory_cpt() {
    // Register Custom Post Type
    $labels = array(
        'name'               => _x( 'My Factories', 'Post Type General Name', 'gustabe-cpt-manager' ),
        'singular_name'      => _x( 'Factory', 'Post Type Singular Name', 'gustabe-cpt-manager' ),
        'menu_name'          => __( 'My Factory', 'gustabe-cpt-manager' ),
        'name_admin_bar'     => __( 'Factory', 'gustabe-cpt-manager' ),
        'add_new'            => __( 'Add New Factory', 'gustabe-cpt-manager' ),
        'add_new_item'       => __( 'Add New Factory', 'gustabe-cpt-manager' ),
        'new_item'           => __( 'New Factory', 'gustabe-cpt-manager' ),
        'edit_item'          => __( 'Edit Factory', 'gustabe-cpt-manager' ),
        'view_item'          => __( 'View Factory', 'gustabe-cpt-manager' ),
        'all_items'          => __( 'All Factories', 'gustabe-cpt-manager' ),
        'search_items'       => __( 'Search Factories', 'gustabe-cpt-manager' ),
        'not_found'          => __( 'No Factories found', 'gustabe-cpt-manager' ),
        'not_found_in_trash' => __( 'No Factories found in Trash', 'gustabe-cpt-manager' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'our-factory' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'       => 5,
        'menu_icon'          => 'dashicons-building',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'taxonomies'          => array( 'our_factory_category', 'our_factory_tag' ), // Add custom taxonomies here
    );

    register_post_type( 'our_factory', $args );
}
add_action( 'init', 'create_our_factory_cpt' );

/**
 * Registers the custom taxonomy 'our_factory_category'.
 */
function create_our_factory_taxonomy() {
    // Labels for the custom taxonomy
    $labels = array(
        'name'              => _x( 'Factory Categories', 'Taxonomy General Name', 'gustabe-cpt-manager' ),
        'singular_name'     => _x( 'Factory Category', 'Taxonomy Singular Name', 'gustabe-cpt-manager' ),
        'search_items'      => __( 'Search Categories', 'gustabe-cpt-manager' ),
        'all_items'         => __( 'All Categories', 'gustabe-cpt-manager' ),
        'parent_item'       => __( 'Parent Category', 'gustabe-cpt-manager' ),
        'parent_item_colon' => __( 'Parent Category:', 'gustabe-cpt-manager' ),
        'edit_item'         => __( 'Edit Category', 'gustabe-cpt-manager' ),
        'update_item'       => __( 'Update Category', 'gustabe-cpt-manager' ),
        'add_new_item'      => __( 'Add New Category', 'gustabe-cpt-manager' ),
        'new_item_name'     => __( 'New Category Name', 'gustabe-cpt-manager' ),
        'menu_name'         => __( 'Factory Categories', 'gustabe-cpt-manager' ),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'our-factory-category' ),
    );

    register_taxonomy( 'our_factory_category', array( 'our_factory' ), $category_args );
}
add_action( 'init', 'create_our_factory_taxonomy' );

/**
 * Registers the custom taxonomy 'our_factory_tag'.
 */
function create_our_factory_tags() {
    // Labels for the custom taxonomy
    $labels = array(
        'name'                       => _x( 'Factory Tags', 'Taxonomy General Name', 'gustabe-cpt-manager' ),
        'singular_name'              => _x( 'Factory Tag', 'Taxonomy Singular Name', 'gustabe-cpt-manager' ),
        'search_items'               => __( 'Search Tags', 'gustabe-cpt-manager' ),
        'popular_items'              => __( 'Popular Tags', 'gustabe-cpt-manager' ),
        'all_items'                  => __( 'All Tags', 'gustabe-cpt-manager' ),
        'edit_item'                  => __( 'Edit Tag', 'gustabe-cpt-manager' ),
        'view_item'                  => __( 'View Tag', 'gustabe-cpt-manager' ),
        'update_item'                => __( 'Update Tag', 'gustabe-cpt-manager' ),
        'add_new_item'               => __( 'Add New Tag', 'gustabe-cpt-manager' ),
        'new_item_name'              => __( 'New Tag Name', 'gustabe-cpt-manager' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'gustabe-cpt-manager' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'gustabe-cpt-manager' ),
        'choose_from_most_used'      => __( 'Choose from the most used tags', 'gustabe-cpt-manager' ),
        'not_found'                  => __( 'No tags found', 'gustabe-cpt-manager' ),
        'menu_name'                  => __( 'Factory Tags', 'gustabe-cpt-manager' ),
    );

    $tag_args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'our-factory-tag' ),
    );

    register_taxonomy( 'our_factory_tag', 'our_factory', $tag_args );
}
add_action( 'init', 'create_our_factory_tags' );
/**
 * Registers strings for 'our_factory' CPT with Polylang.
 */
function register_our_factory_strings_with_polylang() {
    if ( function_exists( 'pll_register_string' ) ) {
        $labels = array(
            'My Factories',
            'Factory',
            'My Factory',
            'Factory',
            'Add New Factory',
            'Add New Factory',
            'New Factory',
            'Edit Factory',
            'View Factory',
            'All Factories',
            'Search Factories',
            'No Factories found',
            'No Factories found in Trash',
            'Factory Categories',
            'Factory Category',
            'Search Categories',
            'All Categories',
            'Parent Category',
            'Parent Category:',
            'Edit Category',
            'Update Category',
            'Add New Category',
            'New Category Name',
            'Factory Categories',
            'Factory Tags',
            'Factory Tag',
            'Search Tags',
            'Popular Tags',
            'All Tags',
            'Edit Tag',
            'Update Tag',
            'Add New Tag',
            'New Tag Name',
            'Separate tags with commas',
            'Add or remove tags',
            'Choose from the most used tags',
            'No tags found',
            'Factory Tags',
        );
        foreach ( $labels as $label ) {
            pll_register_string( $label, $label, 'gustabe-cpt-manager', false );
        }
    }
}

/**
 * Registers 'our_factory' CPT and taxonomies with Polylang.
 */
function register_our_factory_with_polylang() {
    if ( function_exists( 'pll_register_post_type' ) && function_exists( 'pll_register_taxonomy' ) ) {
        pll_register_post_type( 'our_factory' );
        pll_register_taxonomy( 'our_factory_category' );
        pll_register_taxonomy( 'our_factory_tag' );
    }
}