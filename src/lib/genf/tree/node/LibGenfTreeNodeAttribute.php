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
class LibGenfTreeNodeAttribute
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * Die Entity auf dem Das Attribute zu finden ist
   * @var LibGenfTreeNodeEntity
   */
  public $entity          = null;

  /**
   *  backlink to the activ management context
   * @var LibGenfTreeNodeManagement
   */
  public $management      = null;

  /**
   * Wird gesetzt wenn das Attribute?
   * @var LibGenfTreeNodeReference
   *
   * @todo Prüfen ob diese Attribute hier benötigt wird, sein soll
   */
  public $ref             = null;

  /**
   * das standard UI Element für das Attribut
   * @var LibGenfTreeNodeUiElement
   */
  public $uiElement       = null;

  /**
   *
   * @var LibGenfTreeNodeSemantic
   */
  public $semantic       = null;

  /**
   *
   * @var LibGenfTreeNodeFk
   */
  public $fk              = null;


////////////////////////////////////////////////////////////////////////////////
// protected attributes, mainly for caching
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var string
   */
  protected $identifier   = null;

  /**
   * string with the main category of the attribute
   * @var string
   */
  protected $mainCategory = null;

  /**
   * list with all categories
   * @var array
   */
  protected $categories   = array();

  /**
   *
   * @var string
   */
  protected $dbType       = null;

  /**
   *
   * @var array
   */
  protected $firstShift   = array('id','m','flag');

  /**
   *
   * @var array
   */
  protected $displayAlias   = array
  (
    'table'       =>  array
    (
      'listing'
    ),
    'tree'        =>  array
    (
      'listing'
    ),
    'blocklisting'  => array
    (
      'listing'
    ),
    'treetable'     => array
    (
      'listing'
    ),
    'list'          => array
    (
      'listing'
    ),
    'edit'          => array
    (
      'crud'
    ),
    'create'          => array
    (
      'crud'
    ),
    'show'          => array
    (
      'crud'
    ),
  );


