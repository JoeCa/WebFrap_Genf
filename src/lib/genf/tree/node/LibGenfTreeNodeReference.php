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
 * @lang de:
 * Diese Klasse abstrahiert den zugriff auf einen referenz Knoten
 *
 * zb:
 * /bld/entities/entity/references/ref
 *
 * @package WebFrap
 * @subpackage GenF
 *
 * @author dominik alexander bonsch <dominik.bonsch@webfrap.net>
 */
class LibGenfTreeNodeReference
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  protected $rootType   = 'Management';

  /**
   * plain src name
   * @var string
   */
  protected $srcSource  = null;

  /**
   * src name with alias
   * @var string
   */
  protected $srcMask    = null;
  
  /**
   * src name with alias
   * @var string
   */
  protected $targetMask    = null;

  /**
   * src name with alias
   * @var string
   */
  protected $connectionMask    = null;
  
  /**
   * the name of the category if exists
   * @var string
   */
  public $category      = null;

  /**
   * the name of the category if exists
   * @var LibGenfTreeNodeUiReference
   */
  public $ui            = null;
  
  /**
   * Der Modellknoten
   * Wird gesetzte wenn das Element geclont wurde und aus einem layout tag
   * ausgelesen wurde
   * @var SimpleXMLElement
   */
  public $modelNode     = null;

  /**
   *
   * backreference
   * @var LibGenfTreeNodeManagement
   */
  public $management  = null;

  /**
   * @lang de:
   *
   * Der Tabname wird benötigt wenn die Referenz in einem Tab eingebunden ist
   * Nur so lassen sich z.B. die ACLs sauber integrieren da dort der zugriff
   * auf den ab geregelt werden muss
   *
   * @var LibGenfNameDefault
   */
  public $tabName  = null;

  /**
   * debug variable
   */
  public $mTouched    = 'false';
  
  /**
   *
   * @var array
   */
  protected $concepts   = array();

  /**
   *
   * @var array
   */
  protected $conceptKeys  = array();

////////////////////////////////////////////////////////////////////////////////
// setter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameReference
    (
      $this->node,
      array
      (
        'interpreter'   =>  $this->builder->interpreter,
      )
    );

    $mgmtUi = null;
    if( $this->relation( Bdl::MANY_TO_MANY ) )
    {
      
      $mgmt = $this->connectionManagement();
      
      if( $mgmt )
        $mgmtUi = $mgmt->getUi();
    }
    else
    {
      
      $mgmt = $this->targetManagement();
      
      if( $mgmt )
        $mgmtUi = $mgmt->getUi();
    }
    
    // only exists if subnode exists
    if( isset( $this->node->ui ) )
    {
      $uiClassName          = $this->builder->getNodeClass( 'UiReference' );
      $this->ui             = new $uiClassName( $this->node->ui );
      $this->ui->reference  = $this;
      
      $this->ui->setFallback( $mgmtUi );

    }
    else 
    {
      $this->ui = $mgmtUi;
    }
    
      // only exists if subnode exists
    if( isset( $this->node->concepts->concept ) )
    {
      foreach( $this->node->concepts->concept as $concept )
      {
        $key = ucfirst( trim( $concept['name'] ) );

        $globalConcept = $this->builder->globalConcept(strtolower($key));

        // check if a concept is disabled
        if( false === $globalConcept )
          continue;

        $className = $this->builder->getNodeClass( 'Concept'.SParserString::subToCamelCase($key) );
        if( Webfrap::loadable($className) )
        {
          $this->concepts[strtolower($key)] = new $className( $concept );
        }
        else
        {
          Debug::console('missing node for concept '.$key);
          $this->concepts[strtolower($key)] = true;
        }
      }
    }

  }//end protected function loadChilds */

  /**
   * @param SimpleXmlNode $node
   *
   * <reference name="" >
   *  <ui>
   *    <list>
   *      <table>
   *      </table>
   *    </list>
   *  </ui>
   * </reference>
   *
   */
  public function customize( $node )
  {

    // if this node has no children no need for customizing the default model
    if( !$node->children() )
      return;

    $this->customNode = $node;

    $this->name = clone $this->name;
    $this->name->reLabel( $node );

    $interpret = isset( $node->ui['interpret'] )
      ? trim( $node->ui['interpret'] )
      : null;
      
    if( isset( $node->src['management'] )  )
    {
      $this->srcSource = trim( $node->src['management'] );
      $this->srcMask   = trim( $node->src['management'] );
    }
    
    if( isset( $node->target['management'] )  )
    {
      $this->targetMask = trim( $node->target['management'] );
    }
    
    if( isset( $node->connection['management'] )  )
    {
      $this->connectionMask = trim( $node->connection['management'] );
    }
    
    $mgmtUi = null;
    if( $this->relation(Bdl::MANY_TO_MANY) )
    {
      $mgmt = $this->connectionManagement();
      
      if( $mgmt )
        $mgmtUi = $mgmt->getUi();
    }
    else
    {
      $mgmt = $this->targetManagement();
      
      if( $mgmt )
        $mgmtUi = $mgmt->getUi();
    }

    if( isset( $node->ui ) )
    {
      
      if( !$this->ui )
      {

        if( 'clean' == $interpret )
          return;

        $uiClassName          = $this->builder->getNodeClass( 'UiReference' );
        $this->ui             = new $uiClassName( $node->ui );
        $this->ui->reference  = $this;
        
        $this->ui->setFallback( $mgmtUi );

      }
      else
      {

        if( 'replace' == $interpret  )
        {
          $uiClassName          = $this->builder->getNodeClass( 'UiReference' );
          $this->ui             = new $uiClassName( $node->ui );
          $this->ui->reference  = $this;
          
          $this->ui->setFallback( $mgmtUi );
        }
        elseif( 'clean' == $interpret )
        {
          $this->ui = null;
        }
        else
        {
          $this->ui = clone $this->ui;
          //$this->ui->customize( $node->ui );
          
          $this->ui->setFallback( $mgmtUi );
        }

      }

    }


  }//end public function customize */
  
  /**
   * @param SimpleXmlNode $node
   *
   * <reference name="" >
   *  <ui>
   *    <list>
   *      <table>
   *      </table>
   *    </list>
   *  </ui>
   * </reference>
   *
   */
  public function setModelNode( $node )
  {

    // if this node has no children no need for customizing the default model
    if( !$node->children() )
      return;

    $this->customNode = $node;

    $this->name = clone $this->name;
    $this->name->reLabel( $node );

    $interpret = isset( $node->ui['interpret'] )
      ? trim( $node->ui['interpret'] )
      : null;
      
    if( isset( $node->src['management'] )  )
    {
      $this->srcSource = trim( $node->src['management'] );
      $this->srcMask   = trim( $node->src['management'] );
    }
    
    if( isset( $node->target['management'] )  )
    {
      $this->targetMask = trim( $node->target['management'] );
    }
    
    if( isset( $node->connection['management'] )  )
    {
      $this->connectionMask = trim( $node->connection['management'] );
    }
    
    $mgmtUi = null;
    if( $this->relation(Bdl::MANY_TO_MANY) )
    {
      $mgmt = $this->connectionManagement();
      
      if( $mgmt )
        $mgmtUi = $mgmt->getUi();
    }
    else
    {
      $mgmt = $this->targetManagement();
      
      if( $mgmt )
        $mgmtUi = $mgmt->getUi();
    }

    if( isset( $node->ui ) )
    {
      
      if( !$this->ui )
      {

        if( 'clean' == $interpret )
          return;

        $uiClassName          = $this->builder->getNodeClass( 'UiReference' );
        $this->ui             = new $uiClassName( $node->ui );
        $this->ui->reference  = $this;
        
        $this->ui->setFallback( $mgmtUi );

      }
      else
      {

        if( 'replace' == $interpret  )
        {
          $uiClassName          = $this->builder->getNodeClass( 'UiReference' );
          $this->ui             = new $uiClassName( $node->ui );
          $this->ui->reference  = $this;
          
          $this->ui->setFallback( $mgmtUi );
        }
        elseif( 'clean' == $interpret )
        {
          $this->ui = null;
        }
        else
        {
          $this->ui = clone $this->ui;
          //$this->ui->customize( $node->ui );
          
          $this->ui->setFallback( $mgmtUi );
        }

      }

    }


  }//end public function customize */

  /**
   * @return string
   */
  public function __toString()
  {
    return 'Reference :'.$this->name.' on Management: '.($this->management?(string)$this->management:'').' in context '.$this->context;
  }//end public function __toString*/

