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
class LibBdlSstNode
  implements ArrayAccess, Iterator, Countable
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the array data body for the Array Object
   * @var array
   */
  protected $node = array();

  /**
   * Der Wert des Knotens
   * @var string
   */
  protected $value = null;

  /**
   * der Type des Knotens
   * @var string
   */
  protected $type = null;

  /**
   * der name des knotens
   * @var string
   */
  protected $name = null;

  /**
   * die aktuelle Position des "Pointers" fest halten
   * @var int
   */
  protected $autoPointer = 0;

////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $value
   */
  public function __construct( $value )
  {

    $this->value = $value;

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $value
   */
  public function setValue( $value )
  {
    $this->value = $value;
  }//end public function setValue */

  /**
   * @return string
   */
  public function getValue( )
  {
    return $this->value;
  }//end public function getValue */

  /**
   * @return string
   */
  public function getType( )
  {
    return $this->type;
  }//end public function getType */

////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see ArrayAccess:offsetSet
   */
  public function offsetSet($offset, $value)
  {

    if( is_null($offset) )
      $this->node[] = $value;
    else
      $this->node[$offset] = $value;

  }//end public function offsetSet */

  /**
   * @see ArrayAccess:offsetGet
   */
  public function offsetGet($offset)
  {
    return $this->node[$offset];
  }//end public function offsetGet */

  /**
   * @see ArrayAccess:offsetUnset
   */
  public function offsetUnset($offset)
  {
    unset($this->node[$offset]);
  }//end public function offsetUnset */

  /**
   * @see ArrayAccess:offsetExists
   */
  public function offsetExists($offset)
  {
    return isset($this->node[$offset])?true:false;
  }//end public function offsetExists */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Iterator::current
   */
  public function current ()
  {
    return current($this->node);
  }//end public function current */

  /**
   * @see Iterator::key
   */
  public function key ()
  {
    return key($this->node);
  }//end public function key */

  /**
   * @see Iterator::next
   */
  public function next ()
  {
    return next($this->node);
  }//end public function next */

  /**
   * @see Iterator::rewind
   */
  public function rewind ()
  {
    reset($this->node);
  }//end public function rewind */

  /**
   * @see Iterator::valid
   */
  public function valid ()
  {
    return current($this->node)? true:false;
  }//end public function valid */

////////////////////////////////////////////////////////////////////////////////
// Interface: Countable
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Countable::count
   */
  public function count()
  {
    return count($this->node);
  }//end public function count */


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param
   */
  public function append( $entry )
  {

    ++$this->autoPointer;
    $this->node[] = $entry;

  }//end public function append */

  /**
   * @return array
   */
  public function asArray(  )
  {
    return $this->node;
  }//end public function asArray */

  /**
   * @param string $key
   */
  public function exists( $key )
  {
    return array_key_exists( $key , $this->node );
  }//end public function exists */

}//end class LibBdlSstNode







