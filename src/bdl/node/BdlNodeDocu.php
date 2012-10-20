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
class BdlNodeDocu
  extends BdlBaseNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param BdlFile $file
   */
  public function __construct( $file  )
  {
    
    $this->file = $file;
    
    $this->dom = $this->file->getNodeByPath('/bdl/docus/docu');
    
  }//end public function __construct */
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getName()
  {
    return $this['name'];
  }//end  public function getName
  
  /**
   * @param string $name
   */
  public function setName( $name )
  {
    $this['name'] = $name ;
  }//end  public function setName */

  /**
   * @return string
   */
  public function getModule()
  {
    return $this['module'];
  }//end  public function getModule */
  
  /**
   * @param string $name
   */
  public function setModule( $module )
  {
    $this['module'] = $module ;
  }//end  public function setModule */

  /**
   * @return array
   */
  public function getTitles()
  {
    
    return $this->getTextNodes('title');

  }//end public function getTitles */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setTitle( $lang, $content )
  {
    
    return $this->setTextNode( 'title', $lang, $content );

  }//end public function setTitle */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function hasTitle( $lang )
  {
    
    return $this->hasTextNode( 'title', $lang );

  }//end public function hasTitle */
  
  /**
   * @param string $lang
   * @return string
   */
  public function getTitleByLang( $lang )
  {
    return $this->getTextNode( 'title', $lang );
    
  }//end public function getTitleByLang */
  
  /**
   * 
   */
  public function getContents()
  {
    return $this->getTextNodes('content');
    
  }//end public function getContents */
  
  /**
   * @param string $lang
   * @return boolean
   */
  public function hasContent( $lang )
  {
    
    return $this->hasTextNode( 'content', $lang );

  }//end public function hasContent */
  
  /**
   * 
   */
  public function getContentByLang( $lang )
  {
    return $this->getTextNode( 'content', $lang );
    
  }//end public function getContentByLang */
  
  /**
   * @param string $lang
   * @param string $content
   * @return array
   */
  public function setContent( $lang, $content )
  {
    
    return $this->setTextNode( 'content', $lang, $content );

  }//end public function setContent */

}//end class BdlNodeDocu
