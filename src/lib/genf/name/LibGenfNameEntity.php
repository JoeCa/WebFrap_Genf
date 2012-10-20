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
 * Alle relevanten Namenselemente zum benamen der Entity relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameEntity
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Name der Entity
   * @var string
   */
  public $name;

  /**
   *
   * @var string
   */
  public $source;

  /**
   *
   * @var string
   */
  public $label;

  /**
   *
   * @var string
   */
  public $entity;

  /**
   *
   * @var string
   */
  public $class;

  /**
   *
   * @var string
   */
  public $module;

  /**
   *
   * @var string
   */
  public $model;

  /**
   *
   * @var string
   */
  public $package;

  /**
   *
   * @var string
   */
  public $i18nText;

  /**
   *
   * @var string
   */
  public $i18nMsg;
  
  /**
   * @param string 
   */
  public $classAcl        = null;
  
  /**
   * @param string 
   */
  public $classAclUrl     = null;
  
  /**
   * @param string 
   */
  public $classAclQuery   = null;

////////////////////////////////////////////////////////////////////////////////
// attributes
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
  public function parse( $node, $params = array() )
  {
    
    if( is_object($node) )
    {
      $name = trim( $node['name'] );
    }
    else 
    {
      Log::warn( "Entity with string ".$node );
      
      $name = $node;
    }
    
    
    $this->name            = $name;
    $this->source          = $name;

    $this->label          = LibGenfBuild::getInstance()->interpreter->getLabel( $name, 'en', true );
    $this->info           = LibGenfBuild::getInstance()->interpreter->getInfo( $name, 'en', true );

    //$this->label           = $label;

    $this->entity          = SParserString::subToCamelCase($name);
    $this->class           = SParserString::subToCamelCase($name);
    $this->package         = SParserString::subToPackage($name);

    $this->module          = SParserString::getDomainName( $name );
    
    if( isset( $node['module'] ) )
    {
      $this->customModul    = ucfirst(trim($node['module']));
    }
    else 
    {
      $this->customModul    = $this->module;
    }

    $this->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;

    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->modelSub        = implode('_',$tmp);
    $this->entityPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->entityUrl       = $this->module.'.'.$this->model;
    
    // Acl Namens Keys
    $this->classAcl       = 'mod-'.$this->lower('module').'>mgmt-'.$this->name;
    $this->classAclUrl    = $this->entityUrl.'_Acl';
    $this->classAclQuery  = "UPPER('mod-".$this->lower('module')."'), UPPER('mgmt-".$this->name."')";

    $this->i18nKey         = $this->lower('module').'.'.SParserString::subBody($name).'.';
    $this->i18nText        = $this->i18nKey.'label';
    $this->i18nMsg         = $this->i18nKey.'message';

  }//end public function parse */


}//end class LibGenfName

