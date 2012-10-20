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
 * Context Envelop to be able to sourround something with a context
 *
 * @package WebFrap
 * @subpackage WebFrap
 */
class TContextAttribute
  extends TContext
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Das Original Attribut
   * @var LibGenfTreeNodeAttribute
   */
  protected $object   = null;
  
  /**
   * Das Original Attribut
   * @var LibGenfTreeNodeAttribute
   */
  public $customNode   = null;

  /**
   * Das Original Attribut
   * @var LibGenfTreeNodeAttribute
   */
  public $attribute   = null;
  
  /**
   * Das Label des Attributes
   * @var string
   */
  public $label   = null;
  
  /**
   * Das Original Attribut
   * @var LibGenfTreeNodeUiListField
   */
  public $uiField   = null;

  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management  = null;
  
  /**
   * @var LibGenfTreeNodeManagement
   */
  public $target  = null;
  
  /**
   * @var LibGenfName
   */
  public $namespace  = null;

  /**
   * @var LibGenfTreeNodeReference
   */
  public $ref  = null;

  /**
   * @var LibGenfTreeNodeUiListField
   */
  public $ui  = null;
  
  /**
   * Custom UI Element type
   * 
   * wird verwendet um den UI Element type zu überschreiben
   * @var string
   */
  public $uiElementType = null;
  
  /**
   * Context UI Element
   * @var LibGenfTreeNodeUiElement
   */
  public $uiElement = null;

  /**
   * Der Kontext in dem auf das Attribut zugegriffen wird,
   * zb. create (form), edit (form), table (listenelement)
   * @var string
   */
  public $context     = null;

  /**
   * was this feld added implicit? if yes this field contains some imformation
   * why it was added, normaly the context or something like that
   * @var string
   */
  public $implicit     = null;

  /**
   * Zusätzliche Parameter
   * @var TArray
   */
  public $params      = null;

  /**
   * @var string
   */
  public $fieldAction = null;

  /**
   * @var string
   */
  public $fieldName   = null;
  
  /**
   * Der Name der Entity auf welcher sich das aktuelle field befindet
   * Wurde bei colsearch hinzugefügt
   * @var string
   */
  public $entityName   = null;
  
  /**
   * Der Original Name des Feldes
   * Ist dann wichtig, wenn der Name innerhalb eines contexts überschrieben wurde
   * @var string
   */
  public $origName   = null;
  
  /**
   * Der Original Table Key
   * @var string
   */
  public $origTableKey   = null;
  
  /**
   * Die Original Quelle
   * @var string
   */
  public $origTableSource   = null;
  
  /**
   * Alias für die Tabelle
   * @var string
   */
  public $tableAlias   = null;
  
  /**
   * Name der Source / der Tabelle im aktuellen Kontext
   * Eingeführt bei sort/order in den Tabellen Layouts
   * 
   * Prüfen ob redundant, wenn ja ist dieser Bezeichner der Momentan beste
   * und sollte für die anderen Usecases übernommen werden
   * 
   * @var string
   */
  public $contextKeyName   = null;

  /**
   * @var array
   */
  public $children    = array();

  /**
   * reference to the layout
   * @var int
   */
  public $layoutCol   = null;

  /**
   * Variante ist eine debug information die dazu genutzt wird die tatsächliche
   * herkunft eines attributes zu ermitteln
   * 
   * @var string
   */
  public $variante    = null;
  
  /**
   * Das Feld erzwingen egal welche metadaten dabei stehen
   * 
   * @var string
   */
  public $force    = false;
  
  /**
   * FLag ob dieses Feld in diesem Context Readonly ist
   * @var boolean
   */
  public $readOnly = false;
  
  /**
   * FLag ob dieses Feld in diesem Context required
   * @var boolean
   */
  public $required = false;
  
  /**
   * Die Maximale Länge eines Inputs
   * @var int
   */
  public $maxLength = null;
  
  /**
   * Die Minimale Länge eines Inputs
   * @var int
   */
  public $minLength = null;
  
  /**
   * Der Default Value für ein Feld
   * @var string
   */
  public $defaultValue = null;
  
  /**
   * Zugriffskontrolle auf das Attribute
   * @var LibGenfTreeNodeAttributeAccess
   */
  public $access = null;

