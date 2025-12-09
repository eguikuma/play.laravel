#!/bin/bash

set -e

# 1. examples/
rm -rf examples

# 2. _ide_helper_models.php
cat > _ide_helper_models.php << 'EOF'
<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


EOF

# 3. bootstrap/app.php
docker compose exec -T app php -r '
$content = file_get_contents("bootstrap/app.php");
$content = preg_replace("/\s*\\\\Examples\\\\[^,]+,?\n?/", "", $content);
file_put_contents("bootstrap/app.php", $content);
'

# 4. composer.json
docker compose exec -T app php -r '
$json = json_decode(file_get_contents("composer.json"), true);
if (isset($json["autoload-dev"]["psr-4"])) {
    $json["autoload-dev"]["psr-4"] = array_filter(
        $json["autoload-dev"]["psr-4"],
        fn($key) => !str_starts_with($key, "Examples\\"),
        ARRAY_FILTER_USE_KEY
    );
}
file_put_contents(
    "composer.json",
    json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
);
'

# 5. phpunit.xml
docker compose exec -T app php -r '
$xml = new DOMDocument();
$xml->preserveWhiteSpace = false;
$xml->formatOutput = true;
$xml->load("phpunit.xml");
$xpath = new DOMXPath($xml);
foreach ($xpath->query("//testsuite[@name=\"Examples\"]") as $node) {
    $node->parentNode->removeChild($node);
}
$xml->save("phpunit.xml");
'

# 6. phpstan.neon
docker compose exec -T app php -r '
$content = file_get_contents("phpstan.neon");
$lines = explode("\n", $content);
$lines = array_filter($lines, fn($line) => trim($line) !== "- examples");
file_put_contents("phpstan.neon", implode("\n", $lines));
'

# 7. config/ide-helper.php
docker compose exec -T app php -r '
$content = file_get_contents("config/ide-helper.php");
$content = preg_replace("/\s*'\''examples'\'',?\n?/", "\n", $content);
file_put_contents("config/ide-helper.php", $content);
'

# 8. refresh
docker compose exec app composer dump-autoload
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan ide-helper:generate
docker compose exec app php artisan ide-helper:models -N
docker compose exec app php artisan ide-helper:meta
docker compose exec app ./vendor/bin/pint
docker compose exec app ./vendor/bin/phpstan analyse
docker compose exec app php artisan test
