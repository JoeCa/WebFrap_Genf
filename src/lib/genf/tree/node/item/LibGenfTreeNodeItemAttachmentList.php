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
 */
class LibGenfTreeNodeItemAttachmentList
  extends LibGenfTreeNodeItem
{

  /**
   * @return string
   */
  public function getCatridgeClass()
  {
    
    return 'ItemAttachmentList';
    
  }//end public function getCatridgeClass */
  

  /**
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameItem( $this->node );
    
    if( isset($this->node->access) )
    {
      $this->access = new LibGenfTreeNodeElementAccess($this->node->access);
    }

  }//end protected function loadChilds */
  
  /**
   * @return LibGenfTreeNodeElementAccess
   */
  public function getAccess()
  {

    return $this->access;

  }//end public function getAccess */

  
  /**
   * Wenn nicht auf die Rowid sondern auf eine andere id auf dem Datensatz
   * verwiesen wird
   * 
   * @return string
   */
  public function getRefField()
  {
    
    if( isset( $this->node['ref_field'] ) )
      return trim($this->node['ref_field']);

  }//end public function getRefField */
  
  /**
   * @return string
   */
  public function getFilterSource()
  {
    
    return isset( $this->node->filter['key'] )?trim($this->node->filter['key']):null;
    
  }//end public function getFilterSource */
  
  /**
   * @return string
   */
  public function getFilterKeys()
  {
    
    $keys = array();
    
    if( $this->node->filter->type )
    {
      
      foreach( $this->node->filter->type as $type )
      {
        $keys[] = trim($type['key']);
      }
      
    }
    
    return $keys;
    
  }//end public function getFilterKeys */

}//end class LibGenfTreeNodeItemRoleList