////////////////////////////////////////////////////////////////////////////////
// ui getter + setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfTreeNodeUiReference
   */
  public function getUi()
  {
    return $this->ui;
  }//end public function getUi */


  /**
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context )
  {
    if( !$this->ui )
      return null;

    return $this->ui->getListUi( $context );

  }//end public function getListUi */


  /**
   * @param string $context
   * @return LibGenfTreeNodeUiReference
   */
  public function getFormUi( $context )
  {
    if( !$this->ui )
      return null;

    return $this->ui->getFormUi( $context );

  }//end public function getFormUi */

  /**
   * @return LibGenfTreeNodeUi
   */
  public function getMgmtUi()
  {

    if( $this->relation( Bdl::MANY_TO_MANY ) )
      return $this->connectionManagement()->getUi();
    else
      return $this->targetManagement()->getUi();

  }//end public function getMgmtUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeFormUi
   */
  public function getMgmtFormUi( $context )
  {

    if( $this->relation( Bdl::MANY_TO_MANY ) )
      return $this->connectionManagement()->getFormUi( $context );
    else
      return $this->targetManagement()->getFormUi( $context );

  }//end public function getMgmtFormUi */
  
  /**
   * @param string $context
   * @return LibGenfTreeNodeUi
   */
  public function getMgmtListUi( $context )
  {

    if( $this->relation( Bdl::MANY_TO_MANY ) )
      return $this->connectionManagement()->getListUi( $context );
    else
      return $this->targetManagement()->getListUi( $context );

  }//end public function getMgmtListUi */

  /**
   * 
   */
  public function getEvents()
  {
    return null;
  }//end public function getEvents */

  /**
   * @return LibGenfTreeNodeAccessLevel
   */
  public function getAccessLevel()
  {

    $node = null;

    if( isset( $this->node->access->levels ) )
      $node = $this->node->access->levels;

    $className = $this->builder->getNodeClass( 'AccessLevel' );

    return new $className( $node );

  }//end public function getAccessLevel */
  
  /**
   * @return LibGenfTreeNodeReferenceAccess
   */
  public function getAccess()
  {

    if( !isset( $this->node->access ) )
      return null;

    $classname   = $this->builder->getNodeClass( 'ReferenceAccess' );

    return new $classname( $this->node->access );

  }//end public function getAccess */

////////////////////////////////////////////////////////////////////////////////
// setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   */
  public function setSrcMask( $name )
  {
    
    $this->srcSource  = $name;
    $this->srcMask    = $name;

  }//end public function setSrcMask */


  /**
   * @param LibGenfTreeNodeManagement $management
   */
  public function setManagement( $management )
  {
    $this->management  = $management;

  }//end public function setManagement */

  /**
   * @param string $category
   */
  public function setCategory( $category )
  {
    $this->category = $category;
  }//end public function setCategory */

  /**
   * @param string $category
   */
  public function category( $category = null )
  {

    if( $category )
    {
      return ($this->category == $category);
    }
    else
    {
      return $this->category;
    }

  }//end public function category */

/*//////////////////////////////////////////////////////////////////////////////
// events
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $key
   */
  public function event( $key )
  {

    if( isset( $this->node->events->$key ) )
    {
      return trim($this->node->events->$key);
    }
    else
    {
      return null;
    }

  }//end public function event */

/*//////////////////////////////////////////////////////////////////////////////
// ui
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param SimpleXmlElement $uiNode
   * @param boolean $isUiNode
   */
  public function setUi( $uiNode, $isUiNode = false )
  {

    if( $isUiNode )
    {
      $this->ui         = $uiNode;
      
      if( $this->relation( Bdl::MANY_TO_MANY ) )
      {
        $this->ui->management = $this->connectionManagement();
      }
      else
      {
        $this->ui->management = $this->targetManagement();
      }
      
    }
    else 
    {
      $uiClassName      = $this->builder->getNodeClass( 'UiReference' );
      $this->ui         = new $uiClassName( $uiNode );
      
      if( $this->relation( Bdl::MANY_TO_MANY ) )
      {
        $this->ui->management = $this->connectionManagement();
        $mgmtUi = $this->connectionManagement()->getUi();
      }
      else
      {
        $this->ui->management = $this->targetManagement();
        $mgmtUi = $this->targetManagement()->getUi();
      }
        
      $this->ui->setFallback( $mgmtUi );
      
    }
    
  }//end public function setUi */
  
  
////////////////////////////////////////////////////////////////////////////////
// Referenz Parameter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $context
   * @return array<LibGenfTreeNodeReferenceParam>
   */
  public function getContextParams( $context )
  {
    
    if( !isset( $this->node->params->{$context} ) )
      return null;
      
    $params = array();
    
    foreach( $this->node->params->{$context}->param as $param )
    {
      $params[] = new LibGenfTreeNodeReferenceParam( $param, $this );
    }
    
    return $params;
    
  }//end public function getContextParams */



////////////////////////////////////////////////////////////////////////////////
// get form fields
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $context
   * @param string/array $category
   * @return array
   */
  public function getFormFields( $context, $category = null )
  {

    if( $formUi = $this->getMgmtFormUi( $context ) )
    {
      if( $fields = $formUi->getFields(  ) )
      {
        if( !$fields )
        {
           if( !is_null($fields) )
           {
             return array();
           }
        }
        else
        {
          if( $this->relation( Bdl::MANY_TO_MANY ) )
          {
            return $this->sortFormFieldsMany
            (
              $this->connectionManagement(),
              $this->targetManagement(),
              $fields,
              $context
            );
          }
          else
          {
            return $this->sortFormFields
            (
              $this->targetManagement(),
              $fields,
              $context
            );
          }
        }

      }
    }

    // looks like we have no ui node, fall back to the default handler

    $fields   = array();

    if( $this->relation( Bdl::MANY_TO_MANY ) )
    {

      $conMgmt = $this->connectionManagement();
      $fields  = $this->appendFormFields( $fields, $conMgmt, $context );

      if( $references = $conMgmt->getSingleRefs() )
      {
        // append all tables
        foreach( $references as $reference )
        {
          $targetMgmt = $reference->targetManagement();
          $fields     = $this->appendFormFields( $fields, $targetMgmt, $context, $reference );

        }//end foreach
      }

    }


    $trgtMgmt = $this->targetManagement();
    $fields   = $this->appendFormFields( $fields, $trgtMgmt, $context );

    if( $references = $trgtMgmt->getSingleRefs() )
    {
      // append all references
      foreach( $references as $reference )
      {

        $targetMgmt = $reference->targetManagement();
        $fields     = $this->appendFormFields( $fields, $targetMgmt, $context, $reference );

      }//end foreach
    }

    return $fields;

  }//end public function getFormFields */


  /**
   * @param string $context
   * @param array $useCategories
   *
   * @TODO implement the use of $useCategories
   */
  public function getFormCategories( $context , $useCategories = null )
  {

    $catNodes   = array();

    $categories = $this->extractEntityCategories();


    $categoryClass      = $this->builder->getNodeClass( 'CategoryManagement' );
    $contextCategories  = new TArray();

    foreach( $categories as $catName => $category )
    {

      $mgmtCategory = new $categoryClass
      (
        $category->getNode(),
        null,
        array( 'category' => $category  )
      );

      $contextCategories[$catName] = $mgmtCategory;

    }//end foreach

    return $contextCategories;

  }//end public function getFormCategories */

  /**
   * @return array
   */
  public function extractEntityCategories()
  {

    $categories = new TArray();

    if( $this->relation( Bdl::MANY_TO_MANY ) )
    {
      $conMgmt = $this->connectionManagement();

      if($cats = $conMgmt->entity->getCategories() )
      {

        foreach( $cats as $key => $obj )
        {
          if( !isset($categories[$key]) )
            $categories[$key] = $obj;

        }

      }

      if( $references = $conMgmt->getSingleRefs() )
      {
        // append all tables
        foreach( $references as $reference )
        {

          $targetMgmt = $reference->targetManagement();

          if($entityCategories = $targetMgmt->entity->getCategories())
          {

            foreach( $entityCategories as $catName => $catObj )
            {

              if( !isset($categories[$catName]) )
                $categories[$catName] = $catObj;

            }

          }

        }//end foreach

      }//end if

    }//end if( $this->relation( Bdl::MANY_TO_MANY ) )


    $targetMgmt = $this->targetManagement();

    if($cats = $targetMgmt->entity->getCategories() )
    {

      foreach( $cats as $key => $obj )
      {
        if( !isset($categories[$key]) )
          $categories[$key] = $obj;

      }

    }

    if( $references = $targetMgmt->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        $rgtMgmt = $reference->targetManagement();

        if( $entityCategories = $rgtMgmt->entity->getCategories() )
        {

          foreach( $entityCategories as $catName => $catObj )
          {

            if( !isset($categories[$catName]) )
              $categories[$catName] = $catObj;

          }

        }

      }//end foreach

    }//end if


    return $categories;

  }//end public function extractEntityCategories */


  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param LibGenfTreeNodeReference $reference
   */
  protected function appendFormFields( $cols, $management, $context, $reference = null  )
  {

    if( $reference )
    {
      $mgmtKey = $reference->name->name;
    }
    else
    {
      $mgmtKey = $management->name->name;
    }

    foreach( $management->entity as $attribute )
    {

      $key        = $mgmtKey.'-'.$attribute->name->name;
      $attr       = new TContextAttribute( $attribute, $management );

      if($reference)
        $attr->ref = $reference;

      $cols[$key] = $attr;

    }

    return $cols;

  }//end public function appendFormFields */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array $fields
   * @param string $context
   *
   */
  protected function sortFormFields( $management, $fields, $context )
  {

    $cols   = array();
    $rowIds = array();

    $attribute  = $management->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $management, $context );
    $cols[$management->name->name.'-rowid']     = $attr;

    foreach( $fields as $field )
    {

      if( $attr = $field->getField() )
      {

        if( $field->ref )
        {
          $key = $field->ref->name->name.'-'.$attr->name->name;
        }
        else
        {
          $key = $attr->management->name->name.'-'.$attr->name->name;
        }

        $cols[$key] =  $attr;


      }
      else if( $src = $field->src()  )
      {

        // if($refMgmt = $management-> $this->builder->getRoot('Management')->getManagement( $src ))
        if( $refMgmt = $management->getReference( $src )  )
        {

          $targetMgmt = $refMgmt->targetManagement();
          $fieldName  = $field->fieldName();

          if( $refAttr = $targetMgmt->getField( $fieldName ) )
          {
            $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $key = $refMgmt->name->name.'-'.$fieldName;

            $cols[$key] =  $attr;

          }

        }

      }
      else
      {

        if( $attribute = $management->getField( $field->fieldName() ) )
        {
          $attr = new TContextAttribute( $attribute, $management, $context );
          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }

          $cols[$management->name->name.'-'.$attr->name->name]     =  $attr;
        }
      }
    }

    return $cols;

  }//end public function sortFormFields */


  /**
   * @param LibGenfTreeNodeManagement $conMgmt
   * @param LibGenfTreeNodeManagement $targetMgmt
   * @param array $fields
   * @param string $context
   */
  protected function sortFormFieldsMany( $conMgmt, $targetMgmt, $fields, $context )
  {

    $cols   = array();
    $rowIds = array();

    $attribute  = $conMgmt->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $conMgmt, $context );
    $cols[$conMgmt->name->name.'-rowid']     = $attr;

    $attribute  = $targetMgmt->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $targetMgmt, $context );
    $cols[$targetMgmt->name->name.'-rowid']  = $attr;

    foreach( $fields as $field )
    {

      if( $attr = $field->getField() )
      {

        if( $field->ref )
        {
          $key = $field->ref->name->name.'-'.$attr->name->name;
        }
        else
        {
          $key = $attr->management->name->name.'-'.$attr->name->name;
        }

        $cols[$key] =  $attr;

      }
      else
      {
        
        $src = $field->src();
        if( 'connection' == $field->refType() )
        {
          $cols = $this->searchModelFieldSource( $cols, $conMgmt, $field, $context, $src );
        }
        else
        {
          $cols = $this->searchModelFieldSource( $cols, $targetMgmt, $field, $context, $src );
        }

      }
    }

    return $cols;

  }//end public function sortFormFieldsMany */

  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param LibGenfTreeNodeUiFormField $field
   * @param string $context
   * @param string $src = null
   */
  protected function searchModelFieldSource( $cols, $management, $field, $context, $src )
  {

    if( $src )
    {
      
      if( $refMgmt = $management->getReference( $src )  )
      {

        $targetMgmt = $refMgmt->targetManagement();
        $fieldName  = $field->fieldName();

        if( $refAttr = $targetMgmt->getField( $fieldName ) )
        {
          $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }

          $key = $refMgmt->name->name.'-'.$fieldName;

          $cols[$key] =  $attr;
        }

      }
      elseif( $refAttr = $management->entity->getAttrByTarget($src) )
      {

        // can only be a one to one reference, here so we can choose always target
        $refMgmt    = $refAttr->targetManagement();

        $attr       = new TContextAttribute( $refAttr, $refMgmt, $context );
        $attr->ui   = $field;
        if( $fieldAction = $field->action() )
        {
          $attr->fieldAction = $fieldAction;
        }

        $cols[$refMgmt->name->name.'-'.$attr->name->name] =  $attr;

      }
      else
      {
        $this->builder->error('did not find field: '.$field->fieldName().' in '.$this );
      }
    }
    else
    {
      if( $attribute = $management->getField( $field->fieldName() ) )
      {
        $attr = new TContextAttribute( $attribute, $management, $context );
        if( $fieldAction = $field->action() )
        {
          $attr->fieldAction = $fieldAction;
        }

        $cols[$management->name->name.'-'.$attr->name->name]     =  $attr;
      }
    }

    return $cols;

  }//end protected function searchModelFieldNode */

