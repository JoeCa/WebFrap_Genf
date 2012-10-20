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
abstract class LibCartridgeBdlSubService
  extends LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $nodeType     = 'Service';


  /**
   * @param LibGenfEnvService $item
   * @return LibGenfEnvService
   */
  public function createEnvironment( $item )
  {

    $environment = new LibGenfEnvService( $this->builder, $item );

    return $environment;

  }//end public function createEnvironment */

} // end abstract class LibCartridgeBdlSubService
