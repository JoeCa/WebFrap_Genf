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
class LibBdlSst
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
   * die aktuelle Position des "Pointers" fest halten
   * @var int
   */
  protected $autoPointer = 0;

////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Standard Konstruktor
   * Nimmt beliebig viele Elemente oder einen einzigen Array
   */
  public function __construct( )
  {

    if( $anz = func_num_args() )
    {
      if( $anz == 1 and is_array(func_get_arg(0)) )
      {
        $this->node = func_get_arg(0);
      }
      else
      {
        // hier kommt auf jeden fall ein Array
        $this->node = func_get_args();
      }
    }

  }//end public function __construct */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key , $value )
  {

    if(is_null($key))
    {
      $key = $this->autoPointer;
      ++ $this->autoPointer;
    }

    $this->node[$key] = $value;
    
  }// end public function __set */

  /**
   * Zugriff Auf die Elemente per magic get
   *
   * @param string $key
   * @return mixed
   */
  public function __get( $key )
  {
    return isset($this->node[$key])?$this->node[$key]:null;
  }// end public function __get */

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
   *
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

}//end class LibBdlSst







