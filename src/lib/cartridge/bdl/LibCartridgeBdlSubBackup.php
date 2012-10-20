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
abstract class LibCartridgeBdlSubBackup
  extends LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $nodeType     = 'Backup';


  /**
   * @param LibGenfEnvBackup $backup
   * @return LibGenfEnvBackup
   */
  public function createEnvironment( $backup )
  {

    $environment = new LibGenfEnvBackup( $this->builder, $backup );

    return $environment;

  }//end public function createEnvironment */

} // end abstract class LibCartridgeBdlSubBackup */
