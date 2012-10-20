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
 */
class LibGenfLang
{
////////////////////////////////////////////////////////////////////////////////
// constantes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  const NEUTRUM     = 'n';

  /**
   *
   * @var string
   */
  const FEMININUM   = 'f';

  /**
   *
   * @var string
   */
  const MASKULINUM  = 'm';

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $text = array();

////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param $node
   */
  public function __construct( $node )
  {
    $this->loadTexts($node);
  }//end public function __construct */

  /**
   * Zugriff Auf die Elemente per magic set
   * @param string $key
   * @param mixed $value
   */
  public function __set( $key , $value )
  {
    $this->text[$key] = $value;
  }// end of public function __set */

  /**
   * Zugriff Auf die Elemente per magic get
   *
   * @param string $key
   * @return mixed
   */
  public function __get( $key )
  {
    return isset($this->text[$key])?$this->text[$key]:null;
  }// end of public function __get */

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $node
   */
  public function loadTexts( $node )
  {

    $children = $node->children();

    foreach( $children as $name => $child )
    {
      if( 'text' == $name )
        $this->text[$child['lang']] = trim($child);
    }

  }//end public function loadTexts */

}//end class LibGenfLang

