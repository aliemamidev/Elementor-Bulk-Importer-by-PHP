# Elementor Bulk Importer

A simple PHP script to bulk-import Elementor templates (.json) directly into a WordPress site using Elementor's internal API. Ideal for environments where WP-CLI is restricted (cPanel, Cloud hosting, etc.).

## Project Structure

```
elementor-bulk-importer/       # repository root
├── README.md                  # This file
├── elem-json/                 # Place all your .json Elementor exports here
│   ├── alec.json
│   ├── emily.json
│   └── ...
└── elementor-import.php       # Main import script
```

## Prerequisites

1. WordPress installed and Elementor active in your site.
2. Administrator user with ID `1`.
3. PHP 7.4+ with `file_get_contents` and `base64_encode` enabled.
4. Place this script (`elementor-import.php`) in your WordPress **root directory** (where `wp-load.php` is located).

## Setup

1. Download the import script:

   ```bash
   cd /path/to/your/wordpress
   wget https://raw.githubusercontent.com/aliemamidev/Elementor-Bulk-Importer-by-PHP/main/elementor-import.php
   ```

2. Create a folder named `elem-json` in the same root directory:

   ```bash
   mkdir elem-json
   ```

3. Place your Elementor `.json` export files into the `elem-json/` folder:

   ```bash
   mv ~/Downloads/my-template.json elem-json/
   ```

## Usage

### Via Web Browser

1. Visit the script in your browser:

   ```
   https://your-domain.com/elementor-import.php
   ```
2. You’ll see import logs printed in plain text.

### Via CLI

Run:

```bash
php elementor-import.php
```

## Configuration Variables

At the top of `elementor-import.php`, you can adjust:

```php
// Administrator user ID to impersonate during import
$admin_user_id = 1;

// Directory containing .json template files
$templates_dir = __DIR__ . '/elem-json';
```

## elementor-import.php (simplified excerpt)

```php
<?php
require_once __DIR__ . '/wp-load.php';
// Impersonate Admin
wp_set_current_user( $admin_user_id );

$files = glob( $templates_dir . '/*.json' );
foreach ( $files as $file ) {
    $json = file_get_contents( $file );
    $args = [ 'fileData' => base64_encode( $json ), 'fileName' => basename($file) ];
    $result = \Elementor\Plugin::instance()->templates_manager->import_template( $args );
    // ... log results
}
```

## Security Considerations

* **Delete or restrict** `elementor-import.php` after use to prevent unauthorized imports.
* Ensure `elem-json/` is **not** web-accessible or contains only safe JSON files.

## License

MIT © SeyedAli Emami
[aliemamidev@gmail.com](mailto:aliemamidev@gmail.com)
