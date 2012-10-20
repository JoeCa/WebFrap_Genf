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
class LibGenfTreeNode
  implements ArrayAccess, Iterator, Countable
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   * Namensobjekt
   * @var LibGenfName
   */
  public $name        = null;
  
  /**
   *
   * @var array
   */
  public $labels          = null;
  
  /**
   * PLural label
   * @var array
   */
  public $plabel          = null;
  
  /**
   * @var LibGenfTreeNode
   */
  public $nodeParent          = null;

////////////////////////////////////////////////////////////////////////////////
// protected attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var Iterator
   */
  protected $childs   = null;

  /**
   *
   * @var SimpleXmlElement
   */
  protected $node     = null;

  /**
   * Der Customnode wird verwendet um die Eigenschaften des Haupt UI Knotens
   * lokal zu überlagern.
   *
   * @todo wann passiert das?
   *
   * @var SimpleXmlElement
   */
  protected $customNode     = null;

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder  = null;

  /**
   *
   * @var LibGenfTreeRoot
   */
  protected $root     = null;

  /**
   *
   * @var string
   */
  protected $rootType = null;

  /**
   * per default valid until invalid
   * @var boolean
   */
  protected $valid    = true;

  /**
   *
   * @var array
   */
  protected $firstShift   = array('id','m','flag');

  /**
   *
   * give a changeable context
   * @var string
   */
  public $context = null;

  /**
   * Fallback Knoten für den fall das im aktiven knoten keine passenden
   * werte vorhanden sind
   * 
   * @var LibGenfTreeNode
   */
  public $fallback = null;
  
  /**
   * Flag ob das objekt invalid ist
   * @var boolean
   */
  public $isInvalid = false;

////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see ArrayAccess:offsetSet
   */
  public function offsetSet($offset, $value)
  {

    // readonly
    //$this->attribute[$offset] = $value;

  }//end public function offsetSet */

  /**
   * @see ArrayAccess:offsetGet
   */
  public function offsetGet($offset)
  {

    if( isset($this->node[$offset]) )
      return trim($this->node[$offset]);

    Error::report( "Reqested invalid attribute offset: ".$offset , array( $offset, $this->node )   );
    return null;

    /*
    return isset($this->node[$offset])
      ? trim($this->node[$offset])
      : null;
    */

  }//end public function offsetGet */

  /**
   * @see ArrayAccess:offsetUnset
   */
  public function offsetUnset($offset)
  {

    // readonly
    //unset($this->attribute[$offset]);

  }//end public function offsetUnset */

  /**
   * @see ArrayAccess:offsetExists
   */
  public function offsetExists($offset)
  {

    return isset($this->node[$offset])
      ? true
      : false;

  }//end public function offsetExists */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Iterator::current
   */
  public function current ()
  {

    if( is_array( $this->childs ) )
      return current( $this->childs );

    if( is_object( $this->childs ) )
      return $this->childs->current( );

    return null;

  }//end public function current */

  /**
   * @see Iterator::key
   */
  public function key ()
  {

    if( is_array( $this->childs ) )
      return key($this->childs);

    if( is_object($this->childs) )
      return $this->childs->key();

    return null;

  }//end public function key */

  /**
   * @see Iterator::next
   */
  public function next ()
  {

    if( is_array( $this->childs ) )
      return next($this->childs);

    if( is_object($this->childs) )
    {
      return $this->childs->next();
    }

    return null;

  }//end public function next */

  /**
   * @see Iterator::rewind
   */
  public function rewind ()
  {

    if( is_array($this->childs) )
     return reset($this->childs);

    if( is_object($this->childs) )
      return $this->childs->rewind();

    return null;

  }//end public function rewind */

  /**
   * @see Iterator::valid
   */
  public function valid ()
  {

    if( is_array($this->childs) )
    {
      return current($this->childs)? true:false;
    }

    if( is_object($this->childs) )
    {
      return $this->childs->valid();
    }

    return false;


  }//end public function valid */

////////////////////////////////////////////////////////////////////////////////
// magic method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Countable::count
   */
  public function count()
  {
    return count($this->childs);
  }//end public function count */


