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
abstract class LibCartridgeBdlSubWidget
  extends LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $nodeType     = 'Widget';


  /**
   * @param LibGenfTreeNodeWidget $widget
   * @return LibGenfEnvWidget
   */
  public function createEnvironment( $widget )
  {

    $environment = new LibGenfEnvWidget($this->builder,$widget);

    return $environment;

  }//end public function createEnvironment */

} // end abstract class LibCartridgeBdlSubWidget