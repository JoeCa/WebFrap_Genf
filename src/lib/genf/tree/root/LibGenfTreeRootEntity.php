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
 * Einlesen von Entitäten, das sollte in der Regel zuerst passieren
 * Enities legen Standard Managements und Module an
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeRootEntity
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// Index Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * the activ entity
   * @var SimpleXmlElement
   */
  public $entity        = null;

  /**
   *
   * @var array
   */
  protected $classCache = array();

  /**
   *
   * @var array<string:string>
  */
  public  $typeValidMap  = array
  (
    'boolean'   => 'boolean'  ,
    'integer'   => 'int'      ,
    'numeric'   => 'numeric'  ,
    'text'      => 'text'     ,
    'varchar'   => 'text'     ,
    'char'      => 'text'     ,
    'date'      => 'date'     ,
    'time'      => 'time'     ,
    'timestamp' => 'timestamp',
    'uuid'      => 'uuid'     ,
    'bytea'       => 'bytea'      ,
    'inet'        => 'inet'       ,
    'macaddr'     => 'macaddr'    ,
    'cidr'        => 'cidr'       ,
    'inet'        => 'inet'       ,
    'interval'    => 'interval'   ,
    'smallint'    => 'smallint'   ,
    'bigint'      => 'bigint'   ,
  );

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/entities';
    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $nodeRoot = $this->modelTree->createElement('entities');
      $this->nodeRoot = $this->modelRoot->appendChild( $nodeRoot );
    }

  }//end public function preProcessing */

  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile(  $tmpXml, $tmpXpath, $repoPath = null  )
  {

    //$this->interpret( $tmpXml, $tmpXml, $tmpXpath  );
    //$smplNode = simplexml_import_dom($tmpXml);
    //$tmpXpath = new DOMXPath( $tmpXml );

    $this->builder->activRepo = $repoPath;

    $entityQuery  = '/bdl/entities/entity';
    $entityList   = $tmpXpath->query( $entityQuery );

    foreach( $entityList as $entity )
    {

      if( !$entityName = $entity->getAttribute('name') )
      {
        Error::report('Missing the name Attribute for an entity. Please check you Model!');
        continue;
      }

      $this->add( $entity  );

    }
    
    
    $nodeItem    = $this->tree->getRootNode( 'Item' );
    $queryItems  = '/bdl/entities/entity/ui/form//layout//item';
    // item
    $listFoundItems    = $tmpXpath->query( $queryItems );
    
    foreach( $listFoundItems as $item )
    {
      $nodeItem->addItemNode( $item->cloneNode( true ) );
    }
    

  }//end public function importFile */

  /**
   *
   */
  public function postProcessing()
  {


    // get all elements that extend entities from refernces or attributes
    $this->postProcessExtendedElements();

    // check for needed but not fully defined many to many Rererences
    $this->postProcessReferences();

     // interpret conditions
    $this->postInterpretConditions();

     // add default attributes to the attribute nodes
    $this->postProcessDefaultAttributeAttributes();

    // append default attributes to the entity
    $this->postProcessDefaultEntityAttributes();

  }//end public function postProcessing */

  /**
   *
   */
  protected function postProcessReferences()
  {

    $modelXpath = $this->tree->getXpath();

    // fetch all one references from attributes
    $embedLinks   = $modelXpath->query( '/bdl/entities/entity/attributes/attribute/embed' );
    foreach( $embedLinks as $embed )
    {
      $this->createOneToOneRefFromAttribute( $embed, $modelXpath );
    }//end foreach

    
    $references  = $modelXpath->query( '/bdl/entities/entity/references/ref' );
    // <ref target="code_author"     relation="manyToMany" />
    foreach( $references as $ref )
    {
      $this->postProcessReferencesGuessType( $ref, $modelXpath );
    }//end foreach

    $references  = $modelXpath->query( '/bdl/entities/entity/references/ref[@relation="manyToMany"]' );
    // <ref target="code_author"     relation="manyToMany" />
    foreach( $references as $ref )
    {

      // name and target are required if the system should create a reference automaticaly
      if( !$ref->hasAttribute('name') )
        if( !$this->extendRefName( $ref, $modelXpath ) )
          continue;

      $this->postProcessManyToManyReference( $ref, $modelXpath );

    }//end foreach


    $references  = $modelXpath->query( '/bdl/entities/entity/references/ref' );
    // <ref target="code_author"     relation="manyToMany" />
    foreach( $references as $ref )
    {
      $this->postProcessAllReferences( $ref, $modelXpath );
    }//end foreach

  }//end public function postProcessReferences */
  
  /**
   * @param DOMElement $ref
   * @param DOMXpath $modelXpath
   */
  protected function postProcessReferencesGuessType( $ref, $modelXpath )
  {

    $node   = simplexml_import_dom( $ref );

    $nodeManagement = $this->tree->getRootNode( 'Management' );
    
    $tmpList = $modelXpath->query('./connection', $ref);
    
    if( !isset( $node['relation'] ) )
    {
      if( !$tmpList->length )
      {
        $ref->setAttribute( 'relation', 'manyToOne' );
      }
      else 
      {
        $ref->setAttribute( 'relation', 'manyToMany' );
      }
    }
    

  }//end protected function postProcessAllReferences */

  /**
   * @param DOMElement $ref
   * @param DOMXpath $modelXpath
   */
  protected function postProcessAllReferences( $ref, $modelXpath )
  {

    $node   = simplexml_import_dom( $ref );

    $nodeManagement = $this->tree->getRootNode( 'Management' );

    if( isset($node['base']) )
    {
      return;
    }

    if(!isset( $node->src ))
    {
      $node->addChild('src');
    }

    if(!isset( $node->src['name'] ))
    {
      $node->src->addAttribute( 'name', $ref->parentNode->parentNode->getAttribute('name') );
    }

    if( !isset( $node->src['id'] ) )
    {
      $node->src->addAttribute('id',$this->builder->rowidKey);
    }
    elseif( '' == trim($node->src['id']) )
    {
      $node->src['id'] = $this->builder->rowidKey;
    }

    if( isset( $node->src['mask'] ) )
    {

      $result = $modelXpath->query( '/bdl/entities/entity[@name="'.trim($node->src['name']).'"]' );

      if( $result->length )
      {

        $entNode   = simplexml_import_dom( $result->item(0) );

        $nodeManagement->createDefault
        (
          trim($node->src['name']),
          array
          (
            'entity'      => $entNode,
            'management'  => trim($node->src['mask'])
          )

        );
      }
    }

    if( !isset( $node->target['id'] ) )
    {
      $node->target->addAttribute( 'id', $this->builder->rowidKey );
    }
    elseif( '' == trim($node->target['id']) )
    {
      $node->target['id'] = $this->builder->rowidKey;
    }


    if( isset( $node->target['mask'] ) )
    {

      $result = $modelXpath->query( '/bdl/entities/entity[@name="'.trim($node->target['name']).'"]' );

      if( $result->length )
      {

        $entNode   = simplexml_import_dom( $result->item(0) );

        $nodeManagement->createDefault
        (
          trim($node->target['name']),
          array
          (
            'entity' => $entNode,
            'management' => trim($node->target['mask'])
          )

        );
      }
    }

    // if src id is rowid then this is presave
    if( $this->builder->rowidKey == trim($node->src['id']) )
    {
      $node->addAttribute('base','src');
    }
    else
    {
      $node->addAttribute('base','target');
    }

  }//end protected function postProcessAllReferences */

  /**
   * @param DOMNode $ref
   * @param DOMXpath $modelXpath
   */
  protected function postProcessManyToManyReference( $ref , $modelXpath )
  {

    $parentEntity = $ref->parentNode->parentNode;
    $sourceName   = $parentEntity->getAttribute('name');

    if( $ref->hasAttribute('target') )
    {
      $targetName   = $ref->getAttribute('target');
      // remove target attribute
      $ref->removeAttribute('target');
    }
    else
    {
      if( $tmpList = $modelXpath->query( './target', $ref ) )
      {
        if( $tmpList->length && $tmpList->item(0)->hasAttribute('name') )
        {
          $targetName   = $tmpList->item(0)->getAttribute('name');
        }
        else
        {
          Error::report( 'Invalid ManyToMany Reference', $ref );
          $ref->parentNode->removeChild($ref);
          return;
        }
      }
    }

    // check if there is binding is defined, if not we assume
    // that this is a free binding
    if( !$ref->hasAttribute( 'binding' ) )
      $ref->setAttribute( 'binding', 'connected' );


    $relName    = $ref->getAttribute( 'name' );
    $conName    = $relName;

    $sourceConName = null;
    $targetConName = null;


    // create needed default elements

    // check if a src node exits
    $tmpList = $modelXpath->query('./src', $ref);
    if( !$tmpList->length )
    {
      // if not create a default node
      $sourceId = 'id_'.$sourceName;
      $this->stringToNode( '<src name="'.$sourceName.'" id="id_'.$sourceName.'" />' , $ref );
    }
    else
    {
      $tmpNode = $tmpList->item(0);

      // if has no name, use the default name
      if(!$tmpNode->hasAttribute('name'))
      {
        $tmpNode->setAttribute('name',$sourceName);
      }
      else
      {
        // else load the source name and replace the default name
        $sourceName = $tmpNode->getAttribute('name');
      }

      if(!$tmpNode->hasAttribute('id'))
      {
        $sourceId = 'id_'.$sourceName;
        $tmpNode->setAttribute('id','id_'.$sourceName);
      }
      else
      {
        $sourceId = $tmpNode->getAttribute('id');
      }

    }

    // now first use the source name as source connection name
    $sourceConName = $sourceName;

    // connection
    $tmpList = $modelXpath->query('./connection', $ref);
    if( !$tmpList->length )
    {
      $this->stringToNode( '<connection name="'.$conName.'" />' , $ref );
    }
    else
    {
      $tmpNode = $tmpList->item(0);

      if(!$tmpNode->hasAttribute('name'))
      {
        $tmpNode->setAttribute('name',$conName);
      }
      else
      {
        $conName = $tmpNode->getAttribute('name');
      }

      if( $tmpNode->hasAttribute('src' ) )
        $sourceConName = $tmpNode->getAttribute('src' );

      if( $tmpNode->hasAttribute('target' ) )
        $targetConName = $tmpNode->getAttribute('target' );

      if( $conMask = $tmpNode->getAttribute('mask') )
      {

        $result = $modelXpath->query('/bdl/entities/entity[@name="'.$conName.'"]');
        $nodeManagement = $this->tree->getRootNode('Management');

        if( $result->length )
        {

          $entNode   = simplexml_import_dom( $result->item(0) );

          $nodeManagement->createDefault
          (
            $conName,
            array
            (
              'entity'      => $entNode,
              'management'  => $conMask
            )

          );
        }
      }
    }

    //target
    $tmpList = $modelXpath->query('./target', $ref);
    if( !$tmpList->length )
    {
      $targetId = 'id_'.$targetName;
      $this->stringToNode( '<target name="'.$targetName.'" id="'.$targetId.'" />' , $ref );
    }
    else
    {
      $tmpNode = $tmpList->item(0);

      if(!$tmpNode->hasAttribute('name'))
        $tmpNode->setAttribute('name',$targetName);

      if(!$tmpNode->hasAttribute('id'))
      {
        $targetId = 'id_'.$targetName;
        $tmpNode->setAttribute('id',$targetId);
      }
      else
      {
        $targetId = $tmpNode->getAttribute('id');
      }

    }

    // if no specific target connection name was given use the target name for the connection reference
    if( !$targetConName )
      $targetConName = $targetName;

    $checkRefQuery  = '/bdl/entities/entity[@name="'.$conName.'"]';
    $refs           = $modelXpath->query( $checkRefQuery );

    // if the reference not yet exist create a new one
    if( !$refs->length )
    {
      $this->createManyToManyReference( $conName, $sourceConName , $targetConName, $sourceId , $targetId );
    }
    else
    {

      // can only be one
      $refNode = $refs->item(0);

      // must be there
      $getAttrs = './attributes';
      $attrs = $modelXpath->query( $getAttrs , $refNode );

      // check if we got some attributes
      if( !$attrs->length )
      {
        // if not create one
        $attrs = $refNode->ownerDocumnet->createElement('arributes');
        $attrs = $refNode->appendChild( $attrs );
      }//end attrs
      else
      {
        // else use the existing node
        $attrs = $attrs->item(0);
      }


      //$checkIdSource = '//bdl/entities/entity[@name="'.$relName.'"]/attributes/attribute[@name="'.$sourceId.'"]';
      $checkIdSource = './attribute[@name="'.$sourceId.'"]';
      $refAttrSrc = $modelXpath->query( $checkIdSource, $attrs );

      if( !$refAttrSrc->length )
      {
        $this->stringToNode( '<attribute name="'.$sourceId.'" target="'.$sourceConName.'" ><uiElement><readonly /><required /></uiElement></attribute>' , $attrs );
      }

      //$checkIdTarget = '//bdl/entities/entity[@name="'.$relName.'"]/attributes/attribute[@name="'.$targetId.'"]';
      $checkIdTarget = './attribute[@name="'.$targetId.'"]';
      $refAttrTarget = $modelXpath->query( $checkIdTarget, $attrs );

      if( !$refAttrTarget->length )
      {
        $this->stringToNode( '<attribute name="'.$targetId.'" target="'.$targetConName.'" ><uiElement><readonly /><required /></uiElement></attribute>' , $attrs );
      }

    }

  }//end protected function postProcessManyToManyReference */


  /**
   * @param DOMNode   $ref
   * @param DOMXpath  $modelXpath
   */
  protected function extendRefName( $ref, $modelXpath )
  {

    if( !$ref->hasAttribute('relation') )
      return false;

    $relation     = strtolower($ref->getAttribute('relation'));

    if( Bdl::MANY_TO_MANY === $relation )
    {
      //when it's many to many
      if( $tmpList = $modelXpath->query('./connection', $ref) )
      {
        //and the connection has a name
        if( $tmpList->length && $tmpList->item(0)->hasAttribute('name') )
        {
          //use the connection name a reference name
          $ref->setAttribute( 'name', $tmpList->item(0)->getAttribute('name') );
          return true;
        }
      }
    }
    else
    {
      if( $tmpList = $modelXpath->query('./target', $ref) )
      {
        if( $tmpList->length && $tmpList->item(0)->hasAttribute('name') )
        {
          $ref->setAttribute( 'name', $tmpList->item(0)->getAttribute('name') );
          return true;
        }
      }
    }

    return false;

  }//end protected function extendRefName */

  /**
   * @param DOMNode   $ref
   * @param DOMXpath  $modelXpath
   */
  protected function createOneToOneRefFromAttribute( $ref, $modelXpath )
  {

    $refAttribute = $ref->parentNode;
    $refEntity    = $refAttribute->parentNode->parentNode;
    $simpleNode   = $this->simple($ref);

    $src          = $refEntity->getAttribute('name');
    $target       = $refAttribute->getAttribute('target');

    // if the embed tag has no attribute name, we use the target as name
    if( !$refName  = $ref->getAttribute('name')  )
    {
      if( !$refName  = $ref->getAttribute('as')  )
      {
        $refName = $target;
      }
    }

    if( !$targetId = $refAttribute->getAttribute('name') )
    {
      $targetId = 'id_'.$target;
    }

    $exclude = '';
    if( isset($simpleNode->exclude))
    {
      $exclude = '<exclude>';
      if( $children = $simpleNode->exclude->children() )
      {
        foreach( $children as $name => $node )
        {
          $exclude .= '<'.$name.' />';
        }
      }
      $exclude .= '</exclude>';
    }

    $refHtml = <<<CODE

  <ref name="{$refName}" base="src" binding="connected" relation="oneToOne" >
    {$exclude}
    <description>
      <text lang="de" >1 zu 1 Verknüpfung zwischen {$src} und {$target}</text>
      <text lang="en" >1 to 1 Reference between {$src} and {$target}</text>
    </description>
    <src name="{$src}" />
    <target name="{$target}" id="{$targetId}" />
  </ref>

CODE;

    $this->appendReference( $refHtml, $refEntity );

    return true;

  }//end protected function createOneToOneRefFromAttribute */


  /**
   *
   */
  protected function postProcessExtendedElements()
  {

    $modelXpath  = $this->tree->getXpath();

    $listTargets  = $modelXpath->query('/bdl/entities/entity/attributes/attribute/target');


    // request all list targets
    foreach( $listTargets as $target )
    {

      // extract sub attributes and  sub references
      $this->extractExtensions( $target );

      // remove target after processing
      $target->parentNode->removeChild( $target );

    }//end foreach


    $targetRefs   = $modelXpath->query('/bdl/entities/entity/references/ref/target[@name]');
    // get all references
    foreach( $targetRefs as $ref )
    {
      // extract sub attributes and  sub references
      $this->extractExtensions( $ref );
    }//end foreach

    $srcRefs      = $modelXpath->query('/bdl/entities/entity/references/ref/src[@name]');
    // get all references
    foreach( $srcRefs as $ref )
    {
      // extract sub attributes and  sub references
      $this->extractExtensions( $ref );
    }//end foreach

    $connectionRefs = $modelXpath->query('/bdl/entities/entity/references/ref/connection[@name]');
    // get all references
    foreach( $connectionRefs as $ref )
    {
      // extract sub attributes and  sub references
      $this->extractExtensions( $ref );
    }//end foreach


  }//end public function postProcessExtendedElements */

  /**
   * add the default attribute attributes
   *
   */
  protected function postProcessDefaultAttributeAttributes()
  {

    $modelXpath     = $this->tree->getXpath();
    $allAttributes  = $modelXpath->query('/bdl/entities/entity/attributes/attribute');

    foreach( $allAttributes as $tmpAttr )
    {

      ///TODO check if it makes sense to remove rowid here

      if( !$tmpAttr->hasAttribute('size') )
        $tmpAttr->setAttribute( 'size' , '' );

      if( !$tmpAttr->hasAttribute('type') || $tmpAttr->hasAttribute('target')  )
      {
        $this->guessType( $tmpAttr );
      }


      if( !$tmpAttr->hasAttribute('validator') || '' == trim($tmpAttr->getAttribute('validator')) )
      {
        $type = $tmpAttr->getAttribute('type');

        if( isset($this->typeValidMap[$type]) )
        {
          $validator = $this->typeValidMap[$type];
        }
        else
        {
          $validator = 'text';
        }
        
        if( $tmpAttr->hasAttribute('target') && 'id_' == substr(trim($tmpAttr->getAttribute('name')), 0,3) )
        {
          $validator = 'eid';
        }

        $tmpAttr->setAttribute( 'validator' , $validator );
      }


      if( !$tmpAttr->hasAttribute('required') )
        $tmpAttr->setAttribute( 'required' , 'false' );

      if( !$tmpAttr->hasAttribute('minSize') )
        $tmpAttr->setAttribute( 'minSize' , '' );

      if( !$tmpAttr->hasAttribute('maxSize') )
        $tmpAttr->setAttribute( 'maxSize' , '' );

      // testen ob eine kategorie vorhanden ist
      $categories = $tmpAttr->getElementsByTagName('categories');

      if( !$categories->length )
      {
        $cats = $this->modelTree->importNode($this->modelTree->createElement('categories'),true);
        $cats->setAttribute('main','default');
        $tmpAttr->appendChild($cats);
      }

      // testen ob ein uiElement vorhanden ist, wenn nicht ein standar uiElement
      // hinzufügen soweit sinvoll möglich

      $uiElement = $tmpAttr->getElementsByTagName('uiElement');

      if( !$uiElement->length )
      {
        $this->createDefaultUiElement( $tmpAttr );
      }

    }

  }//end protected function postProcessDefaultAttributeAttributes */

  /**
   * create the default entity attributes
   *
   */
  protected function postProcessDefaultEntityAttributes()
  {

    $modelXpath     = $this->tree->getXpath();
    $entities       = $modelXpath->query('/bdl/entities/entity');
    

    $customDefAttr  = $this->getDefaultAttributes();

    foreach( $entities as $entity )
    {
      $name = $entity->getAttribute('name');

      if( !$label = $entity->getAttribute('label') )
        $label    = SParserString::subToName($name);

      $entityQuery  = '/bdl/entities/entity[@name="'.$name.'"]/attributes';
      $attrs        = $modelXpath->query( $entityQuery );

      if( !$attrs->length  )
      {
        $attributes = $this->modelTree->importNode
        (
          $this->modelTree->createElement('attributes'),
          true
        );
        $attrTag    = $entity->appendChild($attributes);
      }
      else
      {

        if( $attrs->length > 1 )
        {
          foreach( $attrs as $pos => $tmpAttr )
          {
            $smplNode = simplexml_import_dom($tmpAttr->parentNode->parentNode);

            Debug::dumpFile( $name.'.'.$pos, $smplNode->asXml() );
          }
        }

        $attrTag = $attrs->item(0);
      }

      if( $dataType = $entity->getAttribute('data') )
      {
        if( 'plain' == $dataType )
          continue;
      }
      
      $simpleEntity = simplexml_import_dom($entity);
      
      if( isset( $simpleEntity->head ) )
      {

        $isMuteAble = isset($simpleEntity->head->muteable)
          ? ( 'true' === trim($simpleEntity->head->muteable) )
          : true;

        $isSyncAble = isset($simpleEntity->head->syncable)
          ? ( 'true' === trim($simpleEntity->head->syncable) )
          : true;

        $useTransactions = isset($simpleEntity->head->transaction)
          ? ( 'true' === trim($simpleEntity->head->transaction) )
          : false;

        $trackCreation = isset($simpleEntity->head->track_creation)
          ? ( 'true' === trim($simpleEntity->head->track_creation) )
          : true;

        $trackChanges = isset($simpleEntity->head->track_changes)
          ? ( 'true' === trim($simpleEntity->head->track_changes) )
          : true;

        $trackDeletion = isset($simpleEntity->head->track_deletion)
          ? ( 'true' === trim($simpleEntity->head->track_deletion) )
          : false;

        $defAttr = $this->getDefaultAttributes
        (
          $trackDeletion,
          $trackChanges,
          $trackCreation,
          $isSyncAble,
          $useTransactions
        );

      }
      else 
      {
        $defAttr = $customDefAttr;
      }

      foreach( $defAttr as $metaAttrs )
      {
        /*
        $ele      = $this->modelTree->createElement('attribute');
        $newAttr  = $this->modelTree->importNode( $ele ,true);

        foreach( $metaAttrs as $tName => $value )
          $ele->setAttribute( $tName, $value );

        $categories = $this->modelTree->importNode($this->modelTree->createElement('categories'),true);
        $categories->setAttribute('main','meta');
        $newAttr->appendChild($categories);

        if( $metaAttrs['name'] == 'rowid' )
        {
          $search = $this->modelTree->importNode($this->modelTree->createElement('search'),true);
          $search->setAttribute('type','equal');
          $newAttr->appendChild($search);
        }
        */

        $newAttr = $this->stringToNode( $metaAttrs );

        $attrTag->appendChild($newAttr);

      }//end foreach

    }//end foreach

  }//end protected function postProcessDefaultEntityAttributes */


  /**
   * create the default entity attributes
   *
   */
  protected function postInterpretConditions()
  {

    $this->builder->interpreter->postInterpret( $this->nodeRoot );

  }//end protected function postProcessDefaultEntityAttributes */

  /**
   * create the default attributes for every entity
   * @param boolean $withDelete
   * @param boolean $muteable
   * @param boolean $creator
   * @param boolean $syncAble
   * @return array
   */
  protected function getDefaultAttributes
  ( 
    $withDelete = false, 
    $muteable = true, 
    $creator = true, 
    $syncAble = true, 
    $transActions = false  
  )
  {

    $defAttr = array();

    $defAttr[] = <<<CODE
  <attribute name="rowid" type="bigint" validator="eid" size="" required="true" maxSize="" minSize=""  >
    <categories main="meta" />
  </attribute>

CODE;

    if( $creator )
    {

    // metadata for creation
    $defAttr[] = <<<CODE
  <attribute name="m_time_created" type="timestamp" validator="timestamp" size="" required="false" maxSize="" minSize=""  >
    <categories main="meta" />
    <uiElement type="date" />
  </attribute>

CODE;

    // who created the entry
    $defAttr[] = <<<CODE
  <attribute name="m_role_create" type="bigint" validator="eid" target="wbfsys_role_user" size="" required="false" maxSize="" minSize=""  >
    <categories main="meta" />
    <uiElement type="window" src="wbfsys_role_user" />
    <fk target="wbfsys_role_user" />
  </attribute>

CODE;

    }

    if( $muteable )
    {
    
    // when was the dataset changed last time
    $defAttr[] = <<<CODE
  <attribute name="m_time_changed" type="timestamp" validator="timestamp" size="" required="false" maxSize="" minSize=""  >
    <categories main="meta" />
    <uiElement type="date" />
  </attribute>

CODE;

    // who changed last time
    $defAttr[] = <<<CODE
  <attribute name="m_role_change" type="bigint" validator="eid" target="wbfsys_role_user" size="" required="false" maxSize="" minSize=""  >
    <categories main="meta" />
    <uiElement type="window" src="wbfsys_role_user" />
    <fk target="wbfsys_role_user" />
  </attribute>

CODE;
    
    // when was the dataset changed last time
    $defAttr[] = <<<CODE
  <attribute name="m_version" type="integer" validator="int" size="" required="false" maxSize="" minSize=""  >
    <categories main="meta" />
  </attribute>

CODE;
    
    }
    
    if( $syncAble )
    {

    // uuid for the dataset
    $defAttr[] = <<<CODE
  <attribute name="m_uuid" type="uuid" validator="text" size="" required="false" maxSize="" minSize=""  >
    <categories main="meta" />
  </attribute>

CODE;

    }

    /*
    // status of an entry for the dataset
    /**/
    
    if( $transActions )
    {
      
    $defAttr[] = <<<CODE
  <attribute name="m_status" type="smallint" validator="int" size="" required="false" maxSize="" minSize=""  >
    <label>
      <text lang="de" >Transaktionsstatus</text>
      <text lang="en" >transaction status</text>
    </label>
    <description>
      <text lang="de" >Der Status der Transaktion in der sich der Eintrag gerade befindet. Wird verwendet um Datensätze in schritten zu erstellen.</text>
      <text lang="en" >transaction status of an entry</text>
    </description>
    <categories main="meta" />
  </attribute>

CODE;

    }
    


    if( $withDelete )
    {
      // when was the entry deleted
      $defAttr[] = <<<CODE
    <attribute name="m_time_deleted" type="timestamp" validator="timestamp" size="" required="false" maxSize="" minSize=""  >
      <categories main="meta" />
      <uiElement type="date" />
    </attribute>

CODE;

      // who deleted the entry
      $defAttr[] = <<<CODE
    <attribute name="m_role_delete" type="bigint" validator="eid" target="wbfsys_role_user" size="" required="false" maxSize="" minSize=""  >
      <categories main="meta" />
      <uiElement type="window" src="wbfsys_role_user" />
      <fk target="wbfsys_role_user" />
    </attribute>

CODE;

    }



    /*
    $defAttr[] = array
    (
      'name'      => 'm_system' , 'type'    => 'char',  'size'    => '1',
      'required'  => 'false'    , 'maxSize' => ''    ,  'minSize' => '' ,
      'validator' => 'boolean'
    );
    */

    return $defAttr;

  }//end protected function getDefaultAttributes */

  /**
   * create all default elements that inherits from entity
   * @param string $name
   */
  public function createDefaultDependencies(  )
  {

    $nodeQuery = '/bdl/entities/entity';

    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query( $nodeQuery );

    $nodeModule     = $this->tree->getRootNode('Module');
    $nodeManagement = $this->tree->getRootNode('Management');

    foreach( $nodeList as $node )
    {
      $tmpName  = $node->getAttribute('name');


      if( $dataType = $node->getAttribute('type') )
      {
         // meta datatypes have no affect
        if( 'meta' == $dataType )
          continue;
      }

      if( $customModul = $node->getAttribute('module') )
      {
        $modName  = strtolower($customModul);
      }
      else 
      {
        $modName  = strtolower(SParserString::getDomainName($tmpName));
      }

      $nodeModule->createDefault( $modName );
      $nodeManagement->createDefault
      ( 
        $tmpName, 
        array
        ( 
          'entity' => simplexml_import_dom($node) 
        ) 
      );
    }

  }//end protected function createDefaultDependencies */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#createIndex()
   */
  public function createIndex()
  {


    // append default attributes to the entity
    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query('/bdl/entities/entity');

    if( !$className = $this->builder->getNodeClass('Entity') )
    {
      throw new LibGenfTree_Exception( 'Got no Node for Entity' );
    }

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // parse Names
      $name     = $this->parseNames( $smplNode );

      // create an entity index in the node
      $this->nodes[trim($smplNode['name'])] = new $className($smplNode, $name);

    }//end foreach

  }//end public function createIndex */

  /**
   * @param string $name
   * @return DOMNode
   */
  public function get( $name, $type = null )
  {
    $check = '/bdl/entities/entity[@name="'.$name.'"]';

    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      if( $nodeList->length > 1 )
      {
        Error::report('wh00t da hell: '.$name.' ?: '.$nodeList->length);
      }

      return $nodeList->item(0);
    }
    else
    {
      return null;
    }

  }//end public function get */


  /**
   * check if a node exists,
   * normaly a node should have a name object, so we can reuse an isset
   * to the name index to check the existence
   *
   * if not create your own method in your parent class
   *
   * @param string $key
   * @return boolean
   */
  public function exists( $key  )
  {

   $check = '/bdl/entities/entity[@name="'.$key.'"]';

    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query($check);

    // convert length to boolean
    return ($nodeList->length > 0);

  }//end public function exists */

  /**
   * @param string $name
   * @param array $params
   * @return void
   */
  public function createDefault( $name, $params = array() )
  {

    if( $this->get( $name ) )
      return true;

    $attributes = null;
    $attributes = null;


    $xml = <<<CODE
  <entity name="$name"  >

  </entity>
CODE;


    $this->stringToNode( $xml , $this->nodeRoot );

  }//end public function createDefault */

  /**
   * @param string $name
   * @return void
   */
  public function createFromExtension( $name, $params = array() )
  {

    if( $this->get($name) )
      return true;


    $label = null;
    if( isset($params['label']) )
    {
      if( is_object( $params['label'] ) )
      {
        $label = LibXml::nodeToString($params['label']);
      }
      else
      {
        $label = $params['label'];
      }
    }

    $description = null;
    if( isset($params['description']) )
    {
      if( is_object( $params['description'] ) )
      {
        $description = LibXml::nodeToString($params['description']);
      }
      else
      {
        $description = $params['description'];
      }
    }

    $attributes = null;
    if( isset($params['attributes']) )
    {
      if( is_object( $params['attributes'] ) )
      {
        $attributes = LibXml::nodeToString($params['attributes']);
      }
      else
      {
        $attributes = $params['attributes'];
      }
    }
    else
    {
      // if we not have any attributes we should add at least an empty attributes block
      $attributes = '<attributes />';
    }

    $references = null;
    if( isset($params['references']) )
    {
      if( is_object( $params['references'] ) )
      {
        $references = LibXml::nodeToString($params['references']);
      }
      else
      {
        $references = $params['references'];
      }
    }


    $temporary = '';
    if( isset($params['temporary']) )
    {
      $temporary = 'temporary="'.$params['temporary'].'"';
    }



    $xml = <<<CODE
  <entity name="{$name}" {$temporary}  >
    {$label}
    {$description}
    {$attributes}
    {$references}
  </entity>
CODE;


    $this->stringToNode( $xml , $this->nodeRoot );

  }//end public function createFromExtension */

  /**
   * @param string $name
   * @param string $entiy1
   * @param string $entity2
   * @param string $entity1Id
   * @param string $entity2Id
   */
  public function createManyToManyReference( $name, $entity1, $entity2, $entity1Id , $entity2Id )
  {

    $namesEnt1 = new LibGenfNameEntity($entity1);
    $namesEnt2 = new LibGenfNameEntity($entity2);

    $xml = <<<CODE
  <entity name="{$name}" relevance="d-3"  >

    <categories main="references" />

    <description>
      <text lang="de" >Referenz Entität zum verknüpfen von {$namesEnt1->label} und {$namesEnt2->label} in einer manyToMany Beziehung</text>
      <text lang="en" >Reference Entity to connect {$namesEnt1->label} and {$namesEnt2->label} in a manyToMany relation</text>
    </description>

    <ui>
      <menu type="disabled" />
    </ui>

    <attributes>
      <attribute name="{$entity1Id}" target="{$entity1}" ref_name="{$entity1}_src" ></attribute>
      <attribute name="{$entity2Id}" target="{$entity2}" ref_name="{$entity2}_target" ></attribute>
    </attributes>

    <!-- should be in the menu in the src related category -->
    <categories main="{$entity1}" />

    <references>

      <ref name="{$entity1}" binding="free"  relation="manyToOne" >
        <label>{$namesEnt1->label}</label>
        <src     name="$name"     id="{$entity1Id}"  ></src>
        <target  name="$entity1"  ></target>
      </ref>

      <ref name="{$entity2}" binding="free"  relation="manyToOne" >
        <label>{$namesEnt2->label}</label>
        <src     name="$name"     id="{$entity2Id}"  ></src>
        <target  name="$entity2"  ></target>
      </ref>

    </references>

  </entity>
CODE;

    $this->stringToNode( $xml, $this->nodeRoot );


  }//end public function createManyToManyReference */

   /**
    * default merge is to replace the old node
    * @param DOMNode $oldEntity
    * @param DOMNode $entity
    */
  public function merge( $oldEntity, $newEntity )
  {

    LibGenfMergeEntity::getInstance( $this->tree )->merge( $oldEntity, $newEntity );

  }//end public function merge */


  /**
   * @param  $newEntity
   */
  public function extractExtensions( $newEntity )
  {

    $nodeName = $newEntity->getAttribute('name');

    if( $oldEntity = $this->get( $nodeName ) )
    {
      LibGenfMergeEntity::getInstance( $this->tree )->mergeEmbeded( $oldEntity, $newEntity );
    }
    else
    {
      $modelXpath = $this->tree->getXpath();

      $params = array();
      $params['temporary'] = 'true';

      $tmpList = $modelXpath->query('./attributes', $newEntity );
      if( !$tmpList->length )
      {
        $params['attributes'] = $tmpList->item(0);
      }

      $tmpList = $modelXpath->query('./references', $newEntity );
      if( !$tmpList->length )
      {
        $params['references'] = $tmpList->item(0);
      }

      $this->createFromExtension( $nodeName, $params  );

    }

  }//end public function extractExtensions */


