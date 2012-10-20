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
 * Context Envelop to be able to sourround something with a context
 * 
 * @package WebFrap
 * @subpackage WebFrap
 */
class TContext
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var object
   */
  protected $object = null;

  /**
   * 
   * @var SimpleXmlElement
   */
  public $node  = null;
  
  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management  = null;
  
  /**
   * @var LibGenfTreeNodeAccess
   */
  public $access  = null;
  
  /**
   * @var LibGenfTreeNodeUi
   */
  public $ui  = null;
  
  /**
   *
   * @var object
   */
  public $context     = null;
  
  /**
   *
   * @var TArray
   */
  public $params     = null;

////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Standard Konstruktor
   * Nimmt beliebig viele Elemente oder einen einzigen Array
   */
  public function __construct( $object, $management, $context = null )
  {
    $this->object       = $object;
    $this->management   = $management;
    $this->context      = $context;
    $this->params       = new TArray();
  }//end public function __construct */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key , $value )
  {

    if( property_exists  ( $this->object  , $key  ) )
    {
      $this->object->$key = $value;
    }
    else
    {
      Error::addError('Wrote to nonexisting property '.$key );
    }

  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic get
   *
   * @param string $key
   * @return mixed
   */
  public function __get( $key )
  {
    if( property_exists  ( $this->object  , $key  ) )
    {
      return $this->object->$key;
    }
    else
    {
      Error::addError('Tried to read from nonexisting property '.$key );
    }
  }// end of public function __get */

  /**
   * map method calls
   *
   * @param string $key
   * @return mixed
   */
  public function __call( $method, $params )
  {

    if( !method_exists  ( $this->object  , $method  ) )
    {
      Error::addError('Tried to call nonexisting method '.$method );
      return;
    }

    return call_user_func_array(array($this->object, $method), $params);

  }// end of public function __call */

  /**
   * unpack the envelop and return the object
   * @return object
   */
  public function unpack()
  {
    return $this->object;
  }//end public function unpack */


}//end class TContext

