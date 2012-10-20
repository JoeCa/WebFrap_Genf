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
class LibGenfTreeNodeControlPanel
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @var array
   */
  protected $elements = array();

/*//////////////////////////////////////////////////////////////////////////////
// loader methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $children = $this->node->children();

    foreach( $children as $typeName => $child )
    {

      $classname   = $this->builder->getNodeClass( 'Control'.ucfirst($typeName) );
      
      if( Webfrap::classLoadable($classname) )
        $this->elements[trim($child['name'])] = new $classname( $child );
      else 
        $this->builder->dumpError( "Requested nonexisting Control".ucfirst($typeName) );

    }


  }//end protected function loadChilds */

/*//////////////////////////////////////////////////////////////////////////////
// getter and access logic
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * Den Type des Panels erfragen
   * @return string
   */
  public function getType()
  {
    
    return isset( $this->node['type'] )
      ? trim($this->node['type'])
      : 'default';
      
  }//end public function isPanelClear */

  /**
   * @return boolean
   */
  public function isPanelClear()
  {
    return isset( $this->node['clear'] );
  }//end public function isPanelClear */
  
  /**
   *
   */
  public function getElements()
  {

    return $this->elements;

  }//end public function getElements */


}//end class LibGenfTreeNodeAccess

