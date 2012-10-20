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
class LibGenfTreeNodeParam
  extends LibGenfTreeNode
{

  /**
   * Der Type des Parameters
   * @var string
   */
  public $type = 'value';
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameParam( $this->node );
    
    if( isset( $this->node['type'] ) )
    {
      $this->type = strtolower(trim($this->node['type']));
    }

  }//end protected function loadChilds */
  


}//end class LibGenfTreeNodeParam

