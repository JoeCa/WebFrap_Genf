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
class LibCartridgeWbf
  extends LibCartridgeBdlManagement
{

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   *
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

    foreach( $this->root as $management )
    {

      if(!$this->root->setActiv( $management ))
        continue;

      $name       = $management->name;
      $this->env  = $this->root->createEnvironment($management);

      if( $this->builder->sandbox )
      {
        $fileName     = $name->class.'_Genf';
        $code = $this->createCodeHead( );
        $code .= $this->render_GenfClass( $this->env, $fileName );
        $code .= $this->createCodeFooter();


        $classPath    = $this->getClassPath($fileName,false);
        $folderPath   = $folder.'genf/'.$name->lower('customModul').'/module/'.$classPath;
        $this->writeFile( $code, $folderPath, $fileName.'.php' );

        $fileName     = $name->class.'_';
        $code = $this->createCodeHead( );
        $code .= $this->render_HandCodeClass( $this->env, $fileName );
        $code .= $this->createCodeFooter();

        $classPath    = $this->getClassPath($fileName,false);
        $folderPath   = $folder.'hand/'.$name->lower('customModul').'/module/'.$classPath;
        $this->writeFile( $code, $folderPath, $fileName.'.php' );
      }
      else
      {
        $fileName     = $name->class.'_';
        $code = $this->createCodeHead( );
        $code .= $this->render_GenfClass( $this->env, $fileName );
        $code .= $this->createCodeFooter();

        $classPath    = $this->getClassPath($fileName,false);
        $folderPath   = $folder.'hand/'.$name->lower('customModul').'/module/'.$classPath;
        $this->writeFile( $code, $folderPath, $fileName.'.php' );
      }

    }//end foreach

  }//end public function parse */


  /**
   * (non-PHPdoc)
   * @see src/lib/LibCartridge#init()
   */
  protected function init()
  {


  }//end protected function init '/

////////////////////////////////////////////////////////////////////////////////
// Logic Parsers
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param LibGenfEnvManagement $env
   * @param string $className
   * @return string
   */
  protected function render_GenfClass( $env, $className )
  {

    $management = $env->management;
    $name       = $env->name;

    $project    = $this->project;

    $headMessage = $this->getHeadMessage( $className );

    $code = <<<CODE

/**
{$headMessage}
 *
 * @package WebFrap
 * @subpackage Mod{$name->customModul}
 * @author {$project->author}
 * @copyright {$project->copyright}
 * @licence {$project->licence}
 */
class {$className}
  extends
{
CODE;

  $code .= $this->createCodeSeperator( '' );
  $code .= $this->method_( $management );

  $code .= <<<CODE

} // end class {$className} */

CODE;

    return $code;

  }//end public function render_GenfClass */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @return string
   */
  protected function method_( $management )
  {

    $name           = $management->name;

    $code = <<<CODE

CODE;

    return $code;

  }//end public function method_ */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return string
   */
  protected function method_1( $management )
  {

    $name           = $management->name;

    $code = <<<CODE

CODE;

    return $code;

  }//end public function method_ */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return string
   */
  protected function method_2( $management )
  {

    $name           = $management->name;

    $code = <<<CODE

CODE;

    return $code;

  }//end public function method_ */

} // end class LibCartridgeWbf