////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function __toString()
  {
    return 'Entity: '.$this->entity->name->name.' Attribute: '.$this->name->name;
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return LibGenfTreeNodeAttributeSemantic
   */
  public function getSemantic()
  {
    return $this->semantic;
  }//end public function getSemantic */

  /**
   * @return LibGenfTreeNodeAttributeAccess
   */
  public function getAccess()
  {

    if( !isset( $this->node->access ) )
      return null;

    $classname   = $this->builder->getNodeClass( 'AttributeAccess' );

    return new $classname( $this->node->access );

  }//end public function getAccess */

  /**
   *
   * @return string
   */
  public function name( $name = null )
  {

    if( $name )
    {
      return $name == (string)$this->node['name'];
    }
    else
    {
      return (string)$this->node['name'];
    }

  }//end public function name */

  /**
   * Name des Attributs als CamelCase Name
   * @return string
   */
  public function ccName( $name = null )
  {

    return SParserString::subToCamelCase((string)$this->node['name'],true) ;

  }//end public function ccName */

  /**
   * check if the attribute has a target, and if yes return it
   * @return string
   */
  public function target( )
  {

    return isset($this->node['target'])
      ? (string)$this->node['target']
      : null;

  }//end public function target */

  /**
   * Abfragen ob expliziet ein Index auf dieses Attribut gelegt wurde
   * @return string
   */
  public function index( )
  {

    return isset($this->node['index'])
      ? (string)$this->node['index']
      : null;

  }//end public function index */

  /**
   * check if the attribute has a target, and if yes return it
   * @return string
   */
  public function refName( $key = null )
  {

    if( $key )
    {
      return isset($this->node['ref_name'])
        ? (boolean)((string)$this->node['ref_name'] == $key )
        : false;
    }
    else
    {
      return isset($this->node['ref_name'])
        ? (string)$this->node['ref_name']
        : null;
    }

  }//end public function refName */

  /**
   * check if the attribute has a target, and if yes return it
   * @return string
   */
  public function targetField( )
  {

    return isset($this->node['target_field'])
      ? (string)$this->node['target_field']
      : null;

  }//end public function target */


  /**
   * check if the attribute has a target, and if yes return it
   * @return LibGenfTreeNodeManagement
   */
  public function targetManagement( )
  {

    if( !isset($this->node['target']) )
      return null;

    return $this->builder->getManagement(trim($this->node['target']));

  }//end public function targetManagement */

  /**
   * check if the attribute has a target, and if yes return it
   * @return string
   */
  public function targetKey( )
  {

    if( isset($this->node->embed['name']) )
      return trim($this->node->embed['name']);

    if( isset($this->node->embed['as']) )
      return trim($this->node->embed['as']);
    
    if( isset($this->node['target_alias']) )
      return trim($this->node['target_alias']);
      
    if( isset($this->node['as']) )
      return trim($this->node['as']);
  
    return isset($this->node['target'])
      ? (string)$this->node['target']
      : null;

  }//end public function target */

  /**
   * @param string $name
   * @param string $context
   */
  public function targetPath( $name, $context )
  {

    // if there is a target, use it as target
    if(!isset( $this->node['target']) )
      return null;

    /*
    $src          = 'src';
    $srcField     = 'srcField';
    $target       = 'target';
    $targetField  = 'targetField';
    $left         = 'left';
    */

    $path = array();

    $path['type']     = 'left';
    $path['src']      = $name->source;

    if( isset($this->node['target_field'])  )
    {
      $path['srcField'] = trim($this->node['target_field']);
    }
    else
    {
      $path['srcField'] = $this->builder->rowidKey;
    }


    $display = false;

    $target = '';

    // if there is a target, use it as target
    $target = trim($this->node['target']);

    // if the display has a src replace the target with the display source
    if( isset( $this->node->display['src']) )
    {
      $target   = trim( $this->node->display['src'] );
      $display  = true;
    }

    // if there is a contect specific type use this
    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      if( isset( $tmpNode['src']) )
      {
        $target   = trim( $tmpNode['src'] );
        $display  = true;
      }
    }

    // ok now we have the target
    $path['target'] = $target;

    if( $display )
    {
      // if there is an alias for display use this alias
      if(isset( $this->node->display['alias']) )
        $target = trim($this->node->display['alias'] );

      if( isset( $tmpNode['alias']) )
        $target = trim( $tmpNode['alias'] );
    }
    else
    {
      // if there is an alias overwrite the targetname
      if(isset( $this->node['target_alias']) )
        $target = trim($this->node['target_alias']);
    }


    $path['targetAlias'] = $target;
    $path['targetField'] = $this->name();

    $paths = array();
    $paths[] = $path;

    return $paths;

  }//end public function targetPath */


  /**
   * abfragen ob über das Attribute eine one to one referenz erstellt wird
   * @return boolean
   */
  public function isEmbeded( )
  {

    return isset( $this->node->embed  );

  }//end public function isEmbeded */

  /**
   *
   * @return boolean
   * @deprecated use isEmbeded
   */
  public function embeded()
  {
    return isset($this->node->embed);
  }//end public function embeded */

  /**
   * method to check if an attribute is embeded
   * @return string / null wenn nicht vorhanden
   */
  public function embededKey( )
  {

    if( isset($this->node->embed['name']) )
      return trim($this->node->embed['name']);

    if( isset($this->node->embed['as']) )
      return trim($this->node->embed['as']);

    return null;

  }//end public function embededKey */

  /**
   * check if the attribute has a fk, if yes return the object
   * @return string
   */
  public function fk(  $name = null )
  {

    if( $name )
    {
      if(  $name == $this->target() )
        return $this->fk;
      else
        return null;

    }

    return $this->fk;

  }//end public function target */

  /**
   * @return string
   */
  public function fullName()
  {
    return $this->entity->name->name.'-'.(string)$this->node['name'];
  }//end public function fullName */


  /**
   * @return string
   */
  public function identifier()
  {
    
    if( !$this->identifier )
      $this->identifier = SParserString::subToCamelCase( (string)$this->node['name']);

    return $this->identifier;
    
  }//end public function identifier */

  /**
   * @return LibGenfTreeNodeProcess
   */
  public function getProcess()
  {

    if( !isset( $this->node->process ) )
    {
      $processName = $this->entity->getProcessNameByAttribute( trim($this->node['name']) );
      
      if( !$processName )
      {
        return null;
      }
      else 
      {
        return $this->builder->getRoot( 'Process' )->getProcess( $processName );
      }
      
    }
      
    return $this->builder->getRoot( 'Process' )->getProcess( trim($this->node->process['name']) );
    
  }//end public function getProcess */




  /**
   * @interface
   * get the value of the validator
   * @return string the validator for the attribute
   *
   * @interface
   * comprare a given string if it equals to the validator
   * @param string $key
   * @return boolean
   *
   */
  public function validator( $key = null )
  {
    if( $key )
      return $key == trim($this->node['validator']);
    else
      return trim($this->node['validator']);

  }//end public function validator */

  /**
   * check if this attribute is required or not
   * @return boolean
   */
  public function required()
  {
    return ( isset($this->node['required']) &&  'true' == trim($this->node['required'])  )
      ? true
      : false;

  }//end public function required */

  /**
   *
   * @return boolean
   */
  public function unique()
  {
    return (isset($this->node->unique) )
      ? true
      : false;

  }//end public function unique */

  /**
   *
   * @return string
   */
  public function type( $key = null )
  {

    if( $key )
      return strtolower($key) == trim(strtolower($this->node['type']));
    else
      return trim(strtolower($this->node['type']));

  }//end public function type */

  /**
   *
   * @return string
   */
  public function hidden(  )
  {

    if( isset( $this->node->hidden ) )
      return true;
    elseif( $this->uiElement->type( 'hidden' ) )
      return true;
    else
      return false;

  }//end public function hidden */

  /**
   * Den eigentlichen Datenbanktypen abfragen.
   * Der Type muss nicht unbedingt der Datenbanktype sein.
   *
   * @return string
   */
  public function dbType( $key = null )
  {

    if( $this->dbType )
      return $this->dbType;

    $type = trim( strtolower($this->node['type']) );

    if( $type == 'text' && $this->size() )
      $type = 'varchar';

    if( $type == 'cname' )
      $type = 'varchar';

    if( $type == 'float' )
      $type = 'numeric';

    if( $type == 'decimal' )
      $type = 'numeric';

    if( $type == 'eid' )
      $type = 'bigint';

    if( $type == 'int' )
      $type = 'integer';

    if( $type == 'html' )
      $type = 'text';

    $this->dbType = $type;

    return $type;

  }//end public function type */

  /**
   *
   * @return string
   */
  public function isNumeric( )
  {

    $type = $this->dbType();

    return in_array( $type, array('smallint','int','bigint','integer','float','numeric')  );

  }//end public function is_numeric */

  /**
   *
   * @return string/boolean
   */
  public function sequence( )
  {

    if(!isset($this->node->sequence))
      return null;

    if( isset( $this->node->sequence['name'] ) )
      return trim($this->node->sequence['name']);

    return Db::SEQUENCE;

  }//end public function sequence */

  /**
   * @return string
   */
  public function defaultValue()
  {

    if( !isset($this->node->default) )
      return null;
    else
      return trim($this->node->default);

  }//end public function defaultValue */
  
  /**
   * Prüfen auf welches Feld der Default Wert auf der Target Tabelle mappt
   * Wenn nicht explizit angegeben wird der access_key angenommen, das dürfte
   * in 99,9% stimmen, birgt jedoch ein restrisiko für bugs, sollte kein
   * AccessKey auf dem Target definiert sein
   * 
   * @return string
   */
  public function defaultRefAttr()
  {
    
    ///TODO check ob target vorhanden ist und accesskey existiert

    if( !isset($this->node->default['attr']) )
      return 'access_key'; // wenn nicht vorhanden ist es der access_key
    else
      return trim($this->node->default['attr']);

  }//end public function defaultRefField */

  /**
   *
   * @return int
   */
  public function size()
  {

    $noSize = array('text','uuid');

    //if text size must be null
    if( in_array( $this->dbType, $noSize  ) )
      return '';

    return trim( $this->node['size'] );

  }//end public function size */

  /**
   *
   * @return int
   */
  public function minSize()
  {

    if( isset( $this->node['min_size'] ) )
      return trim( $this->node['min_size'] );

    return trim( $this->node['minSize'] );

  }//end public function minSize */

  /**
   *
   * @return int
   */
  public function maxSize()
  {

    if( isset( $this->node['max_size'] ) )
      return trim( $this->node['max_size'] );

    return trim( $this->node['maxSize'] );

  }//end public function maxSize */
  
  /**
   * @return float
   */
  public function stepSize()
  {

    if( isset( $this->node['step_size'] ) )
      return trim( $this->node['step_size'] );

    return '0.5';

  }//end public function stepSize */

  /**
   *
   * @return boolean
   */
  public function autocomplete()
  {
    ///TODO implement me
    //return false;

   return isset( $this->node->display->autocomplete );

  }//end public function autocomplete */

  /**
   * request the main category
   * @param boolean $asName = false return the name als Name Object instead of a string
   * @return string
   */
  public function mainCategory( $asName = false )
  {
    if(! $this->mainCategory )
      $this->mainCategory = (string)$this->node->categories['main'];

    if( $asName )
      return new LibGenfNameCategory($this->mainCategory);
    else
      return $this->mainCategory;


  }//end public function mainCategory */


  /**
   * request the main category
   * @param boolean $asName = false return the name als Name Object instead of a string
   * @return string
   */
  public function getSubCategories(  )
  {

    if( !isset( $this->node->categories->subcategory )  )
      return array();

    $subCats = array();

    foreach( $this->node->categories->subcategory as $subcategory )
    {
      $subCats[trim($subcategory['name'])] = new LibGenfTreeNodeSubcategory($subcategory);
    }

    return $subCats;


  }//end public function getSubCategories */

  /**
   * return all categories
   * @return array
   */
  public function allCategories()
  {
    if( !$this->categories )
    {
      $this->categories[(string)$this->node->categories['main']] = true;

      foreach( $this->node->categories->category as $cat )
      {
        $this->categories[trim($cat['name'])] = true;
      }
    }

    return $this->categories;

  }//end public function allCategories */

  /**
   * @param string $checkCat
   * @return boolean
   */
  public function inCategory( $checkCat )
  {

    $categories = $this->allCategories();

    if( is_array($checkCat) )
    {
      foreach( $checkCat as $check )
      {
        if( isset($categories[$check]) )
          return true;
      }
    }

    return false;

  }//end public function inCategory */


