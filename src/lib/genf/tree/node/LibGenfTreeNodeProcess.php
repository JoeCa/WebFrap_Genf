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
class LibGenfTreeNodeProcess
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfNameProcess
   */
  public $name        = null;

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

  /**
   * 
   * @var array<LibGenfTreeNodeProcessPhase>
   */
  protected $phases = array();
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return string the name of the entity
   */
  public function name()
  {
    return trim( $this->node['name'] );
  }//end public function name */

  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement()
  {

    if( !$this->management )
    {
      $this->management = $this->builder->getManagement( trim($this->node['src']) );
    }

    return $this->management;

  }//end public function getManagement */

  /**
   *
   */
  public function getProcessNodes()
  {
    return $this->childs;
  }//end public function getProcessNodes */
  
  
  /**
   * @return array<LibGenfTreeNodeProcessPhase>
   */
  public function getProcessPhases()
  {
    return $this->phases;
  }//end public function getProcessPhases */
  
  /**
   * @param string $key
   * @return LibGenfTreeNodeProcessPhase
   */
  public function getPhase( $key )
  {
    
    if( !isset($this->phases[$key]) )
      return null;

    return $this->phases[$key];

  }//end public function getPhase */

  /**
   * @param string $key
   * @return LibGenfTreeNodeProcessNode
   */
  public function getProcessNode( $key )
  {

    if( !isset($this->childs[$key]) )
      return null;
    
    return $this->childs[$key];

  }//end public function getProcessNodes */

  /**
   * @return string
   */
  public function processSrc( )
  {

    return $this->node['src'];

  }//end public function processSrc */
  
  /**
   * Die default Slices laden
   * werden verwendet wenn für einen node keine slices definiert werden
   * @return array
   */
  public function getDefaultSlices( )
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

  }//end public function getDefaultSlices */

  /**
   * @param array $params
   */
  protected function prepareNode( $params = array() )
  {

    $rootManagement   = $this->builder->getManagementRoot( );
    $this->management = $rootManagement->getManagement( trim($this->node['src']) );

    $processNameClass    = $this->builder->getModelClass( 'Name', 'Process' );
    $this->name          = new $processNameClass( $this->node );

  }//end protected function prepareNode */

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $stepNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessNode' );
    $stepNameClass  = $this->builder->getModelClass( 'Name', 'ProcessNode' );
    
    $phaseNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessPhase' );
    $phaseNameClass  = $this->builder->getModelClass( 'Name', 'ProcessPhase' );

    $order = 1;

    // only exists if subnode exists
    if( isset($this->node->nodes ) )
    {
      foreach( $this->node->nodes->node as $node )
      {
        $this->childs[trim($node['name'])] = new $stepNodeClass
        (
          $node ,
          new $stepNameClass( $node ),
          array(),
          $this
        );

        $this->childs[trim($node['name'])]->setOrder( $order );

        ++$order;
      }
    }
    
    $this->phases = array();
    
    // only exists if subnode exists
    $order = 1;
    if( isset( $this->node->phases ) )
    {
      foreach( $this->node->phases->phase as $phase )
      {
        $this->phases[trim($phase['name'])] = new $phaseNodeClass
        (
          $phase,
          new $phaseNameClass( $phase ),
          array(),
          $this
        );

        $this->phases[trim($phase['name'])]->setOrder( $order );

        ++$order;
      }
    }

  }//end protected function loadChilds */


  /**
   *
   * @return void
   * @return array<LibGenfTreeNodeProcessAction>
   */
  public function getProcessActions( )
  {

    $actionNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessAction' );
    $minNameClass     = $this->builder->getModelClass( 'Name', 'Min' );

    $actions = array();

    // only exists if subnode exists
    if( isset( $this->node->actions->action ) )
    {
      foreach( $this->node->actions->action as $action )
      {
        $actions[trim($action['name'])] = new $actionNodeClass
        (
          $action ,
          new $minNameClass( $action )
        );
      }
    }

    return $actions;

  }//end public function getProcessActions */
  
  /**
   *
   * @return void
   * @return array<LibGenfTreeNodeProcessAction>
   */
  public function getProcessEvents( )
  {

    $eventNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessEvent' );
    $eventNameClass  = $this->builder->getModelClass( 'Name', 'ProcessEvent' );

    $events = array();

    // only exists if subnode exists
    if( isset( $this->node->events->event ) )
    {
      foreach( $this->node->events->event as $event )
      {
        $events[trim($event['name'])] = new $eventNodeClass
        (
          $event ,
          new $eventNameClass( $event )
        );
      }
    }

    return $events;

  }//end public function getProcessEvents */
  
  /**
   *
   * @return array<LibGenfTreeNodeProcessMessage>
   */
  public function getProcessMessages( )
  {

    $msgNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessMessage' );
    $minNameClass  = $this->builder->getModelClass( 'Name', 'Min' );

    $messages = array();

    // only exists if subnode exists
    if( isset( $this->node->messages->message ) )
    {
      foreach( $this->node->messages->message as $message )
      {
        $messages[trim($message['name'])] = new $msgNodeClass
        (
          $message ,
          new $minNameClass( $message )
        );
      }
    }

    return $messages;

  }//end public function getProcessMessages */
  
  
  /**
   *
   * @return array<LibGenfTreeNodeProcessDataSource>
   */
  public function getDataSources( )
  {

    $sourceNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessDataSource' );

    $sources = array();

    // only exists if subnode exists
    if( isset( $this->node->data_sources->source ) )
    {
      foreach( $this->node->data_sources->source as $source )
      {
        $sources[] = new $sourceNodeClass( $source );
      }
    }

    return $sources;

  }//end public function getDataSources */
  
  /**
   *
   * @return array<LibGenfTreeNodeProcessConstraint>
   */
  public function getConstraints( )
  {

    $constNodeClass  = $this->builder->getModelClass( 'TreeNode', 'ProcessConstraint' );

    $constraints = array();

    // only exists if subnode exists
    if( isset( $this->node->constraints->constraint ) )
    {
      foreach( $this->node->constraints->constraint as $constraint )
      {
        $constraints[] = new $constNodeClass( $constraint );
      }
    }

    return $constraints;

  }//end public function getConstraints */
  
  /**
   * @return array<LibGenfTreeNodeResponsible>
   */
  public function getRespsonsibles( )
  {

    //$sourceNodeClass  = $this->builder->getModelClass( 'TreeNode', 'Responsible' );

    $responsibles = array();

    // only exists if subnode exists
    if( isset( $this->node->responsibles->responsible ) )
    {
      foreach( $this->node->responsibles->responsible as $responsible )
      {
        $responsibles[] = new LibGenfTreeNodeResponsible( $responsible );
      }
    }

    return $responsibles;

  }//end public function getRespsonsibles */
  
  /**
   * @param string $key
   * @return LibGenfTreeNodeResponsible
   */
  public function getResponsible( $key )
  {

    $resp = $this->node->xpath( './responsibles/responsible[@name="'.$key.'"]' );
    
    if( !$resp )
      return null;
    
    return new LibGenfTreeNodeResponsible( $resp[0] );
    
  }//end public function getResponsible */

////////////////////////////////////////////////////////////////////////////////
// Advanced getter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getDefaultNode()
  {

    return isset( $this->node->default_node )
      ? trim( $this->node->default_node )
      : '';

  }//end public function getDefaultNode */

  /**
   * @return string
   */
  public function getStatusAttribute()
  {

    return isset( $this->node->status_attribute )
      ? trim( $this->node->status_attribute )
      : null;

  }//end public function getStatusAttribute */

}//end class LibGenfTreeNodeProcess