////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param string $name
   * @param string $label
   * @return void
   */
  public function parseNames( $node  )
  {

    $name       = trim($node['name']);

    if( isset($this->names[$name]) )
      return $this->names[$name];

    $obj = new LibGenfNameEntity( $node , array('builder'=>$this->builder,'node'=>$node) );

    $label      = $this->builder->interpreter->getLabel( $node );

    /* deprecated oldstyle variables */
    $obj->tableName       = $name;

    $obj->entityText      = SParserString::subToName($name);

    $obj->entityLabel     = $label;

    $obj->lowEntityLabel  = strtolower($obj->entityLabel);

    $obj->entityName      = SParserString::subToCamelCase($name);
    $obj->lowEntityName   = strtolower( $obj->entityName );

    $obj->domainName      = SParserString::getDomainName($name);
    $obj->lowDomainName   = strtolower( $obj->domainName );

    $obj->modelName       = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;
    //$obj->lowModelName    = strtolower( $obj->modelName );


    $this->names[$name]   = $obj;
    return $obj;

  }//end public function parseNames */

  /**
   * set entity activ
   *
   * @param string $table
   */
  public function setActiv( $name )
  {

    if( is_null($name) )
    {
      Error::report( 'Empty set activ request' );
      return false;
    }


    if( is_object($name) )
      $name = trim($name['name']);

    /*
    if( is_object($name) )
    {
      if( $name instanceof SimpleXmlElement )
        $name = trim($name['name']);
      else
        $name = $name->name();
    }
    */

    if( isset($this->nodes[$name]) )
    {
      $this->entity = $this->nodes[$name];
    }
    else
    {
      $this->entity = null;
      Log::warn('Found no Entity Object for Entity:'.$name );
      return false;
    }

    if( isset($this->names[$name]) )
    {
      $this->name = $this->names[$name];
    }
    else
    {
      $this->name = null;
      Log::warn( 'Found no Name Object for Entity:'.$name  );
      return false;
    }

    return true;

  }//end public function setActiv */

  /**
   *
   * @param string $key LibGenfName with all parsed Names for the Tree
   * @return LibGenfTreeNodeEntity
   */
  public function getEntity( $key = null )
  {

    if(!$key)
      return $this->entity;

    if( is_object($key))
      $key = $key->source;

    return isset($this->nodes[$key])?$this->nodes[$key]:null;

  }//end public function getEntity */


  /**
   *
   * @param string $key LibGenfName with all parsed Names for the Tree
   * @return LibGenfTreeNodeEntity
   */
  public function getRawReference( $entityName , $referenceName )
  {

    $modelXpath = $this->tree->getXpath();
    $references = $modelXpath->query('/bdl/entities/entity[@relation="'.$entityName.'"]/references/ref[@name="'.$referenceName.'"]');

    return $references->length > 0
      ? $references->item(0)
      : null;

  }//end public function getRawReference */



