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
class LibGenfTreeNodeModule
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  public $name = null;

  /**
   *
   * @return string the name of the entity
   */
  public function name()
  {
    return trim( $this->node['name'] );
  }//end public function name

/*//////////////////////////////////////////////////////////////////////////////
// Visibility
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Visibility node getter
   * @return LibGenfTreeNodeUi oder null im fehlerfall
   */
  public function getUi()
  {

    if( !isset( $this->node->ui ) )
      return null;

    $className = $this->builder->getNodeClass( 'Ui' );

    return new $className( $this->node->ui );

  }//end public function getUi */


  /**
   * @return LibGenfTreeNodeAccessLevel
   */
  public function getAccessLevel()
  {

    $node = null;

    if( isset( $this->node->access->levels ) )
      $node = $this->node->access->levels;

    $className            = $this->builder->getNodeClass( 'AccessLevel' );

    return new $className( $node );

  }//end public function getAccessLevel */

}//end class LibGenfTreeNodeModule

