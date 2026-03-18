<?php
/**
 * setup_db.php
 * Run this ONCE from the command line or browser to create the SQLite database.
 * Place this file in a /database/ folder, e.g. database/setup_db.php
 *
 * Command line: php database/setup_db.php
 */

$db_path = __DIR__ . '/models.db';

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table
    $db->exec("
        CREATE TABLE IF NOT EXISTS models (
            id          INTEGER PRIMARY KEY AUTOINCREMENT,
            title       TEXT    NOT NULL,
            description TEXT    NOT NULL,
            model_path  TEXT    NOT NULL,
            source      TEXT    NOT NULL
        )
    ");

    // Clear existing data for safe re running
    $db->exec("DELETE FROM models");

    // Insert the three models ususinge the same path as model_data.json
    $stmt = $db->prepare("
        INSERT INTO models (title, description, model_path, source)
        VALUES (:title, :description, :model_path, :source)
    ");

    $models = [
        [
            'title'       => 'Retro Game Console',
            'description' => 'This retro console captures my lifelong love for gaming. It began with the Nintendo DS Lite and evolving through the 3DS and Switch. The model was built in Blender 5.0 on mac, featuring a wood-panelled body with an image texture mapped to the screen displaying a Mario game. Key technical challenges included managing shared materials across multiple objects, resolving overlapping geometry that caused rendering artefacts, and ensuring all textures exported correctly to GLTF format for use in Three.js.',
            'model_path'  => 'assets/models/retro_console.glb',   // Relative path used by Three.js loader
            'source'      => 'Original Model by Ayodele Pearce'
        ],
        [
            'title'       => 'Pick Axe',
            'description' => 'A pickaxe does not wait for opportunity, it creates it. This model represents my drive to work hard, stay focused and carve out the future I want for myself, one swing at a time. No one sees the hours of hard work or the moments spent doubting and wondering if all the hard work will beworth it, but we keep pushing one day at a time.',
            'model_path'  => 'assets/models/pick_axe.glb',
            'source'      => 'Original Model by Ayodele Pearce'
        ],
        [
            'title'       => 'Sushi',
            'description' => 'Sushi represents my first taste of Japanese culture, experienced at Yo Sushi with my sister during a holiday from Nigeria. Beyond its personal significance, this model presented an interesting technical challenge in Blender — replicating the translucent textures of raw fish, the subtle sheen of rice, and the deep green of nori using Principled BSDF materials. Each element required careful attention to roughness and colour values to achieve a realistic result.',
            'model_path'  => 'assets/models/sushi.glb',
            'source'      => 'Original Model by Ayodele Pearce'
        ]
    ];

    foreach ($models as $model) {
        $stmt->execute($model);
    }

    echo "Database created successfully at: " . $db_path . "\n";
    echo "Rows inserted: " . count($models) . "\n";

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage() . "\n");
}
?>