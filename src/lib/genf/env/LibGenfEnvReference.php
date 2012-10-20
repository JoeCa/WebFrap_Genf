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
class LibGenfEnvReference
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfName
   */
  public $refName       = null;

  /**
   *
   * @var LibGenfTreeNodeReference
   */
  public $ref           = null;

  /**
   *
   * Enter description here ...
   * @var string
   */
  public $type      = 'env_ref';


  /**
   *
   * Enter description here ...
   */
  public function __toString()
  {
    return $this->debugData();
  }//end public function __toString */


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeReference $reference
   */
  public function __construct( $builder, $reference  )
  {

    $this->builder = $builder;
    $this->setData( $reference );

  }//end public function __construct */

  /**
   * @return string
   */
  public function debugData()
  {
    
    return 'class: '.get_class($this)
      .' context: '.$this->context
      .' reference: '.$this->ref->name->name
      .' management: '.$this->ref->management->name->name ;
      
  }//end public function debugData */

  /**
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {

    return $this->ref->getUi();


  }//end public function getUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context = null  )
  {

    if( !$context )
      $context = $this->context;
    
    if( $ui = $this->ref->getListUi($context) )
    {
      return $ui;
    }
    else
    { // fallback auf die UI beschreibung der MGMT nodes
      
      if( $this->ref->relation( Bdl::MANY_TO_MANY ) )
      {
        return $this->ref->connectionManagement()->getListUi($context);
      }
      else 
      {
        return $this->ref->targetManagement()->getListUi($context);
      }
      
    }

  }//end public function getListUi */
  
  /**
   * @param string $context
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context = null  )
  {

     if( !$context )
      $context = $this->context;
    
    if( $ui = $this->ref->getFormUi( $context ) )
    {
      return $ui;
    }
    else
    { // fallback auf die UI beschreibung der MGMT nodes
      
      if( $this->ref->relation( Bdl::MANY_TO_MANY ) )
      {
        return $this->ref->connectionManagement()->getFormUi($context);
      }
      else 
      {
        return $this->ref->targetManagement()->getFormUi($context);
      }
      
    }

  }//end public function getFormUi */
  
  /**
   * @param string $name
   * @return LibGenfTreeNodeUiNode
   */
  public function getFormRefLayoutNode( $name )
  {
    
    /* @var $mgmtNode SimpleXMLElement  */
    $mgmtNode = $this->management->getNode();
    
    $xPath = './ui/form/edit/layout//reference[@name="'.$name.'"] ';
    
    $nodes = $mgmtNode->xpath($xPath);
    
    if( !isset( $nodes[0] ) )
      return null;
      
    return new LibGenfTreeNodeUiNode( $nodes[0] );
    
  }//end public function getFormRefLayoutNode */

  /**
   *
   * @return LibGenfTreeNodeUi
   */
  public function getMgmtUi()
  {

    if( $this->ref->relation(Bdl::MANY_TO_MANY) )
      return $this->ref->connectionManagement()->getUi();
    else
      return $this->ref->targetManagement()->getUi();


  }//end public function getUi */
  
  /**
   *
   * @return LibGenfNameManagement
   */
  public function getName()
  {
    
    if( $this->ref->relation( Bdl::MANY_TO_MANY ) )
      return $this->ref->connection();
    else
      return $this->ref->target();
    
  }//end public function getName */

  /**
   *
   * @return LibGenfTreeNodeManagement
   */
  public function getMgmt()
  {

    if( $this->ref->relation( Bdl::MANY_TO_MANY ) )
      return $this->ref->connectionManagement();
    else
      return $this->ref->targetManagement();


  }//end public function getMgmt */
  
  /**
   *
   * @return LibGenfTreeNodeManagement
   */
  public function getContextMgmt()
  {

    if( $this->ref->relation( Bdl::MANY_TO_MANY ) )
      return $this->ref->connectionManagement();
    else
      return $this->ref->targetManagement();


  }//end public function getContextMgmt */
  
  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->ref->getAccess() )
    {
      return $access;
    }
    
    return null;
    
  }//end public function getAccess */

  /**
   * @param LibGenfTreeNodeReference $ref
   * @param LibGenfName $compName
   */
  public function setData( $ref, $compName = null )
  {

    $this->ref          = $ref;
    $this->refName      = $ref->name;

    /*
    if(  $ref->relation(Bdl::MANY_TO_MANY) )
    {
      $this->management   = $this->ref->connectionManagement();
    }
    else
    {
      $this->management   = $this->ref->management;
    }
    */

    $this->management   = $this->ref->management;

    $this->entity       = $this->management->entity;
    $this->name         = $this->management->name;


    if( $compName )
    {
      $this->compName   = $compName;
    }
    else
    {
      $this->compName   = $this->name;
    }

  }//end public function setData */

  /**
   * @param string $context
   * @param array $useCategories
   */
  public function switchFormContext( $context, $useCategories = null )
  {

    $this->cleanContext();

    $this->context      = $context ;
    $this->fields       = $this->ref->getFormFields( $context, $useCategories ) ;
    $this->categories   = $this->ref->getFormCategories( $context, $useCategories );


    foreach( $this->fields as $key => $field )
    {
      $catName = $field->mainCategory();

      if(!isset($this->categoryFields[$catName]))
        $this->categoryFields[$catName] = array();

      $this->categoryFields[$catName][$key] = $field;
    }


  }//end public function switchFormContext */

  /**
   * @param string $context
   * @param array $categories
   * @param array $additionalFields
   */
  public function switchListingContext( $context, $additionalFields = array() )
  {
    
    $categories = null;

    $this->cleanContext();

    $this->context      = $context ;

    $this->fields       = $this->ref->getFields( $context, $categories, $additionalFields ) ;
    $this->tables       = $this->ref->getTables( $context );
    $this->srcIndex     = $this->ref->getFieldSources( $context );

    $this->searchFields     = $this->ref->getSearchFields( $context );
    $this->freeSearchFields = $this->ref->getSearchFields( $context, true );
    
    if( $this->ref->relation( Bdl::MANY_TO_MANY ) )
    {
      
      $conMgmt = $this->ref->connectionManagement();
      
      $numFields = $conMgmt->countFields( array( 'meta' ), true );
      
      if( 2 == $numFields )
      {
        $this->editAble = false;
        $this->hasRights = false;
      }
    }
    

    if( $listUi = $this->ref->getListUi( $context ) )
    {
      $this->layout     = $listUi->getLayout( );

      if( $filterChecks   = $listUi->getFilter( ) )
      {
        $this->filtered   = true;
        $this->filters    = $filterChecks;
        
        if( $this->filters )
        {
          $filterComp = $this->getFilterCompiler( );
          foreach( $this->filters as $pathFilter )
          {
            
            if( !$pathFilter->isType( 'path' ) )
              continue;
            
            $filterComp->analyse( $pathFilter->getCode() );
    
            $this->srcIndex = $filterComp->appendAffectedSources( $this->srcIndex );
            $filterComp->appendAffectedJoins( $this->tables );
          }
        }

      }

    }

  }//end public function switchListingContext */

  /**
   * @param string $context
   * @param array $categories
   */
  public function switchMultiContext( $context, $categories = null )
  {

    $this->cleanContext();

    $this->context      = $context ;

    $this->fields       = array();
    $this->tables       = array();
    $this->srcIndex     = array();

    /*
    $this->fields       = $this->ref->getFields( $context, $categories ) ;
    $this->tables       = $this->ref->getTables( $context );
    $this->srcIndex     = $this->ref->getFieldSources( $context );
    */

  }//end public function switchMultiContext */

  /**
   * @param string $key
   * @param string $reference
   * @return TContextAttribute
   */
  public function getFieldObj( $key , $reference = null )
  {

    return $this->ref->targetManagement()->getField( $key, $reference );

  }//end public function getFieldObj */

  /**
   * @param string $key
   * @param string $reference
   * @param string $refType
   * 
   * @return TContextAttribute
   */
  public function addField( $key, $reference = null, $refType = null  )
  {

    if( $refType && 'connection' == $refType )
    {
      $field = $this->ref->connectionManagement()->getField($key,$reference);
      if($reference)
      {
        $this->fields[$reference.'-'.$key] = $field;
      }
      else
      {
        $this->fields[$this->ref->connection()->name.'-'.$key] = $field;
      }
    }
    else
    {
      $field = $this->ref->targetManagement()->getField($key,$reference);
      if($reference)
      {
        $this->fields[$reference.'-'.$key] = $field;
      }
      else
      {
        $this->fields[$this->ref->target()->name.'-'.$key] = $field;
      }
    }

  }//end public function addField */

  /**
   * @param string $key
   * @return TContextAttribute
   */
  public function addSrcField( $key, $reference = null, $refType = null  )
  {

    $field = $this->ref->srcManagement()->getField($key,$reference);
    
    if($reference)
    {
      $this->fields[$reference.'-'.$key] = $field;
    }
    else
    {
      $this->fields[$this->ref->target()->name.'-'.$key] = $field;
    }

  }//end public function addSrcField */

}//end class LibGenfEnvManagement */