////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Standard Konstruktor
   * Nimmt beliebig viele Elemente oder einen einzigen Array
   *
   * @param LibGenfTreeNodeAttribute $object
   * @param LibGenfTreeNodeManagement $management
   * @param int $context
   * @param LibGenfTreeNodeUiListField $field
   */
  public function __construct( $object, $management, $context = null, $field = null )
  {

    // wenn ein Contextattribute übergeben wird, dann wird es entpackt
    // das sollte eigentlich nicht passieren
    if( $object instanceof TContextAttribute )
    {
      $object = $object->unpack();
    }
    
    if( !$object )
    {
      $trace = Debug::backtrace();
      throw new LibGenf_Exception( 'Added noexisting object in '.$management." context {$context} ".$trace );
    }

    $this->object       = $object;
    $this->attribute    = $object;
    $this->label        = $object->label( 'en' );
    
    $this->uiElement    = $object->uiElement;
    
    $defValue = $object->defaultValue();
    
    if( !is_null( $defValue )  )
      $this->defaultValue = $defValue;
    
    $this->management   = $management;
    $this->context      = $context;

    $this->uiField      = $field;
    
    $this->readOnly     = $this->uiElement->readonly();
    $this->required     = $object->required()?'true':$this->uiElement->required();

    $this->params       = new TArray();

    if( $type = $this->object->fieldAction( $context ) )
    {
      $this->fieldAction  = $type;
    }


  }//end public function __construct */


  /**
   *
   * @param LibGenfEnvelopFormattribute $child
   * @return void
   */
  public function addChild( $child )
  {
    $uiPos = $child->uiElement->layout();

    if(! isset( $this->children[$uiPos->priority] ) )
      $this->children[$uiPos->priority] = array();

    $this->children[$uiPos->priority][] = $child;

  }//end public function addChild */

  /**
   * get all children sorted correct
   * @param string $relation
   * @return array
   */
  public function getChildren( $relation = null )
  {

    // return empty array
    if(!$this->children)
      return $this->children;

    ksort($this->children);

    $childs = array();

    foreach( $this->children as $subChilds )
    {
      ksort($subChilds);

      foreach( $subChilds as $child )
      {
        $childs[] = $child;
      }//end foreach

    }//end foreach

    return $childs;

  }//end public function getChildren */

  /**
   * the weight of this node is himeself + children
   * needed to get better size balance in the cols
   *
   * @todo use the uielement size for better calculations of the weight
   *
   * @return int
   */
  public function weight()
  {

    $size = 1;

    foreach( $this->children as $subChilds )
    {
      foreach($subChilds as $child)
      {
        $size += $child->weight();
      }
    }

    return $size;

  }//end public function weight */
  
////////////////////////////////////////////////////////////////////////////////
// Mapper code
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $context
   * @return boolean
   */
  public function sourceAlias( $context )
  {
    
    return $this->object->sourceAlias( $context );

  }//end public function sourceAlias */
  
  /**
   * @lang de:
   * Abfragen der display eigenschaften eines Attributes
   *
   * @param string $context
   * @return string
   */
  public function sourceField( $context )
  {
    
    if( !is_null($this->fieldName) )
      return $this->fieldName;

    return $this->object->sourceField( $context );

  }//end public function sourceField */
  
  
