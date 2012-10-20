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
 * Auslesen aller vorhandener Komponenten
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeComponentSelectbox
  extends LibGenfTreeComponent
{
////////////////////////////////////////////////////////////////////////////////
// Index Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * create a component node in the tree
   * @param unknown_type $name
   * @param unknown_type $params
   * @return unknown_type
   */
  public function create( $name, $params = array() )
  {

    /*
      <component type="selectbox">
        <fields value="this.name" id="this.rowid" title=""/>
      </component>
     */

    $value = isset( $params['value'] )?trim($params['value']):'name';
    $id = isset( $params['id'] )?trim($params['id']):'name';


    $xml =     <<<CODE
  <component type="selectbox" name="$name" src="$name" >
     <fields value="this.name" id="this.rowid" title="" />
  </component>
CODE;


    return $xml;

  }//end public function create */
  
  

  /**
   * @return array<LibGenfTreeNodeFilterCheck>
   */
  public function getFilter( )
  {

    $filter = array();
    
    if( isset($this->node->filter->check ) )
    {
      $filter = $this->node->filter;
    }

    if( !$filter )
    {
      return array();
    }
    
    $checks = array();
    
    foreach( $filter->check as $check  )
    {
      
      $className = 'LibGenfTreeNodeFilter_'.SParserString::subToCamelCase( $check['type'] );
      
      if(  !Webfrap::classLoadable($className) )
      {
        $this->builder->dumpError( "Invalid Filtercheck ".$check['type'] );
        continue;
      }
      
      $checks[] = new $className( $check );
    }
  
    return $checks;

  }//end public function getFilter */
  
  
  /**
   * Prüfen ob filter vorhanden sind
   * 
   * @param string $type
   * @param boolean $withControls Nur Filter zurückgeben welche auch Controll Elemente beschreiben
   * @return string
   */
  public function hasFilter(  )
  {
    
    return isset($this->node->filter->check);

  }//end public function hasFilter */




} // end class LibGenfTreeComponentSelectbox
