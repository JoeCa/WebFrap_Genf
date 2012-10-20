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
 * Bdl Node
 */
class BdlProject
  extends BdlNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

    
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $fileName
   */
  public function __construct( $fileName  )
  {
    
    $this->file = new BdlFile($fileName);
    
    $this->dom = $this->file->getNodeByPath('/project');
    
  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// Getter & Setter
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @return string
   */
  public function getAuthor()
  {
    return $this->getNodeValue('author');
  }//end  public function getAuthor
  
  /**
   * @param string $author
   */
  public function setAuthor( $author )
  {
    $this->setNodeValue('author', $author);
  }//end  public function setAuthor */
  
  /**
   * @return string
   */
  public function getCopyright()
  {
    return $this->getNodeValue('copyright');
  }//end  public function getCopyright

  /**
   * @param string $copyright
   */
  public function setCopyright( $copyright )
  {
    $this->setNodeValue('copyright', $copyright);
  }//end  public function setCopyright */
  
  /**
   * @return string
   */
  public function getLicence()
  {
    return $this->getNodeValue('licence');
  }//end  public function getLicence
  
  /**
   * @return string
   */
  public function getUrl()
  {
    return $this->getNodeValue('url');
  }//end  public function getUrl
  
  /**
   * @return string
   */
  public function getName()
  {
    return $this->getNodeValue('name');
  }//end  public function getName
  
  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->getNodeValue('title');
  }//end  public function getTitle
  
  /**
   * @return string
   */
  public function getKey()
  {
    return $this->getNodeValue('key');
  }//end  public function getKey
  
  /**
   * @return string
   */
  public function getHeader()
  {
    return $this->getNodeValue('header');
  }//end  public function getHeader
  
  /**
   * @return string
   */
  public function getSandbox()
  {
    return $this->getNodeValue('sandbox');
  }//end  public function getSandbox
  
} // end class BdlProject

