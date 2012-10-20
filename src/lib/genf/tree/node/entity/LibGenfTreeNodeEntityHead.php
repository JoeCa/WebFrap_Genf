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
class LibGenfTreeNodeEntityHead
  extends LibGenfTreeNode
{

  /**
   * any approximations about the datavolume in this table?
   *
   */
  public function expectedDataVolumen()
  {

    return isset($this->node->data['volume'])
      ? trim($this->node->data['volume'])
      : 'default';

  }//end public function expectedDataVolumen */
  
  /**
   * Können Einträge auf dieser Entity geändern werden? 
   * Wenn nicht brauchen wir keine Edit Einträge
   */
  public function isMuteAble()
  {

    return isset($this->node->muteable)
      ? ( 'true' === trim($this->node->muteable) )
      : true;

  }//end public function isMuteAble */
  
  /**
   * Können Einträge aus dieser tabelle gesynct werden?
   */
  public function isSyncAble()
  {
    
    return isset($this->node->syncable)
      ? ( 'true' === trim($this->node->syncable) )
      : true;

  }//end public function isSyncAble */
  
  /**
   * Verwenden von Transaktinen beim Speichern 
   * Noch keine Verwendung
   */
  public function useTransactions()
  {
    
    return isset($this->node->transaction)
      ? ( 'true' === trim($this->node->transaction) )
      : false;

  }//end public function useTransactions */
  
  /**
   * Soll das Erstellen getrackt werden?
   */
  public function trackCreation()
  {
    
    return isset($this->node->track_creation)
      ? ( 'true' === trim($this->node->track_creation) )
      : true;

  }//end public function trackCreation */
  
  /**
   * Sollen Änderungen getrackt werden?
   */
  public function trackChanges()
  {

    return isset($this->node->track_changes)
      ? ( 'true' === trim($this->node->track_changes) )
      : true;

  }//end public function trackChanges */
  
  
  /**
   * Sollen deleteflags generiert werden, oder sollen die Einträge
   * komplett gelöscht werden?
   */
  public function trackDeletion()
  {

    return isset($this->node->track_deletion)
      ? ( 'true' === trim($this->node->track_deletion) )
      : false;

  }//end public function isDeleteable */


  /**
   * @return array
   */
  public function getProfiles()
  {

    if(!isset($this->node->profiles))
      return array();

    $profiles = array();
    $cName    = $this->builder->getNodeClass('HeadProfile');

    foreach ($this->node->profiles as $profile)
    {
      $profiles[trim($profile['name'])] = new $cName($profile);
    }

    return $profiles;


  }//end public function getProfiles */


}//end class LibGenfTreeNodeEntityHead

