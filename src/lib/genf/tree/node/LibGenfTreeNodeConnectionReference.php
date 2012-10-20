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
class LibGenfTreeNodeConnectionReference
  extends LibGenfTreeNodeReference
{
////////////////////////////////////////////////////////////////////////////////
// setter
////////////////////////////////////////////////////////////////////////////////



  /**
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {
    return $this->ui;
  }//end public function getUi */

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param array $categories
   */
  public function getFields( $context, $categories = null, $additionalFields = array()  )
  {

    $conManagement = $this->connectionManagement();
    $targetMgmt    = $this->targetManagement();

    if( $listUi = $this->getListUi( $context ) )
    {
      if( $fields = $listUi->getFields(  ) )
      {
        return $this->extractUiListingFields( $conManagement, $fields,  $context, $additionalFields );
      }
    }
    else if( $listUi = $conManagement->getListUi( $context ) )
    {
      if( $fields = $listUi->getFields(  ) )
      {
        return $this->extractUiListingFields( $conManagement, $fields,  $context, $additionalFields );
      }
    }

    $fields   = array();

    $fields = $this->appendContextFields( $fields, $conManagement, $context, $categories, $additionalFields );
    if( $refs = $conManagement->getSingleRefs() )
    {
      // append all oneToOne references
      foreach( $refs as $ref )
      {

        if(!$innerTMgmt = $ref->targetManagement())
          continue;

        $fields = $this->appendContextFields( $fields, $innerTMgmt, $context, $categories );

      }//end foreach
    }


    $fields       = $this->appendContextFields( $fields, $targetMgmt, $context, $categories );

    if( $targetRefs = $targetMgmt->getSingleRefs() )
    {
      // append all tables
      foreach( $targetRefs as $targetRef )
      {

        if( !$innerTMgmt = $targetRef->targetManagement() )
          continue;

        $fields = $this->appendContextFields( $fields, $innerTMgmt, $context, $categories );

      }//end foreach
    }

    return $fields;

  }//end protected function getFields */


/*//////////////////////////////////////////////////////////////////////////////
// Search Fields
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $context
   * @param LibGenfTreeNodeManagement $management
   * @return array
   */
  public function getSearchFields( $context, $free = false )
  {

    $conManagement = $this->connectionManagement();

    /*
    if( $ui = $this->getUi() )
    {
      if( $fields = $ui->getSearchFields( $context ) )
      {
        return $this->extractSearchFields( $conManagement, $fields,  $context );
      }
    }
    */

    $cols   = array();
    $cols   = $this->appendSearchFields( $cols, $conManagement, $context, $free );

    if( $references = $conManagement->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        /*
        if( $reference->exclude($context) )
        {
          continue;
        }
        */

        $targetMgmt = $reference->targetManagement();
        $cols       = $this->appendSearchFields( $cols, $targetMgmt, $context, $free );

      }//end foreach
    }


    $targetMgmt = $this->targetManagement();

    $cols   = $this->appendSearchFields( $cols, $targetMgmt, $context, $free );

    if( $references = $targetMgmt->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        /*
        if( $reference->exclude($context) )
        {
          continue;
        }
        */

        $innerMgmt = $reference->targetManagement();
        $cols       = $this->appendSearchFields( $cols, $innerMgmt, $context, $free );

      }//end foreach
    }

    return $cols;

  }//end public function getSearchFields */


