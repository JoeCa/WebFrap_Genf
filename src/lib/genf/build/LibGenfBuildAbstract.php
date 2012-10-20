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
abstract class LibGenfBuildAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  public $outputPath  = null;

  /**
   *
   * @var LibGenfBuild
   */
  public $builder     = null;

  /**
   *
   * @var SimpleXmlElement
   */
  public $project     = null;

  /**
   *
   * @var LibGenfTree
   */
  public $tree        = null;

////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param $outputPath
   * @param $builder
   */
  public function __construct( $builder, $outputPath )
  {

    $this->builder  = $builder;
    $this->project  = $builder->getProject();
    $this->tree     = $builder->getTree();

    $this->outputPath = $outputPath;

  }//end public function __construct */

  /**
   * @return unknown_type
   */
  public function __destruct()
  {
    $this->shutDown();
  }//end public function __destruct */


  /**
   *
   * @return void
   */
  public function shutDown()
  {
    $this->builder  = null;
    $this->project  = null;
    $this->tree     = null;
  }//end public function shutDown */


////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param $cartridge
   * @return string
   */
  public function getCartridgeClass( $cartridge )
  {

    //<cartridge architecture="Wbf"  class="Entity"      version="0.3" type="Entity" />

    $arch = isset($cartridge['architecture'])
      ? ucfirst($this->builder->replaceVars(trim($cartridge['architecture'])))
      : ucfirst($this->builder->arch);


    $version = isset($cartridge['version'])
      ? SWbf::versionToString($this->builder->replaceVars(trim($cartridge['version'])))
      : $this->builder->archVersion;

    $class = ucfirst(trim($cartridge['class']));

    $className = null;

    if( WebFrap::classLoadable( 'LibCartridge'.$arch.$version.$class ) )
      $className = 'LibCartridge'.$arch.$version.$class;
    else if( WebFrap::classLoadable('LibCartridge'.$arch.$class ) )
      $className =  'LibCartridge'.$arch.$class;
    else if( WebFrap::classLoadable('LibCartridge'.$class ) )
      $className =  'LibCartridge'.$class;
    else
      Error::addError( 'Found no Cartridge for Class: '.$class  );

    if( Log::$levelDebug )
      Log::debug( 'Found Cartridge :'. $className );

    return $className;

  }//end public function getClassName */


} // end class LibGenfBuildEntity

