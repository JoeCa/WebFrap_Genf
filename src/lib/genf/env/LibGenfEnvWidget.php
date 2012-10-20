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
class LibGenfEnvWidget
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Type des Environments
   * @var string
   */
  public $type      = 'env_widget';

  /**
   * Der Modellknoten des Widgets
   * @var LibGenfTreeNodeWidget
   */
  public $widget    = null;


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeSubpage $page
   */
  public function __construct( $builder, $widget  )
  {

    $this->builder = $builder;
    $this->setData( $widget );

  }//end public function __construct */

  /**
   * @param LibGenfTreeNodeWidget $widget
   */
  public function setData( $widget )
  {

    $this->widget     = $widget;
    $this->name       = $widget->name;

    $this->management = $this->builder->getRoot('Management')->getNode( $widget->name->mask );

  }//end public function setData */


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {

    return $this->widget->getUi();
    
  }//end public function getUi */

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
   * @return LibGenfNameWidget
   */
  public function getName()
  {
    
    return $this->widget->getName();
    
  }//end public function getName */
  
  /**
   *
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context = null )
  {
    
    if( !$context )
      $context = $this->context;

    return $this->widget->getListUi( $context );

  }//end public function getListUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context = null )
  {

    return $this->widget->getFormUi( $context );

  }//end public function getFormUi */

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->widget->getAccess() )
    {
      return $access;
    }
    else if( $access = $this->management->getAccess() )
    {
      return $access;
    }
    
    return null;
    
  }//end public function getAccess */
  
  /**
   * 
   * @param string $key
   * @param string $reference
   * @param string $refType
   * 
   * @return TContextAttribute
   */
  public function getFieldObj( $key , $reference = null, $refType = null )
  {

    return $this->management->getField( $key, $reference, $refType );

  }//end public function getFieldObj */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @param array $categories
   */
  public function switchListingContext( $context, $categories = null )
  {

    $this->cleanContext();

    $this->context      = $context ;
    
    $listUi = $this->getListUi( $context );

    $this->fields       = $this->management->getListingFields( $context, $categories, $listUi ) ;
    $this->tables       = $this->management->getTables( $context );
    $this->srcIndex     = $this->management->getFieldSources( $context, $listUi );

    $this->searchFields     = $this->management->getSearchCols($context, false, $listUi );
    $this->freeSearchFields = $this->management->getSearchCols($context, true,  $listUi );

    if( $listUi )
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
            
            if( !$pathFilter->isType('path') )
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
  public function switchSearchContext( $context, $categories = null )
  {

    $this->cleanContext();

    $this->searchFields     = $this->management->getSearchCols( $context );
    $this->freeSearchFields = $this->management->getSearchCols( $context, true );

  }//end public function switchSearchContext */

  /**
   * @param string $context
   * @param array $useCategories
   */
  public function switchFormContext( $context, $useCategories = null )
  {

    $this->cleanContext();

    $this->context      = $context ;
    $this->fields       = $this->management->getFormFields( $context, $useCategories ) ;
    $this->categories   = $this->management->getFormCategories( $context, $useCategories );

    foreach( $this->fields as $key => $field )
    {
      
      $catName = $field->mainCategory( );

      if( !isset( $this->categoryFields[$catName] ) )
        $this->categoryFields[$catName] = array();

      $this->categoryFields[$catName][$key] = $field;
      
    }

  }//end public function switchFormContext */


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
   * @return TContextAttribute
   */
  public function addField( $key, $refKey = null  )
  {

    if($refKey)
    {
      $this->fields[$refKey.'-'.$key] = $this->management->getField($key,$refKey);
    }
    else
    {
      $this->fields[$this->management->name.'-'.$key] = $this->management->getField($key);
    }


  }//end public function getField */


}//end class LibGenfEnvPage */