////////////////////////////////////////////////////////////////////////////////
// field getter
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * Enter description here ...
   * @param string $type
   */
  protected function getDisplayNode( $type )
  {

    if( !$this->node->display )
      return null;

    $tmpNode = null;

    // ok if the type not exists it's possible that the type has an alias
    if( !isset($this->node->display->$type) )
    {
      // if there is no alias, god by
      if( !isset( $this->displayAlias[$type] ) )
        return null;

      $aliases = $this->displayAlias[$type];

      // if he have aliases check if the type is shown
      foreach( $aliases as $alias )
      {
        if( isset($this->node->display->$alias) )
        {
          // first alias wins if exists
          $tmpNode = $this->node->display->$alias;
          break;
        }
      }

    }
    else
    {

      // ignore ignore fields
      if
      (
        isset($this->node->display->{$type}['type'])
          && 'false' == trim($this->node->display->{$type}['type'])
      )
      {
        return null;
      }

      $tmpNode = $this->node->display->$type;
    }

    return $tmpNode;

  }//end protected function getDisplayNode */

  /**
   * @param $type
   */
  public function weight( $type )
  {

    if( !$node = $this->getDisplayNode( $type ) )
      return 1;

    return isset( $node['weight'] )?(float)trim($node['weight']):1;

  }//end public function weight */


  /**
   * @param string $type
   * @param array $category
   * @param boolean $inherit
   * @return boolean
   */
  public function fieldAction( $context )
  {

    if( $tmpNode = $this->getDisplayNode( $context ))
    {
      if( isset( $tmpNode['action'] )  )
        return trim($tmpNode['action']);
    }

    if( isset( $this->node->display['action'] )  )
      return trim($this->node->display['action']);

    return null;

  }//end public function fieldAction */
  
  /**
   * @param string $type
   * @param array $category
   * @param boolean $inherit
   * @return boolean
   */
  public function fieldPriority( $context )
  {

    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      if( isset( $tmpNode['priority'] )  )
        return trim($tmpNode['priority']);
    }

    if( isset( $this->node->display['priority'] )  )
      return trim($this->node->display['priority']);

    return null;

  }//end public function displayPriority */

  /**
   * Abfragen ob das Attribute im aktuellen Context sichtbar ist
   *
   * @param string $context
   * @param array $category
   * @param boolean $inherit
   * @return boolean
   */
  public function field( $context, $category = null, $inherit = false )
  {

    // zuerste wird auf die Felder geprüft die standardmäßig vorhanden sind
    // und on demand ausgeblendet werden müssen
    // formulare und export sind standardmäßig aktiv
    // listen contexts sind per default inaktive per default
    if( in_array( $context , array('create','edit','crud','export') ) )
    {

      if
      (
        isset($this->node->display->{$context}['type'])
          && 'false' == trim($this->node->display->{$context}['type'])
      )
      {
        return null;
      }

      // return fieldname if exists else attrname
      return isset( $this->node->display['field'] )
        ? trim( $this->node->display['field'] )
        : trim( $this->node['name'] ) ;

    }

    if( !isset( $this->node->display ) )
      return null;

    if( isset($this->node->display['type']) && 'false' == trim($this->node->display['type']) )
      return null;

    if( isset( $this->node->display['type'] ) && 'exclude' == trim($this->node->display['type']) )
    {

      if( !isset($this->node->display->$context) )
      {
        // return fieldname if exists else attrname
        return isset($this->node->display['field'])
          ? trim($this->node->display['field'])
          : trim($this->node['name']) ;
      }
      else
      {
        return null;
      }

    }
    else
    {

      if(!$tmpNode = $this->getDisplayNode( $context ) )
        return null;

      $name = trim($this->node['name']);

      // if exists overwrite
      if( isset( $this->node->display['field']) )
      {
        $name = trim( $this->node->display['field'] );
      }

      // if exists overwrite
      if( isset( $tmpNode['field'] ) )
      {
        $name = trim( $tmpNode['field'] );
      }

      // wenn von einer referenz auf dieses attribut referenziert wird
      if( $inherit )
      {

        if( isset($tmpNode['inherit']) )
        {
          return trim($tmpNode['inherit']) == 'true'
            ? $name
            : null;
        }

        if( isset($this->node->display['inherit']) )
        {
          return trim($this->node->display['inherit']) == 'true'
            ? $name
            : null;
        }

      }

      if( !is_null($category)  )
      {
        if( $this->inCategory( $category )  )
          return $name;
        else
          return null;
      }

      return $name;
    }

  }//end public function field */

  /**
   * Abfragen ob das Attribute im aktuellen Context sichtbar ist
   *
   * @param string $context
   * @param array $category
   * @param boolean $inherit
   * @return boolean
   */
  public function vissible( $context, $category = null, $inherit = false )
  {

    // zuerste wird auf die Felder geprüft die standardmäßig vorhanden sind
    // und on demand ausgeblendet werden müssen
    // formulare und export sind standardmäßig aktiv
    // listen contexts sind per default inaktive per default
    if( in_array( $context , array('create','edit','crud','export') ) )
    {

      if
      (
        isset($this->node->display->{$context}['type'])
          && 'false' == trim($this->node->display->{$context}['type'])
      )
      {
        return false;
      }

      // return fieldname if exists else attrname
      return true;

    }

    if(!isset($this->node->display) )
      return false;

    if( isset($this->node->display['type']) && 'false' == trim($this->node->display['type']) )
      return false;

    if( isset( $this->node->display['type'] ) && 'exclude' == trim($this->node->display['type']) )
    {

      if( !isset($this->node->display->$context) )
      {
        return true;
      }
      else
      {
        return false;
      }

    }
    else
    {

      if(!$tmpNode = $this->getDisplayNode( $context ) )
        return false;

      // wenn von einer referenz auf dieses attribut referenziert wird
      if( $inherit )
      {

        if( isset($tmpNode['inherit']) )
        {
          return trim($tmpNode['inherit']) == 'true'
            ? true
            : false;
        }

        if( isset($this->node->display['inherit']) )
        {
          return trim($this->node->display['inherit']) == 'true'
            ? true
            : false;
        }

      }

      if( !is_null($category)  )
      {
        if( $this->inCategory( $category )  )
          return true;
        else
          return false;
      }

      return true;
    }

  }//end public function vissible */


  /**
   * @param string $context
   * @return string
   */
  public function sourceName( $context   )
  {

    $source = null;

    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      if( isset( $tmpNode['src'] ) )
        return trim( $tmpNode['src'] );
    }

    if( isset( $this->node['target'] ) )
      return trim( $this->node['target'] );

    return null;


  }//end public function sourceName */
  
  /**
   * @param string $context
   * @return string
   */
  public function sourceKey( $context   )
  {

    $source = null;

    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      
      if( isset( $tmpNode['alias'] ) )
        return trim( $tmpNode['alias'] );
      
      if( isset( $tmpNode['src'] ) )
        return trim( $tmpNode['src'] );
    }

    if( isset( $this->node->embed['name'] ) )
      return trim( $this->node->embed['name'] );
      
    if( isset( $this->node['target_alias'] ) )
      return trim( $this->node['target_alias'] );

    if( isset( $this->node['target'] ) )
      return trim( $this->node['target'] );

    return null;


  }//end public function sourceKey */

  /**
   * @lang de:
   * Abfragen der display eigenschaften eines Attributes
   *
   * @param string $context
   */
  public function sourceField( $context )
  {

    $field = null;

    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      if( isset( $tmpNode['field'] ) )
        $field = trim($tmpNode['field']);
    }

    if( isset( $this->node->display['field'] ) )
      $field = trim($this->node->display['field']);

    return $field;

  }//end public function sourceField */

  /**
   * @param string $context
   * @return boolean
   */
  public function sourceAlias( $context )
  {

    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      if( isset( $tmpNode['alias']) )
        return trim( $tmpNode['alias'] );
    }

    if( isset( $this->node->display['alias']) )
      return trim($this->node->display['alias'] );

    return null;

  }//end public function sourceAlias */

  /**
   * @param string $name
   * @param string $context
   */
  public function sourcePath( $name, $context )
  {

    /*
    $src          = 'src';
    $srcField     = 'srcField';
    $target       = 'target';
    $targetField  = 'targetField';
    $left         = 'left';
    */

    $path = array();

    $path['type']     = 'left';
    $path['src']      = $name->source;

    if( isset( $this->node['target_field']) )
    {
      $path['srcField'] = trim($this->node['target_field']);
    }
    else
    {
      $path['srcField'] = $this->builder->rowidKey;
    }



    $display = false;

    $target = null;

    // if there is a target, use it as target
    if(isset( $this->node['target']) )
      $target = trim($this->node['target']);

    // if the display has a src replace the target with the display source
    if( isset( $this->node->display['src']) )
    {
      $target   = trim( $this->node->display['src'] );
      $display  = true;
    }

    // if there is a contect specific type use this
    if($tmpNode = $this->getDisplayNode( $context ))
    {
      if( isset( $tmpNode['src']) )
      {
        $target   = trim( $tmpNode['src'] );
        $display  = true;
      }
    }

    // if there is no target we can not join, so return an empty path
    if( is_null($target) )
      return array();

    // ok now we have the target
    $path['target'] = $target;

    if( $display )
    {
      // if there is an alias for display use this alias
      if( isset( $this->node->display['alias'] ) )
        $target = trim($this->node->display['alias'] );

      if( isset( $tmpNode['alias'] ) )
        $target = trim( $tmpNode['alias'] );
    }
    else
    {
      // if there is an alias overwrite the targetname
      if( isset( $this->node['target_alias'] ) )
        $target = trim($this->node['target_alias']);
    }


    $path['targetAlias'] = $target;
    $path['targetField'] = $this->name();

    $paths = array();
    $paths[] = $path;

    return $paths;

  }//end public function sourcePath */

  /**
   * @param string $context
   * @return boolean
   */
  public function fieldRef( $context )
  {

    if( $tmpNode = $this->getDisplayNode( $context ) )
    {
      if( isset( $tmpNode['ref'] ) )
        return trim( $tmpNode['ref'] );
    }

    if( isset( $this->node->display['ref'] ) )
      return trim( $this->node->display['ref'] );

    return null;

  }//end public function fieldRef */


  /**
   * @param string $checkType
   * @param boolean $andIds
   * 
   * @return boolean
   */
  public function search( $checkType = null, $andIds = false )
  {

    if( $andIds )
    {
      if( 'id_' == substr( (string)$this->node['name'], 0, 3 ) )
        return 'equal';
    }

    if( !isset( $this->node->search ) )
      return false;

    $type = 'equal';

    if( isset( $this->node->search['type'] ) )
      $type = trim( $this->node->search['type'] );

    if( $checkType )
    {
      if( 'free' == $checkType )
        return isset( $this->node->search['free'] );
      else
        return ( $checkType == $type &&  $type != 'false' );
    }
    else
    {
      return $type != 'false'?$type:false;
    }

  }//end public function search */


  /**
   * @param boolean $andIds
   * @return boolean
   */
  public function searchFree(  $andIds = false )
  {

    if( $andIds )
    {
      if( 'id_' == substr( (string)$this->node['name'], 0,3 ) )
        return 'equal';
    }

    return isset( $this->node->search['free'] );

  }//end public function searchFree */


  /**
   * @param string $checkType
   * @return boolean
   */
  public function searchParam( $checkType  )
  {

    if( !isset($this->node->search) )
      return false;

    return isset($this->node->search->$checkType);

  }//end public function searchParam */

  /**
   * parser for the qoutes check
   *
   * @return string
   */
  public function searchLike( )
  {

    $type = strtolower(trim($this->node['type']));

    $likes = array
    (
      'varchar',
      'text'
    );

    if( in_array( $type , $likes ) )
      return true;
    else
      return false;

  }//end protected function searchLike */

