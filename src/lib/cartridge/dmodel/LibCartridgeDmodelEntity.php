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
class LibCartridgeDmodelEntity
  extends LibCartridgeBdlEntity
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  protected $classType  = 'Entity';

  /**
   * @var string
   */
  protected $class      = 'Entity';

  /**
   * @var array
   */
  protected $attrGens   = array();

////////////////////////////////////////////////////////////////////////////////
// P+W
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the cartridge
   * @return void
   */
  public function parse()
  {

    if(!$this->pathOutput)
    {
      $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $folder = $this->pathOutput;
    }

    $project = $this->builder->getProjectNode();
    $package = $project->getMainPackage();

    $code = <<<CODE
package {$package}
{

CODE;


    foreach( $this->root as $entity )
    {

      if( !$this->root->setActiv( $entity ) )
        continue;

      $code .= $this->buildNode( $package, $entity );

    }//end foreach */

    $code .= <<<CODE
}

CODE;

    $this->writeFile( $code, $folder.'dmodel/', $project->getKey().'.dmodel' );

  }//end protected function parse */

  /**
   * leere write methode zum deaktivieren der standard funktionalität
   */
  public function write(){}//end public function write */

  /**
   * (non-PHPdoc)
   * @see src/lib/LibCartridge#init()
   */
  protected function init()
  {
    $this->multiple = LibDbAdmin::getInstance()->getMultiple();
  }//end protected function init '/

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $package
   * @param LibGenfTreeNodeEntity $entity
   * @return string
   */
  protected function buildNode( $package,  $entity )
  {

    $name      = $entity->getName();


    $description = str_replace( '"','\\"', $entity->description() );

    $code = <<<CARTRIDGE
  entity {$name->entity}
  "{$description}"
  {

CARTRIDGE;

    foreach( $entity as $attribute )
    {
      $code .= "    ".$this->buildAttribute( $attribute );
    }

    $code .= <<<CARTRIDGE
  }


CARTRIDGE;

    return $code;

  }//end public function render_GenfClass */

  /**
   * @param LibGenfTreeNodeAttribute
   * @return string
   */
  protected function buildAttribute( $attribute  )
  {


    if( $attribute->target()  )
    {
      $gen = $this->getAttributeGen( 'reference' );
    }
    else if( !$gen = $this->getAttributeGen( $attribute->type() ) )
    {
      $this->builder->warn( "Requested nonexisting DB Attribute Type ".$attribute->dbType() );
      return '';
    }

    return $gen->buildSetupField( $attribute );

  }//end public function buildAttribute */

  /**
   * @param string $key
   * @return LibGeneratorSpiroAttribute
   *
   */
  protected function getAttributeGen( $key )
  {

    if( isset( $this->attrGens[$key] ) )
      return $this->attrGens[$key];

    if( !$gen = $this->getGenerator( 'Attribute'.ucfirst( $key ) ) )
      return null;

    $this->attrGens[$key] = $gen;

    return $this->attrGens[$key];


  }//end protected function getAttributeGen */




} // end class LibCartridgeWbfEntity
