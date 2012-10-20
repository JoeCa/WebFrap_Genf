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
abstract class LibCartridgeBdlSubManagement
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
  protected $nodeType     = 'Management';

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
  
  /**
   * @var string
   */
  protected $prefixLabel = null;
  
  /**
   * @var string
   */
  protected $validNodeTypes = null;
  
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
   * @return LibBdlCodeParser
   */
  public function getFilterCompiler( )
  {
    return $this->builder->bdlRegistry->getFilterParser();
  }//end public function getFilterCompiler */

  /**
   * @param LibGenfTreeRootManagement
   */
  public function setRoot( $root )
  {
    $this->root = $root;
  }//end public function setRoot */
  
  /**
   * @return array
   */
  public function getInjectGenerators()
  {

    $generators = array();

    $generators[] = $this->getGenerator( 'ProcessInjector' );
    $generators[] = $this->getGenerator( 'EventActionClass' );

    return $generators;

  }//end public function getInjectGenerators */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfEnvWidget
   */
  public function createEnvironment( $management )
  {

    $environment = new LibGenfEnvManagement( $this->builder, $management );

    return $environment;

  }//end public function createEnvironment */
  
  /**
   * @param LibGenfTreeNodeManagement $management
   */
  public function isTypeValid( $management )
  {
    
    if( is_null( $this->validNodeTypes ) )
    {
      return true;
    }
    
    $type = $management->getType();
    
    return in_array( $type, $this->validNodeTypes );
    
  }//end public function isTypeValid */
  
  

} // end abstract class LibCartridgeBdlSubWidget
