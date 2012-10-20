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
 * Alle relevanten Namenselemente zum benamen der Widget relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameItem
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Name des Items
   * @var string
   */
  public $name = null;
  
  /**
   * Das Label für das Management
   * @var string
   */
  public $label;
  
  /**
   * Das Label für das Management
   * @var string
   */
  public $pLabel;

  /**
   * Der Key zum ansprechen des Items
   * @var string
   */
  public $key = null;
  
  
  /**
   * Type des Items
   * @var string
   */
  public $type = null;
  
  
  /**
   * Type des Items
   * @var string
   */
  public $source = null;
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////
  
  
  /**
   * @param string $name
   * @param array $params
   */
  public function __construct( $name, $params = array()  )
  {
    $this->parse($name, $params);
  }//end public function __construct */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $node , $params = array() )
  {

    $nameKey = trim($node['name']);

    if( isset( $node['class'] ) )
      $class = trim($node['class']);
    else
      $class = $nameKey;


    $label = LibGenfBuild::getInstance()->interpreter->getLabel( $node );
    $pLabel = LibGenfBuild::getInstance()->interpreter->getPLabel( $node );

     if( $label )
      $this->label = $label;
    else
      $this->label = SParserString::subToName( $nameKey );
      
    if( $pLabel )
    {
      $this->pLabel = $pLabel;
    }
    else
    {
      

      if( 's' != strtolower(substr($this->label, -1)) )
      {
        $this->pLabel = $this->label.'s';
      }
      else
      {
        $this->pLabel = $this->label;
      }

    }
      
    $this->name    = $nameKey;
    $this->key     = SParserString::subToCamelCase( $nameKey );             
    $this->source  = trim($node['source']);
    $this->type    = SParserString::subToCamelCase( trim( $node['type'] ) );

    if( isset( $node['class'] ) )
    {
      
      $this->model      = SParserString::subToCamelCase( SParserString::removeFirstSub($node['class']) ) ;
      $this->module     = SParserString::getDomainName( $node['class'] );
      
      $this->management = trim($node['class']);
      $this->managementClass = SParserString::subToCamelCase( $this->management );
      
      $this->className  = $this->managementClass.'_Item_'.$this->key;
      $this->classUrl   = $this->module.'.'.$this->model.'_Item_'.$this->key;

      $this->codeKey    = $class.'-item-'.$this->name;
      
      $tmp = explode('_',$class);
      array_shift($tmp);
      $this->classPath  = $this->lower('module').'/'.implode('_',$tmp);
 
    }
    else 
    {
      $this->model        = SParserString::subToCamelCase( SParserString::removeFirstSub($this->source) ) ;
      $this->module       = SParserString::getDomainName( $this->source );
      
      $this->management   = trim( $this->source );
      $this->managementClass = SParserString::subToCamelCase( $this->source );
      
      $this->className  = $this->managementClass.'_Item_'.$this->key;
      $this->classUrl   = $this->module.'.'.$this->model.'_Item_'.$this->key;
      
      $this->codeKey    = $this->source.'-item-'.$this->name;
      
      $tmp = explode('_',$this->source);
      array_shift($tmp);
      $this->classPath  = $this->lower('module').'/'.implode('_',$tmp);
    }


    /*
      $tmp = explode('_',$node);
      array_shift($tmp);
  
      $this->modelSub    = implode('_',$tmp);
      $this->entityPath  = $this->lower('module').'/'.implode('_',$tmp);
      $this->entityUrl   = $this->module.'.'.$this->model;
  
      $this->classPath   = $this->lower('module').'/'.implode('_',$tmp);
      $this->classUrl   = $this->module.'.'.$this->model;
    */

    $this->module   = SParserString::getDomainName( $nameKey );
    $this->model    = SParserString::subToCamelCase( SParserString::removeFirstSub($nameKey) ) ;
    
    if( isset( $node['module'] ) )
      $this->customModul = strtolower( trim($node['module']) );
    else 
      $this->customModul = $this->module;
    
    $this->i18nKey  = $this->lower('module').'.'.SParserString::subBody($nameKey).'.';
    $this->i18nText = $this->lower('module').'.'.SParserString::subBody($nameKey).'.label';
    $this->i18nMsg  = $this->lower('module').'.'.SParserString::subBody($nameKey).'.message';

  }//end public function parse */


}//end class LibGenfNameItem

