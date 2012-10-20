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
class LibGenfModelBdl
{
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  public $nodeName  = null;
  
  public $nodeValue = '';
  
  /**
   * Der Original Node
   * @var SimpleXMLElement
   */
  public $node = null;

  /**
   * @param SimpleXmlElement $node
   */
  public function __construct( $node = null )
  {

    if( $node )
      $this->import( $node );

  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->parse();
  }//end public function __toString */

  /**
   * @param string $key
   */
  public function __get( $key )
  {

    $propName = 'tag'.ucfirst($key);
    $attrName = 'attr'.ucfirst($key);

    if( property_exists( $this , $propName ) )
    {
      if( is_null( $this->$propName ) )
      {
        $className = 'LibGenfModelBdl'.ucfirst($key);
        $this->$propName = new $className();
      }

      return $this->$propName;
    }
    else if( property_exists( $this , $attrName ) )
    {
      return $this->$attrName;
    }
    else
    {
      LibGenfBuild::getInstance()->error( 'BDL Model: Try to request an invalid tag '.$key.' on '.get_class($this) );
      throw new LibGenf_Exception( 'Try to request an invalid tag '.$key.' on '.get_class($this) );
    }

  }//end public function __get */

  /**
   * @param string $key
   * @param string $value
   */
  public function __set( $key, $value )
  {

    $propName = 'tag'.ucfirst($key);
    $attrName = 'attr'.ucfirst($key);

    if( property_exists( $this , $propName ) )
    {
      if( is_null( $this->$propName ) )
      {
        $className        = 'LibGenfModelBdl'.ucfirst($key);
        $this->$propName  = new $className();
      }

      $this->$propName->import($value);
    }
    else if( property_exists( $this , $attrName ) )
    {
      if( is_null($this->$attrName) )
      {
        $this->$attrName = $value;
      }
    }
    else
    {
      LibGenfBuild::getInstance()->error( 'BDL Model: Try to write in invalid tag '.$key.' on '.get_class($this) );
      throw new LibGenf_Exception('try to write in invalid tag '.$key.' on '.get_class($this) );
    }

  }//end public function __set */
  
  /**
   * @param string $key
   * @param string $value
   */
  public function isEmpty( $key )
  {

    $propName = 'tag'.ucfirst($key);
    $attrName = 'attr'.ucfirst($key);

    if( property_exists( $this, $propName ) )
    {
      if( is_null( $this->$propName ) )
      {
        return true;
      }
      else 
      {
        return false;
      }
    }
    else if( property_exists( $this, $attrName ) )
    {
      if( is_null( $this->$attrName ) )
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
      LibGenfBuild::getInstance()->error( 'BDL Model: Try to request an invalid tag '.$key.' on '.get_class($this) );
      throw new LibGenf_Exception( 'Try to request an invalid tag '.$key.' on '.get_class($this) );
    }

  }//end public function __set */

  /**
   * @return string
   */
  public function parse()
  {
    
    // wenn ein original node vorhanden ist geben wir einfach diesen zurück
    //if( $this->node )
      //return $this->node->asXML();
    
    // wenn wir keinen nodenamen haben können wir nichts machen
    if( !$this->nodeName )
      return '';
    
    // kein value ok dann ein leeres tag
    if( !$this->nodeValue )
      return '<'.$this->nodeName.' />'.NL;
      
    // node mit value
    return '<'.$this->nodeName.'>'.$this->nodeValue.'</'.$this->nodeName.'>'.NL;
    
  }//end public function parse */

  /**
   * @param SimpleXmlElement $node
   */
  public function import( $node )
  {
    
    $this->node = $node;
    
    $this->nodeValue = trim($node);

  }//end public function import */


}//end class LibGenfModelBdl

