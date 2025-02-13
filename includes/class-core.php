<?php  
/**
 * Handles static file generation, serving, and plugin setup.  
 */  
class StaticSnap_Core {  

    /**  
     * Initialize plugin hooks.  
     */  
    public static function initialize() {  
        // Generate HTML when a post is saved  
        add_action('save_post', [__CLASS__, 'generate_html'], 10, 3);  
        // Delete HTML when a post is trashed  
        add_action('trashed_post', [__CLASS__, 'delete_html']);  
        // Serve static HTML files  
        add_action('template_redirect', [__CLASS__, 'serve_html'], 1);  
    }  

    /**  
     * Plugin activation tasks.  
     */  
    public static function activate() {  
        self::create_static_dir();  
        self::add_rewrite_rules();  
        flush_rewrite_rules(); // Refresh rewrite rules  
    }  

    /**  
     * Plugin deactivation tasks.  
     */  
    public static function deactivate() {  
        self::remove_rewrite_rules();  
        flush_rewrite_rules();  
    }  

    /**  
     * Generate static HTML for a post.  
     *  
     * @param int $post_id Post ID.  
     * @param WP_Post $post Post object.  
     * @param bool $update Whether this is an update.  
     */  
    public static function generate_html($post_id, $post, $update) {  
        // Skip autosaves, revisions, and excluded posts  
        if (  
            wp_is_post_autosave($post_id) ||  
            wp_is_post_revision($post_id) ||  
            !in_array($post->post_type, ['post', 'page']) ||  
            get_post_meta($post_id, STATICSNAP_EXCLUDED_META_KEY, true)  
        ) {  
            return;  
        }  

        // Render the post as HTML  
        $html = self::render_html($post_id);  
        $filename = self::get_filename($post_id);  

        // Save to file  
        if ($html && $filename) {  
            $filepath = STATICSNAP_DIR . $filename;  
            file_put_contents($filepath, $html);  
        }  
    }  

    /**  
     * Render a post's frontend HTML.  
     *  
     * @param int $post_id Post ID.  
     * @return string HTML content.  
     */  
    private static function render_html($post_id) {  
        ob_start(); // Start output buffering  
        global $wp_query, $post;  

        // Backup original query and post  
        $original_query = $wp_query;  
        $original_post = $post;  

        // Simulate a frontend request  
        $wp_query = new WP_Query(['p' => $post_id, 'post_type' => get_post_type($post_id)]);  

        if ($wp_query->have_posts()) {  
            while ($wp_query->have_posts()) {  
                $wp_query->the_post();  
                // Load theme templates  
                get_header();  
                the_content();  
                get_footer();  
            }  
        }  

        // Restore original query and post  
        $wp_query = $original_query;  
        $post = $original_post;  
        wp_reset_postdata();  

        return ob_get_clean(); // Return captured HTML  
    }  

    /**  
     * Serve static HTML if it exists.  
     */  
    public static function serve_html() {  
        // Skip admin, feeds, or search pages  
        if (is_admin() || is_feed() || is_search()) {  
            return;  
        }  

        $post_id = get_queried_object_id();  
        $filename = self::get_filename($post_id);  
        $filepath = STATICSNAP_DIR . $filename;  

        // Serve the static file  
        if (file_exists($filepath)) {  
            readfile($filepath);  
            exit; // Stop WordPress from loading dynamically  
        }  
    }  

    /**  
     * Delete static HTML when a post is trashed.  
     *  
     * @param int $post_id Post ID.  
     */  
    public static function delete_html($post_id) {  
        $filename = self::get_filename($post_id);  
        $filepath = STATICSNAP_DIR . $filename;  

        if (file_exists($filepath)) {  
            unlink($filepath);  
        }  
    }  

    /**  
     * Get the filename for a post's static HTML.  
     *  
     * @param int $post_id Post ID.  
     * @return string Sanitized filename.  
     */  
    private static function get_filename($post_id) {  
        $slug = get_post_field('post_name', $post_id);  
        return sanitize_file_name($slug) . '.html';  
    }  

    /**  
     * Create the static directory and secure it.  
     */  
    private static function create_static_dir() {  
        if (!is_dir(STATICSNAP_DIR)) {  
            wp_mkdir_p(STATICSNAP_DIR);  
        }  

        // Block direct access via .htaccess  
        $htaccess = STATICSNAP_DIR . '.htaccess';  
        if (!file_exists($htaccess)) {  
            file_put_contents($htaccess, 'Deny from all');  
        }  
    }  

    /**  
     * Add rewrite rules to .htaccess.  
     */  
    private static function add_rewrite_rules() {  
        $htaccess = get_home_path() . '.htaccess';  
        $rules = [  
            '# BEGIN StaticSnap',  
            '<IfModule mod_rewrite.c>',  
            'RewriteEngine On',  
            'RewriteCond %{REQUEST_FILENAME} !-f',  
            'RewriteCond %{REQUEST_FILENAME} !-d',  
            'RewriteCond %{DOCUMENT_ROOT}/wp-content/static/$1.html -f',  
            'RewriteRule ^(.*)$ /wp-content/static/$1.html [L]',  
            '</IfModule>',  
            '# END StaticSnap'  
        ];  

        insert_with_markers($htaccess, 'StaticSnap', $rules);  
    }  

    /**  
     * Remove rewrite rules from .htaccess.  
     */  
    private static function remove_rewrite_rules() {  
        $htaccess = get_home_path() . '.htaccess';  
        insert_with_markers($htaccess, 'StaticSnap', []);  
    }  
}  