////////////////////////////////////////////////////////////////////////////////
// Getter Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param string $key
   * @namedParam $params
   * {
   *    boolean noMeta    true
   *    boolean asString  true
   * }
   *
   */
  public function getAttributes( $entity, $params = array() )
  {

    // names parameters
    if( !is_object($entity) )
      if( !$entity = $this->getEntity($entity) )
        return array();

    return $entity->getAttributes( $params );

  }//end public function getAttributes */

  /**
   *
   * @param string $key
   * @namedParam $params
   * {
   *    boolean noMeta    true
   *    boolean asString  true
   * }
   *
   */
  public function getSearchAttributes( $key, $params = array() )
  {

    // names parameters
    $noMeta   = true;
    $asString = true;

    if(isset($params['meta']))
      $noMeta = $params['meta'];

    if(isset($params['asString']))
      $asString = $params['asString'];

    //end names parameters

    if(! $entity = $this->getEntity($key) )
      return array();

    $attributes = array();

    foreach( $entity->attributes->attribute as $attribute )
    {

      if( !isset($attribute->search)  )
       continue;

      $attributes[] = trim($attribute['name']);

    }

    if( $asString )
      $attributes = "'".implode("','",$attributes)."'";

    return $attributes;

  }//end public function getAttributes

  /**
   *  request all existing sequences for alle entities and attributes
   *  @return array
   */
  public function getSequences(  )
  {
    return array();
  }//end public function getSequences

  /**
   * Enter description here...
   *
   * @param SimpleXmlElement $ref
   * @return unknown
   */
  public function preSave( $ref )
  {

    if
    (
      !isset( $ref->src['id'] )
        || trim($ref->src['id']) == ''
        || trim($ref->src['id']) == $this->builder->rowidKey
    )
      return true;
    else
      return false;

  }//end public function preSave */

  /**
   * @param string $name
   * @return LibGenfTreeNodelistReference
   */
  public function getReferences( $name )
  {
    if( is_object($name) )
    {
      $name = $name->name;
    }

    if( isset( $this->nodes[$name] ) )
      return $this->nodes[$name]->getReferences();

    return null;

  }//end public function getReferences */

  /**
   * @param string $target
   */
  public function getEntityReferences( $target )
  {

    $modelXpath = $this->tree->getXpath();
    $references = $modelXpath->query('/bdl/entities/entity/references/ref/target[@name="'.$target.'"]');

    $simpleReferences = array();

    foreach( $references as $refNode )
    {
      $simpleReferences[] = simplexml_import_dom($refNode->parentNode);
    }

    return $simpleReferences;

  }//end public function getEntityReferences */
  
  /**
   * @param string $con
   * @return array<LibGenfTreeNodeReference>
   */
  public function getManyToManyReferencesByName( $con )
  {

    $modelXpath = $this->tree->getXpath();
    $referencesList = $modelXpath->query( '/bdl/entities/entity/references/ref/connection[@name="'.$con.'"]' );

    if( !$referencesList->length )
      return null;
    
    $references = array();

    foreach( $referencesList as $refNode )
    {
      //$simpleReferences[] = simplexml_import_dom($refNode->parentNode);
      
      $refName    = $refNode->parentNode->getAttribute( 'name' );
      $entityName = $refNode->parentNode->parentNode->parentNode->getAttribute( 'name' );
      
      $entity = $this->builder->getEntity( $entityName );
      
      $references[] = $entity->getReference( $refName );
      
    }

    return $references;

  }//end public function getManyToManyReferencesByName */

  /**
   * @param DOMNode $reference
   * @param DOMNode $entity
   */
  public function appendReference( $reference, $entity )
  {

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query( './references', $entity  );

    if( $nodeList->length )
    {
      $refNode = $nodeList->item(0);
    }
    else
    {
      $refNode = $entity->appendChild
      (
        $entity->ownerDocument->createElement( 'references' )
      );
    }

    ///TODO add some colission prevention logic here
    if( is_object($reference) && $reference instanceof DOMNode )
    {
      $refNode->appendChild($reference);
    }
    else
    {
      $this->stringToNode( $reference, $refNode );
    }


  }//end public function appendReference */

