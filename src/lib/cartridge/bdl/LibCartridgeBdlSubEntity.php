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
abstract class LibCartridgeBdlSubEntity
  extends LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibParserWbf
   */
  protected $catridgeParser = null;

  /**
   * @var LibGenerator
   */
  protected $generator    = null;

  /**
   * @var string
   */
  protected $nodeType     = 'Entity';

  /**
   * @var LibGenfEnvManagement
   */
  protected $env  = null;

  /**
   * @var LibGenfTreeRootManagement
   */
  protected $root = null;
  
  /**
   * @var string
   */
  protected $preFix = null;
  
  /**
   * @var string
   */
  protected $prefixClass = null;
  
  /**
   * @var string
   */
  protected $prefixName = null;

  /**
   * @var string
   */
  protected $prefixPath = null;
  
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param LibGenfEnvManagement $env
   * @param string $className
   * @return string
   */
  protected function build_Hand( $env, $className )
  {

    $name     = $env->name;
    $project  = $this->project;

    $code     = $this->createCodeHead(  );

    $code .= <<<CODE

/**
 * Class for handwritten Code.
 * Write all your Code in this class. NEVER write in the Genf Classes.
 * Genf Classes are not persistent!
 *
 * @package WebFrap
 * @subpackage Mod{$name->customModul}
 * @author {$project->author}
 * @copyright {$project->copyright}
 * @licence {$project->licence}
 */
class {$className}
  extends {$className}_Genf
{

}//end class {$className}

CODE;

    $code .= $this->createCodeFooter();

    return $code;

  }//end public function build_Hand */


  /**
   *
   * @param LibGenfEnvManagement $env
   * @param string $className
   * @return string
   */
  protected function render_HandCodeClass( $env, $className )
  {

    $name     = $env->name;
    $project  = $this->project;

    $code     = $this->createCodeHead(  );

    $code .= <<<CODE

/**
 * Class for handwritten Code.
 * Write all your Code in this class. NEVER write in the Genf Classes.
 * Genf Classes are not persistent!
 *
 * @package WebFrap
 * @subpackage Mod{$name->customModul}
 * @author {$project->author}
 * @copyright {$project->copyright}
 * @licence {$project->licence}
 */
class {$className}
  extends {$className}_Genf
{

}//end class {$className}

CODE;

    $code .= $this->createCodeFooter();

    return $code;

  }//end public function render_HandCodeClass */


  /**
   *
   * @return array
   */
  public function getInjectGenerators()
  {

    $generators = array();

    //$generators[] = $this->getGenerator( 'EntityInjector' );

    return $generators;

  }//end public function getInjectGenerators */



} // end abstract class LibCartridgeBdlSubEntity