////////////////////////////////////////////////////////////////////////////////
// checker
////////////////////////////////////////////////////////////////////////////////

  /**
   * parser for the qoutes check
   *
   * @param string $row
   * @return string
   */
  public function quote( )
  {
    $validator = strtolower(trim($this->node['validator']));

    switch($validator)
    {
      case 'int':
      {
        return '';
      }

      default:
      {
        return '\\\'';
      }

    }//end switch

  }//end public function quote */

////////////////////////////////////////////////////////////////////////////////
// implement parent nodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @overwrite should be implemented if needed
   * @return null
   */
  protected function prepareNode( $params = array() )
  {

    // the first attribute should be the entity
    $this->entity   = $params['entity'];
    $this->dbType();

    $nodeClass        = $this->builder->getNodeClass( 'UiElement' );
    $this->uiElement  = new $nodeClass( $this->node->uiElement, null, array($this) );

    if( isset( $this->node->semantic  ) )
    {
      $nodeClass       = $this->builder->getNodeClass( 'AttributeSemantic' );
      $this->semantic  = new $nodeClass( $this->node->semantic, null, array($this) );
    }

    // ok little redundant but whatever...
    $this->name         = new LibGenfName( );
    $this->name->label  = ucfirst($this->label());
    $this->name->name   = $this->name();
    $this->name->field  = $this->name->name;
    $this->name->class  = $this->identifier();
    $this->name->source = $this->entity->name->name;

    //
    if( isset( $this->node->fk ) )
    {
      $nodeClass  = $this->builder->getNodeClass('Fk');
      $this->fk   = new $nodeClass( $this->node->fk, null, array('attribute'=>$this) );
    }

  }//end protected function prepareNode */

  
  /**
   * @return array
   */
  public function getDebugDump()
  {

    $name = 'no name';

    if( $this->name )
      $name = $this->name->name;

    return array
    (
      'node type: '.get_class($this),
      'name : '.$name,
    );

  }//end public function getDebugDump */

}//end class LibGenfTreeNodeAttribute