/*//////////////////////////////////////////////////////////////////////////////
// listing fields
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $context
   * @param array $categories
   * @param array $additionalFields
   */
  public function getFields( $context, $categories = null, $additionalFields = array() )
  {

    if( is_null( $this->management ) )
    {
      return array( );
    }

    $management = $this->management;
    $targetMgmt = $this->targetManagement( );

    ///TODO CHECK IF STILL REQUIRED
    if( $this->relation( Bdl::MANY_TO_MANY ) )
    {
      $conManagement = $this->connectionManagement( );

      if( $listUi = $this->getListUi( $context ) )
      {

        if( $fields = $listUi->getFields(  ) )
        {
          $additionalFields = $this->extractColorFields( $conManagement, $listUi, $additionalFields );
          return $this->extractUiListingFieldsMany
          ( 
            $conManagement, 
            $targetMgmt, 
            $fields, 
            $context, 
            $additionalFields 
          );
        }
      }
      else if( $listUi = $conManagement->getListUi( $context ) )
      {
        
        if( $fields = $listUi->getFields(  ) )
        {
          $additionalFields = $this->extractColorFields( $conManagement, $listUi, $additionalFields );
          return $this->extractUiListingFieldsMany
          ( 
            $conManagement, 
            $targetMgmt, 
            $fields, 
            $context, 
            $additionalFields 
          );
        }
      }

    }
    else
    {

      if( $listUi = $this->getListUi( $context ) )
      {
        if( $fields = $listUi->getFields() )
        {
          $additionalFields = $this->extractColorFields( $targetMgmt, $listUi, $additionalFields );
          return $this->extractUiListingFields( $targetMgmt, $fields, $context, $additionalFields );
        }
      }
      else if( $listUi = $targetMgmt->getListUi( $context ) )
      {
        if( $fields = $listUi->getFields() )
        {
          $additionalFields = $this->extractColorFields( $targetMgmt, $listUi, $additionalFields );
          return $this->extractUiListingFields( $targetMgmt, $fields, $context, $additionalFields );
        }
      }

    }

    $cols   = array();
    
    $listUi = $this->getListUi( $context );

    if( $this->relation( Bdl::MANY_TO_MANY ) )
    {

      $conManagement  = $this->connectionManagement( );

      if( !$listUi )
        $listUi = $conManagement->getListUi( $context );
        
      if( $listUi )
        $additionalFields = $this->extractColorFields( $conManagement, $listUi, $additionalFields );
      
      $cols           = $this->appendContextFields
      ( 
        $cols, 
        $conManagement, 
        $context, 
        $categories, 
        $additionalFields 
      );

      if( $refs = $conManagement->getSingleRefs( ) )
      {
        // append all oneToOne references
        foreach( $refs as $ref )
        {

          // in manchen contexten kann eine referenz ignoriert werden
          if( $ref->exclude( $context ) )
            continue;

          if( !$innerTMgmt = $ref->targetManagement( ) )
            continue;

          $cols = $this->appendContextFields( $cols, $innerTMgmt, $context, $categories );

        }//end foreach
      }
    }
    else 
    {

      if( !$listUi )
        $listUi = $targetMgmt->getListUi( $context );
        
      if( $listUi )
        $additionalFields = $this->extractColorFields( $targetMgmt, $listUi, $additionalFields );
      
    }


    $cols  = $this->appendContextFields( $cols, $targetMgmt, $context, $categories, $additionalFields );

    if( $targetRefs = $targetMgmt->getSingleRefs( ) )
    {
      // append all tables
      foreach( $targetRefs as $targetRef )
      {

        // in manchen contexten kann eine referenz ignoriert werden
        if( $targetRef->exclude( $context ) )
          continue;

        if( !$innerTMgmt = $targetRef->targetManagement( ) )
          continue;

        $cols = $this->appendContextFields( $cols, $innerTMgmt, $context, $categories );

      }//end foreach
    }

    return $cols;

  }//end protected function getFields */
  
  /**
   * @param LibGenfTreeNodeManagement $management
   * @param LibGenfTreeNodeUiListing $listUi
   * @param array $additionalFields
   * 
   * @return $additionalFields
   */
  protected function extractColorFields( $management, $listUi, $additionalFields )
  {
    
    $colorSource = $listUi->getColorSource();
    
    // prüfen ob farbinformationen mitgejoint werden sollen
    if( $colorSource )
    {
      
      $colFieldName = $colorSource->getAttrField();
      
      if( !$attrSrc = $colorSource->getAttrSource()  )
      {
         $attrSrc = null;
      }
      
      $colRefField = $management->getField( $colFieldName, $attrSrc );
      
      if( !$colRefField )
      {
        $this->builder->error
        (
          "Requested Color Information over a nonexisting Field: {$colFieldName} "
            .$this->debugData().' '.__METHOD__
        );
      }
      else 
      {
        
        if( $targetMgmt = $colRefField->targetManagement( ) )
        {
        
          $targetKey  = $colRefField->targetKey( );
          
          if( $bgField = $colorSource->getBackgroundField() )
          {
            if( $targetMgmt->hasField( $bgField ) )
            {
              $additionalFields[$targetKey][$bgField] = $bgField;
            }
            else
            {
              $this->builder->error
              ( 
                "Missing Missing Background Color Field: {$bgField} "
                  .$this->debugData().' '.__METHOD__ 
              );
            }
          }
          
          if( $textField = $colorSource->getTextField() )
          {
            if( $targetMgmt->hasField( $textField ) )
            {
              $additionalFields[$targetKey][$textField] = $textField;
            }
            else
            {
              $this->builder->error
              ( 
                "Missing Missing Text Color Field: {$textField} "
                  .$this->debugData().' '.__METHOD__
              );
            }
          }
          
          if( $borderField = $colorSource->getBackgroundField() )
          {
            if( $targetMgmt->hasField( $borderField ) )
            {
              $additionalFields[$targetKey][$borderField] = $borderField;
            }
            else
            {
              $this->builder->error
              ( 
                "Missing Missing Border Color Field: {$borderField} "
                  .$this->debugData().' '.__METHOD__
              );
            }
          }

        }
        else 
        {
          $this->builder->error
          ( 
            "Missing the target Management for Color Information Field: {$colFieldName} "
              .$this->debugData().' '.__METHOD__
          );
        }
      }
      
    }
    
    return $additionalFields;
    
  }//end protected function extractColorFields */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array<LibGenfTreeNodeUiListField> $fields
   * @param string $context
   * @param array $additionalFields
   */
  protected function extractUiListingFields( $management, $fields, $context, $additionalFields = array()  )
  {

    $cols   = array();
    $rowIds = array();

    $attribute  = $management->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $management, $context );
    //$attr->ref  = $this;

    $cols[$management->name->name.'-rowid']  = $attr;
    
    if( $additionalFields )
    {

      foreach( $additionalFields as $source => $addFields  )
      {
        
        foreach( $addFields as $addField )
        {
          
          if( !$attribute  = $management->getField( $addField, $source ) )
          {
            $this->builder->error
            ( 
              "Tried to append a nonexisting field {$addField} in ".$this->debugData().Debug::backtrace() 
            );
            continue;
            //throw new LibGenf_Exception( "Tried to append a nonexisting field {$addField} in ".$this );
          }
          
          if( $attribute->ref )
          {
            $key = $attribute->ref->name->name.'-'.$attribute->name->name;
          }
          else if( $attribute->namespace )
          {
            $key = $attribute->namespace->name->name.'-'.$attribute->name->name;
          }
          else
          {
            $key = $attribute->management->name->name.'-'.$attribute->name->name;
          }
          
          $attribute->variante = 'additional';
      
          $cols[$key]  = $attribute;
        }
        
      }      
    }

    foreach( $fields as $field )
    {
      // check if the field is on the local management or on an reference
      if( $src = $field->src( )  )
      {

        // src is a reference key to a reference on the active management
        if( $refReference = $management->getReference( $src ) )
        {

          // can only be a one to one reference, here so we can choose always target
          $refMgmt    = $refReference->targetManagement( );

          if( $refAttr = $refMgmt->getField( $field->fieldName( ) ) )
          {

            $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
            if( $fieldAction = $field->action( )  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $attr->ref = $refReference;

            $cols[$refMgmt->name->name.'-'.$attr->name->name]     =  $attr;
            
          }
          else
          {
            $this->error( 'did not find a attribute '.$field->fieldName().' on management '.$refMgmt->name->name );
          }

        }
        elseif( $refAttr = $management->entity->getAttrByTarget( $src ) )
        {

          // can only be a one to one reference, here so we can choose always target
          $refMgmt    = $refAttr->targetManagement( );

          $attr       = new TContextAttribute( $refAttr, $refMgmt, $context );
          $attr->ui   = $field;
          if( $fieldAction = $field->action( ) )
          {
            $attr->fieldAction = $fieldAction;
          }

          $cols[$refMgmt->name->name.'-'.$attr->name->name] =  $attr;

        }
        else
        {
          $this->error( 'did not find a reference '.$src.' on management '.$management->name->name );
        }

      }
      else
      {
        
        $fieldName = $field->fieldName();

        if( $attribute = $management->getField( $fieldName ) )
        {
          
          $attr = new TContextAttribute( $attribute, $management, $context );
          if( $fieldAction = $field->action( )  )
          {
            $attr->fieldAction = $fieldAction;
          }

          //$attr->ref = $this;
          $cols[$management->name->name.'-'.$attr->name->name]     =  $attr;

        }//end if
        else
        {
          $this->error( 'Did not find a Attribute '.$fieldName.' on management '.$management->name->name.' ref '.$this->debugData() );
        }

      }//end else

    }//end foreach

    return $cols;

  }//end public function extractUiListingFields */


  /**
   * @param LibGenfTreeNodeManagement $conMgmt
   * @param LibGenfTreeNodeManagement $targetMgmt
   * @param array $fields
   * @param string $context
   * @param array $additionalFields zusätzliche felder hinzufügen, bzw dafür sorgen, dass diese auf jeden fall vorhanden sind
   */
  protected function extractUiListingFieldsMany( $conMgmt, $targetMgmt, $fields, $context, $additionalFields = array()  )
  {

    $cols   = array();
    $rowIds = array();

    $attribute  = $conMgmt->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $conMgmt, $context );
     //$attr->ref  = $this;
    $cols[$conMgmt->name->name.'-rowid']  = $attr;
    
    if( $additionalFields  )
    {
      
      Debug::console( "\$additionalFields in ".$this->debugData() );
      
      foreach( $additionalFields as $addField )
      {
        if( !$attribute  = $conMgmt->getField( $addField ) )
        {
          $this->builder->error
          ( 
            "Tried to append a nonexisting field {$addField} in "
              .$this->debugData().Debug::backtrace() 
          );
          continue;
          //throw new LibGenf_Exception( "Thried to append a nonexisting field {$addField} ".$this );
        }
        
        $attr       = new TContextAttribute( $attribute, $conMgmt, $context );
         //$attr->ref  = $this;
        $cols[$conMgmt->name->name.'-'.$addField]  = $attr;
      }
    }    


    $attribute  = $targetMgmt->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $targetMgmt, $context );
    //$attr->ref  = $this;

    $cols[$targetMgmt->name->name.'-rowid']  = $attr;

    foreach( $fields as $field )
    {
      
      /* @var $field LibGenfTreeNodeUiListField */
      
      // check if the field is on the local management or on an reference
      if( $src = $field->reference()  )
      {

        if( 'target' == $field->refType() )
        {
          // src is a reference key to a reference on the active management
          if( $targetMgmt->referenceExists( $src ) )
          {
            
            $refReference = $targetMgmt->getReference( $src );

            // can only be a one to one reference, here so we can choose always target
            $refMgmt    = $refReference->targetManagement();

            if( $refAttr = $refMgmt->getField( $field->fieldName() ) )
            {

              $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
              $attr->ui = $field;
              if( $fieldAction = $field->action()  )
              {
                $attr->fieldAction = $fieldAction;
              }

              $attr->ref = $refReference;

              $cols[$refMgmt->name->name.'-'.$attr->name->name]     =  $attr;
            }
            else
            {
              $this->error
              ( 
                'did not find a attribute '.$field->fieldName().' on management '.$refMgmt->name->name 
              );
            }

          }
          elseif( $refAttr = $targetMgmt->entity->getAttrByTarget( $src ) )
          {

            // can only be a one to one reference, here so we can choose always target
            $refMgmt    = $refAttr->targetManagement();

            $attr       = new TContextAttribute( $refAttr, $refMgmt, $context );
            $attr->ui   = $field;
            if( $fieldAction = $field->action() )
            {
              $attr->fieldAction = $fieldAction;
            }

            $cols[$refMgmt->name->name.'-'.$attr->name->name] =  $attr;

          }
          else
          {
            $this->error
            ( 
              'did not find a reference '.$src.' on management '.$conMgmt->name->name 
            );
          }
        }
        else
        {
          // src is a reference key to a reference on the active management
          if( $conMgmt->referenceExists( $src ) )
          {
            
            $refReference = $conMgmt->getReference( $src );
            
            // can only be a one to one reference, here so we can choose always target
            $refMgmt    = $refReference->targetManagement();

            if( $refAttr = $refMgmt->getField( $field->fieldName() ) )
            {

              $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
              $attr->ui = $field;
              if( $fieldAction = $field->action()  )
              {
                $attr->fieldAction = $fieldAction;
              }

              $attr->ref = $refReference;

              $cols[$refMgmt->name->name.'-'.$attr->name->name]     =  $attr;
            }
            else
            {
              $this->error( 'did not find a attribute '.$field->fieldName().' on management '.$refMgmt->name->name );
            }

          }
          elseif( $refAttr = $conMgmt->entity->getAttrByTarget( $src ) )
          {

            // can only be a one to one reference, here so we can choose always target
            $refMgmt    = $refAttr->targetManagement();

            $attr       = new TContextAttribute( $refAttr, $refMgmt, $context );
            $attr->ui   = $field;
            if( $fieldAction = $field->action() )
            {
              $attr->fieldAction = $fieldAction;
            }

            $cols[$refMgmt->name->name.'-'.$attr->name->name] =  $attr;

          }
          else
          {
            $this->error( 'did not find a reference '.$src.' on management '.$conMgmt->name->name );
          }
        }

      }
      else
      {

        if( 'target' == $field->refType() )
        {
          
          /* @var $field TContextAttribute */
          if( $attribute = $targetMgmt->getField( $field->fieldName() ) )
          {
            $attr = new TContextAttribute( $attribute, $targetMgmt, $context );
            $attr->ui = $field;
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            //$attr->ref = $this;
            $cols[$targetMgmt->name->name.'-'.$attr->name->name]     =  $attr;
            
            
            if( $targetFieldKey = $field->displayField() )
            {
              
              $fRefTargetMgmt = $attribute->attribute->targetManagement();
              
              $attr->fieldName = $targetFieldKey;
              
              if( $fRefTargetMgmt && $fieldAttrNode = $fRefTargetMgmt->getField( $targetFieldKey ) )
              {
                $fieldAttr = new TContextAttribute( $fieldAttrNode, $fRefTargetMgmt, $context );
                $fieldAttr->ui = $field;
                if( $fieldAction = $field->action()  )
                {
                  $attr->fieldAction = $fieldAction;
                }
    
                //$attr->ref = $this;
                $cols[$fRefTargetMgmt->name->name.'-'.$fieldAttrNode->name->name]     =  $fieldAttr;
    
              }//end if
              
            }

          }//end if
          else 
          {
            $this->builder->dumpError( 'Missing Attribute '.$field->fieldName() );
          }
 
        }
        else
        {
          
          if( $attribute = $conMgmt->getField( $field->fieldName() ) )
          {
            $attr = new TContextAttribute( $attribute, $conMgmt, $context );
            $attr->ui = $field;
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            //$attr->ref = $this;

            $cols[$conMgmt->name->name.'-'.$attr->name->name]     =  $attr;

          }//end if

        }

      }//end else

    }//end foreach

    return $cols;

  }//end protected function extractUiListingFieldsMany */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array $fields
   * @param string $context
   */
  protected function extractReferenceListingCols( $management, $fields, $context  )
  {

    $cols   = array();
    $rowIds = array();

    $attribute  = $management->getField( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $management, $context );
    $attr->ref  = $this;
    $cols[]     = $attr;

    foreach( $fields as $field )
    {
      if( $src = $field->src()  )
      {
        if($refRef = $management->getReference( $src ))
        {

          $refMgmt = $refRef->targetManagement();

          if($refAttr = $refMgmt->getField( $field->fieldName() ))
          {
            $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $attr->ref = $refRef;
            $cols[]    =  $attr;
          }

        }
        else if( $refAttr = $management->entity->getAttrByTarget( $src ) )
        {

          $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }

          $cols[]    =  $attr;

        }
        else
        {
          $this->builder->error('missing field: '.$field->fieldName().' for source: '.$src );
        }
      }
      else
      {
        if( $attribute = $management->getField( $field->fieldName() ) )
        {
          $attr = new TContextAttribute( $attribute, $management, $context );
          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }
          $attr->ref  = $this;

          $cols[]     =  $attr;
        }
      }
    }

    return $cols;

  }//end public function extractReferenceListingCols */


  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param array $categories
   * @param array $additionalFields zusätzliche felder anfordern
   */
  protected function appendContextFields( $cols, $management, $context, $categories = null, $additionalFields = array()  )
  {

    foreach( $management->entity as $attribute )
    {
      
      if( $attribute->name->name == 'rowid'  )
      {
        $attr       = new TContextAttribute( $attribute, $management );
        //$attr->ref  = $this;
        $cols[$management->name->name.'-'.'rowid'] = $attr;
        
      }
      else if
      ( 
        $additionalFields 
          && isset($additionalFields[$management->name->name]) 
          && in_array( $attribute->name->name, $additionalFields[$management->name->name] ) 
      )
      {
        $attr       = new TContextAttribute( $attribute, $management );
        //$attr->ref  = $this;
        $cols[$management->name->name.'-'.$attr->name->name] = $attr;
        
      }
      // check if field type exists
      else if( $attribute->field( $context ) )
      {
        $attr       = new TContextAttribute( $attribute, $management );
        //$attr->ref  = $this;
        $cols[$management->name->name.'-'.$attr->name->name] = $attr;
        
      }
      
    }

    return $cols;

  }//end public function appendContextFields */

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

    $management = $this->management;
    $targetMgmt = $this->targetManagement();

    /*
    if( $ui = $this->getUi() )
    {
      if( $fields = $ui->getSearchFields( $context ) )
      {
        return $this->extractSearchFields( $targetMgmt, $fields,  $context );
      }
    }
    */

    $cols   = array();

    if( $this->relation( Bdl::MANY_TO_MANY ) )
    {
      $conMgmt  = $this->connectionManagement();
      $cols     = $this->appendSearchFields( $cols, $conMgmt, $context, $free );

      if( $references = $conMgmt->getSingleRefs() )
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
          $cols      = $this->appendSearchFields( $cols, $innerMgmt, $context, $free );

        }//end foreach
      }
    }

    // now append the target cols

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

  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   */
  protected function appendSearchFields( $cols, $management, $context, $free  )
  {

    foreach( $management->entity as $attribute )
    {

      if( $free )
      {
        if( !$attribute->searchFree( ) )
          continue;
      }
      else
      {
        if( !$attribute->search() )
        {
          continue;
        }
      }

      if( $attribute->name->name == 'rowid'  )
      {
        $attr       = new TContextAttribute($attribute,$management);
        $attr->ref  = $this;
        if(!isset($cols[$management->name->name]))
          $cols[$management->name->name] = array();

        $cols[$management->name->name][$attr->name->name] = $attr;
      }

      // check if field type exists
      else if( $attribute->field( $context ) )
      {
        $attr       = new TContextAttribute($attribute,$management);
        $attr->ref  = $this;

        if(!isset($cols[$management->name->name]))
          $cols[$management->name->name] = array();

        $cols[$management->name->name][$attr->name->name] = $attr;
      }
    }

    return $cols;

  }//end public function appendSearchFields */

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

    $targetManagement = $this->targetManagement();
    $nameTarget       = $targetManagement->name;

    $targetId         = $this->targetId();
    $targetRefId      = $this->targetRefId();

    $tables           = new TTabJoin();

    if( $this->relation(Bdl::MANY_TO_MANY) )
    {
      $conManagement  = $this->connectionManagement();
      $conName        = $conManagement->name;

      $tables->table  = $conName->source;
      $tables->index[$tables->table] = true;

      // ref join
      $tables->joins[] = array
      (
        null,                     // join
        $conName->source,
        $targetId,
        $nameTarget->source,
        $targetRefId,
        null,                       // where
        null,  // alias
        'default reference join'
      );

      $tables->index[$nameTarget->source] = array
      (
        null,                       // join type
        $conName->source,
        $targetId,
        $nameTarget->source,
        $targetRefId,
        null,                       // where
        null, // alias
        'default reference join'
      );

      $this->appendAttributeReferenceTables( $tables, $conManagement, $context );

      // check if there are any references
      if( $references = $conManagement->getSingleRefs()  )
      {
        // else
        foreach( $references as $reference )
        {
          $this->appendReferenceTables( $tables, $reference, $context );
        }//end foreach
      }

    }
    else
    {
      $tables->table  = $nameTarget->source;
      $tables->index[$tables->table] = true;
    }


    $this->appendAttributeReferenceTables( $tables, $targetManagement, $context );

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


  /**
   * @param TTabJoin $tables
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   */
  protected function appendAttributeReferenceTables( $tables, $management, $context )
  {

    $name   = $management->getName();
    $entity = $management->getEntity();

    $code = '';

    foreach( $entity as $attribute )
    {
      // wenn keine quelle vorhanden ist oder eine bereits vorhandene referenz
      // verwendet werden soll weitermachen ref mappt auf vorhandenen oneTo* referenz

      $attrName   = $attribute->name();
      $entityName = $entity->name();

      // wenn eingebunden dann wird der join nochmal als referenz erstellt
      // daher wird dieser join hier ignoriert
      if( $attribute->isEmbeded() )
        continue;

      if( $paths = $attribute->sourcePath( $name, $context) )
      {
        foreach( $paths as $path )
        {

          $tables->joins[] = array
          (
            'left',                     // join
            trim($path['src']),
            trim($path['targetField']),
            trim($path['target']),
            trim($path['srcField']),
            null,                       // where
            trim($path['targetAlias']),  // alias
            'attribute reference'
          );

          $tables->index[trim($path['targetAlias'])] = array
          (
            'left',                     // join
            trim($path['src']),
            trim($path['targetField']),
            trim($path['target']),
            trim($path['srcField']),
            null,                       // where
            trim($path['targetAlias']),  // alias
            'attribute reference'
          );

        }//end foreach

      }//end if

    }//end foreach

    return $code;

  }//end protected function appendAttributeReferenceTables */

  /**
   * @param TTabJoin $tables
   * @param LibGenfTreeNodeReference $rRef
   * @param string $context
   */
  protected function appendReferenceTables( $tables, $rRef, $context )
  {

    // nur one to referenzen werden betrachtet
    if( !$rRef->relation( 'one' ) )
      return;

    $nameSrc      = $rRef->src();
    $nameTarget   = $rRef->target();

    $targetId     = $rRef->targetId( );
    $srcId        = $rRef->srcId( );

    if( $rRef->preSave( ) )
    {

      $tables->joins[] = array
      (
        'left',                     // join
        $nameSrc->source,
        $targetId,
        $nameTarget->source,
        $srcId,
        null,                       // where
        null,                       // alias
        'one to one pre save'    // comment
      );

      $tables->index[$nameTarget->source] = array
      (
        'left',                     // join
        $nameSrc->source,
        $targetId,
        $nameTarget->source,
        $srcId,
        null,                       // where
        null,                       // alias
        'one to one pre save'    // comment
      );

      //$code .= "    \$criteria->leftJoinOn( '".$nameTarget->source.".'.Db::PK, '".$nameSrc->source.".".$srcId."' ); //{$rRef->relation()} pre".NL;
    }
    else
    {

      $tables->joins[] = array
      (
        'right',                     // join
        $nameSrc->source,
        $srcId,
        $nameTarget->source,
        $targetId,
        null,                       // where
        null,                       // alias
        'one to one post save'   // comment
      );

      $tables->index[$nameTarget->source] = array
      (
        'right',                     // join
        $nameSrc->source,
        $srcId,
        $nameTarget->source,
        $targetId,
        null,                       // where
        null,                       // alias
        'one to one post save'   // comment
      );

      //$code .= "    \$criteria->rightJoinOn( '".$nameTarget->source.".".$srcId."', '".$nameSrc->source.".'.Db::PK ); //{$rRef->relation()} post".NL;
    }

    // parse the reference display fields with another table as target
    $this->appendAttributeReferenceTables( $tables, $rRef->targetManagement(), $context  );

  }//end protected function appendReferenceTables */


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @return array
   */
  public function getFieldSources( $context )
  {

    $targetManagement = $this->targetManagement();


    if( $this->relation(Bdl::MANY_TO_MANY) )
    {
      $conManagement = $this->connectionManagement();

      if( $ui = $this->getListUi( $context ) )
      {
        if( $fields = $ui->getFields(  ) )
        {
          return $this->extractFieldSourcesMany( $conManagement, $targetManagement, $fields, $context );
        }
      }

      if( $ui = $conManagement->getListUi( $context ) )
      {
        if( $fields = $ui->getFields(  ) )
        {
          return $this->extractFieldSourcesMany( $conManagement, $targetManagement, $fields,  $context );
        }
      }

    }
    else
    {

      if( $ui = $this->getListUi( $context ) )
      {
        if( $fields = $ui->getFields(  ) )
        {
          return $this->extractFieldSources( $targetManagement, $fields, $context );
        }
      }

      if( $ui = $targetManagement->getListUi( $context ) )
      {
        if( $fields = $ui->getFields(  ) )
        {
          return $this->extractFieldSources( $targetManagement, $fields,  $context );
        }
      }
    }


    $index   = array();

    if( $this->relation(Bdl::MANY_TO_MANY) )
    {

      $index          = $this->appendContextTables( $index, $conManagement, $context );
      $index[$targetManagement->name->source] = true;

      if( $references = $conManagement->getSingleRefs() )
      {
        // append all tables
        foreach( $references as $reference )
        {
          // in manchen contexten kann eine referenz ignoriert werden
          if( $reference->exclude($context) )
            continue;

          $innerMgmt = $reference->targetManagement();
          $index      = $this->appendRefContextTables( $index, $innerMgmt, $context );

        }//end foreach
      }
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

    return $index;

  }//end public function getFieldSources */


  /**
   * @param LibGenfTreeNodemanagement $management
   * @param array $fields
   * @param string $context
   */
  protected function extractFieldSources( $management, $fields, $context )
  {

    $name           = $management->getName();

    $tables         = new TTabJoin();
    $tables->table  = $name->source;
    $index          = array();

    foreach( $fields as $field )
    {

      if( $src = $field->src()  )
      {

        if($refRef = $management->getReference( $src ))
        {

          $refMgmt = $refRef->targetManagement();
          $index[$refMgmt->name->source] = true;

          if($refAttr = $refMgmt->getField( $field->fieldName() ))
          {
            if( $target = $refAttr->targetKey() )
            {
              $index[$target] = true;
            }
          }
        }
        else if($refAttr = $management->entity->getAttrByTarget( $src ))
        {

          $refMgmt      = $refAttr->targetManagement();
          $index[$refMgmt->name->source] = true;
          $index[$src]  = true;

        }

      }//end if( $src = $field->src()  )
      else
      {
        if( $attribute = $management->getField( $field->fieldName(),null, $this ) )
        {
          if( $target = $attribute->targetKey() )
          {
            $index[$target] = true;
          }
        }
      }

    }//end foreach

    return $index;

  }//end protected function extractFieldSources */


  /**
   * @param LibGenfTreeNodemanagement $conMgmt
   * @param LibGenfTreeNodemanagement $targetMgmt
   * @param array $fields
   * @param string $context
   */
  protected function extractFieldSourcesMany( $conMgmt, $targetMgmt, $fields, $context )
  {

    $name           = $conMgmt->getName();

    $tables         = new TTabJoin();
    $tables->table  = $name->source;
    $index          = array();

    $index[$targetMgmt->name->name] = true;

    foreach( $fields as $field )
    {

      if( $src = $field->src()  )
      {

        if( 'target' == $field->refType() )
        {
          if($refReference = $targetMgmt->getReference( $src ))
          {

            $refMgmt = $refReference->targetManagement();
            $index[$refMgmt->name->source] = true;

            if($refAttr = $refMgmt->getField( $field->fieldName() ))
            {
              if( $target = $refAttr->targetKey() )
              {
                $index[$target] = true;
              }
            }

          }
          else if($refAttr = $targetMgmt->entity->getAttrByTarget( $src ))
          {

            $refMgmt      = $refAttr->targetManagement();
            $index[$refMgmt->name->source] = true;
            $index[$src]  = true;

          }

        }
        else
        {

          if($refReference = $conMgmt->getReference( $src ))
          {
            $refMgmt = $refReference->targetManagement();
            $index[$refMgmt->name->source] = true;

            if($refAttr = $refMgmt->getField( $field->fieldName() ))
            {
              if( $target = $refAttr->targetKey() )
              {
                $index[$target] = true;
              }
            }
          }
          else if($refAttr = $conMgmt->entity->getAttrByTarget( $src ))
          {
            $refMgmt      = $refAttr->targetManagement();
            $index[$conMgmt->name->source] = true;
            $index[$src]  = true;
          }

        }

      }//end if( $src = $field->src()  )
      else
      {
        if( 'target' == $field->refType() )
        {
          if($attribute = $targetMgmt->getField( $field->fieldName() ))
          {
            if( $target = $attribute->targetKey() )
            {
              $index[$target] = true;
            }
          }
        }
        else
        {
          if($attribute = $conMgmt->getField( $field->fieldName() ))
          {
            if( $target = $attribute->targetKey() )
            {
              $index[$target] = true;
            }
          }
        }

      }

    }//end foreach

    return $index;

  }//end protected function extractFieldSourcesMany */

  /**
   * @param array $cols
   * @param LibGenfTreeNodeEntity $entity
   * @param string $context
   */
  protected function appendContextTables( $index, $management, $context  )
  {

    $tmp    = array();

    foreach( $management->entity as $attribute )
    {
      // check if field type exists
      if( $attribute->field( $context ) )
      {
        if( $targetKey = $attribute->targetKey( $context ) )
        {
          $tmp[] = $targetKey;
        }
      }
    }

    if( $tmp )
    {
      foreach( $tmp as $tmpKey )
      {
        $index[$tmpKey] = true;
      }
    }

    return $index;

  }//end public function appendContextTables */

 /**
   * @param array $cols
   * @param LibGenfTreeNodeEntity $entity
   * @param string $context
   */
  protected function appendRefContextTables( $index, $management, $context  )
  {

    $tmp    = array();
    $join   = false;

    foreach( $management->entity as $attribute )
    {
      // check if field type exists
      if( $attribute->field( $context ) )
      {
        if( $targetKey = $attribute->targetKey( $context ) )
        {
          $tmp[] = $targetKey;
        }

        $join = true;
      }
    }

    if( $join )
    {
      $index[$management->name->source] = true;

      if( $tmp )
      {

        foreach( $tmp as $tmpKey )
        {
          $index[$tmpKey] = true;
        }

      }

    }

    return $index;

  }//end public function appendRefContextTables */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $relation
   *
   */
  public function relation( $relation = null )
  {

    if(!$relation)
      return strtolower($this->node['relation']);

    if( is_string( $relation ) )
    {

      $relation = strtolower( $relation );

      if( Bdl::ONE == $relation )
        $relation   = array( Bdl::ONE_TO_ONE , Bdl::ONE_TO_MANY );
      else if( Bdl::MANY == $relation )
        $relation   = array(Bdl::MANY_TO_ONE , Bdl::MANY_TO_MANY);

    }

    if( is_array($relation) )
    {
      $lowerRel = strtolower($this->node['relation']);

      foreach( $relation as $rel  )
      {
        if( $lowerRel == strtolower($rel) )
          return true;
      }

      return false;

    }
    else
    {
      return (strtolower($this->node['relation']) == $relation);
    }

  }//end public function relation */


 /**
  * check the binding type of the reference
  * @param string $relation
  *
  */
  public function binding( $binding = null )
  {

    if(!$binding)
      return strtolower($this->node['binding']);
    else
      return strtolower($this->node['binding']) === strtolower($binding);

  }//end public function binding */

  /**
   * pre save is relevant to resolv dependencies on the references between
   * datasets
   *
   * if a references returns true on presave this means that the target reference
   * has to be saved befor the src entity
   *
   * @param SimpleXmlElement $ref
   * @return unknown
   */
  public function preSave(  )
  {

    // check if preSave is defined by the user
    if( isset( $this->node['base'] ) )
    {
      // if src is the base target has to be save first
      return ('src' === trim($this->node['base']));
    }

    if
    (
      // if the target maps to the rowid of the src, src has to be saved first
      !isset( $this->node->src['id'] )
        || '' === trim($this->node->src['id'])
        || $this->builder->rowidKey == trim($this->node->src['id'])
    )
    {
      return true;
    }
    else
    {
      return false;
    }

  }//end public function preSave */


  /**
   * check if this reference should be exclude from
   * handling under some specific circumstances
   *
   * Like exclude from table/ tree, always etc
   *
   * @param string $key
   * @return boolean
   */
  public function exclude( $key = null  )
  {

    if( $key )
    {
      return isset( $this->node->exclude->$key );
    }
    else
    {
      return isset( $this->node->exclude );
    }

  }//end public function exclude */

  /**
   * Enter description here...
   *
   * @param SimpleXmlElement $ref
   * @return string
   */
  public function getRefid( )
  {

    if( $this->preSave() )
      return $this->targetId();
    else
      return $this->srcId();

  }//end public function getRefid */

  /**
   * Enter description here...
   *
   * @param SimpleXmlElement $ref
   * @return string
   */
  public function srcId( )
  {

    if( $this->relation(Bdl::MANY_TO_MANY) )
    {
      if
      (
        !isset( $this->node->src['id'] )
          || '' !== trim( $this->node->src['id'] )
      )
      {
        return trim($this->node->src['id']);
      }
      else
      {
        Message::addWarning( 'Invalid Reference: srcId in a many to many reference' );
        Debug::console( 'Invalid Reference: srcId in a many to many reference' , $this->node );
        return null;
      }

    }
    else
    {
      if
      (
        isset( $this->node->src['id'] )
          && trim( $this->node->src['id'] ) != ''
      )
      {
        return trim($this->node->src['id']);
      }
      else
      {
        //Message::addWarning( 'Invalid Reference: srcId' );
        //Debug::console( 'Invalid Reference: srcId' , $this->node );
        return 'rowid';
      }

    }

  }//end public function srcId */


  /**
   * refid is only used in many to many relations at the moment,
   * there is no behaviour defined how it reacts in other relation types
   *
   * @param SimpleXmlElement $ref
   * @return string
   */
  public function srcRefId( )
  {

    if
    (
      ///DEPRECATED VERSION
      isset( $this->node->src['refId'] )
        && '' !== trim( $this->node->src['refId'] )
    )
    {
      return trim($this->node->src['refId']);
    }
    else if
    (
      isset( $this->node->src['ref_id'] )
        && '' !== trim( $this->node->src['ref_id'] )
    )
    {
      return trim($this->node->src['ref_id']);
    }
    else
    {
      return 'rowid';
    }

  }//end public function srcId */


  /**
   * Enter description here...
   *
   * @param SimpleXmlElement $ref
   * @return string
   */
  public function targetId( )
  {

    if
    (
      $this->modelNode
        && isset( $this->modelNode->target['id'] )
        && trim( $this->modelNode->target['id'] ) != ''
    )
    {
      return trim( $this->modelNode->target['id'] );
    }
    else if
    (
      isset( $this->node->target['id'] )
        && trim( $this->node->target['id'] ) != ''
    )
    {
      return trim($this->node->target['id']);
    }
    else
    {
      Error::report('Invalid Reference: targetId' , $this->node);
      //Debug::console( 'Invalid Reference: targetId' , $this->node );
      return null;
    }

  }//end public function targetId */


  /**
   * Enter description here...
   *
   * @param SimpleXmlElement $ref
   * @return string
   */
  public function targetRefId( )
  {

  
    if
    (
      $this->modelNode
        && isset( $this->modelNode->target['ref_id'] )
        && '' !== trim( $this->modelNode->target['ref_id'] )
    )
    {
      return trim($this->modelNode->target['ref_id']);
    }
    else if
    (
      /// DEPRECATED VERSION
      isset( $this->node->target['refId'] )
        && '' !== trim( $this->node->target['refId'] )
    )
    {
      return trim($this->node->target['refId']);
    }
    else if
    (
      isset( $this->node->target['ref_id'] )
        && '' !== trim( $this->node->target['ref_id'] )
    )
    {
      return trim($this->node->target['ref_id']);
    }
    else
    {
      return 'rowid';
    }

  }//end public function targetRefId */

  /**
   * @param boolean $asName
   * @return string
   */
  public function connection( $asName = true )
  {

    
    if( $this->connectionMask && trim($this->connectionMask) != '' )
    {
      if( !$asName )
        return trim($this->connectionMask);

      // check if original exists, if not return null
      if( !$mgmt = $this->root->getManagement( trim( $this->connectionMask ) ) )
        return null;

      return $mgmt->name;
    }
    else if
    ( 
      $this->modelNode
        && isset( $this->modelNode->connection['management'] ) 
        && trim($this->modelNode->connection['management']) != '' 
    )
    {
      if( !$asName )
        return trim( $this->modelNode->connection['management'] );

      // check if original exists, if not return null
      if( !$mgmt = $this->root->getManagement( trim($this->modelNode->connection['management'] ) ) )
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->node->connection['mask'] ) && trim($this->node->connection['mask']) != '' )
    {
      if(!$asName)
        return trim($this->node->connection['mask']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->node->connection['mask']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->node->connection['management'] ) && trim($this->node->connection['management']) != '' )
    {
      if(!$asName)
        return trim($this->node->connection['management']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->node->connection['management']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset($this->node->connection['name'])  )
    {
      if(!$asName)
        return trim($this->node->connection['name']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->connection['name']) ) )
        return null;

      return $mgmt->name;
    }
    else
    {

      $caller = Debug::getCaller();
      $caller .= Debug::getCaller(3);

      Message::addWarning( 'Invalid Reference: connection ' );
      Debug::console( 'Invalid Reference: connection'.$caller , $this->node );
      return null;
    }

  }//end public function connection */

  /**
   * @param boolean $asName
   * @return string
   */
  public function connectionSrc( $asName = true )
  {

  
    if( $this->modelNode && isset($this->modelNode->connection['src_management'])  )
    {
      if(!$asName)
        return trim($this->modelNode->connection['src_management']);

      if(!$mgmt = $this->root->getManagement( trim( $this->modelNode->connection['src_management']) ) )
        return null;

      return $mgmt->name;
    }
    else if( $this->modelNode &&  isset($this->modelNode->connection['src'])  )
    {
      if(!$asName)
        return trim($this->modelNode->connection['src']);

      if(!$mgmt = $this->root->getManagement( trim( $this->modelNode->connection['src']) ) )
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->node->connection['src_mask'] ) && trim($this->node->connection['src_mask']) != '' )
    {
      if(!$asName)
        return trim($this->node->connection['src_mask']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->node->connection['src_mask']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset($this->node->connection['src_management'])  )
    {
      if(!$asName)
        return trim($this->node->connection['src_management']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->connection['src_management']) ) )
        return null;

      return $mgmt->name;
    }
    else if( isset($this->node->connection['src'])  )
    {
      if(!$asName)
        return trim($this->node->connection['src']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->connection['src']) ) )
        return null;

      return $mgmt->name;
    }
    else
    {
      return $this->src($asName);
    }

  }//end public function connectionSrc */

  /**
   * @param boolean $asName
   * @return string
   */
  public function connectionTarget( $asName = true )
  {

    if( $this->modelNode && isset($this->modelNode->connection['target_management'])  )
    {
      if(!$asName)
        return trim($this->modelNode->connection['target_management']);

      if(!$mgmt = $this->root->getManagement( trim( $this->modelNode->connection['target_management']) ) )
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->modelNode->connection['target'] ) )
    {
      if( !$asName )
        return trim( $this->modelNode->connection['target'] );

      if( !$mgmt = $this->root->getManagement( trim( $this->modelNode->connection['target'] ) ) )
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->node->connection['target_mask'] ) && trim($this->node->connection['target_mask']) != '' )
    {
      if(!$asName)
        return trim($this->node->connection['target_mask']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->node->connection['target_mask']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset($this->node->connection['target_management'])  )
    {
      if(!$asName)
        return trim($this->node->connection['target_management']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->connection['target_management']) ) )
        return null;

      return $mgmt->name;
    }
    else if( isset($this->node->connection['target'])  )
    {
      if(!$asName)
        return trim($this->node->connection['target']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->connection['target']) ) )
        return null;

      return $mgmt->name;
    }
    else
    {
      return $this->target($asName);
    }

  }//end public function connectionTarget */

  /**
   * @return string
   */
  public function connectionKey(  )
  {
    return trim($this->node->connection['name']);
  }//end public function connectionKey */

  /**
   * @param boolean $asName
   * @return string
   */
  public function target( $asName = true )
  {

    if( $this->targetMask ) 
    {
      if( !$asName )
        return trim($this->targetMask);

      // check if original exists, if not return null
      if( !$mgmt = $this->root->getManagement( $this->targetMask ) )
        return null;
        
      return $mgmt->name;
    }
    else if
    ( 
      $this->modelNode 
        && isset( $this->modelNode->target['management'] ) 
        && trim($this->modelNode->target['management']) != '' 
    )
    {

      //Debug::console('target has mask '.trim($this->node->target['mask']));

      if(!$asName)
        return trim($this->modelNode->target['management']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->modelNode->target['management']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->modelNode->target['mask'] ) && trim($this->modelNode->target['mask']) != '' )
    {

      //Debug::console('target has mask '.trim($this->node->target['mask']));

      if(!$asName)
        return trim($this->node->target['mask']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->node->target['mask']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset( $this->node->target['management'] ) && trim($this->node->target['management']) != '' )
    {

      //Debug::console('target has mask '.trim($this->node->target['mask']));

      if(!$asName)
        return trim($this->node->target['management']);

      // check if original exists, if not return null
      if(!$mgmt = $this->root->getManagement( trim($this->node->target['management']) ))
        return null;

      return $mgmt->name;
    }
    else if( isset($this->node->target['name'])  )
    {
      if(!$asName)
        return trim($this->node->target['name']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->target['name']) ) )
        return null;

      return $mgmt->name;
    }
    else
    {
      Message::addWarning( 'Invalid Reference: target' );
      Debug::console( 'Invalid Reference: target' , $this->node->asXML() );
      return null;
    }

  }//end public function target */

  /**
   * @return string
   */
  public function targetKey(  )
  {
    
    return trim($this->node->target['name']);

  }//end public function targetKey */

  /**
   * @return string
   */
  public function inherit(  )
  {
    return
    (
      isset($this->node->target['inherit'])
        && 'true' == trim($this->node->target['inherit'])
    )
      ?true
      :false;

  }//end public function inherit */


  /**
   * @param boolean $asName
   * @return string
   */
  public function src( $asName = true  )
  {

    if( $this->srcMask )
    {
      
      if( !$asName )
        return $this->srcMask;

      // check if original exists, if not return null
      if( !$mgmt = $this->root->getManagement( $this->srcMask ) )
        return null;

      return $mgmt->name;
      
    }
    else if( isset( $this->node->src['mask'] ) && trim($this->node->src['mask']) != '' )
    {
      
      if( !$asName )
        return trim($this->node->src['mask']);

      // check if original exists, if not return null
      if( !$mgmt = $this->root->getManagement( trim($this->node->src['mask'] ) ) )
        return null;

      return $mgmt->name;
      
    }
    else if( isset( $this->node->src['management'] ) && trim($this->node->src['management']) != '' )
    {
      
      if( !$asName )
        return trim($this->node->src['management']);

      // check if original exists, if not return null
      if( !$mgmt = $this->root->getManagement( trim($this->node->src['management'] ) ) )
        return null;

      return $mgmt->name;
      
    }
    else if( isset($this->node->src['name'])  )
    {
      
      if( !$asName )
        return trim($this->node->src['name']);

      if(!$mgmt = $this->root->getManagement( trim( $this->node->src['name']) ) )
        return null;

      return $mgmt->name;
      
    }
    else
    {
      Message::addWarning( 'Invalid Reference: src' );
      Debug::console( 'Invalid Reference: src' , $this->node );
      return null;
    }

  }//end public function src */



