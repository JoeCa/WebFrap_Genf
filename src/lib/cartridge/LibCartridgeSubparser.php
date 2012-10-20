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
abstract class LibCartridgeSubparser
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the builder class
   *
   * @var LibGenfBuild
   */
  protected $builder        = null;

  /**
   * the xml project description
   * @var SimpleXmlElement
   */
  protected $project        = null;

  /**
   *
   * @var LibCartridgeI18n
   */
  protected $i18nPool       = null;

  /**
   *
   * @var LibGenfName
   */
  protected $name           = null;

  /**
   *
   * @var LibGenfTreenode
   */
  protected $node           = null;

  /**
   *
   * @var LibGenfTreeRoot
   */
  protected $root           = null;

  /**
   *
   * @var LibGenfEnv
   */
  protected $env           = null;
  
  /**
   *
   * @var LibResponseHttp
   */
  protected $response      = null;

////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild $builder the Filepath of the Metamodel to parse
   */
  public function __construct( $builder )
  {

    $this->builder    = $builder;
    $this->project    = $builder->getProject();
    $this->i18nPool   = LibCartridgeI18n::getInstance();
    
    //Message::addMessage( "Using Subcartridge : ".get_class( $this ) );

    $this->init( );

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param $node
   */
  public function setNode( $node )
  {
    $this->node  = $node;
  }//end public function setNode */

  /**
   * @param $name
   */
  public function setName( $name )
  {
    $this->name  = $name;
  }//end public function setName */

  /**
   * @return LibResponseHttp
   */
  public function getResponse(  )
  {
    
    if( !$this->response )
      $this->response = Response::getActive();
      
    return $this->response;
    
  }//end public function getResponse */

////////////////////////////////////////////////////////////////////////////////
// redundant code, search a better solution for that
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the head
   * @param LibGenfBuilder $project
   * @return  string
   */
  public function createCodeHead(  )
  {

    if( $projectHead = $this->builder->getHeader() )
      return '<?php '.NL.$projectHead.NL;

    $project = $this->builder->getProject();

    $head = '<?php
/*******************************************************************************
* Webfrap.net Legion
*
* @author      : '.$project->author.'
* @date        : @date@
* @copyright   : '.$project->copyright.'
* @project     : '.$project->projectName.'
* @projectUrl  : '.$project->projectUrl.'
* @licence     : '.$project->licence.'
*
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/
';

  return $head;


  }//end public function createCodeHead */
  
  /**
   * @param string $className
   */
  public function getHeadMessage( $className )
  {
    
    if( $this->builder->sandbox )
    {

      return <<<MESSAGE
 * DO NOT CHANGE THIS CLASS BY HAND
 * ALL CHANGES WILL BE DROPPED BY THE SYSTEM

MESSAGE;

    }
    else
    {

      return <<<MESSAGE
 * Read before change:
 * It's not recommended to change this file inside a Mod or App Project.
 * If you want to change it copy it to a custom project.

MESSAGE;

    }
    
    /* zu langer header
    if( $this->builder->sandbox )
    {

      return <<<MESSAGE
 * This is the Genf Class, this means this class only contains generated code
 * If you want to extend this class write your coden in {$className}
 *
 * NEVER WRITE CODE IN THIS CLASS
 * THE CONTENT OF THIS CLASS IS NOT PERSISTENT AND CAN CHANGE OFTEN
 * ALL YOUR CHANGES WILL BE OVERWRITEN!!!
 * YOU HAVE BEEN WARNED!!!
MESSAGE;

    }
    else
    {

      return <<<MESSAGE
 * This Class was generated with a Cartridge based on the WebFrap GenF Framework
 * This is the final Version of this class.
 * It's not expected that somebody change the Code via Hand.
 *
 * You are allowed to change this code, but be warned, that you'll loose
 * all guarantees that where given for this project, for ALL Modules that
 * somehow interact with this file.
 * To regain guarantees for the code please contact the developer for a code-review
 * and a change of the security-hash.
 *
 * The developer of this Code has checksums to proof the integrity of this file.
 * This is a security feature, to check if there where any malicious damages
 * from attackers against your installation.
 *
MESSAGE;
    

    }*/

  }//end public function getHeadMessage */

  /**
   * parse the footer
   * @return string
   */
  public function createCodeFooter()
  {
    return NL;
  }//end public function createCodeFooter */

  /**
   * parse the footer
   * @return string
   */
  public function parseFoot()
  {
    return NL;
  }//end public function parseFoot */

  /**
   * parse a code seperator banner with text
   *
   * @param string $content
   * @return string
   */
  public function parseCodeSeperator( $content )
  {

    $code ='
////////////////////////////////////////////////////////////////////////////////
// '.$content.'
////////////////////////////////////////////////////////////////////////////////
    ';

    return $code;

  }//end public function parseCodeSeperator */

  /**
   * parse a code seperator banner with text
   *
   * @param string $content
   * @return string
   */
  public function createCodeSeperator( $content )
  {

    $code ='
////////////////////////////////////////////////////////////////////////////////
// '.$content.'
////////////////////////////////////////////////////////////////////////////////
';

    return $code;

  }//end public function createCodeSeperator */
  
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
   * empty init function for the constructor
   */
  protected function init()
  {
  }//end protected function init

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return TArray
   */
  protected function newCodeStack()
  {
    return new TCodeStack();
  }//end protected function newCodeStack */
  
  /**
   * @param string $key
   * @param LibGenfEnv $env
   * @param string $methodRequired Name einer Methode die vorhanden sein muss
   *   Wird verwendet wenn der Generator ein bestimmtes Interface implementieren muss
   *  Um das Ganze abzukürzen wird einfach auf die Existenz einer Methode geprüft
   *  Das mit dem Interface kommt vielleicht noch bei bedarf
   * @return LibGenfGenerator
   */
  public function getGenerator( $key, $env = null, $methodRequired = null )
  {

    if( !$env )
      $env = $this->env;

    $generator = $this->builder->getGenerator( $key, $env );
    
    if( !$methodRequired )
      return $generator;
      
    if( method_exists( $generator, $methodRequired ) )
      return $generator;
      
    return null;
    
  }//end public function getGenerator */
  
  /**
   * @return LibGeneratorWbfAccessContainer
   */
  public function getAccessContainerGenerator()
  {
    return $this->builder->getGenerator('AccessContainer');
  }//end public function getAccessContainerGenerator */

  /**
   *
   * @return LibBdlCodeParser
   */
  public function getCodeCompiler( )
  {
    return $this->builder->bdlRegistry->getCodeCompiler();
  }//end public function getCodeCompiler */
  
  /**
   * @return LibBdlFilterParser
   */
  public function getFilterParser( )
  {
    return $this->builder->bdlRegistry->getFilterParser();
  }//end public function getFilterParser */

  /**
   *
   * @return LibBdlAclCompiler
   */
  public function getAclCompiler( )
  {
    return $this->builder->bdlRegistry->getAclCompiler( );
  }//end public function getAclCompiler */
  
  /**
   * @param $className
   * @param $full
   */
  public function getClassPath( $className , $full = false )
  {
    return SParserString::getClassPath( $className , $full );
  }//end public function getClassPath */

  /**
   * @param string $code
   * @param string $folderPath
   * @param string $filename
   *
   */
  protected function writeFile( $code, $folderPath, $filename )
  {

    $absolute = $folderPath[0]=='/'?true:false;
    
    $response = $this->getResponse();
    

    if( !file_exists($folderPath) )
    {
      if(!SFilesystem::createFolder($folderPath,true,$absolute))
      {
        
        $errorMsg = $response->i18n->l
        (
          'Failed to create temporary path {@folder@}',
          'wbf.message',
          array( 'folder' => $folderPath )  
        );
        
        Error::addError($errorMsg);

        $response->addError( $errorMsg );
        return;
      }
    }

    if( !SFiles::write( $folderPath.'/'.$filename , $code ) )
    {
      
      Error::addError( 'Failed to write '.$folderPath.'/'.$filename );
      
      $response->addWarning( 'Failed to write '.$folderPath.'/'.$filename );
      
    }
    else
    {
      
      if( Log::$levelDebug )
        Log::debug( 'Wrote: '.$folderPath.'/'.$filename );
    }


  }//end public function writeFile */
  
  
  /**
   * @param string $message
   */
  public function reportError( $message )
  {
    
    $this->builder->error( "Sub Cartridge ".get_class($this)." ".$message." ".$this->builder->dumpEnv() );
    
  }//end public function reportError */

} // end abstract class LibCartridgeSubparser
