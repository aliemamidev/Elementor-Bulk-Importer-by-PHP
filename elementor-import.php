<?php
// elementor-import.php
// Place this file in the WordPress root directory (where wp-load.php lives).

// Output plain text
header('Content-Type: text/plain; charset=UTF-8');

// Load WordPress environment
if ( ! file_exists( __DIR__ . '/wp-load.php' ) ) {
    die( 'Error: wp-load.php not found. Ensure this file is in the WordPress root directory.' );
}
require __DIR__ . '/wp-load.php';

// Check Elementor is active
if ( ! class_exists( '\Elementor\Plugin' ) ) {
    die( 'Error: Elementor plugin is not active.' );
}

// Temporarily allow import without checking user capabilities
add_filter( 'elementor/templates/manager/import_permissions', '__return_true' );

// Set current user to administrator ID 1
wp_set_current_user( 1 );

// Directory containing JSON templates
$dir = __DIR__ . '/elem-json';

// Find all JSON files
$files = glob( $dir . '/*.json' );
if ( empty( $files ) ) {
    die( 'Error: No JSON files found in elem-json directory.' );
}

// Import each template
foreach ( $files as $file ) {
    $json = file_get_contents( $file );
    if ( ! $json ) {
        echo "Failed to read $file\n";
        continue;
    }

    $args = [
        'fileData' => base64_encode( $json ),
        'fileName' => basename( $file ),
    ];

    $result = \Elementor\Plugin::instance()->templates_manager->import_template( $args );

    if ( is_wp_error( $result ) ) {
        echo sprintf( "Error importing %s: %s\n", basename( $file ), $result->get_error_message() );
    } else {
        // $result is an array of imported template IDs
        $ids = is_array( $result ) ? implode( ',', $result ) : (string) $result;
        echo sprintf( "Imported %s successfully (Template ID(s): %s)\n", basename( $file ), $ids );
    }
}
