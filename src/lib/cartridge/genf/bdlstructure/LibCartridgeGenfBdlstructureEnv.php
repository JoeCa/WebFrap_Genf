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
class LibCartridgeGenfBdlstructureEnv
  extends LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// Builder
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $folder
   * @param LibGenfEnvManagement $env
   * 
   * @return string
   */
  public function buildNode( $folder, $env )
  {

    $name = $env->name;

    
    $env->switchFormContext( 'create' );
    

    if( $this->builder->sandbox )
    {
      
      $fileName     = $name->class.'_Crud_Create_Subwindow_View_Genf';
      $code = $this->render_GenfClass( $env, $fileName );

      $classPath    = $this->getClassPath($fileName,false);
      $folderPath   = $folder.'genf/'.$name->lower('customModul').'/module/'.$classPath;
      $this->writeFile( $code, $folderPath, $fileName.'.php', $phar, '/module/'.$classPath );
      
      $fileName     = $name->class.'_Crud_Create_Subwindow_View';
      $code = $this->render_HandCodeClass( $env, $fileName );

      $classPath    = $this->getClassPath($fileName,false);
      $folderPath   = $folder.'hand/'.$name->lower('customModul').'/module/'.$classPath;
      $this->writeFile( $code, $folderPath, $fileName.'.php', $phar, '/module/'.$classPath );

    }
    else
    {
      
      $fileName     = $name->class.'_Crud_Create_Subwindow_View';
      $code = $this->render_GenfClass( $env, $fileName );

      $classPath    = $this->getClassPath($fileName,false);
      $folderPath   = $folder.'hand/'.$name->lower('customModul').'/module/'.$classPath;
      $this->writeFile( $code, $folderPath, $fileName.'.php', $phar, '/module/'.$classPath );

    }

  }//end public function buildNode */

  /**
   * (non-PHPdoc)
   * @see src/lib/LibCartridge#init()
   */
  protected function init()
  {
  }//end protected function init '/

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param LibGenfEnvManagement $env
   * @return string
   */
  protected function render_GenfClass( $env, $className  )
  {

    $name     = $env->name;
    $project  = $this->project;

    $code     = $this->createCodeHead(  );

    $code .= <<<CODE

/**
 * @package WebFrap
 * @subpackage Mod{$name->customModul}
 * @author {$project->author}
 * @copyright {$project->copyright}
 * @licence {$project->licence}
 */
class {$className}
  extends WgtWindowTemplate
{
CODE;

  $code .= $this->createCodeSeperator( 'Attributes' );
  $code .= $this->generateMemberVariables( $env );
  
  $code .= <<<CODE

}//end class {$className}

CODE;

    $code .= $this->createCodeFooter();

    return $code;

  }//end public function render_GenfClass

/*//////////////////////////////////////////////////////////////////////////////
// Display Methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param LibGenfEnvManagement $env
   * @return string
   */
  protected function generateMemberVariables( $env )
  {

    $name = $env->name;

    $code = <<<CODE

    /**
    * @var {$name->class}_Crud_Model
    */
    public \$model = null;
    
    /**
     * (non-PHPdoc)
     * @see WgtWindowTemplate::init()
     */
    public function init()
    {
    
      \$this->minWidth  = 300;
      \$this->minHeight = 400;
      \$this->width     = 300;
      \$this->height    = 400;
      \$this->resizable = true;
      \$this->movable   = true;
      \$this->closable  = true;
      
      \$this->models    = new TArray();
      
    }//end public function init */

CODE;

    return $code;

  }//end protected function generateMemberVariables */
  
} // end class LibCartridgeGenfBdlstructureEnv
