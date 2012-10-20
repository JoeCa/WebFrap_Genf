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
class LibGenfTreeNodeDesktopWidget
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * Enter description here ...
   * @var string
   */
  public $size  = 'medium';

  /**
   *
   * Enter description here ...
   * @var string
   */
  public $id    = null;

/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {


    $this->name = new LibGenfNameWidget( $this->node );

    $this->id   = trim( $this->node['id'] );

    if( isset( $this->node['size'] ) )
    {
      $this->size = trim($this->node['size']);
    }

  }//end protected function loadChilds */


}//end class LibGenfTreeNodeDesktopWidget

