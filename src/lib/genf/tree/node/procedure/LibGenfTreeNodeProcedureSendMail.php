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
 * 
 * @example Tag Beispiel
 * <procedure type="send_mail" >
 * 
 *   <message name="need_more_information" />
 *   
 *   <messages>
 *     <success>
 *       <text lang="en" >Notified Assignment Creator</text>
 *     </success>
 *   </messages>
 *   
 *   <receivers>
 *     <receiver type="responsible" name="responsible" />
 *   </receivers>
 *   
 * </procedure>
 * 
 */
class LibGenfTreeNodeProcedureSendMail
  extends LibGenfTreeNodeProcedure
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name       = new LibGenfNameProcedure( $this->node );
    $this->loadConditions();

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function getMessageName( )
  {
    return trim( $this->node->message['name'] );
  }//end public function getMessage */
  
  /**
   * @return string
   */
  public function getMessageClass( )
  {
    return SParserString::subToCamelCase( trim( $this->node->message['name'] ) );
  }//end public function getMessageClass */
  
  /**
   * @param string $key
   * @param string $lang
   * 
   * @return string
   */
  public function getMessage( $key, $lang = 'en' )
  {
    
    if( !isset( $this->node->messages->{$key} ) )
      return null;
      
    return $this->i18nValue( $this->node->messages->{$key}, $lang );
    
  }//end public function getMessage */
  
  /**
   * @return array
   */
  public function getReceivers()
  {
    
    $receivers = array();
    
    foreach( $this->node->receivers->receiver as $receiver )
    {
      $receivers[] = array
      ( 
        'type' => trim($receiver['type']),
        'name' => trim($receiver['name']),
      );
    }
    
    return $receivers;
    
    
  }//end public function getReceivers */
  
  /**
   * @return array
   */
  public function getChannels()
  {
    
    $channels = array();
    
    if( isset($this->node->channels) )
    {
      $cNodes = $this->node->channels->children();
      
      foreach( $cNodes as $channelName => $node )
      {
        $channels[] = $channelName;
      }
    }
      
    return $channels;
    
  }//end public function getChannels */


}//end class LibGenfTreeNodeAction

