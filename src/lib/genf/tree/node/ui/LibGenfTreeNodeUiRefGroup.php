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
 * Knoten welcher zum gruppieren in statistiken verwendet wird
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeUiRefGroup
  extends LibGenfTreeNode
{
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameMin( $this->node );

  }//end protected function loadChilds */
  

  /**
   * @return [string]
   */
  public function getKeys()
  {
    
    $keys = array();
    
    foreach( $this->node->keys->key as $key )
    {
      $keys[] = trim($key);
    }
    
    return $keys;
    
  }//end public function getKeys */



}//end class LibGenfTreeNodeUiRefGroup

