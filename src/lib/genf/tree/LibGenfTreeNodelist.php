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
class LibGenfTreeNodelist
  implements ArrayAccess, Iterator, Countable
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $childs   = array();

  /**
   *
   * @var SimpleXml
   */
  protected $node     = null;

  /**
   * list of parameters
   * @var array
   */
  protected $params   = array();

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder  = null;

////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess  / Direct access to the simplexml attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see ArrayAccess:offsetSet
   */
  public function offsetSet($offset, $value)
  {
    // readonly
    //$this->attribute[$offset] = $value;
  }//end public function offsetSet */

  /**
   * @see ArrayAccess:offsetGet
   */
  public function offsetGet($offset)
  {
    return isset($this->attribute[$offset])
      ? trim($this->node[$offset])
      : null;
  }//end public function offsetGet */

  /**
   * @see ArrayAccess:offsetUnset
   */
  public function offsetUnset($offset)
  {
    // readonly
    //unset($this->attribute[$offset]);
  }//end public function offsetUnset */

  /**
   * @see ArrayAccess:offsetExists
   */
  public function offsetExists($offset)
  {
    return isset($this->node[$offset])
      ? true
      : false;
  }//end public function offsetExists */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Iterator::current
   */
  public function current ()
  {

    return current($this->childs);

  }//end public function current */

  /**
   * @see Iterator::key
   */
  public function key ()
  {
    return key($this->childs);
  }//end public function key */

  /**
   * @see Iterator::next
   */
  public function next ()
  {
    return next($this->childs);
  }//end public function next */

  /**
   * @see Iterator::rewind
   */
  public function rewind ()
  {
    reset($this->childs);
  }//end public function rewind */

  /**
   * @see Iterator::valid
   */
  public function valid ()
  {
    return current($this->childs)? true:false;
  }//end public function valid */

////////////////////////////////////////////////////////////////////////////////
// Interface: Countable
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Countable::count
   */
  public function count()
  {
    return count($this->childs);
  }//end public function count */

////////////////////////////////////////////////////////////////////////////////
// magic / Direct access to the simplexml nodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Direct access to the simplexml nodes
   */
  public function __get( $key )
  {
    return isset($this->node->$key)
      ? $this->node->$key
      : null;
  }//end public function __get */


  public function __set( $key , $value )
  {
    // readonly
  }//end public function __set */

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param SimpleXmlElement $attribute
   */
  public function __construct( $node , $params = array() )
  {

    $this->builder  = LibGenfBuild::getInstance();
    $this->node     = $node;
    $this->params   = $params;

    $this->parseParams( $params );
    $this->extractChildren( $node );

  }//end public function __construct */


  /**
   *
   * @param SimpleXmlElement $node
   */
  protected function extractChildren( $node )
  {
    $this->node = $node;
  }//end protected function extractChildren */

  /**
   *
   * @param array $params
   */
  protected function parseParams( $params )
  {
    $this->params   = $params;
  }//end protected function parseParams */


  /**
   * get the model node
   * @return SimpleXMLElement
   */
  public function getNode()
  {
    return $this->node;
  }//end public function getNode */

/*//////////////////////////////////////////////////////////////////////////////
// Debug Console
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * Enter description here ...
   */
  public function getDebugDump()
  {
    return array
    (

    );
  }//end public function getDebugDump

}//end class LibGenfTreeNodelist

