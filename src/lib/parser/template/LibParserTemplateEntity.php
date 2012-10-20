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
 * @subpackage ModGenf
 */
class LibParserTemplateEntity
  extends LibParserTemplateParser
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var array
   */
  protected $entities     = array();

  /**
   * @var array
   */
  protected $attributes     = array();


////////////////////////////////////////////////////////////////////////////////
// init
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param String $xmlFile the Filepath of the Metamodel to parse
   * @param SimpleXMLElement $xml
   */
  public function init( )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $this->importEntities();

  }//end public function init( )

////////////////////////////////////////////////////////////////////////////////
// Class Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function importEntities( )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    foreach( $this->xmlModel->tables->table as $tab  )
    {

      $tabname = (string)$tab['name'];

      $this->entities[] = $tabname;
      $this->importAttributes($tabname,$tab);

    }

  }//end public function importEntities( )


  /**
   * Example Input:
   * <table name="core_eckbert" >
   * <row name="rowid" type="int" size="" notNull="false" sequence="core_eckbert_rowid" default="" unique="false"  />
   * <row name="akuu" type="int" size="" notNull="false" sequence="" default="" unique="false"  />
   * <primaryKey>
   * <key>rowid</key>
   * </primaryKey>
   * </table>
   * @return string
   *
   */
  public function importAttributes( $tabname, $tab )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($tabname, $tab));

    foreach( $tab->row as $row  )
    {
      if(Log::$levelTrace)
       Log::getInstance()->trace(__file__,__line__,'Attribute: '.$tabname.' : '.$row['name']);

      $this->attributes[$tabname][(string)$row['name']] = $row;
    }

  }//end public function importEntities( )


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
  public function extractModelData( $name , $template )
  {
    if(Log::$levelDebug)
     Log::start(__file__,__line__,__method__,array($name , $template));

    $this->parser->cleanParser();
    $this->parser->setTemplate( $template , 'mdsd' );

    $vars = array();

    $vars['attributes']  = $this->attributes[$name];

    // name
    $vars['subHump']      = IncParserString::subToCamelCase(
     IncParserString::removeFirstSub($name));

    $vars['tabname']       = $name;
    $vars['name']          = IncParserString::subToCamelCase( $name );
    $vars['modName']       = IncParserString::getFirstHump( $vars['name'] );
    $vars['lowerModName']  = strtolower($vars['modName']);
    $vars['modFullName']   = 'Mod'.$vars['modName'];
    $vars['dboName']       = 'Entity'.$vars['name'];
    $vars['lowerName']     = strtolower($vars['name']);
    $vars['nameBodyHumps'] = IncParserString::getBodyHumps($vars['name']);
    $vars['lowerBodyHumps'] = strtolower($vars['nameBodyHumps']);

    $vars['author']     = $this->author;
    $vars['copyright']  = $this->copyright;
    $vars['date']       = date('Y-m-d h:i:s');

    $this->parser->addVar($vars);

  }//end public function extractModelData( $name , $template )

////////////////////////////////////////////////////////////////////////////////
// Implemet Abstract Methods
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function parse()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    foreach( $this->templates as $template )
    {
      foreach( $this->entities as $tabName )
      {

          $this->parsedElements[$tabName.$this->classEnding[$template]] =
            $this->parseTemplate($tabName,$template);

      }//end foreach entities
    }//end foreach templates
  }//end public function parse()

} // end class ObjParserWbfcodeEntity

