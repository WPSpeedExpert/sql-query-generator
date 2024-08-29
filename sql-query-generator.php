<?php
/*
Plugin Name:        SQL Query Generator for Search and Replace
Description:        A simple plugin to generate SQL queries for search and replace operations in WordPress.
Version:            0.1.0
Author:             WP Speed Expert
Author URI:         https://wpspeedexpert.com
License:            GPLv2 or later
GitHub Plugin URI:  https://github.com/WPSpeedExpert/sql-query-generator
GitHub Branch:      main
*/

// Shortcode to display the form and generate SQL queries
function sql_query_generator_shortcode() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_queries'])) {
        $from = esc_url_raw($_POST['from_url']);
        $to = esc_url_raw($_POST['to_url']);
        $prefix = sanitize_text_field($_POST['prefix']);

        // Generate the SQL queries
        $queries = "
        <code>
        UPDATE {$prefix}options SET option_value = REPLACE(option_value, '{$from}', '{$to}') WHERE option_name = 'home' OR option_name = 'siteurl';<br>
        UPDATE {$prefix}posts SET post_content = REPLACE (post_content, '{$from}', '{$to}');<br>
        UPDATE {$prefix}posts SET post_excerpt = REPLACE (post_excerpt, '{$from}', '{$to}');<br>
        UPDATE {$prefix}postmeta SET meta_value = REPLACE (meta_value, '{$from}', '{$to}');<br>
        UPDATE {$prefix}termmeta SET meta_value = REPLACE (meta_value, '{$from}', '{$to}');<br>
        UPDATE {$prefix}comments SET comment_content = REPLACE (comment_content, '{$from}', '{$to}');<br>
        UPDATE {$prefix}comments SET comment_author_url = REPLACE (comment_author_url, '{$from}', '{$to}');<br>
        UPDATE {$prefix}posts SET guid = REPLACE (guid, '{$from}', '{$to}') WHERE post_type = 'attachment';<br>
        </code>";
    } else {
        $queries = '';
    }

    // HTML form for user input
    ob_start();
    ?>
    <form method="post">
        <label for="from_url">From:</label><br>
        <input type="text" id="from_url" name="from_url" value="http://" required><br><br>
        <label for="to_url">To:</label><br>
        <input type="text" id="to_url" name="to_url" value="http://" required><br><br>
        <label for="prefix">Prefix:</label><br>
        <input type="text" id="prefix" name="prefix" value="wp_" required><br><br>
        <input type="submit" name="generate_queries" value="Generate queries">
    </form>
    <br>
    <?php echo $queries; ?>
    <?php
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('sql_query_generator', 'sql_query_generator_shortcode');
