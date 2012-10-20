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
class LibGenfEnvService
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $type      = 'env_service';


  /**
   * @var LibGenfTreeNodeService
   */
  public $service   = null;

////////////////////////////////////////////////////////////////////////////////
// Mttributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeService $service
   */
  public function __construct( $builder, $service  )
  {

    $this->builder = $builder;

    $this->service = $service;
    $this->name    = $service->name;

    $this->management = $this->builder->getManagement( $service->name->source );

  }//end public function __construct */


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/


  /**
   *
   * @return LibGenfNameService
   */
  public function getName()
  {

    return $this->service->getName();

  }//end public function getName */

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {

    if( $access = $this->service->getAccess() )
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
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {

    return $this->service;

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
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context = null )
  {

    return $this->service;

  }//end public function getListUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context = null )
  {

    return $this->service;

  }//end public function getFormUi */


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

    $this->context      = 'service' ;


    $this->fields       = $this->management->getListingFields
    (
      $this->context,
      $categories,
      $this->service,
      array( $this->management->name->name => array( 'm_uuid' ) )
    );

    $this->tables       = $this->management->getTables
    (
      $this->context,
      $this->service
    );

    $this->srcIndex     = $this->management->getFieldSources
    (
      $this->context,
      $this->service
    );


    if( $this->service->hasFilter() )
    {

      if( $filterChecks   = $this->service->getFilter() )
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



}//end class LibGenfEnvService */
