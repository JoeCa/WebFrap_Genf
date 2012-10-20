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
class LibGenfTreeNodeDataSource
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfNameMin
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */


  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getDataSource( )
  {
    
    return $this->management;
      
  }//end public function getDataSource */
  
  /**
   * @return string
   */
  public function getLinkAttr()
  {
    
    if( !isset( $this->node['link'] ) )
      return null;
    
    return trim($this->node['link']);
    
  }//end public function getLinkAttr */
  
  /**
   * @return string
   */
  public function getLinkEntity()
  {
    
    if( !isset( $this->node['entity_link'] ) )
      return null;
    
    return trim($this->node['entity_link']);
    
  }//end public function getLinkEntity */
  
  /**
   * @return array
   */
  public function getPath()
  {
    
    if( !isset( $this->node->path ) )
      return array();
      
    if( !isset( $this->node->path ) )
      return null;
      
    $path = explode( '.', trim($this->node->path) ) ;
    array_shift( $path );
     
    return $path;
    
  }//end public function getPath */
  
  /**
   * @return string
   */
  public function getPathStart()
  {
    
    if( !isset( $this->node->path ) )
      return null;
      
    $path = explode( '.', trim($this->node->path) ) ;
    
    return array_shift( $path );
    
  }//end public function getPathStart */


  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDatasource( $this->node );

    if( isset( $this->node['management'] ) )
      $this->management = $this->builder->getManagement( trim( $this->node['management'] ) );
    else
      $this->management = $this->builder->getManagement( trim( $this->node['entity'] ) );
    
  }//end protected function loadChilds */


}//end class LibGenfTreeNodeProcessDataSource

