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
class LibGenfEnvComponent
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Type des Environments
   * @var string
   */
  public $type      = 'env_component';

  /**
   * Der Modellknoten der Component
   * @var LibGenfTreeNodeComponentSelectbox
   */
  public $component    = null;


  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeComponent $component
   */
  public function __construct( $builder, $component  )
  {

    $this->builder = $builder;
    $this->setData( $component );

  }//end public function __construct */

  /**
   * @param LibGenfTreeNodeComponent $component
   */
  public function setData( $component )
  {

    $this->component  = $component;
    $this->name       = $component->name;

    $this->management = $this->builder->getRoot('Management')->getNode( $component->name->source );
    
    if( !$this->management )
    {
      throw new LibGenf_Exception( 'Tried to create a Component for a nonexisting Management '.$component->name->source );
    }

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

    return $this->management->getUi();
    
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
    
    return $this->management->getName();
    
  }//end public function getName */
  
  /**
   *
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context = null )
  {

    return $this->component;

  }//end public function getListUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context = null )
  {

    return $this->component;

  }//end public function getFormUi */

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {
    
    if( $access = $this->component->getAccess() )
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
  public function getFieldObj( $key, $reference = null, $refType = null )
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

    $this->context      = 'listing' ;


    $this->fields       = $this->management->getListingFields
    ( 
      $this->context, 
      $categories, 
      $this->component 
    );
    
    $this->tables       = $this->management->getTables
    ( 
      $this->context, 
      $this->component  
    );
    
    $this->srcIndex     = $this->management->getFieldSources
    ( 
      $this->context, 
      $this->component 
    );


    if( $this->component->hasFilter() )
    {
      
      $filterChecks   = $this->component->getFilter();
 
      if( $filterChecks )
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
    
    // selectbox special code
    if( 'selectbox' == strtolower($this->component->type()) )
    {
      if( $this->component->isDynFiltered() )
      {
        
        ///TODO error handling
        $filterField   = $this->component->getDynFilterField();
        
        $filterTarget  = $this->management->entity->getAttrTarget( $filterField, 'entity' );
        
        $this->tables->joins[] = array
        (
          'left',                     // join
          $this->management->name->source,
          $filterField,
          $filterTarget->name->source,
          'rowid',
          null,                       // where
          $filterTarget->name->name,  // alias
          'filter reference '
        );

        $this->tables->index[$filterTarget->name->name] = array
        (
          'left',                     // join
          $this->management->name->source,
          $filterField,
          $filterTarget->name->source,
          'rowid',
          null,                       // where
          $filterTarget->name->name,        // alias
          'filter reference '
        );
        
        $this->srcIndex[$filterTarget->name->name] = true;
 
      }
      
      $groupBy = $this->component->getGroupBy( $this->management );
      
      if( $groupBy )
      {
        
        $groupMgmt = $groupBy->targetManagement();
        
        if( !$groupMgmt )
        {
          $this->builder->dumpError
          ( 
            'Grouping attribute '.$groupBy->name->name.' Referenced on a nonexisting management' 
          );
          return null;
        }

        if( !isset($this->srcIndex[$groupMgmt->name->name]) )
        {
          $this->tables->joins[] = array
          (
            'left',                     // join
            $this->management->name->source,
            'rowid',
            $groupMgmt->name->source,
            $groupBy->name->name,
            null,                       // where
            $groupMgmt->name->name,  // alias
            'group reference '
          );
  
          $this->tables->index[$groupMgmt->name->name] = array
          (
            'left',                     // join
            $this->management->name->source,
            'rowid',
            $groupMgmt->name->source,
            $groupBy->name->name,
            null,                       // where
            $groupMgmt->name->name,        // alias
            'group reference '
          );
          
          $this->srcIndex[$groupMgmt->name->name] = true;
        }
        
      }
      
    }

  }//end public function switchListingContext */


  /**
   * @param string $key
   * @param string $refKey
   * @return TContextAttribute
   */
  public function addField( $key, $refKey = null  )
  {

    if( $refKey )
    {
      $this->fields[$refKey.'-'.$key] = $this->management->getField($key,$refKey);
    }
    else
    {
      $this->fields[$this->management->name.'-'.$key] = $this->management->getField($key);
    }

  }//end public function addField */


}//end class LibGenfEnvPage */
