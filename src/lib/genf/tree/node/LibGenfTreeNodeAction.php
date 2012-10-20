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
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeAction
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var array
   */
  public $methodes = array();
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameAction( $this->node );
    
    if( isset( $this->node->methodes->method ) )
    {
      foreach( $this->node->methodes->method as $method )
      {
        $this->methodes[] = new LibGenfTreeNodeActionMethod( $method );
      }
    }

  }//end protected function loadChilds */
  
  /**
   * Visibility node getter
   * @return LibGenfTreeNodeUiVisibility oder null im fehlerfall
   */
  public function getVisibility()
  {

    if( !isset( $this->node->visibility ) )
      return null;

    $className = $this->builder->getNodeClass( 'UiVisibility' );

    return new $className( $this->node->visibility );

  }//end public function getVisibility */
  
  /**
   * @return LibGenfTreeNodeActionAccess
   */
  public function getAccess()
  {

    if( !isset( $this->node->access ) )
      return null;

    $classname   = $this->builder->getNodeClass( 'ActionAccess' );

    return new $classname( $this->node->access );

  }//end public function getAccess */
  
  /**
   * Checken ob überhaupt ein Service für die Action generiert werden soll
   * @return boolean
   */
  public function createService()
  {

    if( !isset( $this->node->service ) )
      return true;

    if( 'false' == trim($this->node->service ) )
      return false;
    else
      return true;

  }//end public function createService */
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return array
   */
  public function getMethodes()
  {

    return $this->methodes;

  }//end public function getMethodes */
  
  /**
   * @return string
   */
  public function getType()
  {

    if( !isset($this->node['type']) )
      return null;
    else
      return SParserString::subToName(trim($this->node['type'])) ;

  }//end public function getType */

  /**
   * @return string
   */
  public function getAction()
  {

    if( isset($this->node['action']) )
      return trim($this->node['action']);
    else
      return trim($this->node);

  }//end public function getAction */

  /**
   * Kontext definiert ob die action in relation zu einem datensaz,
   * in relation zu einer enity, maske
   * oder whatever ist
   * 
   * @return string
   */
  public function getContext()
  {

    if( isset($this->node['context']) )
      return trim($this->node['context']);
    else
      return null;

  }//end public function getContext */

  /**
   * @return string
   */
  public function getHref()
  {

    if( isset($this->node['href']) )
      return trim($this->node['href']);
    else
      return trim($this->node);

  }//end public function getHref */

  /**
   * @return string
   */
  public function getTarget()
  {

    if( isset( $this->node['target'] ) )
      return trim( $this->node['target'] );    
    else if( isset( $this->node['entity'] ) )
      return trim( $this->node['entity'] );
    else
      return null;

  }//end public function getTarget */

  /**
   * @return string
   */
  public function getRequestType()
  {

    if( !isset( $this->node['type'] ) )
      return 'wcm wcm_req_mtab';
    else
      return 'wcm wcm_req_'.trim( $this->node['type'] );

  }//end public function getRequestType */

  /**
   * @return array<LibGenfTreeNodeProcedure>
   */
  public function getProcedures()
  {
    
    if( !isset( $this->procedures->procedure ) )
      return array();
      
    $procedures = array();
      
    foreach( $this->procedures->procedure as $procedure )
    {
      
      $procedureType = SParserString::subToCamelCase( trim($procedure['type'] ) );
      $className = $this->builder->getNodeClass( 'Procedure'.$procedureType );
      
      if( !$className )
      {
        $this->builder->error( "Requested nonexisting Procedure{$procedureType} in ".$this->builder->dumpEnv() );
        continue;
      }
      
      $procedures[] = new  $className( $procedure );
    }

    return $procedures;
    
  }//end public function getProcedures */
  


}//end class LibGenfTreeNodeAction

