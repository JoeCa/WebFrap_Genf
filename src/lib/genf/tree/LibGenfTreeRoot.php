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
class LibGenfTreeRoot
  implements Iterator, Countable
{
////////////////////////////////////////////////////////////////////////////////
// public attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * index for the parsed names
   * @var array
   */
  public $names         = array();

  /**
   * index for the parsed names
   * @var null
   */
  public $name          = null;

////////////////////////////////////////////////////////////////////////////////
// protected attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder    = null;

  /**
   *
   * @var LibGenfTree
   */
  protected $tree       = null;

  /**
   *
   * @var DOMDocument
   */
  protected $modelTree  = null;

  /**
   * the root element of the xml tree
   * @var DOMElement
   */
  protected $modelRoot  = null;

  /**
   *
   * @var DOMXpath
   */
  protected $modelXpath = null;

  /**
   * the xml root node for tree root
   * @var DOMElement
   */
  protected $nodeRoot   = null;

  /**
   *
   * @var DOMDocument
   */
  protected $tmpXml     = null;

  /**
   *
   * @var DOMXpath
   */
  protected $tmpXpath   = null;

  /**
   *
   * @var array
   */
  protected $nodes      = array();

////////////////////////////////////////////////////////////////////////////////
// magic
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param LibGenfTree $tree
   */
  public function __construct( $tree )
  {

    $this->tree         = $tree;
    $this->modelTree    = $tree->getModelTree();
    $this->modelRoot    = $tree->getModelRoot();
    //$this->modelXpath   = $tree->getModelXpath();
    $this->builder      = $tree->builder;

    if( Log::$levelDebug )
    {
      $className = get_class( $this );
      Log::debug( 'Create Node: '.$className );
    }

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// Interface: Iterator
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Iterator::current
   */
  public function current ()
  {
    return current($this->nodes);
  }//end public function current */

  /**
   * @see Iterator::key
   */
  public function key ()
  {
    return key($this->nodes);
  }//end public function key */

  /**
   * @see Iterator::next
   */
  public function next ()
  {
    return next($this->nodes);
  }//end public function next */

  /**
   * @see Iterator::rewind
   */
  public function rewind ()
  {
    reset($this->nodes);
  }//end public function rewind */

  /**
   * @see Iterator::valid
   */
  public function valid ()
  {
    return current($this->nodes)? true:false;
  }//end public function valid */

////////////////////////////////////////////////////////////////////////////////
// Interface: Countable
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see Countable::count
   */
  public function count()
  {
    return count($this->nodes);
  }//end public function count */

////////////////////////////////////////////////////////////////////////////////
// methdode
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $name
   * @return SimpleXmlElement
   */
  public function getSimple( $name )
  {

    if( $node = $this->get($name) )
    {
      return simplexml_import_dom($node);
    }
    else
    {
      return null;
    }

  }//end public function getSimple */

  /**
   * @param string $name
   * @return SimpleXmlElement
   */
  public function simple( $node )
  {

    return simplexml_import_dom($node);

  }//end public function simple */

  /**
   *
   * @param string $key
   * @return LibGenfName with all parsed Names for the Tree
   */
  public function getName( $key = null )
  {
    if( !$key )
      return $this->name;
    else if( is_object($key) )
      $name = $key['name'];
    else
      $name = $key;

    return isset($this->names[$name])
      ? $this->names[$name]
      : null;

  }//end public function getName */


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

    return isset($this->names[$key]);

  }//end public function getName */


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
  public function nodeExists( $key  )
  {

    return isset($this->names[$key]);

  }//end public function getName */


  /**
   * getter for a node root child object
   *
   * @param string $key
   * @return boolean
   */
  public function getNode( $key  )
  {

    return isset($this->nodes[$key])?$this->nodes[$key]:null;

  }//end public function getName */


  /**
   * getter for a node root child object
   *
   * @param string $key
   * @return boolean
   */
  public function getNodes( )
  {

    return $this->nodes;

  }//end public function getName */

  /**
   * apply some defaults...
   * @todo for what the hell was this method damn?!
   * @param DOMNode $concept
   * @return void
   */
  protected function applyDefaults( $concept )
  {

  }//end protected function applyDefaults

  /**
   * take a given hopefully valid xml string, convert it to a domnode and
   * append it as child to a given parent node
   *
   * if no parent is given, the string is just imported in the action tree
   * DOMDocument an returned as free addable DOMNode
   *
   * @param string $xml
   * @return DOMNode
   */
  protected function stringToNode( $xml,  $parent = null )
  {

    ///TODO add some error handling
    $tmpDoc = new DOMDocument( '1.0', 'utf-8' );
    $tmpDoc->preserveWhitespace  = false;
    $tmpDoc->formatOutput        = true;

    if( !$tmpDoc->loadXML($xml) )
    {
      Error::addError('Failed to load an XML String',null,htmlentities($xml));
      return null;
    }

    $child = $tmpDoc->childNodes->item(0);
    $this->builder->interpreter->interpret( $child );

    if( $parent )
    {
      $child = $parent->ownerDocument->importNode( $child, true);
      $child = $parent->appendChild( $child );
    }
    else
    {
      $child = $this->modelTree->importNode( $child, true );
    }

    return $child;

  }//end protected function stringToNode */

   /**
    * default merge is to replace the old node
    *
    * this is the most laziest implementation, it just replace the old with the
    * new node, normaly this is a little to radical, so implement your own merge
    * method
    *
    * Use the LibGenMerge* Classes. Use the implementation in LibGenfTreeRootEntity
    * as a good example how to create a merge method
    *
    * @param DOMNode $old
    * @param DOMNode $new
    */
  public function merge( $old, $new )
  {

    if( $old->ownerDocument !== $new->ownerDocument )
      $node = $old->ownerDocument->importNode( $new , true );
    else
      $node = $new;

    $old->parentNode->replaceChild( $node, $old  );

  }//end public function merge */


////////////////////////////////////////////////////////////////////////////////
// Semi Abstract Methodes
////////////////////////////////////////////////////////////////////////////////

   /**
    * get a DOMNode via a key from the treenode
    * you have to implement this method if you want to use it
    * this is just a dummy
    *
    * @overwrite
    * @return DOMNode
    */
  public function get( $name, $type = null ){return null;}

   /**
    * @overwrite
    * @return boolean
    */
  public function add( $node )
  {

    if( is_string($node) )
    {
      $origString = $node;
      if(!$node = $this->stringToNode( $node ))
      {
        $this->builder->error( 'Tried to add an invalid string node '.$origString );
        return null;
      }
    }

    if( !is_object($node) || !$node instanceof DOMNode )
    {
        Error::report('Got invalid Node to add' , $node  );
        return null;
    }


    $this->builder->interpreter->interpret( $node );
    $this->applyDefaults(  $node );

    if( $oldEntity = $this->get( $node->getAttribute('name') ) )
    {
      return $this->merge( $oldEntity, $node );
    }
    else
    {
      if( $node->ownerDocument !== $this->nodeRoot->ownerDocument )
      {
        $node = $this->nodeRoot->ownerDocument->importNode( $node , true );
      }

      return $this->nodeRoot->appendChild( $node );
    }

  }//end public function add */

  /**
   * mapper method to append a node direct to the root node
   *
   * @param DOMNode $newNode
   * @return DOMNode
   */
  public function append( $newNode )
  {
    return $this->nodeRoot->appendChild( $newNode );
  }//public function append */

  /**
   * mapper method to append a node direct to the root node
   *
   * @param DOMNode $old
   * @param DOMNode $new
   * @return DOMNode
   */
  public function replace( $old, $new  )
  {

    if( $old->ownerDocument !== $new->ownerDocument )
      $node = $old->ownerDocument->importNode( $new , true );
    else
      $node = $new;

    $old->parentNode->replaceChild( $node, $old  );

    return $new;

  }//public function replace */

   /**
    * @overwrite
    * @return void
    */
  public function preProcessing(){}

   /**
    * @overwrite
    * @return void
    */
  public function postProcessing(){}

  /**
   * create the index for this node
   * @return boolean
   */
  public function createIndex(){}

   /**
    * @overwrite
    * @param string $name
    * @deprecated use setActive
    */
  public function setActiv( $name ){}

   /**
    * @overwrite
    * @param string $name
    */
  public function setActive( $name ){}

   /**
    * @overwrite
    * @param DOMDocument $tmpXml
    * @param DOMXpath $tmpXpath
    * @param string $repoPath
    * @return void
    */
  public function importFile( $tmpXml, $tmpXpath, $repoPath = null ){}

  /**
   * @overwrite
   * @return unknown_type
   */
  public function createDefaultDependencies(){}

  /**
   * @overwrite
   * @param string $name
   * @return void
   */
  public function createDefault( $name, $params = array() ){}

  /**
   * @overwrite
   * @param string $name
   * @return void
   */
  public function addElement( $newNode, $params = array() )
  {

    if( is_string($newNode) )
    {
      if(!$newNode = $this->stringToNode( $newNode ))
      {
        Error::addError( 'Failed to load the given XML String as Node in the Tree. The Attribute name is missing.' );
        return false;
      }
    }

    if(!$name =  $newNode->getAttribute('name') )
    {
      Error::addError( 'Tried to add an invalid node. The Attribute name is missing.' );
      return false;
    }

    if( $old = $this->get( $name  ) )
    {
      return $this->merge( $old, $newNode );
    }
    else
    {
      return $this->add( $newNode );
    }

  }//end public function addElement

} // end class LibGenfTreeRoot
