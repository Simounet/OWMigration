<?php

// Operator autoloading

$eZTemplateOperatorArray = array( );

$eZTemplateOperatorArray[] = array(
    'script' => 'extension/owmigration/autoloads/owmigrationoperators.php',
    'class' => 'OWMigrationOperators',
    'operator_names' => array( 'camelize', 'display_content_migration_class' )
);

?>