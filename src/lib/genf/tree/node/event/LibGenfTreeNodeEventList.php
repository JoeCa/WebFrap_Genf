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
class LibGenfTreeNodeEventList
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $context
   * @param string $method
   * @param string $on
   * 
   * @return array<LibGenfTreeNodeEvent>
   */
  public function getEvents( $class, $method, $on )
  {

    $events = $this->node->xpath('./event[@class="'.$class.'" and @method="'.$method.'" and @on="'.$on.'"]');

    if( !$events )
      return array();

    $className  = $this->builder->getNodeClass( 'Event' );

    $eventList = array();

    foreach( $events as $event )
    {
      $eventList[] = new $className($event);
    }

    return $eventList;

  }//end public function getEvents */
  
  
  /**
   *
   * @param string $context
   * @param string $type
   * @param string $on
   */
  public function getContextEvents( $context, $type, $on )
  {
    $events = $this->node->xpath('./context/'.$context.'/event[@on="'.$on.'" and @type="'.$type.'"]');

    if( !$events )
      return array();

    $className  = $this->builder->getNodeClass( 'Event' );

    $eventList = array();

    foreach( $events as $event )
    {
      $eventList[] = new $className($event);
    }

    return $eventList;

  }//end public function getContextEvents */

}//end class LibGenfTreeNodeEvents

