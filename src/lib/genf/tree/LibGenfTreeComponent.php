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
class LibGenfTreeComponent
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder  = null;

  /**
   *
   * @var LibGenfTreeRoot
   */
  protected $root     = null;


////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param SimpleXmlElement $attribute
   */
  public function __construct(  )
  {

    $this->builder  = LibGenfBuild::getInstance();


  }//end public function __construct */


  public function create( $name, $management, $compNode )
  {

  }

}//end class LibGenfTreeComponent