////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   */
  public function __get( $key )
  {
    return isset($this->node->$key)
      ? $this->node->$key
      : null;
  }//end public function __get */

  /**
   * @param string $key
   * @param string $value
   */
  public function __set( $key , $value )
  {
    // readonly
    Error::report( 'tried to write to the readonly property: '.$key.' in: '.__class__.Debug::backtrace() );
  }//end public function __set */

  /**
   *
   * @param SimpleXmlElement $node
   * @param LibGenfName $name
   * @param $params
   */
  public function __construct( $node, $name = null, $params = array(), $nodeParent = null )
  {

    $this->builder  = LibGenfBuild::getInstance();

    if( $this->rootType )
      $this->root   = $this->builder->getRoot($this->rootType);

    $this->name       = $name;
    $this->nodeParent = $nodeParent;

    $this->validate( $node );
    $this->prepareNode( $params );
    $this->loadChilds( );

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// getter + setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return  SimpleXmlElement
   */
  public function getNode()
  {
    return $this->node;
  }//end public function getNode */
  
  /**
   * @param string $key
   * @return string
   */
  public function getNodeAttr( $key )
  {

    return isset( $this->node[$key] )
      ? trim( $this->node[$key] )
      : null ;

  }//end public function getNodeAttr */

  /**
   * @param SimpleXmlElement $node
   */
  public function setCustomNode( $node )
  {
    $this->customNode = $node;
  }//end public function setCustomNode */

  /**
   * check if the node is valid
   * @return boolean
   */
  public function isValid()
  {
    return $this->valid;
  }//end public function isValid */

  /**
   * @return LibGenfName
   */
  public function name()
  {
    return $this->name;
  }//end public function name */

  /**
   * @return LibGenfName
   */
  public function getName()
  {
    return $this->name;
  }//end public function getName */

  /**
   * @return LibGenfName
   */
  public function setName( $name )
  {
    $this->name = $name;
  }//end public function setName */
  
  /**
   * @return string
   */
  public function getTargetGroup( $def = null )
  {
    if( isset( $this->node['target_group'] ) )
      return trim($this->node['target_group']);
    else   
      return $def;
    
  }//end public function getTargetGroup */

  /**
   * prüfen ob der Knoten einen bestimmtes Kind hat
   *
   * @param string $key der Name des Kindknotens
   * @return boolean true wenn der Kindknoten existiert
   */
  public function hasSubNode( $key )
  {
    return isset( $this->node->$key );
  }//end public function hasSubNode */

  /**
   *
   * @param string $key
   */
  public function getSubNodeParser( $key )
  {

  }//end public function getSubNodeParser */
  
  /**
   *
   * @param string $fallback
   */
  public function setFallback( $fallback )
  {
    
    if( $this === $fallback )
    {
      $this->error( "Tried to assign a node as it's fallback, that's not possible an would run in an endless loop ".$this->debugData() );
      return null;
    }
    
    $this->fallback = $fallback;
    
  }//end public function setFallback */

  /**
   * @param string $langKey
   * @return string
   */
  public function label( $langKey = 'en'  )
  {
    
    if( isset($this->labels[$langKey]) )
      return $this->labels[$langKey];

    // check if there is a label
    if( isset( $this->node->label->text ) )
    {

      // check if we find a lable for the given language
      foreach( $this->node->label->text as $text )
      {
        if( trim($text['lang']) ==  $langKey && '' != trim($text) )
        {
          $this->labels[$langKey] = trim($text);
          return $this->labels[$langKey];
        }
      }

      // fallback auf englisch wenn der angefragte key nicht vorhanden war
      if( 'en' !== $langKey )
      {
        foreach( $this->node->label->text as $text )
        {
          if( 'en' === trim($text['lang']) && '' !== trim($text)  )
          {
            $this->labels[$langKey] = trim($text);
            return $this->labels[$langKey];
          }
        }
      }

      // if not yet found return the first text, asuming this textnode has
      // the highest priority, if not fix your model and RTFM
      foreach( $this->node->label->text as $text )
      {
        // ends in the first element, did not found an easier way to get the
        // first node in simple xml... just fix it if you know better
        if( '' != trim($text) )
        {
          $this->labels[$langKey] = trim($text);
          return $this->labels[$langKey];
        }
      }


    }
    else if( isset( $this->node->label )  && '' != trim($this->node->label) )
    {
      // if no text but a label is given, we asume that the lable just contains
      // the text, if not... RTFM
      $this->labels[$langKey] = trim( $this->node->label );
      return $this->labels[$langKey];
    }

    // ok no labels, so we have to create one by transforming the name
    $preName = (string)$this->node['name'];

    $tmp = explode( '_', $preName );

    // remove the first field if its a given keyword like "id", "m" or "flag"
    // but not if its the only field ( can happen with flag maybe )
    if( count($tmp)>1 && in_array( strtolower( $tmp[0]), $this->firstShift ) )
      array_shift($tmp);


   $this->labels[$langKey] = SParserString::ucAll( $tmp );

   return $this->labels[$langKey];

  }//end public function getLabel */
  
  /**
   * @param string $langKey
   * @return string
   */
  public function pLabel( $langKey = 'en'  )
  {
    
    if( isset($this->plabel[$langKey]) )
      return $this->plabel[$langKey];

    // check if there is a label
    if( isset( $this->node->plabel->text ) )
    {

      // check if we find a lable for the given language
      foreach( $this->node->plabel->text as $text )
      {
        if( trim($text['lang']) ==  $langKey && '' != trim($text) )
        {
          $this->plabel[$langKey] = trim($text);
          return $this->plabel[$langKey];
        }
      }

      // fallback auf englisch wenn der angefragte key nicht vorhanden war
      if( 'en' !== $langKey )
      {
        foreach( $this->node->plabel->text as $text )
        {
          if( 'en' === trim($text['lang']) && '' !== trim($text)  )
          {
            $this->plabel[$langKey] = trim($text);
            return $this->plabel[$langKey];
          }
        }
      }

      // if not yet found return the first text, asuming this textnode has
      // the highest priority, if not fix your model and RTFM
      foreach( $this->node->plabel->text as $text )
      {
        // ends in the first element, did not found an easier way to get the
        // first node in simple xml... just fix it if you know better
        if( '' != trim($text) )
        {
          $this->plabel[$langKey] = trim($text);
          return $this->plabel[$langKey];
        }
      }


    }
    else if( isset( $this->node->plabel )  && '' != trim($this->node->plabel) )
    {
      // if no text but a label is given, we asume that the lable just contains
      // the text, if not... RTFM
      $this->plabel[$langKey] = trim( $this->node->plabel );
      return $this->plabel[$langKey];
    }

    // ok no labels, so we have to create one by transforming the name
    $preName = (string)$this->node['name'];

    $tmp = explode( '_', $preName );

    // remove the first field if its a given keyword like "id", "m" or "flag"
    // but not if its the only field ( can happen with flag maybe )
    if( count($tmp)>1 && in_array( strtolower( $tmp[0]), $this->firstShift ) )
      array_shift($tmp);

   // das normale label nehmen und ein s dran hängen
   // wird schon passen... hoffentlich
   
   $theLabel = $this->label($langKey);
   
   if( 's' == strtolower( substr($theLabel, -1) ) )
   {
     $this->labels[$langKey] = $theLabel;
   }
   else 
   {
     $this->labels[$langKey] = $theLabel.'s';
   }

   return $this->labels[$langKey];

  }//end public function pLabel */

  /**
   * @param boolean $escape
   * @param string $lang
   */
  public function description( $langKey = 'en', $escape = false )
  {

    // check if there is a label
    if( isset( $this->node->description->text ) )
    {

      foreach( $this->node->description->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
        {
          if( $escape )
          {
            return str_replace( "'", "\'", trim($lang) );
          }
          else
          {
            return trim($lang);
          }

        }
      }
    }

    if( isset( $this->node->description ) )
    {
      if( $escape )
      {
        return str_replace( "'", "\'", trim($this->node->description) );
      }
      else
      {
        return trim($this->node->description);
      }
    }

   return null;

  }//end public function description */
  
  /**
   * @param boolean $escape
   * @param string $lang
   */
  public function shortDescription( $langKey = 'en', $escape = false )
  {

    // check if there is a label
    if( isset( $this->node->short_desc->text ) )
    {

      foreach( $this->node->short_desc->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
        {
          if( $escape )
          {
            return str_replace( "'", "\'", trim($lang) );
          }
          else
          {
            return trim($lang);
          }

        }
      }
      
    }

    if( isset( $this->node->short_desc ) )
    {
      if( $escape )
      {
        return str_replace( "'", "\'", trim($this->node->short_desc) );
      }
      else
      {
        return trim($this->node->short_desc);
      }
    }

   return null;

  }//end public function shortDescription */
  
  
  /**
   * @param boolean $escape
   * @param string $lang
   */
  public function docu( $langKey = 'en', $escape = false )
  {

    // check if there is a label
    if( isset( $this->node->docu->text ) )
    {

      foreach( $this->node->docu->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
        {
          if( $escape )
          {
            return str_replace( "'", "\'", trim($lang) );
          }
          else
          {
            return trim($lang);
          }

        }
      }
    }

    if( isset( $this->node->docu ) )
    {
      if( $escape )
      {
        return str_replace( "'", "\'", trim($this->node->docu) );
      }
      else
      {
        return trim($this->node->docu);
      }
    }

   return null;

  }//end public function docu */
  
  /**
   * @param SimplXmlElement $node
   * @param string $langKey
   * @param boolean $escape
   */
  public function i18nValue( $node, $langKey = 'en', $escape = false )
  {

    if( true === $escape )
      $escape = "'";
    
    // check if there is a label
    if( isset( $node->text ) )
    {

      foreach( $node->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
        {
          if( $escape )
          {
            return str_replace( $escape, "\\{$escape}", trim($lang) );
          }
          else
          {
            return trim($lang);
          }

        }
      }
    }

    if( isset( $node ) )
    {
      if( $escape )
      {
        return str_replace( $escape, "\\{$escape}", trim($lang) );
      }
      else
      {
        return trim($node);
      }
    }

   return null;

  }//end public function i18nValue */


  /**
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess()
  {

    if( !isset( $this->node->access ) )
      return null;

    $classname   = $this->builder->getNodeClass( 'Access' );

    return new $classname( $this->node->access );

  }//end public function getAccess */


  /**
   *
   * @param LibGenfTreeNodeEntity $entity
   * @param array $path
   *
   * @return array<string,Entity>
   */
  public function buildEntityPath( $entity, $path )
  {

    $pathData = array();

    foreach( $path as $node )
    {
      
      if( !$newEntity  = $entity->getAttrTarget( $node, 'entity' ) )
      {
        $this->builder->error( "Invalid path ".$this->builder->dumpEnv() );
        return null;
      }
             
      $pathData[] = array( $node, $newEntity );
      $entity     = $newEntity;
      
    }

    return $pathData;

  }//end public function buildEntityPath */

////////////////////////////////////////////////////////////////////////////////
// method
////////////////////////////////////////////////////////////////////////////////


  /**
   * Informationen im Knoten zum compilieren aufbereiten
   *
   * Hier kommt alle Logik rein, die verwendet wird um den rohen Knoten
   * für eine bessere Compilierbarkeit aufzubereiten
   *
   * @overwrite should be implemented if needed
   * @param array $params
   */
  protected function prepareNode( $params = array() ){}

  /**
   * @overwrite
   */
  protected function loadChilds(){}

  /**
   * Den Knoten auf korrektheit prüfen
   *
   * Hier werden alle Checks implementier die nötig sind sicher zu stellen,
   * dass der Knoten in dieser Form compilierbar ist
   *
   * @param SimpleXmlNode $node
   */
  protected function validate( $node )
  {

    $this->valid  = true;
    $this->node   = $node;

  }//end protected function validate */


/*//////////////////////////////////////////////////////////////////////////////
// Error Reporting
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Eine Warning in das Protokoll schreiben
   *
   * @param string $message
   */
  public function warn( $message )
  {
    $this->builder->warn( $message );
  }//end public function warn */

  /**
   * Einen Fehler in die das Protokoll schreiben
   *
   * @param string $message
   * @param string $call
   */
  public function error( $message, $call = 'logicError' )
  {

    $this->builder->protocol->{$call}( $message );

  }//end public function error */

/*//////////////////////////////////////////////////////////////////////////////
// Debug Console
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return array
   */
  public function getDebugDump()
  {

    if( $this->name )
      $name = $this->name->name;
    else 
    {
      $name = '"unnamed '.get_class($this).' node"';
    }

    return array
    (
      'node type: '.get_class($this),
      'name : '.$name,
    );

  }//end public function getDebugDump */

  /**
   * @return string
   */
  public function debugData()
  {
    
    $code = 'Node: '.get_class( $this );
    
    if( $this->name )
      $code .= ": ".$this->name->name;
    
    return $code;
    
  }//end public function debugData */
  
  /**
   * @param string $message
   */
  public function reportError( $message )
  {
    
    $this->builder->error
    ( 
      "Invalid Node ".get_class($this)." ".$message." ".$this->builder->dumpEnv() 
    );
    
  }//end public function reportError */
  
  /**
   * @return string
   */
  public function __toString()
  {
    
    return $this->debugData();
    
  }//end public function __toString */
  
  /**
   * @return string
   */
  public function asXml()
  {
    
    if( $this->node && $this->node instanceof SimpleXMLElement )
      return $this->node->asXML();
    else 
      return 'Node type: '.gettype( $this->node );
    
  }//end public function __toString */

}//end class LibGenfTreeNode