/*//////////////////////////////////////////////////////////////////////////////
// Nodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return LibGenfTreeNodeManagement
   */
  public function targetManagement(  )
  {

    if( $this->targetMask )
    {
      return $this->root->getManagement( $this->targetMask );
    }
    else if( $this->modelNode && isset(  $this->modelNode->target['management'] )  )
    {
      return $this->root->getManagement( trim($this->modelNode->target['management']) );
    }
    else if( isset(  $this->node->target['mask'] )  )
    {
      return $this->root->getManagement( trim($this->node->target['mask']) );
    }
    else if( isset(  $this->node->target['management'] )  )
    {
      return $this->root->getManagement( trim($this->node->target['management']) );
    }
    else if( isset(  $this->node->target['name'] )  )
    {
      return $this->root->getManagement( trim($this->node->target['name']) );
    }
    else
    {
      Message::addWarning( 'Invalid Reference: target Node' );
      Debug::console( 'Invalid Reference: target Node' , $this->node );
      return null;
    }

  }//end public function targetManagement */

  /**
   * @param boolean $asName
   * @return LibGenfTreeNodeManagement
   */
  public function srcManagement(  )
  {

    if( $this->srcMask )
    {
      return $this->root->getManagement($this->srcMask);
    }
    else if( isset(  $this->node->src['mask'] )  )
    {
      return $this->root->getManagement(trim($this->node->src['mask']));
    }
    else if( isset(  $this->node->src['management'] )  )
    {
      return $this->root->getManagement(trim($this->node->src['management']));
    }
    else if( isset(  $this->node->src['name'] )  )
    {
      return $this->root->getManagement(trim($this->node->src['name']));
    }
    else
    {
      Message::addWarning( 'Invalid Reference: src Management' );
      Debug::console( 'Invalid Reference: src Management' , $this->node );
      return null;
    }

  }//end public function srcManagement */

  /**
   * @return LibGenfTreeNodeManagement
   */
  public function connectionManagement(  )
  {

    if( $this->connectionMask )
    {
      return $this->root->getManagement( $this->connectionMask );
    }    
    else if( $this->modelNode && isset( $this->node->modelNode['management'] ) )
    {
      return $this->root->getManagement( trim($this->modelNode->connection['management']) );
    }    
    else if( isset( $this->node->connection['mask'] ) )
    {
      return $this->root->getManagement( trim($this->node->connection['mask']) );
    }    
    else if( isset(  $this->node->connection['management'] ) )
    {
      return $this->root->getManagement( trim($this->node->connection['management']) );
    }
    else if( isset(  $this->node->connection['name'] ) )
    {
      return $this->root->getManagement( trim($this->node->connection['name']) );
    }
    else
    {
      $caller = Debug::getCaller();
      $caller .= Debug::getCaller(3);

      $this->builder->warn( 'Invalid Reference: Connection Management '.$caller );
      Debug::console( 'Invalid Reference: Connection Management called from'.$caller , $this->node );
      return null;
    }

  }//end public function connectionManagement */

