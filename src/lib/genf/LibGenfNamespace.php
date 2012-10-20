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
 * namespace
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNamespace
  extends TArray
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfName
   */
  public $name = null;


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   */
  public function __construct( $name )
  {
    
    $this->name = new LibGenfNameDefault( $name );
    
  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    
    return isset($this->name)
      ? (string)$this->name
      : '';
      
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////


}//end class LibGenfNamespace

