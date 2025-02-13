<?php  
/**
 * WP-CLI commands for StaticSnap.  
 */  
class StaticSnap_CLI {  

    /**  
     * Regenerate all static HTML files.  
     *  
     * ## EXAMPLES  
     * wp staticsnap generate  
     */  
    public function generate() {  
        WP_CLI::line('Starting static file generation...');  

        $query = new WP_Query([  
            'post_type' => ['post', 'page'],  
            'posts_per_page' => -1,  
            'post_status' => 'publish',  
        ]);  

        while ($query->have_posts()) {  
            $query->the_post();  
            $post_id = get_the_ID();  
            StaticSnap_Core::generate_html($post_id, get_post(), true);  
            WP_CLI::line('Generated: ' . get_the_title());  
        }  

        WP_CLI::success('All static files regenerated!');  
    }  
}  