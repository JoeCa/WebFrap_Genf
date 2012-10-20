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
class LibGenfTreeNodelistReferenceManagement
  extends LibGenfTreeNodelistReference
{
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Das Management Objekt
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;

  /**
   * Auf die ReferenceList und alle Referenzen das neue Management setzen
   * @param LibGenfTreeNodeManagement $management
   */
  public function setManagement( $management )
  {

    $this->management = $management;

    foreach( $this->multiRef as $ref )
      $ref->setManagement( $management );

    foreach( $this->singleRef as $ref )
      $ref->setManagement( $management );

  }//end public function setManagement */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNodelist#extractChildren($node)
   */
  public function extractChildren( $node )
  {
    $this->extractChildrenObject( $node );
    $this->importCategories( );
  }//end public function extractChildren */

  /**
   * Die Daten zum render aufbereiten
   * @param SimpleXmlElement $node
   */
  public function extractChildrenObject( $node )
  {

    // first clean
    $this->childs     = array();
    $this->multiRef   = array();
    $this->singleRef  = array();
    $this->hasRef     = false;

    $entityName     = trim($node['src']);
    $managementName = trim($node['name']);


    if( !$entity  = $this->builder->getEntity( $entityName ) )
    {
      $this->builder->error( 'tried to request nonexistung entity: '.$entityName.' during reference checking' );
      return;
    }

    // no references
    if( !$entity->hasReferences() )
    {
      return;
    }


    /*
     * So dann wolln wir mal
     * zuerst müssen wir prüfen ob die referenzen nicht
     */
    if( $this->management->ui && $this->management->ui->hasFormLayout()  )
    {

      $tmp        = $this->management->ui->getFormReferences( 'edit', true );
      $references = array();

      foreach( $tmp as /* @var $ref [SimpleXMLElement,'tabOccurenceName'] */ $ref )
      {
        $references[trim($ref[0]['name'])] = $ref;
      }

      // if the entity has references we just geht them
      $multiRef         = $entity->cloneMultiRefs();
      $this->singleRef  = $entity->cloneSingleRefs();

      foreach( $multiRef as /* @var $obj LibGenfTreeNodeReference */ $key => $obj )
      {

        $this->childs[$obj->name->name] = $obj;

        if( isset($references[$obj->name->name]) )
        {
          

          // wenn die referenz direkt auf das aktive management geht, aber kein
          // default management ist
          // wird der aktuelle management name als src maske gesetzt
          if( $entityName == $obj->src(false) && $entityName != $managementName )
          {
            $obj->setSrcMask( $managementName );
          }
          
          $this->multiRef[$obj->name->name] = $obj;
          $this->hasRef   = true;
          
          // das UI Objekt der Entity Referenz
          $origUi = $obj->getUi();
          
          $modelRefNode = $references[$obj->name->name][0];
          
          if( isset( $modelRefNode->ui ) )
          {
            $newUi = new LibGenfTreeNodeUiReference( $modelRefNode->ui );
            $newUi->setFallback( $origUi );
            
            $obj->setUi( $newUi, true );
          }
          
          $obj->modelNode = $modelRefNode;

          //$obj->customize( $references[$obj->name->name][0] );
          // der name des tabs in welchem sich die Referenz befindet
          $obj->tabName = new LibGenfNameDefault( $references[$obj->name->name][1] );
        }
        
      }
      
      // anpassen der one To One single embeded references
      foreach( $this->singleRef as $key => $obj )
      {

        if( $entityName == $obj->src(false) && $entityName != $managementName )
        {
          $obj->setSrcMask( $managementName );
        }

        $this->childs[$obj->name->name] = $obj;
      }

      // when the ui allready defines tabs, we can ignore the rest of the tabs
      return;

    }

    // if the management has no own references node we check if the entity has
    // references
    if( !isset($node->references) )
    {

      // if the entity has references we just geht them
      $this->multiRef   = $entity->cloneMultiRefs();
      $this->singleRef  = $entity->cloneSingleRefs();
      $this->childs     = array_merge( $this->multiRef , $this->singleRef);

      $tmp = $this->childs;
      $this->childs = array();

      foreach( $tmp as $key => $obj )
      {
        if( $entityName == $obj->src(false) && $entityName != $managementName )
        {
          $obj->setSrcMask( $managementName );
        }

        $this->childs[$obj->name->name] = $obj;
      }

      $this->hasRef   = true;

      return;
    }
    else
    {
      $this->node     = $node->references;
    }

    // check if the management cleans the entity references
    if( !isset($this->node->ref) )
    {
      /*
      if(DEBUG)
        Debug::console('mgmt: '.$managementName.' cleans references');
      */
      // keep single references

      $this->singleRef  = $entity->cloneSingleRefs();
      $this->childs     = array();

      foreach( $this->singleRef as $key => $obj )
      {
        if( $entityName == $obj->src(false) && $entityName != $managementName )
        {
          $obj->setSrcMask( $managementName );
          $obj->management = $this->management;
        }

        $this->childs[$obj->name->name] = $obj;
      }

      return;
    }

    $dependencies = array();

    foreach( $this->node->ref as $refNode )
    {
      $refName = trim($refNode['name']);

      if( !$entityRef = $entity->getReference( $refName ) )
      {
        $list = $entity->getReferencesList()->getReferenceKeys();
        $this->builder->error('Requested nonexisting Reference '.$refName.' in management: '.$managementName , $list);
        continue;
      }

      // only clone when it's not the default management class
      $refObj = clone $entityRef;
      $refObj->setSrcMask( $managementName );
      $refObj->management = $this->management;

      if( isset($refNode->ui) )
        $refObj->setUi( $refNode->ui );

      if( isset($refNode->category['name']) )
      {
        $refObj->setCategory(trim($refNode->category['name']));
      }


      // sort is only nessecary for one to references, caus this are the only
      // references that are saved together
      if( !$refObj->relation( Bdl::ONE ) )
      {
        $this->multiRef[] = $refObj;
        $this->hasRef     = true;
        $refObj->customize($refNode);
      }

      $this->childs[(string)$refNode['name']] =  $refObj;

    }//end foreach

    $this->singleRef  = $entity->cloneSingleRefs();
    foreach( $this->singleRef as $key => $obj )
    {
      if( $entityName == $obj->src(false) && $entityName != $managementName )
      {
        $obj->setSrcMask( $managementName );
        $obj->management = $this->management;
      }

      $this->childs[$obj->name->name] = $obj;
    }

  }//end public function extractChildren */


  /**
   *
   * @param array $params
   */
  protected function parseParams( $params )
  {

    if(!isset($params['name']))
    {
      Error::report('Invalid Treenodelist Creation');
      return;
    }

    $this->entityName = $params['name'];
    $this->management = $params['management'];

  }//end protected function parseParams */

}//end class LibGenfTreeNodelistReference

