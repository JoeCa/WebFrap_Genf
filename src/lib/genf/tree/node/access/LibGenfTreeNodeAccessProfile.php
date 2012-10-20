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
class LibGenfTreeNodeAccessProfile
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @overwrite should be implemented if needed
   * @param array $params
   */
  protected function prepareNode( $params = array() )
  {

    $this->name = new LibGenfNameMin( $this->node );

  }//end protected function prepareNode */

  /**
   * @return LibGenfNameMin
   */
  public function getName()
  {

    return $this->name;

  }//end public function getName */


  /**
   * @return array<LibGenfTreeNodeAccessCheck>
   */
  public function getChecks( )
  {

    $className  = $this->builder->getNodeClass( 'AccessCheck' );
    $checks     = array();

    if( isset( $this->node->checks->check ) )
    {

      foreach( $this->node->checks->check as $check )
      {
        $checks[] = new $className( $check );
      }

    }

    return $checks;

  }//end public function getChecks */


}//end class LibGenfTreeNodeAccessProfile