////////////////////////////////////////////////////////////////////////////////
// Eigenschaften
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Abfragen ob das Feld in diesem Kontext readonly ist
   * @return boolean
   */
  public function isReadOnly( )
  {
    // optional
    return $this->readOnly;

  }//public function isReadOnly */

  /**
   * Abfragen ob das Feld in diesem Kontext required ist
   * @return boolean
   */
  public function isRequired( )
  {
    // optional
    return $this->required;

  }//public function isRequired */
  
  /**
   * @return int
   */
  public function minLength( )
  {
    // optional
    return $this->minLength;

  }//public function minLength */
  
  /**
   * @return int
   */
  public function maxLength( )
  {
    // optional
    return $this->maxLength;

  }//public function maxLength */
  
  /**
   * @return string
   */
  public function defaultValue( )
  {
    // optional
    return $this->defaultValue;

  }//public function defaultValue */
  
  /**
   * Abfragen des UI Elements
   * @return boolean
   */
  public function uiElement( )
  {
    // optional
    return $this->uiElement;

  }//public function uiElement */
  
  /**
   * Abfragen des UI Elements
   * @return boolean
   */
  public function getAccess( )
  {
    
    if( $this->access )
      return $this->access;
      
    return $this->attribute->getAccess();

  }//public function getAccess */
  
////////////////////////////////////////////////////////////////////////////////
// Mapper code
////////////////////////////////////////////////////////////////////////////////

  /**
   * 
   */
  public function getDebugDump()
  {

    $debugData = 'Attribute: '.$this->name();

    $debugData .= ' FieldName:  '.$this->fieldName;

    $debugData .= ', Context:  '.$this->context;
    
    $debugData .= ', Label:  '.$this->label;
    
    
    if( $this->context )
    {
      $debugData .= ', sourceField:  '.$this->sourceField( $this->context );
      $debugData .= ', sourceAlias:  '.$this->sourceAlias( $this->context );
    }
    
    
    if( $this->namespace )
      $debugData .= ', Namespace:  '.$this->namespace;
      
    if( $this->entityName )
      $debugData .= ', entityName:  '.$this->entityName;
      
    if( $this->origName )
      $debugData .= ', origName:  '.$this->origName;
      
    if( $this->origTableKey )
      $debugData .= ', origTableKey:  '.$this->origTableKey;

    if( $this->origTableSource )
      $debugData .= ', origTableSource:  '.$this->origTableSource;
  
    if( $this->tableAlias )
      $debugData .= ', tableAlias:  '.$this->tableAlias;
      
 
    if( $this->defaultValue )
      $debugData .= ', Def Value:  '.$this->label;
      
    if( $this->fieldAction )
      $debugData .= ', Field Action:  '.$this->fieldAction;
      
    if( $this->variante )
      $debugData .= ', Variante:  '.$this->variante;

    //$this->uiField      = $field;
    
    if( $this->readOnly  )
      $debugData .= ', is readonly ';
      
    if( $this->required  )
      $debugData .= ', is required ';
      
    if( $this->layoutCol  )
      $debugData .= ', Layout '.$this->layoutCol->asXml() ;


    if( $this->uiField )
    {
      $dbgData = $this->uiField->getDebugDump();
      $debugData .= ' uiField: '.$dbgData;
    }
      
    if( $this->management )
    {
      $dbgData = $this->management->getDebugDump();
      $debugData .= ' Mgmt: '.$dbgData[1];
    }
    
    if( $this->target )
    {
      $dbgData = $this->target->getDebugDump();
      $debugData .= ' Target: '.$dbgData[1];
    }
    
    if( $this->ref )
    {
      $dbgData = $this->ref->getDebugDump();
      $debugData .= ' Ref: '.$dbgData[1];
    }

    if( $this->customNode )
    {
      $debugData .= ' Custom: '.$this->customNode->getDebugDump();
    }

    if( $this->attribute )
    {
      $attrDbg = $this->attribute->getDebugDump();
      $debugData .= ' AttrDbg: '.$attrDbg[1];
    }
    
    return $debugData;
    
  }//end public function getDebugDump */

}//end class TContextAttribute

