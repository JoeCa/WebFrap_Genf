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
class LibGenfEnvBackup
  extends LibGenfEnv
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var string
   */
  public $type      = 'env_backup';


  /**
   * @var LibGenfTreeNodeBackup
   */
  public $backup   = null;

////////////////////////////////////////////////////////////////////////////////
// Mttributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild $builder
   * @param LibGenfTreeNodeBackup $backup
   */
  public function __construct( $builder, $backup  )
  {

    $this->builder = $builder;

    $this->backup   = $backup;
    $this->name     = $backup->name;

    $this->management = $this->builder->getManagement( $backup->name->source );

  }//end public function __construct */


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/


  /**
   *
   * @return LibGenfNameBackup
   */
  public function getName()
  {

    return $this->backup->getName();

  }//end public function getName */

  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {

    if( $access = $this->backup->getAccess() )
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

    return $this->backup;

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

    return $this->backup;

  }//end public function getListUi */


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

    $this->context      = 'backup' ;


    $this->fields       = $this->management->getListingFields
    (
      $this->context,
      $categories,
      $this->backup,
      array( $this->management->name->name => array( 'm_uuid' ) )
    );

    $this->tables       = $this->management->getTables
    (
      $this->context,
      $this->backup
    );

    $this->srcIndex     = $this->management->getFieldSources
    (
      $this->context,
      $this->backup
    );


    if( $this->backup->hasFilter() )
    {

      if( $filterChecks   = $this->backup->getFilter() )
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



}//end class LibGenfEnvBackup */
