<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <dominik.bonsch@webfrap.net>
* @date        :
* @copyright   : Webfrap Developer Network <contact@webfrap.net>
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/


/**
 * TreeNode for Fks
 * @package WebFrap
 * @subpackage GenF
 *
 * Referenz Tags in Listenelementen
 *
 *  <reference name="project_tasks" target="project_tasks"  >
 *    <field tag="small" name="name" />
 *  </reference>
 *
 */
class LibGenfTreeNodeUiListReference
  extends LibGenfTreeNode
{

  /**
   * @var array
   */
  public $fields = array();
  
  /**
   * @var array
   */
  public $groups = null;

  /**
   * Das Referenz Objekt
   * @var LibGenfTreeNodeReference
   */
  public $ref = null;

  /**
   * flag if the field reference maps to the target or the connection
   * @var string
   */
  public $refType = null;

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name     = new LibGenfNameTarget( $this->node );

    if( isset($this->node['ref_type']) )
    {
      $this->refType = trim($this->node['ref_type']);
    }

  }//end protected function loadChilds */


  /**
   * @param LibGenfEnvManagement $env
   * @return []
   */
  public function getFields( $env )
  {

    if( !$this->fields )
      $this->prepareFields( $env );

    return $this->fields;

  }//end public function getFields */



  /**
   * return the ui type for the list element
   * @return string
   */
  public function getUiType(  )
  {
    /*
    if( isset( $this->node['type'] ) )
      return SParserString::subToCamelCase( trim( $this->node['type'] ) );
    */
    
    return 'List';

  }//end public function getUiType */
  
  /**
   * return the ui type for the list element
   * @return string
   */
  public function getRefType(  )
  {

    if( isset( $this->node['type'] ) )
      return SParserString::subToCamelCase( trim( $this->node['type'] ) );
    
    return 'List';

  }//end public function getRefType */

  /**
   *
   * @param LibGenfEnvReference $env
   */
  protected function prepareFields( $env )
  {

    $fields = $this->extractFields( array(), $this->node );


    if( isset($env->ref) )
    {

      if( $env->ref->relation( Bdl::MANY_TO_MANY )  )
      {
        if( 'target' == $this->refType )
        {

          $targetManagement = $env->ref->targetManagement( );
          if( !$ref = $targetManagement->getReference( $this->name->target ) )
          {
            $this->error( 'Missing to Many Target Reference '.$this->name->target.' for env: '.$env->debugData() );
            return;
          }

        }
        else
        {

          $conManagement = $env->ref->connectionManagement();
          if( !$ref = $conManagement->getReference($this->name->target ) )
          {
            $this->error( 'Missing Connection reference '.$this->name->target.' for env: '.$env->debugData() );
            return;
          }
        }
      }
      else
      {

        $targetManagement = $env->ref->targetManagement();
        if( !$ref = $targetManagement->getReference( $this->name->target ) )
        {
          $this->error( 'Missing Target reference '.$this->name->target.' for env: '.$env->debugData() );
          return;
        }
      }

    }
    else
    {

      if( !$ref = $env->management->getReference( $this->name->target ) )
      {
        $this->error( 'Missing reference '.$this->name->target.' for env: '.$env->debugData() );
        return;
      }
    }

    $targetMgmt = $ref->targetManagement();
    $fieldObjs  = array();

    $fieldClassName = $this->builder->getNodeClass( 'UiListField' );
    foreach( $fields as $field )
    {
      $trgtField    = $targetMgmt->getField( trim($field['name']) );

      if( !$trgtField )
      {
        $this->builder->error( "Missing attribute: {$field['name']} on management {$targetMgmt->name->name} in env: {$env->name->name}" );
        continue;
      }

      $fieldObjs[]  = new $fieldClassName( $field, $trgtField );
    }

    $this->fields = $fieldObjs;

  }//end protected function prepareFields */

  /**
   * @param array $fields
   * @param SimpleXmlElement $node
   */
  protected  function extractFields( $fields, $node )
  {

    if( !$children = $node->children() )
      return $fields;

    $listElem   = array( 'field', 'value', 'input' );

    foreach( $children as $child )
    {

      $nodeName = strtolower($child->getName());

      if( in_array($nodeName, $listElem) )
      {
        $fields[] = $child;
      }
      else
      {
        $fields = $this->extractFields( $fields, $child );
      }

    }

    return $fields;

  }//end protected function extractFields */
  
////////////////////////////////////////////////////////////////////////////////
// Grouping
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param LibGenfEnvManagement $env
   * @return []
   */
  public function getGroups( $env )
  {

    if( is_null($this->groups) )
      $this->prepareGroups( $env );

    return $this->groups;

  }//end public function getGroups */
  
  
  /**
   * @return [LibGenfTreeNodeUiRefGroup]
   */
  public function prepareGroups()
  {
    
    $this->groups = array();
    
    foreach( $this->node->groups->group as $group )
    {
      $this->groups[] = new LibGenfTreeNodeUiRefGroup( $group );
    }
    
    return $this->groups;
    
  }//end public function prepareGroups */

  /**
   * @return string
   */
  public function getGroupPath( )
  {

    return isset( $this->node->groups['path'] )
      ? trim($this->node->groups['path'])
      : null;
    
  }//end public function getGroupPath */
  
  /**
   * @return string
   */
  public function getSeperator( )
  {

    return isset( $this->node->groups->seperator )
      ? trim( $this->node->groups->seperator )
      : ',';
    
  }//end public function getSeperator */
  
  /**
   * @return string
   */
  public function getGroupPathAttrKey( )
  {

    $path = $this->getGroupPath();
    $tmp  = explode( '.', $path );
    
    return array_pop( $tmp );
    
  }//end public function getGroupPathAttrKey */
  
  /**
   * @param LibGenfEnvReference $env
   * @return [[string,LibGenfTreeNodeManagement]]
   */
  public function getGroupPathNodes( $env )
  {

    $path = $this->getGroupPath();
    $tmp  = explode( '.', $path );
    
    if( $this->ref->relation( Bdl::MANY_TO_ONE ) )
    {
      $mgmt = $this->ref->targetManagement()->entity->getAttrTarget($tmp[0],'management');
    }
    else 
    {
      $mgmt = $this->ref->connectionManagement()->entity->getAttrTarget($tmp[0],'management');
    }
    
    return array( array( $tmp[0], $mgmt ) );
    
  }//end public function getGroupPathNodes */
  

}//end class LibGenfTreeNodeUiListReference

