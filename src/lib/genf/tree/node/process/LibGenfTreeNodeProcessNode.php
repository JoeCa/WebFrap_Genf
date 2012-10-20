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
class LibGenfTreeNodeProcessNode
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
  public function getType()
  {
    
    if( isset($this->node['type']) )
      return trim( $this->node['type'] );

    return $this->order == "1" ? 'start' : 'default';

  }//end public function getType */
  

  /**
   * @param string $key
   */
  public function event( $key )
  {

    if(!isset($this->node->events) || !isset($this->node->events->$key) )
      return null;

    return trim($this->node->events->$key);

  }//end public function event */

  /**
   * @return string
   */
  public function getIcon()
  {

    return isset($this->node->icon['src'])
      ? trim( $this->node->icon['src'] )
      : 'process/go_on.png';

  }//end public function icon */

  /**
   * @return string
   */
  public function getColor()
  {

    return isset($this->node->color)
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
  
  /**
   * Die Phase des Prozessknotens zurückgeben
   * @return string
   */
  public function getPhase()
  {
    
    if( isset($this->node->phase) )
      return trim( $this->node->phase['name'] );

    return null;

  }//end public function getPhase */
  
  /**
   * Die Phase des Prozessknotens zurückgeben
   * @return LibGenfTreeNodeProcessPhase
   */
  public function getPhaseNode()
  {
    
    if( !isset($this->node->phase['name']) )
      return null;
    
    $phaseName = trim( $this->node->phase['name'] );

    return $this->nodeParent->getPhase( $phaseName );

  }//end public function getPhaseNode */

  /**
   * @return array
   */
  public function getEdges()
  {
    return $this->childs;
  }//end public function getEdges
  
  /**
   * Slices
   * @return array
   */
  public function getSlices( )
  {
    
    if( !isset( $this->node->slices ) )
    {
      return array();
    }
    
    $slices = array();
    
    foreach( $this->node->slices->slice  as $slice )
    {
      $slices[] = trim($slice['type']);
    }
    
    return $slices;

  }//end public function getSlices */
  
  /**
   * @return array
   */
  public function getConstraints()
  {

    $constraints = array();

    if( isset( $this->constraints->constraint ) )
    {
      foreach( $this->constraints->constraint as $constraint )
      {
        $constraints[] = trim( $constraint['name'] );
      }
    }

    return $constraints;

  }//end public function getConstraints */

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $nodeClass = $this->builder->getModelClass( 'TreeNode', 'ProcessEdge' );
    $nameClass = $this->builder->getModelClass( 'Name', 'ProcessEdge' );

    $this->childs = array();

    $order = 1;

    // only exists if subnode exists
    if( isset( $this->node->edges ) )
    {
      foreach( $this->node->edges->edge as $edge )
      {
        $this->childs[trim($edge['target'])] = new $nodeClass
        (
          $edge ,
          new $nameClass($edge)
        );

        $this->childs[trim($edge['target'])]->setOrder( $order );
        ++$order;
      }
    }

  }//end protected function loadChilds */

/*//////////////////////////////////////////////////////////////////////////////
// Zugriff auf die Modell Knoten
//////////////////////////////////////////////////////////////////////////////*/




}//end class LibGenfTreeNodeProcessNode

