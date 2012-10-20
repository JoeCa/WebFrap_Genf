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
class LibGenfGenerator
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
   * the parser tree
   *
   * @var LibGenfTree
   */
  protected $tree           = null;

  /**
   * activ tree root
   *
   * @var LibGenfTreeRoot
   */
  protected $root           = null;

  /**
   * te activ node
   *
   * @var LibGenfTreeNode
   */
  protected $node           = null;

  /**
   * collecting all used lang entries
   *
   * @var LibCartridgeWbfI18n
   */
  protected $i18nPool       = null;

  /**
   * collecting all used lang entries
   *
   * @var LibGenfName
   */
  protected $name           = null;

  /**
   *
   * @var LibGenfName
   */
  protected $compName         = null;

  /**
   * a context to define diffrent points of views
   * @var string
   */
  protected $context         = 'table';

  /**
   *
   * @var LibGenfEnvManagement
   */
  protected $env          = null;


  /**
   * the parsed code
   * @var string
   */
  public $ws              = '';

  /**
   * the parsed code
   * @var string
   */
  public $wsFactor        = 2;

  /**
   * the parsed code
   * @var string
   */
  public $wsCount         = 0;


////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild      $builder
   * @param SimpleXmlElement  $root
   */
  public function __construct( $builder, $root = null  )
  {

    if($root)
      $this->root     = $root;

    $this->builder    = $builder;
    $this->tree       = $builder->getTree();
    $this->i18nPool   = LibCartridgeI18n::getInstance();

    // init method
    $this->init();

  }//end public function __construct */
  
  /**
   * Abfangen von nicht existierenden Calls
   */
  public function __call( $method, $arguments )
  {
    
    $this->builder->dumpError( "The requested Method {$method} not exists in class ".get_class($this), $arguments );
    
    throw new MethodNotExists_Exception( "The requested Method {$method} not exists in class ".get_class($this), $arguments );  
  }//end public function __call */
  
////////////////////////////////////////////////////////////////////////////////
// getter + setter
////////////////////////////////////////////////////////////////////////////////
  

  /**
   *
   * @param SimpleXMLNode $i18nNode
   * @param string $langKey
   * @param string $key
   */
  public function getI18nBody( $i18nNode, $langKey, $i18nKey, $i18nText = null  )
  {

    foreach( $i18nNode->text as $text )
    {
      $lang = trim($text['lang']);

      if( $langKey == $lang)
        $i18nText = trim($text);

      $this->i18nPool->addText($lang,$i18nKey,trim($text) );

    }

    return "'{$i18nText}', '{$i18nKey}'";

  }//end public function getI18nBody */


  /**
   * @param sting $key
   */
  public function getName( $key = null )
  {
    return $this->name;
  }//end public function getName */

  /**
   * @param sting $key
   */
  public function setName( $name  )
  {
    $this->name = $name;
  }//end public function setName */

  /**
   * @param sting $key
   */
  public function setRoot( $root  )
  {
    $this->root = $root;
  }//end public function setRoot */

  /**
   * @param sting $key
   */
  public function setNode( $node  )
  {
    $this->node = $node;
  }//end public function setNode */


  /**
   * @param sting $key
   */
  public function setEnv( $env )
  {
    $this->env = $env;
  }//end public function setEnv */
  
  /**
   * @param sting $key
   */
  public function getEnv(  )
  {
    return $this->env;
  }//end public function $env */

  /**
   *
   * @param string $type
   * @return LibGenfParser
   */
  public function getParser( $type )
  {
    return $this->builder->getParser( $type );
  }//end public function getParser */

  /**
   *
   * @param string $type
   * @return LibGenfParser
   */
  public function getGenerator( $type, $env = null )
  {

    if( !$env )
      $env = $this->env;

    return $this->builder->getGenerator( $type, $env );

  }//end public function getGenerator */

  /**
   *
   * @return LibBdlCodeParser
   */
  public function getCodeCompiler( )
  {
    return $this->builder->bdlRegistry->getCodeCompiler();
  }//end public function getCodeCompiler */

  /**
   *
   * @return LibBdlCodeParser
   */
  public function getFilterCompiler( )
  {
    return $this->builder->bdlRegistry->getFilterParser();
  }//end public function getFilterCompiler */

  /**
   *
   * @param string $type
   * @return LibBdlFilterParser
   */
  public function getFilterParser( )
  {
    return $this->builder->bdlRegistry->getFilterParser( );
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
   * @param LibGenfName $compName
   */
  public function setComponentName( $compName )
  {

    $this->compName = $compName;

  }//end public function setComponentName */

  /**
   * @param string $context
   */
  public function setContext( $context )
  {

    $this->context = $context;

  }//end public function setContext */

  /**
   * @overwrite
   */
  public function init(){}

  /**
   *
   * @param unknown_type $num
   * @return unknown_type
   */
  public function wsp( $num )
  {
    return str_pad('', ($num * $this->builder->indentation) );
  }//end public function wsp */

  /**
   * parse a sub named to camel case
   * @param string $string
   * @return string
   */
  public function subToCamelCase( $string )
  {
    return SParserString::subToCamelCase( $string );
  }//end public function subToCamelCase */

  
  /**
   * @param sting $methodName
   * @param sting $error
   * @throws
   */
  public function methodExists( $methodName, $error = null )
  {
   
    if( method_exists( $this , $methodName) )
      return true;
    
    if( $error )
    {
      throw new LibGenf_Exception( $error );
    }
    else 
    {
      return false;
    }
    
  }//end public function methodExists */

  /**
   * @param string $code
   * @param string $folderPath
   * @param string $filename
   *
   */
  protected function writeFile( $code, $folderPath, $filename )
  {

    $absolute = $folderPath[0]=='/'?true:false;

    if( !file_exists($folderPath) )
    {
      if(!SFilesystem::createFolder($folderPath,true,$absolute))
      {
        Error::addError
          ( I18n::s('wbf.msg.failedToCreateFolder',array($folderPath)) );

        Message::addError('Konnte den Temporären Pfad nicht erstellen');
        return;
      }
    }

    if(!SFiles::write( $folderPath.'/'.$filename , $code ))
    {
      Error::addError( 'Failed to write '.$folderPath.'/'.$filename );
      Message::addWarning( 'Failed to write '.$folderPath.'/'.$filename );
    }
    else
    {
      Log::debug( 'Wrote: '.$folderPath.'/'.$filename );
    }


  }//end public function writeFile */


  /**
   * parse the head
   * @param LibGenfBuilder $project
   * @return  string
   */
  public function createCodeHead()
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
    
    /*
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

    }
    */

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

    $code='
////////////////////////////////////////////////////////////////////////////////
// '.$content.'
////////////////////////////////////////////////////////////////////////////////
    ';

    return $code;

  }//end public function parseCodeSeperator */

