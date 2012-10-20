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
class LibGenfEnvMgmtReference
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var LibGenfName
   */
  public $refName       = null;

  /**
   * @var LibGenfTreeNodeReference
   */
  public $ref           = null;

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $activManagement  = null;

  /**
   * @var LibGenfNameManagement
   */
  public $activName  = null;

  /**
   * @var string
   */
  public $type      = 'env_mgmt_ref';

  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeReference $reference
   */
  public function __construct( $builder, $reference  )
  {

    $this->builder = $builder;
    $this->setData( $reference );
    
    $this->builder->error( "IN MGMT REFERENCE"  );

  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->debugData();
  }//end public function __toString */

  /**
   *
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {

    $this->ref->getUi();

  }//end public function getUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUi
   */
  public function getListUi( $context = null )
  {
    
    if( !$context )
      $context = $this->context;
    

    return $this->ref->getListUi( $context );

  }//end public function getUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUi
   */
  public function getFormUi( $context )
  {

    return $this->ref->getFormUi( $context );

  }//end public function getFormUi */
  
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


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param LibGenfTreeNodeReference $ref
   * @param LibGenfName $compName
   */
  public function setData( $ref, $compName = null )
  {

    $this->ref          = $ref;
    $this->refName      = $ref->name;

    $this->activManagement  = $ref->management;
    $this->activName        = $ref->management->name;

    if(  $ref->relation( Bdl::MANY_TO_MANY ) )
    {
      $this->management   = $this->ref->connectionManagement();
    }
    else
    {
      $this->management   = $this->ref->targetManagement();
    }

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
   * @param array $categories
   */
  public function switchFormContext( $context, $categories = null )
  {

    $this->cleanContext();

    $this->context      = $context ;
    $this->fields       = $this->ref->getFormFields( $context, $categories ) ;
    $this->categories   = $this->ref->getFormCategories( $context, $categories );

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
   * @param array $additionalFields
   */
  public function switchListingContext( $context,  $additionalFields = array() )
  {
    
    $categories = null;

    $this->cleanContext( );

    $this->context      = $context ;

    $this->fields       = $this->ref->getFields( $context, $categories, $additionalFields ) ;
    $this->tables       = $this->ref->getTables( $context );
    $this->srcIndex     = $this->ref->getFieldSources( $context );

    $this->searchFields     = $this->ref->getSearchFields( $context );
    $this->freeSearchFields = $this->ref->getSearchFields( $context, true );


    if( $listUi =  $this->ref->getListUi( $context ) )
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
   * @param string $key
   * @param string $reference
   * @param string $refType
   * @return TContextAttribute
   */
  public function getFieldObj( $key , $reference = null, $refType = null )
  {

    if( $refType && 'connection' == $refType )
    {
      return $this->ref->connectionManagement()->getField($key,$reference);
    }
    else
    {
      return $this->ref->targetManagement()->getField($key,$reference);
    }

  }//end public function getFieldObj */

  /**
   * @param string $key
   * @param string $reference
   * @param string $refType
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

    $field = $this->ref->srcManagement()->getField( $key, $reference );
    if( $reference )
    {
      $this->fields[$reference.'-'.$key] = $field;
    }
    else
    {
      $this->fields[$this->ref->target()->name.'-'.$key] = $field;
    }

  }//end public function addField */

}//end class LibGenfEnvMgmtReference */
