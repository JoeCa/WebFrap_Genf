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
 * Alle relevanten Namenselemente zum benamen der Management relevanten
 * Architekturelemente im Code
 * 
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfNameManagement
  extends LibGenfName
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Der Management Knoten
   * @var SimpleXMLElement $node
   */
  public $node = null;
  
  /**
   * Der Management Knoten
   * @var LibGenfTreeNodeManagement
   */
  public $mgmtNode = null;
  
////////////////////////////////////////////////////////////////////////////////
// 
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Der Name des Managements
   * @var string
   */
  public $name;

  /**
   * 
   * Enter description here ...
   * @var string
   */
  public $original;

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
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $module;

  /**
   * 
   * Enter description here ...
   * @var unknown_type
   */
  public $model;
  
  /**
   * @var string
   */
  public $class;
  
  /**
   * @var string
   */
  public $classPath;
  
  /**
   * @var string
   */
  public $classUrl;

  /**
   * Class für Listenelement
   * Wird zb bei Routen benötigt
   * @var string
   */
  public $classList     = null;
  
  /**
   * @var string
   */
  public $entityAcl       = null;
  
  /**
   * @var string
   */
  public $modAcl       = null;
  
  /**
   * @var string
   */
  public $aclBaseKey       = null;
  
  /**
   * @var string
   */
  public $aclMaskKey       = null;
  
  /**
   * @var string
   */
  public $classAcl       = null;
  
  /**
   * @var string
   */
  public $classAclUrl    = null;
  
  /**
   * @var string
   */
  public $classAclQuery  = null;
  
  /**
   * Name für Listenelement
   * Wird zb bei Routen benötigt
   * @var string
   */
  public $nameList      = null;
  
  /**
   * Die Class Url für Listenelement
   * Wird zb bei Routen benötigt
   * @var string
   */
  public $classUrlList  = null; 
  
  /**
   * Die Class Url für Listenelement
   * Wird zb bei Routen benötigt
   * @var string
   */
  public $moduleList    = null;
  
  /**
   * Die Class Url für Listenelement
   * Wird zb bei Routen benötigt
   * @var string
   */
  public $i18nTextList  = null;
  
  /**
   * Die Class Url für Listenelement
   * Wird zb bei Routen benötigt
   * @var string
   */
  public $i18nMsgList   = null;

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/LibGenfName#parse($name, $params)
   */
  public function parse( $node , $params = array() )
  {
    $this->node         = $node;

    $name         = trim( $node['name'] );
    $source       = trim( $node['src'] );
    
    $builder = LibGenfBuild::getInstance();

    $this->label          = $builder->interpreter->getLabel( $node, 'en', true );
    $this->pLabel         = $builder->interpreter->getPlabel( $node, 'en', true );
    $this->info           = $builder->interpreter->getInfo( $node, 'en', true );

    $this->original       = $name;

    $this->name           = $name;
    $this->source         = $source;

    $this->module         = SParserString::getDomainName($name);
    
    if( isset( $node['module'] ) )
    {
      $this->customModul    = ucfirst(trim($node['module']));
    }
    else 
    {
      $this->customModul    = $this->module;
    }
    

    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->model          = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;

    $this->package        = SParserString::subToPackage($name);

    $this->class          = SParserString::subToCamelCase($name);
    $this->classPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->i18nText       = $this->lower('module').'.'.SParserString::subBody($name).'.label';
    $this->i18nMsg        = $this->lower('module').'.'.SParserString::subBody($name).'.message';

    $this->modelSub       = implode('_',$tmp);
    $this->i18nKey        = $this->lower('module').'.'.SParserString::subBody($name).'.';

    // entity / source names
    $this->emodule        = SParserString::getDomainName($source);
    $this->emodel         = SParserString::subToCamelCase( SParserString::removeFirstSub($source) ) ;

    $tmp = explode('_',$source);
    array_shift($tmp);

    $this->entity         = SParserString::subToCamelCase($source);
    $this->entityPath     = $this->lower('emodule').'/'.implode('_',$tmp);
    $this->entityUrl      = $this->emodule.'.'.$this->emodel;
    $this->entityI18n     = $this->lower('emodule').'.'.SParserString::subBody($source).'.';
    
    $this->entityAcl      = 'mod-'.$this->lower('customModul').'>mgmt-'.$this->source;

    // module
    $this->modAcl         = 'mod-'.$this->lower('customModul');
    
    $entityObj = $builder->getEntity( $source );
    
    if(!( 
      'd-1' == $entityObj->relevance 
        || 'd-2' == $entityObj->relevance 
        || 's-1' == $entityObj->relevance 
    ))
    {
        $this->aclKey         = 'mod-'.$this->lower('customModul').'-cat-core_data';
        $this->aclBaseKey     = 'mod-'.$this->lower('customModul').'-cat-core_data';
        $this->aclMaskKey     = 'mgmt-'.$this->name;
        $this->classAcl       = 'mod-'.$this->lower('customModul').'>mod-'.$this->lower('customModul').'-cat-core_data';
        $this->classAclUrl    = 'Webfrap.Coredata_Acl';
        $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mod-".$this->lower('customModul')."-cat-core_data')";
    }
    else 
    {
    
      // prüfen ob der management knoten der default management knoten ist
      if( $source == $name )
      {
        
        $this->aclKey         = 'mgmt-'.$this->name;
        $this->aclBaseKey     = 'mgmt-'.$this->name;
        $this->aclMaskKey     = 'mgmt-'.$this->name;
        $this->classAcl       = 'mod-'.$this->lower('customModul').'>mgmt-'.$this->name;
        $this->classAclUrl    = $this->classUrl.'_Acl';
        $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->name."')";
      }
      elseif( isset( $node->access['inherit'] ) && ( 'entity' == trim($node->access['inherit']) || $this->source == trim($node->access['inherit']) )  )
      {
            // die rechte werden aus einer anderen maske ausgelesen
        if( $node->access['extend'] )
        {
          
          $this->aclKey         = 'mgmt-'.$this->source;
          $this->aclBaseKey     = 'mgmt-'.$this->source;
          $this->aclMaskKey     = 'mgmt-'.$this->source;
          $this->classAcl       = 'mod-'.$this->lower('customModul').'/mgmt-'.trim($node->access['extend']).'>mgmt-'.$this->source;
          $this->classAclUrl    = $this->entityUrl.'_Acl';
          $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->source."'), UPPER('mgmt-".trim($node->access['extend'])."')";
  
        }
        else 
        {
          
          $this->aclKey         = 'mgmt-'.$this->source;
          $this->aclBaseKey     = 'mgmt-'.$this->source;
          $this->aclMaskKey     = 'mgmt-'.$this->source;
          $this->classAcl       = 'mod-'.$this->lower('customModul').'>mgmt-'.$this->source;
          $this->classAclUrl    = $this->entityUrl.'_Acl';
          $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->source."')";
        
        }
      }
      else 
      {
        // die rechte werden aus einer anderen maske ausgelesen
        if( $node->access['extend'] )
        {
          
          if( isset( $node->access['inherit']  ) )
          {
            
            $inheritUrl = SParserString::getDomainName( trim( $node->access['inherit'] ) );
            $inheritUrl .= '.'.SParserString::subToCamelCase( SParserString::removeFirstSub( trim( $node->access['inherit'] ) ) );
            
            $this->aclKey         = 'mgmt-'.trim($node->access['inherit']);
            $this->aclBaseKey     = 'mgmt-'.$this->source;
            $this->aclMaskKey     = 'mgmt-'.$this->name;
            $this->classAcl       = 'mod-'.$this->lower('customModul').'/mgmt-'.$this->source.'/mgmt-'.trim($node->access['extend']).'>mgmt-'.trim($node->access['inherit']);
            $this->classAclUrl    = $inheritUrl.'_Acl';
            $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->source."'), UPPER('mgmt-".trim($node->access['inherit'])."')";
          }
          else 
          {
            $this->aclKey         = 'mgmt-'.$this->name;
            $this->aclBaseKey     = 'mgmt-'.$this->source;
            $this->aclMaskKey     = 'mgmt-'.$this->name;
            $this->classAcl       = 'mod-'.$this->lower('customModul').'/mgmt-'.$this->source.'/mgmt-'.trim($node->access['extend']).'>mgmt-'.$this->name;
            $this->classAclUrl    = $this->classUrl.'_Acl';
            $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->source."'), UPPER('mgmt-".trim($node->access['extend'])."'), UPPER('mgmt-".$this->name."')";
          }
          
        }
        else 
        {
          
          $inheritUrl = SParserString::getDomainName( trim( $node->access['inherit'] ) );
          $inheritUrl .= '.'.SParserString::subToCamelCase( SParserString::removeFirstSub( trim( $node->access['inherit'] ) ) );
          
          if( isset( $node->access['inherit']  ) )
          {
            $this->aclKey         = 'mgmt-'.trim($node->access['inherit']);
            $this->aclBaseKey     = 'mgmt-'.$this->source;
            $this->aclMaskKey     = 'mgmt-'.$this->name;
            $this->classAcl       = 'mod-'.$this->lower('customModul').'/mgmt-'.$this->source.'>mgmt-'.trim($node->access['inherit']);
            $this->classAclUrl    = $inheritUrl.'_Acl';
            $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->source."'), UPPER('mgmt-".trim($node->access['inherit'])."')";
          }
          else 
          {
            $this->aclKey         = 'mgmt-'.$this->name;
            $this->aclBaseKey     = 'mgmt-'.$this->source;
            $this->aclMaskKey     = 'mgmt-'.$this->name;
            $this->classAcl       = 'mod-'.$this->lower('customModul').'/mgmt-'.$this->source.'>mgmt-'.$this->name;
            $this->classAclUrl    = $this->classUrl.'_Acl';
            $this->classAclQuery  = "UPPER('mod-".$this->lower('customModul')."'), UPPER('mgmt-".$this->source."'), UPPER('mgmt-".$this->name."')";
          }
          
        }
  
      }
    
    }



    /*
      <semantic>
       <genders>
         <gender lang="de" key="n" />
    */

    if( isset( $node->semantic->genders->gender ) )
    {
      $genders = array();

      foreach( $node->semantic->genders->gender as $gender )
      {
        $genders[trim($gender['lang'])] = trim($gender['key']);
      }

      $this->setGender( $genders );
    }
    
    // wird bei bedarf von auserhalb überschrieben
    $this->classList     = $this->class;
    $this->nameList      = $this->name;
    $this->classUrlList  = $this->classUrl;
    $this->classPathList = $this->classPath;
    $this->moduleList    = $this->module;
    $this->i18nTextList  = $this->i18nText;
    $this->i18nMsgList   = $this->i18nMsg;

  }//end public function parse */

  /**
   * set an alias
   * @param string $name
   *
   * /
  public function setAlias( $name )
  {

    $this->name           = $name;
    $this->module         = SParserString::getDomainName($name);

    $tmp = explode('_',$name);
    array_shift($tmp);

    $this->model          = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;

    $this->class          = SParserString::subToCamelCase($name);
    $this->classPath      = $this->lower('module').'/'.implode('_',$tmp);
    $this->classUrl       = $this->module.'.'.$this->model;

    $this->modelSub       = implode('_',$tmp);
    $this->i18nKey        = $this->lower('module').'.'.SParserString::subBody($name).'.';

  } */


}//end class LibGenfNameManagement

