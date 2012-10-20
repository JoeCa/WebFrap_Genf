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
class LibGenfTreeNodeUiMenu
  extends LibGenfTreeNode
{

  /**
   *
   * @param string $key
   * @param string $context
   * @return array
   *
   */
  public function getEntry( $key , $context )
  {

    if( !$eList = $this->node->xpath( './'.$context.'/entry[@key="'.$key.'"]' ) )
      return null;

    $fieldClassName = $this->builder->getNodeClass( 'UiMenuEntry' );

    $entries = array();

    foreach( $eList as $entry )
    {
      $entries[] = new $fieldClassName( $entry ) ;
    }

    return $entries;


  }//end public function getEntry */



}//end class LibGenfTreeNodeUiMenu

