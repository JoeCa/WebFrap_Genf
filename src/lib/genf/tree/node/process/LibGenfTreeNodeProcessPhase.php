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
class LibGenfTreeNodeProcessPhase
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var int
   */
  public $order = null;
  
  
  /**
   * @var LibGenfTreeNodeProcessProcess
   */
  public $nodeParent = null;
  

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfNameProcess
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */


  /**
   * @return string
   */
  public function getIcon()
  {

    return isset( $this->node->icon['src'] )
      ? trim( $this->node->icon['src'] )
      : 'process/go_on.png';

  }//end public function icon */

  /**
   * @return string
   */
  public function getColor()
  {

    return isset( $this->node->color )
      ? trim( $this->node->color )
      : 'default';

  }//end public function color */

  /**
   * @return int
   */
  public function getOrder()
  {

    return $this->order;

  }//end public function getOrder */

  /**
   * @param int $order
   */
  public function setOrder( $order )
  {

    $this->order = $order;

  }//end public function setOrder */
  
 /**
   * @return string
   */
  public function getResponsibleKey()
  {
    
    if( !isset( $this->node->responsibles['key']) )
      return null;
    else 
      return trim( $this->node->responsibles['key'] );
    
  }//end public function getResponsibleKey */
  
  
  /**
   * @return array<LibGenfTreeNodeResponsibleCheck>
   */
  public function getResponsiblesChecks()
  {
    
    if( !isset( $this->node->responsibles->check ) )
      return array();
      
    $respChecks = array();
    
    foreach( $this->node->responsibles->check as $check )
    {
      $respChecks = new LibGenfTreeNodeResponsibleCheck( $check );
    }
    
    return $respChecks;
    
  }//end public function getResponsiblesChecks */



}//end class LibGenfTreeNodeProcessPhase

