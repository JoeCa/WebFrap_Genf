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
 * Enter description here...
 *
 */
define( 'TAG_PHP_OPEN' , '<?php' );

/**
 * Enter description here...
 *
 */
define( 'TAG_PHP_CLOSE' , '?>' );

/**
 * Enter description here...
 *
 */
define( 'TAG_TEMPLATE_OPEN' , '<?php' );

/**
 * Enter description here...
 *
 */
define( 'TAG_TEMPLATE_CLOSE' , '?>' );

/**
 * @package WebFrap
 * @subpackage ModGenf
 * @author Dominik Bonsch <sono@webfrap.net>
 */
abstract class LibParserWbfcodeTemplate
  extends LibParserWbfcodeAbstract
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var String
   */
  protected $basePath = 'data/code_gen_cache/lib/';

  /**
   * The Template parser
   *
   * @var ObjTemplatePhp
   */
  protected $parser = null;

  /**
   * all templates
   *
   * @var array
   */
  protected $templateFolder = null;

  /**
   * all templates
   *
   * @var array
   */
  protected $templates = array();

  /**
   * attributes for the code template
   *
   * @var array
   */
  protected $templateFlags = array();

  /**
   * @var array
   */
  protected $autoMethodes     = array();

  /**
   * @var array
   */
  protected $codeAutoMethodes = array();

  /**
   * Enter description here...
   *
   * @var array
   */
  protected $classEnding = array();


////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @param string $templateFolder
   */
  public function setTemplateFolder($templateFolder)
  {
    if(Log::$levelDebug)
     Log::start(__file__,__line__,__method__,array($templateFolder));

    $this->templateFolder = $templateFolder;
  }//end public function setTemplateFolder($templateFolder)

  /**
   * Add a Template to the parser
   *
   * @param string $type the Type of Class
   * @param string $template the template to use to parse
   * @param string $ending an ending for all Classnames of this type
   */
  public function addTemplate( $type , $template , $ending = null )
  {
    if(Log::$levelDebug)
     Log::start(__file__,__line__,__method__,array($type , $template , $ending));

    $this->templates[$type] = $template;
    $this->classEnding[$type] = $ending;

  }//end public function addTemplate($type , $template , $ending = null)

  /**
   * add attributes for a template
   *
   * @param string $template
   * @param string $attributes
   *
   */
  public function addTemplateAttributes( $template , $attributes)
  {
    if(Log::$levelDebug)
     Log::start(__file__,__line__,__method__,array($template , $attributes));

    $this->templateAttributes[$template] = $attributes;
  }//end public function addTemplate( $type , $template)

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param String $xmlFile the Filepath of the Metamodel to parse
   * @param SimpleXMLElement $xml
   */
  public function __construct( $xmlFile , $xml = null )
  {
    if( Log::$levelVerbose )
      Log::create(get_class($this) ,array($xmlFile , $xml)) ;

    $this->xmlFile = $xmlFile;

    if( !$xml )
    {
      $this->load();
    }
    else
    {
      $this->xmlModel = $xml;
    }

    $this->basePath     = PATH_FW.$this->basePath;
    $this->outputFolder = $this->basePath;

    $this->langParser = ObjParserWbfcodeI18n::getInstance();
    $this->parser = new ObjTemplateMdsd();

    $this->init();

  }//end public function __construct( $xmlFile , $xml = null )

////////////////////////////////////////////////////////////////////////////////
// Parser Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * extract all Data from the Model
   * and put it in the Template
   *
   * @param String $name
   * @return void
   */
  public abstract function extractModelData( $name , $template );

  /**
   * parse the template
   *
   */
  public function parseTemplate($name , $template)
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($name , $template));

    $this->extractModelData( $name , $template );

    $this->parser->setHead( $this->createCodeHead() );
    $this->parser->setFoot( $this->parseFoot() );

    return $this->parser->parse();

  }//end public function parseTemplate()

  /**
   * clean all temporary and generated data
   *
   */
  public function clean()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $this->parser->cleanParser();

    $this->parsedElements = array();
    $this->templates      = array();
    $this->mapToParse     = array();

  }//end public function clean()

////////////////////////////////////////////////////////////////////////////////
// Implemet Abstract Methods
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function highlight()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);


    $code = '';

    foreach( array_keys($this->templates) as $tempType )
    {
      foreach( $this->parsedElements as $name => $data )
      {
        $code .='<h4>'.ucfirst($tempType).' '.$name.'</h4>';
        $code .='<pre>'.highlight_string(utf8_decode($data),true).'</pre>';
      }
    }

    return $code;

  }//end public function highlight

  /**
   * Enter description here...
   *
   */
  public function write()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    if( $this->useSandbox )
    {
      $this->outputFolder = SANDBOX_LIB_PATH;
      $this->useClassPath = true;
    }

    foreach( array_keys($this->templates) as $tempType )
    {
      foreach( $this->parsedElements as $name => $code )
      {

        if(Log::$levelTrace)
          Log::getInstance()->trace(__file__,__line__, 'write Template: '.$tempType.' class: '.$name );

        $fileName =  $tempType.IncParserString::subToCamelCase($name).$this->classEnding[$tempType];
        $classPath = IncParserString::getClassPath($fileName);

        if( $this->useClassPath )
        {
          $folderPath = $this->outputFolder.IncParserString::getClassPath($fileName,false);
          $path =  $folderPath.$fileName.'.php';
        }
        else
        {
          $folderPath = $this->outputFolder;
          $path = $this->outputFolder.$fileName.'.php';
        }

        if( !file_exists($folderPath) )
        {
          if(!IncFilesystem::createFolder($folderPath))
          {
            Error::addError
            (
            I18n::s('wbf.message.failedToCreateFolder'. array($folderPath))
            );
            return;
          }
        }

        if(!SFiles::write( $path , $code ))
        {
          Error::addError
          (
          'Failed to write '.$path
          );
        }
        else
        {
          Message::addMessage($fileName.'.php wurde erfolgreich erstellt.');
        }

      }//end foreach $this->parsedElements

    }//end foreach array_keys($this->templates)

  }//end public function write()

} // end class LibParserWbfcodeTemplate

