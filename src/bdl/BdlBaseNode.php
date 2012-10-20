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
class BdlBaseNode
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
  protected $docu = array();
  
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
   */
  public function getNodes( $path )
  {
    $list = $this->file->xpath( './'.$path, $this->dom );

    return $list;
    
  }//end public function getNodes */
  
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
    
    $pos = strpos( $path, '/'  );
    
    if( $pos )
    {
      $nodeName = substr( $path, 0, $pos );
      
      
      if( $position )
        $position = $position.'/'.$nodeName;
      else 
        $position = $nodeName;
      
      $nextPath = substr( $path, $pos+1, strlen($path) );
    }
    else 
    {
      $nodeName = $path;
      $nextPath = null;
    }
    
    Debug::console("NODE NAAAAME ".$nodeName );
    
    $node = $this->file->xpath( './'.$nodeName, $dom );
    
    if( $node->length )
    {
      if( $nextPath )
      {
        return $this->createPath( $nextPath, $position );
      }
    }
    else 
    {
      $newNode = $this->file->document->createElement( $nodeName, '' );
      $dom->appendChild( $newNode );
      
      if( $nextPath )
      {
        return $this->createPath( $nextPath, $position );
      }
    }
      
    return $newNode;
    
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
  
  /**
   * @param string $tagName
   * @param string $attrName
   */
  public function removeNodeAttr( $tagName, $attrName  )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
    {
      $node->item(0)->removeAttribute( $attrName );
    }
    
  }//end public function removeNodeAttr */
  
  /**
   * @param string $attrName
   */
  public function removeAttr( $attrName )
  {
    
    if( $this->dom->hasAttribute( $attrName ) )
      $this->dom->removeAttribute( $attrName );
    
  }//end public function removeAttr */
  
  /**
   * @param string $tagName
   */
  public function touchNode( $tagName )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
    {
      return $node->item(0);
    }
    else 
    {
      
      if( strpos( $tagName, '/' ) )
      {
        return $this->createPath( $tagName );
      }
      
      $newNode = $this->file->document->createElement( $tagName, '' );
      return $this->dom->appendChild( $newNode );
    }
    
  }//end public function touchNode */
  
  /**
   * @param string $tagName
   */
  public function removeNode( $tagName )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
    {
      $node = $node->item(0);
      $node->parentNode->removeChild( $node );
    }
    
  }//end public function removeNode */
  
  /**
   * @param string $tagName
   */
  public function nodeExists( $tagName )
  {
    $node = $this->file->xpath( './'.$tagName, $this->dom );
    
    if( $node->length )
      return true;
    else 
      return false;  
    
  }//end public function nodeExists */
  
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

  }//end public function getTextNodes */
  
  /**
   * @param string $type
   * @param string $lang
   * @return string
   */
  public function getTextNode( $type, $lang )
  {
    
    $list = $this->file->xpath( './'.$type.'/text[@lang="'.$lang.'"]', $this->dom );

    if( !$list->length )
      return '';
      
    return $list->item(0)->textContent;

  }//end public function getTextNode */
  
  /**
   * @param string $type
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setTextNode( $type, $lang, $content )
  {
    
    $list = $this->file->xpath( './'.$type.'/text[@lang="'.$lang.'"]', $this->dom );
    
    $nodes = array();
    
    if( $list->length )
    {
      $node = $list->item(0);
      
      $cdata = $node->ownerDocument->createCDATASection( $content  );
      
      if( $node->hasChildNodes() )
      {
        $node->replaceChild( $cdata, $node->childNodes->item(0) );
      }
      else 
      {
        $cdata = $node->ownerDocument->createCDATASection( $content  );
        $node->appendChild( $cdata );
      }
      
      
    }
    else 
    {
      $tNodeList = $this->file->xpath( './'.$type, $this->dom );
      
      if( !$tNodeList->length )
      {
        $newNode = $this->file->document->createElement( $type );
        $tNode = $this->dom->appendChild( $newNode );
      }
      else 
      {
        $tNode = $tNodeList->item(0);
      }
      
      $newNode = $this->file->document->createElement( 'text' );
      $newNode->setAttribute( 'lang', $lang );
      $newNode = $tNode->appendChild( $newNode );
      
      $cdata = $newNode->ownerDocument->createCDATASection($content);
      $newNode->appendChild( $cdata );
      
    }
    
  }//end public function setTextNode */

  /**
   * checken ob ein textnode existiert
   * @param string $type
   * @param string $lang
   * @return boolean
   */
  public function hasTextNode( $type, $lang  )
  {
    
    $list = $this->file->xpath( './'.$type.'/text[@lang="'.$lang.'"]', $this->dom );

    if( $list->length )
    {
      return true;
    }
    else 
    {
      return false;
    }
    
  }//end public function hasTextNode */
  
////////////////////////////////////////////////////////////////////////////////
// Semantische Methoden
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return array
   */
  public function getLabels()
  {
    
    return $this->getTextNodes('label');

  }//end public function getLabels */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setLabel( $lang, $content )
  {
    
    return $this->setTextNode( 'label', $lang, $content );

  }//end public function setLabel */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function hasLabel( $lang )
  {
    
    return $this->hasTextNode( 'label', $lang );

  }//end public function hasLabel */
  
  /**
   * @param string $lang
   * @return string
   */
  public function getLabelByLang( $lang )
  {
    return $this->getTextNode( 'label', $lang );
    
  }//end public function getLabelByLang */
  
  /**
   * 
   */
  public function getDescriptions()
  {
    return $this->getTextNodes('description');
    
  }//end public function getDescriptions */
  
  /**
   * @param string $lang
   * @return boolean
   */
  public function hasDescription( $lang )
  {
    
    return $this->hasTextNode( 'description', $lang );

  }//end public function hasDescription */
  
  /**
   * 
   */
  public function getDescriptionByLang( $lang )
  {
    return $this->getTextNode( 'description', $lang );
    
  }//end public function getDescriptionByLang */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setDescription( $lang, $content )
  {
    
    return $this->setTextNode( 'description', $lang, $content );

  }//end public function setDescription */
  
  /**
   * 
   */
  public function getShortDesc()
  {
    return $this->getTextNodes('short_desc');
    
  }//end public function getShortDesc */
  
  /**
   * @param string $lang
   * @return boolean
   */
  public function hasShortDesc( $lang )
  {
    
    return $this->hasTextNode( 'short_desc', $lang );

  }//end public function hasShortDesc */
  
  /**
   * 
   */
  public function getShortDescByLang( $lang )
  {
    return $this->getTextNode( 'short_desc', $lang );
    
  }//end public function getShortDescByLang */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setShortDesc( $lang, $content )
  {
    
    return $this->setTextNode( 'short_desc', $lang, $content );

  }//end public function setShortDesc */
  
  /**
   * @return string
   */
  public function getDocus()
  {
    return $this->getTextNodes( 'docu' );
  }//end public function getDescriptions */
  
  /**
   * @param string $lang
   * @return boolean
   */
  public function hasDocu( $lang )
  {
    
    return $this->hasTextNode( 'docu', $lang );

  }//end public function hasDocu */
  
  /**
   * @param string $lang
   * @return string
   */
  public function getDocuByLang( $lang )
  {
    
    return $this->getTextNode( 'docu', $lang );
    
  }//end public function getDocuByLang */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setDocu( $lang, $content )
  {
    
    return $this->setTextNode( 'docu', $lang, $content );

  }//end public function setDocu */
  
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
  
}//end class BdlRootNode
