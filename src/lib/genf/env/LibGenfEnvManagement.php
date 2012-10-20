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
class LibGenfEnvManagement
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Der Management type
   * @var string
   */
  public $type      = 'env_management';


  /**
   * Standard Listentype
   * @var string
   */
  public $ltype      = 'table';


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeManagement $management
   */
  public function __construct( $builder, $management  )
  {

    $this->builder = $builder;
    $this->setData( $management );

  }//end public function __construct */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param array $categories
   */
  public function setData( $management, $compName = null )
  {

    $this->management   = $management;
    $this->entity       = $management->entity;
    $this->name         = $management->name;

    if( $compName )
      $this->compName   = $compName;
    else
      $this->compName   = $management->name;

  }//end public function setData */

/*//////////////////////////////////////////////////////////////////////////////
// getter + setter
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   *
   * @return LibGenfNameManagement
   */
  public function getName()
  {
    
    return $this->management->getName();
    
  }//end public function getName */
  
  
  /**
   *
   * @return LibGenfTreeNodeManagement
   */
  public function getMgmt()
  {
    
    return $this->management;
    
  }//end public function getMgmt */

  /**
   *
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {
    
    return $this->management->getUi();
    
  }//end public function getUi */
  
  /**
   *
   * @return LibGenfTreeNodeUi
   */
  public function getAccess()
  {
    
    return $this->management->getAccess();
    
  }//end public function getAccess */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context = null )
  {
    
    if( !$context )
      $context = $this->context;
    
    return $this->management->getFormUi( $context );
    
  }//end public function getFormUi */

  /**
   *
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context = null )
  {
    
    if( !$context )
      $context = $this->context;
    
    return $this->management->getListUi( $context );
    
  }//end public function getListUi */
  
  /**
   * @return array
   */
  public function getReadonlyFields( $context )
  {
    
    return $this->management->getReadonlyFields( $context );
    
  }//end public function getReadonlyFields */
  
  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getContextMgmt()
  {
    
    return $this->management;
    
  }//end public function getContextMgmt 

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @param array $useCategories
   */
  public function switchFormContext( $context, $useCategories = null )
  {

    $this->cleanContext();

    $this->context      = $context ;
    
    /*
    if( 'show' == $context )
    {
      if( !$this->management->hasFormContext($context) )
        $context = 'edit';
    }
    */
    
    $this->fields       = $this->management->getFormFields( $context, $useCategories ) ;
    $this->saveFields   = $this->management->getSaveFields( $context, $useCategories ) ;
    $this->categories   = $this->management->getFormCategories( $context, $useCategories );

    foreach( $this->fields as $key => $field )
    {
      $catName = $field->mainCategory();

      if(!isset($this->categoryFields[$catName]))
        $this->categoryFields[$catName] = array();

      $this->categoryFields[$catName][$key] = $field;
    }

    if( $this->management->concept( 'tree' ) )
      $this->ltype = 'treetable';

  }//end public function switchFormContext */
  
  
  /**
   * @param LibGenfTreenodeFormUi $formUi
   * @param string $context
   */
  public function switchFormContextByUi(  $formUi, $context )
  {

    $this->cleanContext();

    $this->context      = $context;

    $this->fields       = $this->management->getFormFields( $context, null, $formUi ) ;
    $this->saveFields   = $this->management->getSaveFields( $context, null, $formUi ) ;
    
    //$this->categories   = $this->management->getFormCategories( $context, $useCategories );

    foreach( $this->fields as $key => $field )
    {
      $catName = $field->mainCategory();

      if(!isset($this->categoryFields[$catName]))
        $this->categoryFields[$catName] = array();

      $this->categoryFields[$catName][$key] = $field;
    }

    if( $this->management->concept( 'tree' ) )
      $this->ltype = 'treetable';

  }//end public function switchFormContextByUi */

  /**
   * @param string $key
   * @param string $reference
   * @param string $refType
   * 
   * @return TContextAttribute
   */
  public function getFieldObj( $key , $reference = null, $refType = null )
  {

    return $this->management->getField( $key, $reference, $refType );

  }//end public function getField */

  /**
   * @param string $context
   * @param array $categories
   */
  public function switchSearchContext( $context, $categories = null )
  {

    $this->cleanContext();

    $this->searchFields     = $this->management->getSearchCols( $context );
    $this->freeSearchFields = $this->management->getSearchCols( $context, true );

  }//end public function switchSearchContext */

  /**
   * @param string $context
   * @param array $additionalFields
   * @param boolean $onlyDisplay
   */
  public function switchListingContext( $context, $additionalFields = array(), $onlyDisplay = false )
  {

    $this->cleanContext();
    
    $categories = null;

    $this->context      = $context;

    $this->fields       = $this->management->getListingFields( $context, $categories, null, $additionalFields ) ;
    $this->tables       = $this->management->getTables( $context );
    $this->srcIndex     = $this->management->getFieldSources( $context );

    $this->searchFields     = $this->management->getSearchCols( $context );
    $this->freeSearchFields = $this->management->getSearchCols( $context, true );

    if( !$onlyDisplay )
    {
      
      if( $listUi = $this->management->getListUi( $context ) )
      {
        $this->layout     = $listUi->getLayout( $context  );
  
        if( $filterChecks   = $listUi->getFilter( ) )
        {
          $this->filtered   = true;
          $this->filters    = $filterChecks;
          
          if( $this->filters )
          {
            $filterComp = $this->getFilterCompiler( );
            foreach( $this->filters as $pathFilter )
            {
              
              if( $pathFilter->isType( 'path' ) )
              {
                $filterComp->analyse( $pathFilter->getCode() );
        
                $this->srcIndex = $filterComp->appendAffectedSources( $this->srcIndex );
                $filterComp->appendAffectedJoins( $this->tables );
              }
              elseif( $pathFilter->isType( 'value' ) )
              {
                $this->appendValueFilterJoins( $this->management, $pathFilter );
              }
              
            }
          }
  
        }
        
        if( $conditions   = $listUi->getListConditions( ) )
        {
          
          foreach( $conditions as $condition )
          {
            
            $fields = $condition->getEnvFields( $this );
            
            if( $fields )
            {
              
              //Debug::console( "GOT FIELDS"  );
              
              foreach( $fields as $key => $field  )
              {
                //Debug::console( "GOT FIELDS $key ".$field->name->name  );
                $this->fields[$key] = $field;
              }
            }
            
            $condition->getEnvJoins( $this, $this->tables );
            
            $joinIndex  = $condition->getEnvJoinIndex( $this );
            
            if( $joinIndex )
            {
              foreach( $joinIndex as $key   )
                $this->srcIndex[$key] = true;
            }

          }
  
        }
  
      }
    
    }
  
    if( $this->management->concept( 'tree' ) )
      $this->ltype = 'treetable';

  }//end public function switchListingContext */
  
  /**
   * @param string $context
   * @param array $additionalFields
   * @param boolean $onlyDisplay
   */
  public function switchExportContext( $context, $additionalFields = array(), $onlyDisplay = false )
  {

    $this->cleanContext();
    
    $categories = null;

    $this->context      = $context;

    $this->fields       = $this->management->getListingFields( $context, $categories, null, $additionalFields ) ;
    $this->tables       = $this->management->getTables( $context );
    $this->srcIndex     = $this->management->getFieldSources( $context );

    $this->searchFields    = $this->management->getSearchCols( $context );
    $this->freeSearchFields = $this->management->getSearchCols( $context, true );

    if( !$onlyDisplay )
    {
      
      if( $listUi = $this->management->getListUi( $context ) )
      {
        $this->layout     = $listUi->getLayout( $context  );
  
        if( $filterChecks   = $listUi->getFilter( ) )
        {
          $this->filtered   = true;
          $this->filters    = $filterChecks;
          
          if( $this->filters )
          {
            $filterComp = $this->getFilterCompiler( );
            foreach( $this->filters as $pathFilter )
            {
              
              if( $pathFilter->isType( 'path' ) )
              {
                $filterComp->analyse( $pathFilter->getCode() );
        
                $this->srcIndex = $filterComp->appendAffectedSources( $this->srcIndex );
                $filterComp->appendAffectedJoins( $this->tables );
              }
              elseif( $pathFilter->isType( 'value' ) )
              {
                $this->appendValueFilterJoins( $this->management, $pathFilter );
              }
              
            }
          }
  
        }
        
        if( $conditions   = $listUi->getListConditions( ) )
        {
          
          foreach( $conditions as $condition )
          {
            
            $fields = $condition->getEnvFields( $this );
            
            if( $fields )
            {
              
              //Debug::console( "GOT FIELDS"  );
              
              foreach( $fields as $key => $field  )
              {
                //Debug::console( "GOT FIELDS $key ".$field->name->name  );
                $this->fields[$key] = $field;
              }
            }
            
            $condition->getEnvJoins( $this, $this->tables );
            
            $joinIndex  = $condition->getEnvJoinIndex( $this );
            
            if( $joinIndex )
            {
              foreach( $joinIndex as $key   )
                $this->srcIndex[$key] = true;
            }

          }
  
        }
  
      }
    
    }


  }//end public function switchExportContext */

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
    $this->fields       = $this->management->getFields( $context, $categories ) ;
    $this->tables       = $this->management->getTables( $context );
    $this->srcIndex     = $this->management->getFieldSources( $context );
    */

  }//end public function switchMultiContext */


  /**
   * @param string $key
   * @param string $refKey
   * 
   * @return TContextAttribute
   */
  public function addField( $key, $refKey = null  )
  {

    if( $refKey )
    {
      $this->fields[$refKey.'-'.$key] = $this->management->getField( $key, $refKey );
    }
    else
    {
      $this->fields[$this->management->name.'-'.$key] = $this->management->getField( $key );
    }

  }//end public function addField */
  
  
  /**
   * @var LibGenfTreeNodeManagement $management
   * @var LibGenfTreeNodeFilter_Value $filter
   */
  public function appendValueFilterJoins( $management, $filter )
  {
    
    $attrName  = $filter->getFieldName();
    $valueType = $filter->getValueType();

     
    $attr = $management->entity->getAttribute( $attrName );
    
    //$targetMgmt = $check->getFieldTarget( $env->management->entity );
    
    if( !$attr )
    {
      Debug::console( 'missing attr '.$attrName.' in filter '.__METHOD__.' '.$management->debugData() );
      return;
    }
    
    $targetKey = $attr->targetKey();
    
    if( $targetKey && 'param' != $valueType )
    {
      $this->srcIndex[$targetKey] = true;
      
      if( !isset($this->tables->index[$targetKey]) )
      {
        $this->tables->joins[] = array
        (
          'left',                     // join
          $management->name->source,
          $attrName,
          $targetKey,
          'rowid',
          null,                       // where
          null,                       // alias
          'value filter '.$targetKey   // comment
        );
  
        $this->tables->index[$targetKey] = array
        (
          'left',                     // join
          $management->name->source,
          $attrName,
          $targetKey,
          'rowid',
          null,                       // where
          null,                       // alias
          'value filter '.$targetKey    // comment
        );
      }
      
    }

  }

}//end class LibGenfEnvManagement */
