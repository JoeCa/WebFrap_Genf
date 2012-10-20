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
class LibGenfTreeNodeMessage
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var LibGenfNameMessage
   */
  public $name = null;
  
  /**
   * @var LibGenfTreeNodeManagement
   */
  public $management = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  
  /**
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameMessage( $this->node );
    $this->management = $this->builder->getManagement( trim( $this->node['entity'] ) );

  }//end protected function loadChilds */
  
  /**
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement()
  {
    
    return $this->management;
    
  }//end public function getManagement */

  /**
   *
   * @return string the name of the entity
   */
  public function name()
  {
    return trim( $this->node['name'] );
  }//end public function name

////////////////////////////////////////////////////////////////////////////////
// getter and setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getSubject( )
  {
    
    if( !$this->node->subject )
      return null;
    
    return trim( $this->node->subject );
    
  }//end public function getSubject */
  
  /**
   * @return string
   */
  public function getIndexName()
  {
    
    if( isset( $this->node->templates->index['name'] ) )
      return trim( $this->node->templates->index['name'] );
    else
      return 'index';
      
  }//end public function getIndexName */
  
  /**
   *
   * @return void
   * @return array<LibGenfTreeNodeMessageDataSource>
   */
  public function getDataSources( )
  {

    $sourceNodeClass  = $this->builder->getModelClass( 'TreeNode', 'MessageDataSource' );

    $sources = array();

    // only exists if subnode exists
    if( isset( $this->node->data_sources->source ) )
    {
      foreach( $this->node->data_sources->source as $source )
      {
        $sources[] = new $sourceNodeClass( $source );
      }
    }

    return $sources;

  }//end public function getDataSources */
  
  /**
   * @return array<string>
   */
  public function getChannels()
  {
    
    // standardmäßig verschicken wir nachrichten als mail
    if( !isset( $this->node->channels->channel ) )
      return array( 'mail' );
    
    $channels = array();
    
    foreach( $this->node->channels->channel as $channel )
    {
      $channels[trim($channel['name'])] = trim($channel['name']);
    }
    
    return $channels;
    
  }//end public function getChannels */
  
  /**
   * Die Attachments für die Nachrichten anfragen
   * @return array<LibGenfTreeNodeAttachment>
   */
  public function getAttachments()
  {
    
    if( !isset( $this->node->attachments->attachment ) )
      return array();
      
    $attachments = array();
    
    foreach( $this->node->attachments->attachment as $attachment )
    {
      $attachments[] = new LibGenfTreeNodeAttachment( $attachment );
    }
    
    return $attachments;
    
  }//end public function getAttachments */
  
  /**
   * @return string
   */
  public function getHtmlContent()
  {
    
    if( !isset( $this->node->content->html ) )
      return null;
    
    return trim( $this->node->content->html );
    
  }//end public function getHtmlContent */
  
  /**
   * @return SimpleXMLElement
   */
  public function getHtmlNode()
  {
    
    if( !isset( $this->node->content->html ) )
      return null;
    
    return $this->node->content->html;
    
  }//end public function getHtmlNode */
  
  /**
   * @return string
   */
  public function getPlainContent()
  {
    
    if( !isset( $this->node->content->plain ) )
      return null;
    
    return trim( $this->node->content->plain );
    
  }//end public function getPlainContent */
  
  /**
   * @return string
   */
  public function getPlainNode()
  {
    
    if( !isset($this->node->content->plain) )
      return null;
    
    return $this->node->content->plain;
    
  }//end public function getPlainNode */

  /**
   * @return string
   */
  public function getContent()
  {
    
    if( !$this->node->content )
      return null;
    
    return $this->node->content;
    
  }//end public function getContent */

}//end class LibGenfTreeNodeMessage

