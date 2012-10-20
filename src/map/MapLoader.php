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
abstract class MapLoader
  implements ArrayAccess, Iterator, Countable
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  protected $data          = null;

  /**
   * @var array
   */
  protected $mapFile = null;

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param String $xmlFile the Filepath of the Metamodel to parse
   */
  public function __construct( $mapFile = null )
  {

    if($mapFile)
      $this->mapFile = $mapFile;

    $this->load();

  }//end public function __construct */



////////////////////////////////////////////////////////////////////////////////
// Normal methodes
////////////////////////////////////////////////////////////////////////////////

  public function load()
  {
    $mapFile = PATH_GW.'conf/map/'.$this->mapFile.'.php';
    include $mapFile;
  }//end public function load */


////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see ArrayAccess:offsetSet
   */
  public function offsetSet($offset, $value)
  {
    $this->data[$offset] = $value;
  }//end public function offsetSet */

  /**
   * @see ArrayAccess:offsetGet
   */
  public function offsetGet($offset)
  {
    return $this->data[$offset];
  }//end public function offsetGet */

  /**
   * @see ArrayAccess:offsetUnset
   */
  public function offsetUnset($offset)
  {
    unset($this->data[$offset]);
  }//end public function offsetUnset */

  /**
   * @see ArrayAccess:offsetExists
   */
  public function offsetExists($offset)
  {
    return isset($this->data[$offset])?true:false;
  }//end public function offsetExists */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Iterator::current
   */
  public function current ()
  {
    return current($this->data);
  }//end public function current */

  /**
   * @see Iterator::key
   */
  public function key ()
  {
    return key($this->data);
  }//end public function key */

  /**
   * @see Iterator::next
   */
  public function next ()
  {
    return next($this->data);
  }//end public function next */

  /**
   * @see Iterator::rewind
   */
  public function rewind ()
  {
    reset($this->data);
  }//end public function rewind */

  /**
   * @see Iterator::valid
   */
  public function valid ()
  {
    return current($this->data)? true:false;
  }//end public function valid */

////////////////////////////////////////////////////////////////////////////////
// Interface: Countable
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Countable::count
   */
  public function count()
  {
    return count($this->data);
  }//end public function count  */

} // end abstract class MapLoader