/*//////////////////////////////////////////////////////////////////////////////
// field data
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param LibGenfEnvReference $env
   * @param LibGenfTreeNodeAttribute $field (TContextAttribute)
   * @param SimpleXmlElement $node der Modellknoten
   * 
   * @return LibGenfFieldData
   */
  public function fieldData( $env, $field, $node = null )
  {

    $data = new LibGenfFieldData();


    if( $field instanceof LibGenfTreeNode )
    {
      throw new LibGenf_Exception
      (
        'Got a non context TreeNode in fieldData '.$this->builder->dumpEnv()
      );
    }


    if( $field instanceof TContext )
    {

      $hasField = false;

      $data->obj          = $field;
      $data->fieldType    = $field->uiElement->type();

      // der SimpleXmlNode des Field Nodes
      $data->fieldNode    = $node;

      // wenn kein typ definiert ist bestimmt der validator den field type
      if( 'guess' == $data->fieldType )
        $data->fieldType  = $field->validator();

      $data->type         = $field->type();
      $data->fieldAction  = $field->fieldAction;
      
      if( $node )
      {
        if( isset( $node->action ) )
        {
          $type      = trim($node->action['type']);
          $className = 'LibGenfTreeNodeTrigger_'.SParserString::subToCamelCase($type);
          
          $data->actionTrigger  = new $className( $node->action );
        }
      }

      // link auf den original namen des attributes
      $data->origName     = $data->attrName;

      // prüfen ob das aktuelle attribute eine referenz in diesem context
      // ist und durch einen textwert aus der referenztabelle ersetzt werden soll
      if( !$data->attrName  = $field->field( $this->context ) )
      {
        $data->attrName     = $field->name->name;
      }

      $data->table        = $field->management->name->name;
      $data->tableSrc     = $field->management->name->source;
      $data->management   = $field->management->name->name;
      $data->mgmtName     = $field->management->name->name;
      $data->origTable    = $data->table;

      // check auf tableField is hier auch bei selection ok
      if( $srcField = $field->sourceField( $this->context ) )
      {

        $hasField = true;

        $data->attrName   = $srcField;
        $data->table      = $field->sourceName( $this->context );
        $data->management = $data->table;
        $data->mgmtName   = $data->table;

        // overwrite type cause now it's a text and no more int number
        $data->type       = 'text';
        $data->fieldType  = 'text';
      }

      if( isset( $field->ref )  )
      {
        $data->table  = $field->ref->name->name;
      }

      if( $targetManagement  = $field->targetManagement( ) )
      {
        if( $hasField )
          $data->table  = $targetManagement->name->name;
      }

      if( $alias      = $field->sourceAlias( $this->context ) )
      {
        $data->table  = $alias;
      }

      $data->i18nKey  = $field->name->i18nKey;
      $data->label    = $field->name->label;

    }
    /*
    else if( !$field->type )
    {
      
      $this->builder->dumpEnv('reached strange code');
      
      try
      {

        if( !$entity = $this->root->getEntity( $field->source ) )
          throw new LibParser_Exception( 'Found no entity '.$field->source );

        if( !$attribute = $entity->getAttribute( $field->field ) )
          throw new LibParser_Exception( 'Found no field '.$field->field.' in entity '.$field->source );

      }
      catch( LibParser_Exception $e )
      {

        $this->builder->error( $e->getMessage()." ".$this->builder->dumpEnv() );

      }//end catch

      $data->obj          = $attribute;
      $data->fieldType    = $attribute->validator();
      $data->type         = $attribute->type();

      $data->table        = $field->source;
      $data->origTable    = $data->table;
      $data->attrName     = $field->name;
      $data->origName     = $data->attrName;
      
      $data->fieldAction  = $field->fieldType( $this->context );

    }
    */
    else
    {

      throw new LibGenf_Exception
      (
        'Field Data: Reached else field: '.$field->name.' table:'.$data->table.' in TreeNode in fieldData '.$this->env
      );

    }


    // korrektur
    $data->origName = $field->attribute->name->name;
    if( isset( $env->ref ) )
    {
      if( $env->ref->relation( Bdl::MANY_TO_MANY ) )
      {

        $data->origTable = $env->ref->connection()->source;
        $data->origKey   = $env->ref->connection()->name;

      }
      else
      {

        $data->origTable = $env->ref->target()->source;
        $data->origKey   = $env->ref->target()->name;

      }
    } // no ref
    else
    {

      if( isset( $data->obj->ref ) && $data->obj->ref->relation( Bdl::ONE_TO_ONE )  )
      {
        $data->origTable = $data->obj->ref->target()->source;
        $data->origKey   = $data->obj->ref->name->name;
        $data->table     = $data->obj->ref->name->name;
      }
      else
      {
        $data->origTable = $env->management->name->source;
        $data->origKey   = $env->management->name->name;
      }

    }

    return $data;

  }//end public function fieldData */

