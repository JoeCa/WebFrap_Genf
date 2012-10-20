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
class LibGenfTreeNodePermissionArea
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var array
   */
  public $references = null;
  
/*//////////////////////////////////////////////////////////////////////////////
// Methodes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @return string
   */
  public function getKey()
  {
    
    if( isset($this->node['name']) )
      return trim($this->node['name']);
    else
      return null;
    
  }//end public function getKey */
  
  /**
   * @return string
   */
  public function getLevel()
  {
    
    if( isset($this->node['level']) )
      return trim($this->node['level']);
    else
      return null;
    
  }//end public function getLevel */
  
  /**
   * @return [LibGenfTreeNodePermissionAreaRef]
   */
  public function getReferences()
  {
    
    if( !is_null( $this->references ) )
      return $this->references;
      
    $this->references = array();
    
    if( !isset( $this->node->references->ref ) )
      return array();
      
    foreach( $this->node->references->ref as $ref )
    {
      $this->references[] = new LibGenfTreeNodePermissionAreaRef( $ref );
    }
    
    return $this->references;
    
  }//end public function getReferences */

}//end class LibGenfTreeNodePermission

