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
 * Eine Name Lib für die Namings
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfName
  extends TArray
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Label für das Objekt, Optimiert für UI Ausgabe,
   * Wird in der Architektur meist in i18n als Fallback / Key verwendet
   * Darf nicht als technischer Key verwendet werde!
   * 
   * @var string
   */
  public $label = null;
  
  /**
   * Pluralversion des labels
   * 
   * @var string
   */
  public $pLabel = null;

  /**
   * Name / key des Nodes. Sollte nicht in der UI ausgegeben werden ist als
   * technischer Key für den Code gedacht
   * 
   * @var string
   */
  public $name  = null;

////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $genders = array();

  /**
   *
   * @var SimpleXmlElement
   */
  protected $node = null;

  /**
   *
   * @var SimpleXmlElement
   */
  protected $nodeLabel = null;

  /**
   *
   * @var LibGenfInterpreter
   */
  protected $interpreter = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name = null, $params = array()  )
  {
    $this->parse($name, $params);
  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    ///TODO some error handling
    return ''.$this->name;
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $gender
   */
  public function setGender( $gender )
  {
    $this->genders = $gender;
  }//end public function setGender */

  /**
   *
   * @param string $lang
   */
  public function label( $lang = 'en' )
  {

    if( $this->nodeLabel )
    {
      return LibGenfBuild::getInstance()->interpreter->i18nText( $this->nodeLabel, $lang, true  );
    }
    else
    {
      return LibGenfBuild::getInstance()->interpreter->getLabel( $this->node, $lang, true  );
    }

  }//end public function label */

  /**
   * Change the Label of this name object
   * @param SimpleXmlElement $node
   */
  public function reLabel( $node )
  {

    if( isset($this->nodel->label) )
    {
      $this->nodeLabel = $this->nodel->label;
      $this->label = LibGenfBuild::getInstance()->interpreter->i18nText( $this->nodeLabel, 'en', true  );
    }

  }//end public function reLabel */


  /**
   * @param $lang
   * @return string
   */
  public function gender( $lang = null )
  {
    
    if( isset( $this->genders[$lang] ) )
      return trim($this->genders[$lang]);
    else
      return LibGenfLang::NEUTRUM;
      
  }//end public function gender */

  /**
   * @param string $key
   * @return string
   */
  public function lower( $key )
  {
    return $this->$key ? strtolower( $this->$key ):'';
  }//end public function lower

  /**
   * @param string $key
   * @return string
   */
  public function upper( $key )
  {
    return $this->$key ? ucfirst(strtolower( $this->$key )):'';
  }//end public function upper
  
  /**
   * @param string $key
   * @return string
   */
  public function ucfirst( $key )
  {
    return $this->$key ? ucfirst($this->$key):'';
  }//end public function upper
  
  /**
   * @param string $key
   * @return string
   */
  public function lcfirst( $key )
  {
    return $this->$key ? lcfirst($this->$key):'';
  }//end public function lcfirst
  
  /**
   * wenn der key nicht existiert auf fallback zurückfallen
   * @param string $key
   * @param string $fallback
   * @return string
   */
  public function ofb( $key, $fallback )
  {
    return $this->$key ? $this->$key : $fallback;
  }//end public function ofb

  /**
   * @param DOMNode $name
   * @param array $params
   * @return string
   */
  public function parse( $name, $params = array() )
  {
    $this->node         = $name;

  }//end public function parse */

}//end class LibGenfName