/*//////////////////////////////////////////////////////////////////////////////
// Tables
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Enter description here...
   *
   * @param string $context
   * @return TTabJoin
   */
  public function getTables( $context  )
  {

    $conManagement       = $this->connectionManagement();
    $nameCon             = $conManagement->name;

    $targetManagement = $this->targetManagement();
    $nameTarget       = $targetManagement->name;

    $targetId         = $this->targetId();
    $targetRefId      = $this->targetRefId();

    $tables         = new TTabJoin();
    $tables->table  = $nameCon->source;
    $tables->index[$tables->table] = true;

    // ref join
    $tables->joins[] = array
    (
      null,                     // join
      $nameCon->source,
      $targetId,
      $nameTarget->source,
      $targetRefId,
      null,                       // where
      $nameTarget->name,  // alias
      'default reference join'
    );

    $tables->index[$nameTarget->source] = array
    (
      null,                       // join type
      $nameCon->source,
      $targetId,
      $nameTarget->source,
      $targetRefId,
      null,                       // where
      $nameTarget->name,          // alias
      'default reference join'
    );

    $this->appendAttributeReferenceTables( $tables, $conManagement, $context );
    $this->appendAttributeReferenceTables( $tables, $targetManagement, $context );

    $refManagement = null;
    if( $targetRefId != 'rowid' )
    {

      if( !$refAttribute = $targetManagement->entity->getAttribute($targetRefId) )
      {
        throw new LibGenf_Exception('link to a nonexisting attribute');
      }

      if( !$refManagement = $refAttribute->targetManagement( ) )
      {
        throw new LibGenf_Exception('link to a nonexisting attribute');
      }

      $this->appendAttributeReferenceTables( $tables, $refManagement, $context );
    }

    // check if there are any references
    if( $references = $conManagement->getSingleRefs()  )
    {
      // else
      foreach( $references as $reference )
      {
        $this->appendReferenceTables( $tables, $reference, $context );
      }//end foreach
    }

      // check if there are any references
    if( $refManagement && $references = $refManagement->getSingleRefs()  )
    {
      // else
      foreach( $references as $reference )
      {
        $this->appendReferenceTables( $tables, $reference, $context );
      }//end foreach
    }

    // check if there are any references
    if( $references = $targetManagement->getSingleRefs()  )
    {
      // else
      foreach( $references as $reference )
      {
        $this->appendReferenceTables( $tables, $reference, $context );
      }//end foreach
    }

    return $tables;

  }//end public function getTables */



/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @param LibGenfTreeNodeManagement $management
   * @return array
   */
  public function getFieldSources( $context )
  {

    $conManagement    = $this->connectionManagement();
    $targetManagement = $this->targetManagement();

    if( $listUi = $this->getListUi( $context ) )
    {
      if( $fields = $listUi->getFields(  ) )
      {
        return $this->extractFieldSources( $conManagement, $fields, $context );
      }
    }
    else if( $listUi = $conManagement->getListUi( $context ) )
    {
      if( $fields = $listUi->getFields(  ) )
      {
        return $this->extractFieldSources( $conManagement, $fields, $context );
      }
    }

    // join the reference table always
    $index   = array
    (
      $targetManagement->name->name =>  true
    );
    $index   = $this->appendContextTables( $index, $conManagement, $context );

    if( $references = $conManagement->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {
        // in manchen contexten kann eine referenz ignoriert werden
        if( $reference->exclude($context) )
          continue;

        $targetMgmt = $reference->targetManagement();
        $index      = $this->appendRefContextTables( $index, $targetMgmt, $context );

      }//end foreach
    }

    $index   = $this->appendContextTables( $index, $targetManagement, $context );

    if( $references = $targetManagement->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {
        // in manchen contexten kann eine referenz ignoriert werden
        if( $reference->exclude($context) )
          continue;

        $targetMgmt = $reference->targetManagement();
        $index      = $this->appendRefContextTables( $index, $targetMgmt, $context );

      }//end foreach
    }

    $targetRefId      = $this->targetRefId();
    if( $targetRefId != 'rowid' )
    {

      if( !$refAttribute = $targetManagement->entity->getAttribute($targetRefId) )
      {
        throw new LibGenf_Exception('link to a nonexisting attribute');
      }

      if( !$refManagement = $refAttribute->targetManagement( ) )
      {
        throw new LibGenf_Exception('link to a nonexisting attribute');
      }


      $index   = $this->appendContextTables( $index, $refManagement, $context );

      if( $references = $refManagement->getSingleRefs() )
      {
        // append all tables
        foreach( $references as $reference )
        {
          // in manchen contexten kann eine referenz ignoriert werden
          if( $reference->exclude($context) )
            continue;

          $targetMgmt = $reference->targetManagement();
          $index      = $this->appendRefContextTables( $index, $targetMgmt, $context );

        }//end foreach
      }

    }

    return $index;

  }//end public function getFieldSources */



}//end class LibGenfTreeNodeReference