////////////////////////////////////////////////////////////////////////////////
// Concept Logic
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param string $key
   * @return LibGenfTreeNode
   *
   */
  public function concept( $key )
  {
    
    $key = strtolower($key);

    // if no local concept
    if( !isset($this->concepts[$key]) )
    {
      // check for a global concept
      return $this->builder->globalConcept($key);
    }
    else
    {
      return $this->concepts[$key];
    }

  }//end public function concept
  
  /**
   * @param string $key
   * @return LibGenfTreeNode
   *
   */
  public function getConcept( $key )
  {
    
    $key = strtolower($key);

    // if no local concept
    if( !isset($this->concepts[$key]) )
    {
      // check for a global concept
      return $this->builder->globalConcept($key);
    }
    else
    {
      return $this->concepts[$key];
    }

  }//end public function getConcept

  /**
   * @param string $key
   * @return LibGenfTreeNode
   *
   */
  public function hasConcept( $key )
  {
    $key = strtolower($key);

    // if no local concept
    if( isset($this->concepts[$key]) )
    {
      return true;
    }
    
    if( $concept = $this->entity->concept($key) )
      return true;

    // check for a global concept
    if( is_null( $this->builder->globalConcept($key) ) )
      return false;
    else
      return true;


  }//end public function hasConcept

  /**
   * 
   * @return array
   */
  public function getConceptKeys()
  {

    if( is_array( $this->conceptKeys ) )
      return $this->conceptKeys;

    $this->conceptKeys = array_unique
    (
      array_merge
      (
        array_keys($this->concepts),
        $this->builder->globalConceptKeys()
      )
    );

    return $this->conceptKeys;

  }//end public function getConceptKeys */
  
