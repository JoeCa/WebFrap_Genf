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
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 *
 */
class LibGenfEnvelopFormattribute
  extends TEnvelop
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  public $children    = array();

  /**
   * reference to the layout
   * @var int
   */
  public $layoutCol   = null;

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management  = null;

  /**
   *
   * @var LibGenfTreeNodeReference
   */
  public $ref  = null;

  /**
   * reference to the layout
   * @var int
   */
  public $weight      = 1;

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * Standard Konstruktor
   * Nimmt beliebig viele Elemente oder einen einzigen Array
   * @param LibGenfTreeNodeAttribute $object
   * @param LibGenfTreeNodeManagement $management
   */
  public function __construct( $object , $management  )
  {
    
    if( $object instanceof TContextAttribute )
    {
      $this->object     = $object->unpack();
    }
    else 
    {
      $this->object     = $object;
    }
    
    $this->management = $management;

  }//end public function __construct */

  /**
   *
   */
  public function fullName()
  {

    if( $this->ref )
    {
      return $this->ref->name->name.'-'.$this->object->name->name;
    }
    else
    {
      return $this->management->name->name.'-'.$this->object->name->name;
    }

  }//end public function fullName */

  /**
   *
   * @param LibGenfTreeNodeAttribute $child
   * @return unknown_type
   */
  public function addChild( $child )
  {

    $uiPos = $child->uiElement->position();

    if(! isset( $this->children[$uiPos->priority] ) )
      $this->children[$uiPos->priority] = array();

    $this->children[$uiPos->priority][] = $child;

  }//end public function addChild */

  /**
   * get all children sorted correct
   * @param string $relation
   * @return array
   */
  public function getChildren( $relation = null )
  {

    // return empty array
    if(!$this->children)
      return $this->children;

    ksort($this->children);

    $childs = array();

    foreach( $this->children as $subChilds )
    {
      ksort($subChilds);

      foreach( $subChilds as $child )
      {
        $childs[] = $child;
      }//end foreach

    }//end foreach

    return $childs;

  }//end public function getChildren */

  /**
   * the weight of this node is himeself + children
   * needed to get better size balance in the cols
   *
   * @todo use the uielement size for better calculations of the weight
   *
   * @return int
   */
  public function weight()
  {

    $size = $this->weight;

    foreach( $this->children as $subChilds )
    {
      foreach($subChilds as $child)
      {
        $size += $child->weight();
      }
    }

    return $size;

  }//end public function weight */

}//end class LibGenfEnvelopFormattribute

