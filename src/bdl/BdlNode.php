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
class BdlNode
  implements ArrayAccess
{
////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var SimpleXmlElement
   */
  public $simple = null;
  
  /**
   * @var DOMElement
   */
  public $dom = null;

  /**
   * @var BdlFile
   */
  public $file = null;
  
////////////////////////////////////////////////////////////////////////////////
// BDL Def Tags
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var array
   */
  protected $label = array();
  
  /**
   * @var array
   */
  protected $info = array();
  
  /**
   * @var array
   */
  protected $description = array();
  
////////////////////////////////////////////////////////////////////////////////
// Construct
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param BdlFile $file
   * @param DOMElement $node
   */
  public function __construct( $file, $node )
  {
    
    $this->file = $file;
    $this->dom  = $node;
    
  }//end public function __construct */
  
////////////////////////////////////////////////////////////////////////////////
// Interface: ArrayAccess
////////////////////////////////////////////////////////////////////////////////

  /**
   * @see ArrayAccess:offsetSet
   */
  public function offsetSet( $offset, $value )
  {
    $this->dom->setAttribute( $offset , $value );
  }//end public function offsetSet */

  /**
   * @see ArrayAccess:offsetGet
   */
  public function offsetGet( $offset )
  {
    return $this->dom->getAttribute( $offset );
  }//end public function offsetGet */

  /**
   * @see ArrayAccess:offsetUnset
   */
  public function offsetUnset( $offset )
  {
    $this->dom->removeAttribute( $offset );
  }//end public function offsetUnset */

  /**
   * @see ArrayAccess:offsetExists
   */
  public function offsetExists( $offset )
  {
    return $this->dom->hasAttribute( $offset );
  }//end public function offsetExists */
  
////////////////////////////////////////////////////////////////////////////////
// FCK DOM!
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $tagName
   */
  public function getNode( $tagName )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
      return $node->item(0);
      
    return null;
    
  }//end public function getNode */
  
  /**
   * @param string $path
   * @param string $position
   */
  public function createPath( $path, $position = null )
  {
    
    if( $position )
    {
      $dom = $this->getNode( './'.$position, $this->dom  );
    }
    else 
    {
      $dom = $this->dom;
    }
    
    $pos = strpos( '/', $path );
    
    if( $pos )
    {
      $nodeName = substr( $path, 0, ($pos-1) );
      
      if( $position )
        $position = $position.'/'.$nodeName;
      else 
        $position = $nodeName;
      
      $nextPath = substr( $path, $pos, strlen($path) );
    }
    else 
    {
      $nodeName = $path;
      $nextPath = null;
    }
    
    $node = $this->file->xpath( './'.$nodeName, $dom );
    
    if( $node->length )
    {
      if( $nextPath )
      {
        return $this->createPath( $path, $position );
      }
    }
    else 
    {
      $newNode = $this->file->document->createElement( $nodeName, '' );
      $dom->dom->appendChild( $newNode );
      
      if( $nextPath )
      {
        return $this->createPath( $path, $position );
      }
    }
      
    return $node->item(0);
    
  }//end public function createPath */
  
  /**
   * @param string $tagName
   * @param string $value
   * @param string $cData
   */
  public function setNodeValue( $tagName, $value, $cData = true )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
    {
      $node->item(0)->nodeValue = $value;
    }
    else 
    {
      $newNode = $this->file->document->createElement( $tagName, $value );
      $this->dom->appendChild( $newNode );
    }
    
  }//end public function setNodeValue */
  
  /**
   * @param string $tagName
   */
  public function getNodeValue( $tagName )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
      return $node->item(0)->textContent;
    else 
      return null;  
    
  }//end public function setNodeValue */
  
  
  
  /**
   * @param string $tagName
   * @param string $attrName
   * @return string
   */
  public function getNodeAttr( $tagName, $attrName )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
      return $node->item(0)->getAttribute( $attrName );
    else 
      return null;  
      
  }//end  public function getNodeAttr
  
  /**
   * @param string $tagName
   * @param string $attrName
   * @param string $value
   */
  public function setNodeAttr( $tagName, $attrName, $value )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
    {
      $node->item(0)->setAttribute( $attrName, $value );
    }
    else 
    {
      $newNode = $this->file->document->createElement( $tagName, '' );
      $newNode->setAttribute( $attrName, $value );
      $this->dom->appendChild( $newNode );
    }
    
  }//end public function setNodeAttr */
  
////////////////////////////////////////////////////////////////////////////////
// Label & Description
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $type
   * @return array
   */
  public function getTextNodes( $type )
  {
    
    $list = $this->file->xpath( './'.$type.'/text', $this->dom );
    
    $nodes = array();
    
    foreach( $list as $node  )
    {
      $nodes[$node->getAttribute('lang')] = $node->textContent;
    }
    
    return $nodes;

  }//end public function getLabels */
  
  /**
   * @param string $type
   * @return array
   */
  public function setTextNode( $type, $lang, $content )
  {
    
    $list = $this->file->xpath( './'.$type.'/text[@lang="'.$lang.'"]', $this->dom );
    
    $nodes = array();
    
    if( $list->length )
    {
      $node = $list->item(0);
      $node->nodeValue = $content;
    }
    else 
    {
      $tNodeList = $this->file->xpath( './'.$type, $this->dom );
      
      if( !$tNodeList->length )
      {
        $newNode = $this->file->document->createElement( $type, '' );
        $tNode = $this->dom->appendChild( $newNode );
      }
      else 
      {
        $tNode = $tNodeList->item(0);
      }
      
      $newNode = $this->file->document->createElement( 'text', $content );
      $newNode->setAttribute( 'lang', $lang );
      $tNode->appendChild( $newNode );
    }
    
  }//end public function setTextNode */

  /**
   * @return array
   */
  public function getLabels()
  {
    
    return $this->getTextNodes('label');

  }//end public function getLabels */
  
  /**
   * @return array
   */
  public function setLabel( $lang, $content )
  {
    
    return $this->setTextNode( 'label', $lang, $content );

  }//end public function setLabel */
  
  /**
   * 
   */
  public function getDescriptions()
  {
    return $this->getTextNodes('description');
    
  }//end public function getDescriptions */
  
  /**
   * @return array
   */
  public function setDescription( $lang, $content )
  {
    
    return $this->setTextNode( 'description', $lang, $content );

  }//end public function setDescription */
  
  /**
   * 
   */
  public function getInfos()
  {
    return $this->getTextNodes('info');
  }//end public function getDescriptions */
  
  /**
   * @return array
   */
  public function setInfo( $lang, $content )
  {
    
    return $this->setTextNode( 'info', $lang, $content );

  }//end public function setInfo */
  
  /**
   * 
   */
  public function getComment()
  {
    
  }//end public function getDescriptions */
  

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return SimpleXMLElement
   */
  public function getSimple( )
  {
    
    if( $this->simple )
      return $this->simple;
    
    $this->simple = simplexml_import_dom($this->dom);
    
    return $this->simple;
    
  }// public function getSimple */
  
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Rückgabe der Debugdaten des Knotens
   */
  public function debugData()
  {
    
    return null;
    
  }//end public function debugData */
  
  /**
   * Nach XML Serialisieren
   */
  public function serializeXml()
  {
    return null;
  }//end public function serializeXml */
  
}//end class BdlNode