////////////////////////////////////////////////////////////////////////////////
// implement base methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Prüfen ob es Filter für das Selection Element gibt
   * 
   * @return boolean
   */
  public function hasSelectionFilter( )
  {

    return isset( $this->node->selection_filter->check );

  }//end public function hasSelectionFilter */
  
  /**
   * Erfragen der Context filter.
   * Ein Context Filter wird nur eingebunden wenn er expliziet über die
   * URL angefragt wird.
   * 
   * Wird zb zum einschränken in selections verwendet, zb nur die Employees
   * anzeigen welche auch eine Verknüpfung zum aktuellen Projekt haben etc.
   * 
   * @return array<LibGenfTreeNodeFilterCheck>
   */
  public function getSelectionFilter( )
  {

    if( !isset( $this->node->selection_filter->check ) )
    {
      return null;
    }

    $checks = array();
    
    foreach( $this->node->selection_filter->check as $check  )
    {
      
      $className = 'LibGenfTreeNodeFilter_'.SParserString::subToCamelCase(trim($check['type']));
      
      if( !Webfrap::classExists( $className ) )
      {
        $this->builder->dumpError( "Requested noexisting filter ".$className );
        continue;
      }
      
      $checks[] = new $className( $check );
    }
    
    return $checks;
    
  }//end public function getSelectionFilter */

////////////////////////////////////////////////////////////////////////////////
// implement base methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNode#validate($node)
   */
  protected function validate( $node )
  {

    $this->valid  = true;
    $this->node   = $node;

  }//end protected function validate */



}//end class LibGenfTreeNodeReference