////////////////////////////////////////////////////////////////////////////////
// protected methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param $entity
   * @return array
   */
  protected function extractAttributes( $entity )
  {

    if( !$entity )
      return array();

    if(!$nodeClass = $this->builder->getNodeClass('Attribute'))
      return array();

    $attributes = array();

    foreach( $entity->attributes->attribute as $attribute )
    {
      $attributes[trim($attribute['name'])] = new $nodeClass($attribute);
    }

    return $attributes;

  }//end public function extractAttributes */

  /**
   * @param $attrNode
   */
  protected function createDefaultUiElement( $attrNode )
  {

    $uiel = $this->modelTree->importNode($this->modelTree->createElement('uiElement'),true);

    // here it's shure that type exists, cause we checked befor or added a default
    $type = trim(strtolower($attrNode->getAttribute('type')));

    $arrayEleManp = array
    (
      'eid'         => 'int',
      'sequence'    => 'int',
      'int'         => 'int',
      'integer'     => 'int',
      'smallint'    => 'smallint',
      'bigint'      => 'bigint',
      'numeric'     => 'numeric',
      'varchar'     => 'text',
      'date'        => 'date',
      'time'        => 'time',
      'timestamp'   => 'timestamp',
      'boolean'     => 'checkbox'
    );

    // wenn eid, checken ob ein fk vorhanden ist, wenn ja ist das uiElement
    // ein window auf die fk entity
    if( $type == 'eid' )
    {
      $fkEle = $attrNode->getElementsByTagName('fk');

      if( !$fkEle->length )
      {
        $uiel->setAttribute( 'type' , 'bigint' );
      }
      else
      {
        $uiel->setAttribute( 'type' , 'window' );

        // alias mappt auf ein management wenn vorhanden
        if( $fkEle->hasAttribute('mask') )
          $target = $fkEle->getAttribute('mask');
        else
          $target = $fkEle->getAttribute('target');

        $uiel->setAttribute( 'src' , $target );
      }

    }
    elseif( isset( $arrayEleManp[$type] ) )
    {
      // map attr type to uiElement type
      $uiel->setAttribute( 'type' , $arrayEleManp[$type] );
    }
    else if( 'text' == $type )
    {

      $size = $attrNode->getAttribute('size');

      if( '' == trim($size) || !$size )
      {
         $uiel->setAttribute( 'type' , 'textarea' );
      }
      else
      {
        $uiel->setAttribute( 'type' , 'text' );
      }

    }
    else
    {
      // default type is text for all other elements
      $uiel->setAttribute( 'type' , 'text' );
    }

    $attrNode->appendChild($uiel);

  }//end protected function createDefaultUiElement */

  /**
   * @param $attrNode
   */
  protected function guessType( $attrNode )
  {

    // there must be a target or name beginning with 'id_'
    // then ob of both is optional
    if( !$attrNode->hasAttribute('target')  )
    {

      $name = $attrNode->getAttribute('name');

      // no id, no target... normal text
      if( 'id_' != substr( $name , 0, 3 ) )
      {
        $attrNode->setAttribute( 'type', 'text' );
        return;
      }
      else
      {
        $attrNode->setAttribute('target',substr( $name , -3, (strlen($name)-3) ));
      }

    }
    else
    {

      if( !$attrNode->hasAttribute('name') )
      {

        if( !$targetName = $attrNode->getAttribute('target') )
        {
          Debug::console( 'Missing Name and Target Attributes in an Attribute Node' );
          $attrNode->setAttribute ( 'name', 'tmp_'.Webfrap::uniqid() );
          return;
        }
        else
        {
          $attrNode->setAttribute
          (
            'name',
            'id_'.$targetName
          );
        }
      }

    }

    // if there is a target, extend the ui element, fk and types
    if( $target = $attrNode->getAttribute('target') )
    {

      if( !$attrNode->hasAttribute('target_field') )
      {
        $attrNode->setAttribute( 'type', 'eid' );
      }

      if( !$attrNode->hasAttribute('validator') )
      {
        $attrNode->setAttribute( 'validator', 'eid' );
      }

      if( !$attrNode->hasAttribute('ref_name') )
      {
        $attrNode->setAttribute( 'ref_name', trim($target) );
      }


      $fkEle = $attrNode->getElementsByTagName('fk');
      if( !$fkEle->length )
      {
        $fkNode = $this->modelTree->importNode($this->modelTree->createElement('fk'),true);
        $fkNode->setAttribute( 'target' , $target );
        $attrNode->appendChild($fkNode);
      }

      $uiEle = $attrNode->getElementsByTagName('uiElement');
      if( !$uiEle->length )
      {
        $uiNode = $this->modelTree->importNode($this->modelTree->createElement('uiElement'),true);
        $uiNode->setAttribute( 'type' , 'window' );
        $uiNode->setAttribute( 'src' , $target );
        $attrNode->appendChild($uiNode);
      }
      else
      {
        // if exists check if valid, if not create default values
        $uiNode = $uiEle->item(0);

        if( !$uiNode->hasAttribute('src') )
          $uiNode->setAttribute( 'src' , $target);

        if( !$uiNode->hasAttribute('type') )
          $uiNode->setAttribute( 'type' , 'window' );

      }

    }


  }//end protected function guessType */

} // end class LibGenfTreeRootBdlEntity
