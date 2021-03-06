<?php

$Module = $Params["Module"];
include_once ('kernel/common/template.php');
if( is_callable( 'eZTemplate::factory' ) ) {
    $tpl = eZTemplate::factory( );
} else {
    $tpl = templateInit( );
}

$workflowID = FALSE;
$workflow = FALSE;
if( $Module->hasActionParameter( 'WorkflowID' ) ) {
    $workflowID = $Module->actionParameter( 'WorkflowID' );
} elseif( isset( $Params['WorkflowID'] ) ) {
    $workflowID = $Params['WorkflowID'];
}

if( $workflowID && is_numeric( $workflowID ) ) {
    $workflow = eZWorkflow::fetch( $workflowID );
}
if( ( $workflow instanceof eZWorkflow && ($Module->isCurrentAction( 'ExportCode' ) ) || $Module->isCurrentAction( 'ExportAllClassCode' )) ) {
    $mainTmpDir = eZSys::cacheDirectory( ) . '/owmigration/';
    $tmpDir = $mainTmpDir . time( ) . '/';
    OWMigrationWorkflowCodeGenerator::createDirectory( $tmpDir );
}
if( $Module->isCurrentAction( 'ExportCode' ) ) {
    $filepath = OWMigrationWorkflowCodeGenerator::getMigrationClassFile( $workflow, $tmpDir );
    $file = pathinfo( $filepath, PATHINFO_BASENAME );
    eZFile::download( $filepath, true, $file );
    OWMigrationWorkflowCodeGenerator::removeDirectory( $tmpDir );
} elseif( $Module->isCurrentAction( 'ExportAllClassCode' ) ) {
    $workflowList = eZWorkflow::fetchList( );
    $archiveFile = 'workflows.zip';
    $archiveFilepath = $tmpDir . $archiveFile;
    eZFile::create( $archiveFile, $tmpDir );
    @unlink( $archiveFilepath );
    $zip = new ZipArchive;
    if( $zip->open( $archiveFilepath, ZIPARCHIVE::CREATE ) === TRUE ) {
        foreach( $workflowList as $workflow ) {
            $filepath = OWMigrationWorkflowCodeGenerator::getMigrationClassFile( $workflow, $tmpDir );
            $file = pathinfo( $filepath, PATHINFO_BASENAME );
            $zip->addFile( $filepath, $file );
        }
        $zip->close( );
        eZFile::download( $archiveFilepath, true, $archiveFile );
        OWMigrationWorkflowCodeGenerator::removeDirectory( $tmpDir );
    }
} else {
    $tpl->setVariable( 'workflowlist', eZWorkflow::fetchList( ) );
    $tpl->setVariable( 'workflow_id', $workflowID );
    $Result['content'] = $tpl->fetch( 'design:owmigration/codegenerator/workflows.tpl' );
    $Result['left_menu'] = 'design:owmigration/menu.tpl';
    if( function_exists( 'ezi18n' ) ) {
        $Result['path'] = array(
            array(
                'url' => 'owmigration/dashboard',
                'text' => ezi18n( 'design/admin/parts/owmigration/menu', 'Migrations' )
            ),
            array( 'text' => ezi18n( 'design/admin/parts/owmigration/menu', 'Code generator' ) ),
            array(
                'url' => 'owmigration/workflows',
                'text' => ezi18n( 'design/admin/parts/owmigration/menu', 'Workflow' )
            )
        );

    } else {
        $Result['path'] = array(
            array(
                'url' => 'owmigration/dashboard',
                'text' => ezpI18n::tr( 'design/admin/parts/owmigration/menu', 'Migrations' )
            ),
            array( 'text' => ezpI18n::tr( 'design/admin/parts/owmigration/menu', 'Code generator' ) ),
            array(
                'url' => 'owmigration/workflows',
                'text' => ezpI18n::tr( 'design/admin/parts/owmigration/menu', 'Workflow' )
            )
        );
    }
}
?>
