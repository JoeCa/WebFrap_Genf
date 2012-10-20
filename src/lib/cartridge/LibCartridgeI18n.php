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
 * Collector for alle Language files to write
 * @package WebFrap
 * @subpackage Genf
 */
class LibCartridgeI18n
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @var LibCartridgeI18n
   */
  private static $instance  = null;

  /**
   * Enter description here...
   *
   * @var array
   */
  protected $lPoolKeys      = array();

  /**
   * pool with all language texts
   *
   * @var array
   */
  protected $lPoolText      = array();

  /**
   * Enter description here...
   *
   * @var array
   */
  protected $files          = array();

  /**
   * @var string
   */
  protected $subPath        = 'i18n/default/';

  /**
   *
   * @var string
   */
  protected $pathOutput     = null;

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder        = null;

  /**
   *
   * @var array
   */
  protected $langs          = array
  (
    'de', // deutsch
    'en', // english
    'fr', // französisch
    'ru', // russisch
    'nl', // niederlande
    'it', // italiänisch
    'es', // spanisch
    'ca', // catalanisch
    'hr', // croatian
  );

  /**
   *
   * @var unknown_type
   */
  protected $genfCode       = array();

  /**
   *
   * @var unknown_type
   */
  protected $handCode       = array();

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Singleton get Instance Method
   * @param LibGenfBuild $builder
   * @return LibCartridgeI18n
   */
  public static function createInstance( $builder )
  {

    $className = $builder->getCartridgeClass('I18n');

    ///TODO Some better errorhandler here
    self::$instance = new $className( $builder );

    return self::$instance;

  }//end public static function createInstance */

  /**
   * Singleton get Instance Method
   * @return LibCartridgeI18n
   */
  public static function getInstance()
  {
    return self::$instance ;
  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Constructor
   */
  public function __construct( $builder = null )
  {
    $this->builder = $builder;
    // plain constructor
  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// p & w methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the parser
   */
  public function parse()
  {


    foreach( $this->lPoolText as $lang => $repos )
    {

      if(!in_array($lang, $this->langs))
        continue;

      foreach( $repos as $repo => $texts  )
      {

        $file    = SParserString::geti18nBasePath( $repo );
        $modName = strtolower( SParserString::geti18nModname($repo) );

        if(!isset($this->genfCode[$modName]))
          $this->genfCode[$modName] = array();

        if(!isset($this->genfCode[$modName][$lang]))
          $this->genfCode[$modName][$lang] = array();

        if(!isset($this->genfCode[$modName][$lang][$file]))
          $this->genfCode[$modName][$lang][$file] = $this->addLangfile($lang).NL;

        $entry = '';

        if(!is_array($texts))
        {
          Debug::console("TEXT $texts is kein array");
          continue;
        }

        foreach( $texts as $key => $value )
        {
          $entry .= "\$this->l['{$repo}']['{$key}'] = '".addslashes($value)."';".NL;
        }

        $this->genfCode[$modName][$lang][$file] .= $entry;

      }
    }



  }//end public function parse */

  /**
   * write the lang files
   */
  public function write()
  {


    if(!$this->pathOutput)
    {
      $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $folder = $this->pathOutput;
    }

    $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';


    foreach( $this->genfCode as $mod => $elements )
    {
      foreach( $elements as $lang => $parsedData )
      {
        foreach( $parsedData as $name => $data )
        {

          $folderPath = strtolower( $folder.'genf/'.$mod.'/'.$this->subPath.$lang.SParserString::getFileFolder($name));
          $filePath   = strtolower( $folder.'genf/'.$mod.'/'.$this->subPath.$lang.'/').$name;

          if( !SFilesystem::createFolder($folderPath) )
          {
            Error::addError
            (
              I18n::s
              ( 
                'Failed to create Folder {@folder@}',
                'wbf.message', 
                array( 'folder' => $folderPath) 
              )
            );
            return;
          }

          if( !SFiles::write( $filePath, '<?php'.NL.$data.NL  ) )
          {
            Error::addError('Failed to write '.$filePath);
            Message::addError($filePath.' : '.$folderPath.' konnte nicht erstellt werden.');
          }


        }//end foreach( $parsedData as $name => $data )

      }//end foreach( $elements as $lang  => $parsedData )

    }//end foreach( $this->parsedElements as $mod => $elements )

  }//end public function write */


////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *  @param  array $lang
   */
  public function setLang( $lang )
  {
    $this->langs = $lang;
  }//end public function setLang */


  /**
   * @param $entry
   * @param $data
   * @param $realLang
   * @return string
   * /
  public function sKey( $entry, $data = array() , $realLang = null)
  {

     $this->lPoolKeys[$entry] = $entry;

     if( $realLang )
      $realLang = "'$realLang', ";

     return "I18n::s( $realLang'$entry' ".$this->parseData($data)." )";

  }//end public function sKey */

  /**
   * @param $entry
   * @param $data
   * @param $realLang
   * @return string
   * /
  public function oKey( $entry, $data = array() , $realLang = null)
  {

     $this->lPoolKeys[$entry] = $entry;

     if( $realLang )
      $realLang = "'$realLang', ";

     return "\$this->i18n->l( $realLang'$entry' ".$this->parseData($data)." )";

  }//end public function oKey */

  /**
   * @param $entry
   * @param $data
   * @param $realLang
   * @return string
   * /
  public function tKey( $entry, $data = array() , $realLang = null)
  {

     $this->lPoolKeys[$entry] = $entry;

     if( $realLang )
      $realLang = "'$realLang', ";

     return "\$I18N->l( $realLang'$entry' ".$this->parseData($data)." )";

  }//end public function tKey */


  /**
   * @param $entry
   * @param $data
   * @param $realLang
   * @return string
   */
  public function key( $entry, $data = array() , $realLang = null)
  {

     $this->lPoolKeys[$entry] = $entry;

     if( $realLang )
      $realLang = "'$realLang', ";

     return "I18n::s( $realLang'$entry' ".$this->parseData($data)." )";

  }//end public function langKey */

  /**
   * @param $entry
   * @param $data
   * @param $realLang
   * @return string
   */
  public function langKey( $entry, $data = array() , $realLang = null)
  {

     $this->lPoolKeys[$entry] = $entry;

     if( $realLang )
      $realLang = "'$realLang', ";

     return "I18n::s( $realLang'$entry' ".$this->parseData($data)." )";

  }//end public function langKey */

  /**
   * übergabe der sprachdaten in
   * @param array $texts
   */
  public function addTexts($texts)
  {



    foreach( $texts as $lang => $data )
    {
      foreach( $data as $repo => $pair )
      {

        if(!is_array($pair))
        {
          Debug::console('invalid input in add text '.$pair );
          continue;
        }

        foreach( $pair as $key => $value )
        {
          $this->addText($lang,$repo,$key, $value );
        }

      }
    }

  }//end public function addTexts */

  /**
   * add a text to all language keys
   * usefull for labels somehow
   *
   * @param array $texts
   * /
  public function addListToAll($texts)
  {

    foreach( $this->langs as $lang )
    {
      foreach( $texts as $key => $entry )
      {
        $this->addText($lang,$key,$entry );
      }
    }

  }//end public function addListToAll */

  /**
   * add a text to all language keys
   * usefull for labels somehow
   *
   * @param array $texts
   * /
  public function addI18nText( $key, $entry )
  {

    foreach( $this->langs as $lang )
    {
      $this->addText($lang,$key,$entry );
    }

  }//end public function addListToAll */

  /**
   * add a text to all language keys
   * usefull for labels somehow
   *
   * @param array $texts
   * /
  public function addLabel( $key, $nodeObj )
  {

    foreach( $this->langs as $lang )
    {
      $this->addText($lang,$key, $nodeObj->label($lang) );
    }

  }//end public function addListToAll */

  /**
   * @param string $lang
   * @param string $key
   * @param string $text
   * @return void
   */
  public function addText( $lang, $repo, $key, $value = null )
  {

    if( is_null($value) )
    {
      Debug::console("INVALID ADD TEXT CALL: $lang, $repo, $key ");
      return;
    }

    if( !isset( $this->lPoolText[$lang]) )
      $this->lPoolText[$lang] = array();

    if( !isset( $this->lPoolText[$lang][$repo]) )
      $this->lPoolText[$lang][$repo] = array();

    // only write if not yet exists
    if( !isset( $this->lPoolText[$lang][$repo][$key]) || '' == trim( $this->lPoolText[$lang][$repo][$key] ) )
    {
      //var_dump("$lang,$repo,$key, $value");
      $this->lPoolText[$lang][$repo][$key]  = $value;
    }


  }//end public function addText */
  
  
  

  /**
   * @param string $entry
   * @param array $data
   * @param string $realLang
   *
   */
  public function addTemplateLang( $entry, $data = array(), $realLang = null)
  {

     if( $realLang )
      $realLang = "'$realLang', ";

     //$this->lPoolKeys[$entry] = $entry;
     return "\$I18N->l( $realLang'$entry' ".$this->parseData($data)." )";

  }//end public function addTemplateLang */

  /**
   *
   */
  public function setPathOutput( $pathOutput )
  {
    $this->pathOutput = $pathOutput;
  }//end public function setPathOutput */

  /**
   * Enter description here...
   *
   * @param string $lang
   * @return string
   */
  public function addLangfile( $lang = 'de' )
  {

    $file ='/*******************************************************************************
* @lang '.$lang.'
* @version 1.0
*******************************************************************************/';

    return $file;

  }//end public function addLangfile */

////////////////////////////////////////////////////////////////////////////////
// Parsers
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param
   * @return String
   */
  protected function parseData( $data )
  {
    if(!$data)
      return '';

    $code = ',array( ';

    foreach( $data as $entry )
      $code .= ' '.$entry.',';

    $code = substr( $code , 0 , -1 ).' )';

   return $code;

  }//end public function parseData */



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


  }//end public function writeFile */


} // end class LibCartridgeWbfI18n
