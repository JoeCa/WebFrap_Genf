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
class LibGenfTreeNodeUiTab
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**.
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameMin( $this->node );

  }//end protected function loadChilds */

  /**
   *
   * Enter description here ...
   */
  public function getType()
  {
    
    return isset($this->node['type'])
      ?trim($this->node['type'])
      :'default';
      
  }//end public function getType */
  
  /**
   * @return boolean
   */
  public function loadable()
  {
    
    if( isset($this->node['type']) )
    {
      return ('load' === trim($this->node['type']));
    }
    else 
    {
      return false;
    }

  }//end public function loadable */
  
  /**
   * @return string
   */
  public function isHideable()
  {

    return isset($this->node['hide_able'])
      ? strtolower(trim($this->node['hide_able'])) == 'false'
        ? false 
        : true
      : true;

  }//end public function isHideable */  
  
  /**
   * @return string
   */
  public function isReadonly()
  {

    return isset($this->node['readonly'])
      ? strtolower(trim($this->node['readonly'])) == 'false'
        ? false 
        : true
      : false;

  }//end public function isReadonly */  
  
  /**
   * @return string
   */
  public function isDisabled()
  {

    return isset($this->node['disabled'])
      ? strtolower(trim($this->node['disabled'])) == 'false'
        ? false 
        : true
      : false;

  }//end public function isReadonly */
  
  /**
   * @return string
   */
  public function isCloseable()
  {

    return isset($this->node['close_able'])
      ? strtolower(trim($this->node['close_able'])) == 'false'
        ? false 
        : true
      : false;

  }//end public function isReadonly */


  /**
   * @return string
   */
  public function getTargetUrl()
  {

    return isset($this->node['url'])
      ?trim($this->node['url'])
      :'';

  }//end public function getTargetUrl */

  /**
   * Die References welche im Tab eingebunden sind erfragen
   * @return [LibGenfTreeNodeLayoutReference]
   */
  public function getReferences()
  {

    if( !isset( $this->node->body ) )
      return null;

    $search = LibGenfTreeSearchLayout::get();

    if( !$references = $search->extractLayoutReferences( array(), $this->node->body ) )
      return null;

    $refNodes = array();

    foreach( $references as $ref )
    {

      if( $foundRef = $this->management->getReference( trim($ref['name'])) )
      {
        $refLayout      = new LibGenfTreeNodeLayoutReference($ref); ;
        $refLayout->ref = $foundRef;

        $refNodes[] = $refLayout;
      }
      else
      {
        $this->builder->error
        ( 
        	'Missing reference: '.trim($ref['name']).' in tab '.$this->name.' '.$this->builder->dumpEnv() 
        );
      }

    }

    return $refNodes;


  }//end public function getReferences */

  /**
   * Die Items auslesen welche im Tab eingebunden sind
   * 
   * @state dev
   * @return array
   * 
   */
  public function getItems()
  {

    if( !isset( $this->node->body ) )
      return null;

    $search = LibGenfTreeSearchLayout::get();

    if( !$references = $search->extractLayoutReferences( array(), $this->node->body ) )
      return null;

    $refNodes = array();

    foreach( $references as $ref )
    {

      if( $foundRef = $this->management->getReference( trim($ref['name'])) )
      {
        $refLayout      = new LibGenfTreeNodeLayoutReference($ref); ;
        $refLayout->ref = $foundRef;

        $refNodes[] = $refLayout;
      }
      else
      {
        $this->builder->error
        ( 
        	'Missing reference: '.trim($ref['name']).' in tab '.$this->name.' '.$this->builder->dumpEnv() 
        );
      }

    }

    return $refNodes;


  }//end public function getItems */
  
  /**
   * @return void
   */
  public function getFields()
  {

  }//end public function getFields */


}//end class LibGenfTreeNodeUiTab