/*//////////////////////////////////////////////////////////////////////////////
// line and inheritance
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   */
  public function wsPad( $count )
  {
    $this->wsCount = $count;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function wsPad */


  /**
   *
   */
  public function setWsPadding( $count )
  {
    $this->wsCount = $count;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function setWsPadding */

  /**
   *
   * Enter description here ...
   */
  public function wsInc()
  {
    ++$this->wsCount;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function wsInc */

  /**
   *
   * Enter description here ...
   */
  public function wsDec()
  {
    --$this->wsCount;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function wsDec */


  /**
   * @return string
   */
  public function line( $code )
  {
    return $this->ws.$code.NL;
  }//end public function line */


  /**
   * @return sline
   */
  public function sLine( $code )
  {
    return $this->ws.$code;
  }//end public function line */

  /**
   * @return string
   */
  public function cLine( $code )
  {
    return $code.NL;
  }//end public function cline */

  /**
   * @return string
   */
  public function nl(  )
  {
    return NL;
  }//end public function cline */

  /**
   * @param string $code
   * @param int $indent
   * @deprecated use self::indent
   *
   * @return string
   */
  public function ident( $code, $indent  )
  {

    return SParserString::setIndentinon( $code, $indent );

  }//end public function ident */
  
  /**
   * @param string $code
   * @param int $indent
   *
   * @return string
   */
  public function indent( $code, $indent  )
  {

    return SParserString::setIndentinon( $code, $indent );

  }//end public function indent */

  /**
   * @return string
   */
  public function string( $code )
  {
    return '"'.$code.'"';
  }//end public function string */


  public function warn( $message )
  {
    $this->builder->warn( $message );
  }

  /**
   *
   * @param string $message
   * @param call $call
   */
  public function error( $message ,$call = 'logicError' )
  {

    $this->builder->error( $message );

    $this->builder->protocol->{$call}($message);

  }//end public function error */

  /**
   * @param string $message
   */
  public function reportError( $message )
  {

    $this->builder->error( "Generator ".get_class($this)." ".$message." ".$this->builder->dumpEnv() );

  }//end public function reportError */

}//end class LibGenfParser
