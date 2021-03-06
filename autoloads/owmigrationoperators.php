<?php

class OWMigrationOperators {
    /*!
     Constructor
     */
    function OWMigrationOperators( ) {
        $this->Operators = array(
            'camelize',
            'display_content_migration_class',
            'display_role_migration_class',
            'display_workflow_migration_class',
            'display_state_group_migration_class'
        );
    }

    /*!
     Returns the operators in this class.
     */
    function & operatorList( ) {
        return $this->Operators;
    }

    /*!
     \return true to tell the template engine that the parameter list
     exists per operator type, this is needed for operator classes
     that have multiple operators.
     */
    function namedParameterPerOperator( ) {
        return true;
    }

    /*!
     Both operators have one parameter.
     See eZTemplateOperator::namedParameterList()
     */
    function namedParameterList( ) {

        return array(
            'camelize' => array( ),
            'display_content_migration_class' => array( ),
            'display_role_migration_class' => array( ),
            'display_workflow_migration_class' => array( ),
            'display_state_group_migration_class' => array( )
        );
    }

    /*!
     \Executes the needed operator(s).
     \Checks operator names, and calls the appropriate functions.
     */
    function modify( $tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters ) {
        switch ( $operatorName ) {
            case 'camelize' :
                $operatorValue = $this->camelize( $operatorValue );
                break;
            case 'display_content_migration_class' :
                $operatorValue = $this->displayContentMigrationClass( $operatorValue );
                break;
            case 'display_role_migration_class' :
                $operatorValue = $this->displayRoleMigrationClass( $operatorValue );
                break;
            case 'display_workflow_migration_class' :
                $operatorValue = $this->displayWorkflowMigrationClass( $operatorValue );
                break;
            case 'display_state_group_migration_class' :
                $operatorValue = $this->displayStateGroupMigrationClass( $operatorValue );
                break;
        }
    }

    function camelize( $operatorValue ) {
        $trans = eZCharTransform::instance( );
        return $trans->transformByGroup( $operatorValue, 'camelize' ); 
    }

    function displayContentMigrationClass( $operatorValue ) {
        $geshi = new GeSHi(OWMigrationContentClassCodeGenerator::getMigrationClass( $operatorValue ), 'php');
        $geshi->set_tab_width(4);
        $geshi->set_line_ending( "\n" );
        $geshi->enable_keyword_links( FALSE );
        $output = $geshi->parse_code();
        $geshi->indent($output);
        return $output;
    }

    function displayRoleMigrationClass( $operatorValue ) {
        $geshi = new GeSHi(OWMigrationRoleCodeGenerator::getMigrationClass( $operatorValue ), 'php');
        $geshi->set_tab_width(4);
        $geshi->set_line_ending( "\n" );
        $geshi->enable_keyword_links( FALSE );
        $output = $geshi->parse_code();
        $geshi->indent($output);
        return $output;
    }

    function displayWorkflowMigrationClass( $operatorValue ) {
        $geshi = new GeSHi(OWMigrationWorkflowCodeGenerator::getMigrationClass( $operatorValue ), 'php');
        $geshi->set_tab_width(4);
        $geshi->set_line_ending( "\n" );
        $geshi->enable_keyword_links( FALSE );
        $output = $geshi->parse_code();
        $geshi->indent($output);
        return $output;
    }

    function displayStateGroupMigrationClass( $operatorValue ) {
        $geshi = new GeSHi(OWMigrationStateGroupCodeGenerator::getMigrationClass( $operatorValue ), 'php');
        $geshi->set_tab_width(4);
        $geshi->set_line_ending( "\n" );
        $geshi->enable_keyword_links( FALSE );
        $output = $geshi->parse_code();
        $geshi->indent($output);
        return $output;
    }
    /// \privatesection
    var $Operators;
}
?>